<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Models\ForumReply;
use Illuminate\Http\Request;

class ForumModerationController extends Controller
{
    /**
     * Forum moderation dashboard — pending reports + recent threads.
     */
    public function index()
    {
        $reports = ForumReport::pending()
            ->with(['user'])
            ->latest()
            ->paginate(20);

        // Eager-load reportable content manually since it's polymorphic
        $reports->getCollection()->transform(function ($report) {
            if ($report->reportable_type === ForumThread::class) {
                $report->setRelation('reportable', ForumThread::with('user')->find($report->reportable_id));
            } else {
                $report->setRelation('reportable', ForumReply::with(['user', 'thread'])->find($report->reportable_id));
            }
            return $report;
        });

        $pendingCount = ForumReport::pending()->count();

        $recentThreads = ForumThread::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.forum.index', compact('reports', 'pendingCount', 'recentThreads'));
    }

    /**
     * Resolve a report (mark content as actioned).
     */
    public function resolve(ForumReport $report)
    {
        $report->update([
            'status' => 'resolved',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Report resolved.');
    }

    /**
     * Dismiss a report (no action needed).
     */
    public function dismiss(ForumReport $report)
    {
        $report->update([
            'status' => 'dismissed',
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Report dismissed.');
    }

    /**
     * Toggle pin status on a thread.
     */
    public function togglePin(ForumThread $thread)
    {
        $thread->update(['is_pinned' => !$thread->is_pinned]);
        $label = $thread->is_pinned ? 'pinned' : 'unpinned';

        return back()->with('success', "Thread {$label} successfully.");
    }

    /**
     * Toggle lock status on a thread.
     */
    public function toggleLock(ForumThread $thread)
    {
        $thread->update(['is_locked' => !$thread->is_locked]);
        $label = $thread->is_locked ? 'locked' : 'unlocked';

        return back()->with('success', "Thread {$label} successfully.");
    }

    /**
     * Mark or unmark (restore) a thread or reply as spam.
     */
    public function markSpam(string $type, int $id)
    {
        if ($type === 'thread') {
            $item = ForumThread::findOrFail($id);
        } else {
            $item = ForumReply::findOrFail($id);
        }

        $newStatus = !$item->is_spam;
        $item->update(['is_spam' => $newStatus]);

        if ($newStatus) {
            // Auto-resolve any pending reports on this item
            ForumReport::where('reportable_type', get_class($item))
                ->where('reportable_id', $id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'resolved',
                    'resolved_by' => auth()->id(),
                    'resolved_at' => now(),
                ]);
            $msg = ucfirst($type) . ' marked as spam and removed from public view.';
        } else {
            $msg = ucfirst($type) . ' restored from spam (unmarked).';
        }

        return back()->with('success', $msg);
    }
}
