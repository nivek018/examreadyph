<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamCategory;
use App\Models\ExamSession;
use App\Models\UserProgress;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Recent exam sessions
        $recentSessions = ExamSession::with('exam.category')
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // In-progress sessions (resumable)
        $inProgressSessions = ExamSession::with('exam')
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->get()
            ->filter(fn($s) => $s->isActive());

        // User progress per exam
        $progress = UserProgress::with('exam.category')
            ->where('user_id', $user->id)
            ->orderByDesc('last_attempt_at')
            ->get();

        // Available exams grouped by category
        $categories = ExamCategory::where('is_active', true)
            ->with(['exams' => fn($q) => $q->where('is_active', true)->withCount('questions')])
            ->orderBy('sort_order')
            ->get();

        // Stats
        $stats = [
            'total_exams_taken' => ExamSession::where('user_id', $user->id)->where('status', 'completed')->count(),
            'average_score' => ExamSession::where('user_id', $user->id)->where('status', 'completed')->avg('score') ?? 0,
            'best_score' => ExamSession::where('user_id', $user->id)->where('status', 'completed')->max('score') ?? 0,
            'total_questions_answered' => ExamSession::where('user_id', $user->id)->where('status', 'completed')->sum('correct_count') + ExamSession::where('user_id', $user->id)->where('status', 'completed')->sum('wrong_count'),
        ];

        return view('dashboard', compact('recentSessions', 'inProgressSessions', 'progress', 'categories', 'stats'));
    }
}
