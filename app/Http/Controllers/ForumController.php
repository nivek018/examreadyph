<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\ForumReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller
{
    /**
     * Forum homepage — flat discussion feed with category filters.
     */
    public function index(Request $request)
    {
        $categories = ForumCategory::orderBy('sort_order')->get();

        $query = ForumThread::visible()
            ->with(['user', 'lastReplyUser', 'category']);

        // Category filter
        if ($request->filled('category')) {
            $cat = ForumCategory::where('slug', $request->category)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Sort: newest (default) or trending (most replies in last 7 days)
        $sort = $request->input('sort', 'newest');
        if ($sort === 'trending') {
            $query->orderByDesc('replies_count')
                  ->orderByDesc('views_count');
        } else {
            $query->pinnedFirst();
        }

        $threads = $query->paginate(15)->appends($request->query());

        // Community stats
        $totalPosts = ForumThread::visible()->count();
        $weeklyReplies = ForumReply::visible()
            ->where('created_at', '>=', now()->subWeek())
            ->count();

        return view('forum.index', compact('categories', 'threads', 'totalPosts', 'weeklyReplies', 'sort'));
    }

    /**
     * Category view — list threads in a category.
     */
    public function category(ForumCategory $category)
    {
        $threads = $category->visibleThreads()
            ->with(['user', 'lastReplyUser'])
            ->pinnedFirst()
            ->paginate(20);

        return view('forum.category', compact('category', 'threads'));
    }

    /**
     * Thread view — show thread with replies.
     */
    public function show(ForumCategory $category, ForumThread $thread)
    {
        // Enforce spam removal: block public & regular users from viewing spam threads via direct URL
        if ($thread->is_spam && (!auth()->check() || !auth()->user()->is_admin)) {
            abort(404);
        }

        // Increment view count
        $thread->increment('views_count');

        $thread->load(['user', 'category']);

        // Get top-level visible replies with their children
        $replies = $thread->visibleReplies()
            ->whereNull('parent_id')
            ->with(['user', 'children.user'])
            ->oldest()
            ->paginate(25);

        return view('forum.show', compact('category', 'thread', 'replies'));
    }

    /**
     * Show create thread form.
     */
    public function createThread(?ForumCategory $category = null)
    {
        $categories = ForumCategory::orderBy('sort_order')->get();
        if (!$category || !$category->exists) {
            $category = $categories->first();
        }

        return view('forum.create', compact('category', 'categories'));
    }

    /**
     * Store a new thread.
     */
    public function storeThread(Request $request, ?ForumCategory $category = null)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:forum_categories,id',
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|string|min:10|max:10000',
        ]);

        if (!empty($validated['category_id'])) {
            $category = ForumCategory::findOrFail($validated['category_id']);
        } elseif (!$category || !$category->exists) {
            $category = ForumCategory::orderBy('sort_order')->firstOrFail();
        }

        $thread = $category->threads()->create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        // Update category thread count
        $category->increment('threads_count');

        return redirect()
            ->route('forum.show', [$category, $thread])
            ->with('success', 'Thread created successfully!');
    }

    /**
     * Store a reply to a thread.
     */
    public function storeReply(Request $request, ForumCategory $category, ForumThread $thread)
    {
        if ($thread->is_locked) {
            return back()->with('error', 'This thread is locked and no longer accepting replies.');
        }

        $validated = $request->validate([
            'body' => 'required|string|min:2|max:5000',
            'parent_id' => 'nullable|exists:forum_replies,id',
        ]);

        // If replying to a nested reply, flatten to 1 level
        if ($validated['parent_id'] ?? null) {
            $parent = ForumReply::find($validated['parent_id']);
            if ($parent && $parent->parent_id) {
                $validated['parent_id'] = $parent->parent_id;
            }
        }

        $reply = $thread->replies()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Update thread metadata
        $thread->update([
            'replies_count' => $thread->visibleReplies()->count(),
            'last_reply_at' => now(),
            'last_reply_user_id' => auth()->id(),
        ]);

        // Update category reply count
        $category->increment('replies_count');

        return back()->with('success', 'Reply posted!');
    }

    /**
     * Report a thread or reply.
     */
    public function report(Request $request, string $type, int $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $reportableType = $type === 'thread'
            ? ForumThread::class
            : ForumReply::class;

        // Prevent duplicate reports from same user
        $exists = ForumReport::where('reportable_type', $reportableType)
            ->where('reportable_id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already reported this content.');
        }

        ForumReport::create([
            'reportable_type' => $reportableType,
            'reportable_id' => $id,
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Report submitted. Our team will review it shortly.');
    }
}
