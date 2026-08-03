<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestPosts = \App\Models\BlogPost::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('home', compact('latestPosts'));
    }

    public function reviewers()
    {
        $exams = \App\Models\Exam::where('is_active', true)
            ->with('category')
            ->withCount('questions')
            ->latest()
            ->get();

        $categories = \App\Models\ExamCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('reviewers', compact('exams', 'categories'));
    }
}
