<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportedQuestion;
use Illuminate\Http\Request;

class ReportedQuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = ReportedQuestion::with(['question.exam', 'user', 'resolver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        $reports = $query->latest()->paginate(20);

        return view('admin.reported_questions.index', compact('reports'));
    }

    public function resolve(Request $request, ReportedQuestion $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'resolved_by' => auth()->id(),
        ]);

        return back()->with('success', "Report marked as {$validated['status']}.");
    }
}
