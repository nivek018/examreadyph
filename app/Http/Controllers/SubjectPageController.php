<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subtopic;

class SubjectPageController extends Controller
{
    /**
     * SEO subject landing page for an exam.
     * URL: /reviewers/{exam:slug}
     */
    public function show(Exam $exam)
    {
        if (!$exam->is_active) {
            abort(404);
        }

        $exam->loadCount('questions');
        $subtopics = $exam->activeSubtopics()->withCount(['questions' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        $totalQuestions = $subtopics->sum('questions_count');
        $totalSessions = $exam->sessions()->where('status', 'completed')->count();

        // Build SEO title
        $seoTitle = $exam->seo_title ?? "{$exam->name} Reviewer " . date('Y') . " — Free Practice Test | ExamReady PH";
        $seoDescription = $exam->seo_description ?? "Free {$exam->name} reviewer with answer key and AI Taglish explanations. Practice " . number_format($totalQuestions) . "+ questions across " . $subtopics->count() . " topics. No registration required for mock exams!";

        return view('exam.subject', [
            'exam' => $exam,
            'subtopics' => $subtopics,
            'totalQuestions' => $totalQuestions,
            'totalSessions' => $totalSessions,
            'seoTitle' => $seoTitle,
            'seoDescription' => $seoDescription,
        ]);
    }

    /**
     * Per-subtopic SEO landing page.
     * URL: /reviewers/{exam:slug}/{subtopic:slug}
     */
    public function subtopic(Exam $exam, string $subtopicSlug)
    {
        $subtopic = Subtopic::where('exam_id', $exam->id)
            ->where('slug', $subtopicSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $subtopic->loadCount(['questions' => function ($q) {
            $q->where('is_active', true);
        }]);

        $seoTitle = "{$subtopic->name} — {$exam->name} Reviewer " . date('Y') . " | ExamReady PH";
        $seoDescription = "Free {$subtopic->name} reviewer for {$exam->name}. Practice {$subtopic->questions_count}+ questions with instant AI Taglish answer explanations. Start reviewing now!";

        return view('exam.subtopic', [
            'exam' => $exam,
            'subtopic' => $subtopic,
            'seoTitle' => $seoTitle,
            'seoDescription' => $seoDescription,
        ]);
    }
}
