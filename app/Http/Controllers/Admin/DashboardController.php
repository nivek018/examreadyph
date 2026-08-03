<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Question;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_exams' => Exam::count(),
            'total_questions' => Question::count(),
            'active_sessions' => ExamSession::where('status', 'in_progress')->count(),
            'completed_sessions' => ExamSession::where('status', 'completed')->count(),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_sessions' => ExamSession::with(['user', 'exam'])->latest()->take(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
