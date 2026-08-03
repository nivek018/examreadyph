<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Subtopic;
use App\Services\AdPopupService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamSetupController extends Controller
{
    public function __construct(protected AdPopupService $adService) {}

    /**
     * Mode selection page redirect to subject page.
     * URL: GET /exam/{exam:slug}/setup
     */
    public function setup(Exam $exam)
    {
        if (!$exam->is_active) abort(404);

        return redirect()->route('reviewer.show', $exam);
    }

    /**
     * Practice mode subtopic picker (registered users only).
     * URL: GET /exam/{exam:slug}/practice-setup
     */
    public function practiceSetup(Exam $exam)
    {
        if (!$exam->is_active) abort(404);

        $subtopics = $exam->activeSubtopics()->withCount(['questions' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        return view('exam.practice-setup', [
            'exam' => $exam,
            'subtopics' => $subtopics,
        ]);
    }

    /**
     * Create an exam session with the selected mode and options.
     * URL: POST /exam/{exam:slug}/start-session
     */
    public function startSession(Request $request, Exam $exam)
    {
        if (!$exam->is_active) abort(404);

        $mode = $request->input('mode', 'mock');
        if (!in_array($mode, ['mock', 'relaxed', 'practice'])) {
            $mode = 'mock';
        }

        // Practice mode requires auth — redirect back to show the signup modal
        if ($mode === 'practice' && !auth()->check()) {
            return redirect()->back()
                ->withInput()
                ->with('showAuthModal', true);
        }

        $user = auth()->user();
        $isPremium = $user ? $user->isPremium() : false;

        // Premium exam gate
        if ($exam->is_premium && !$isPremium) {
            return redirect()->route('pricing')
                ->with('error', 'This is a Premium exam. Please upgrade to Pro to unlock.');
        }

        // Guest token handling
        $guestToken = null;
        if (!$user) {
            $guestToken = session()->get('guest_token');
            if (!$guestToken) {
                $guestToken = Str::random(32);
                session()->put('guest_token', $guestToken);
            }
        }

        // Build question query
        $questionsQuery = $exam->activeQuestions();

        if (!$isPremium) {
            $questionsQuery->where('is_premium', false);
        }

        // Filter by subtopics for practice mode
        $subtopicIds = null;
        if ($mode === 'practice' && $request->has('subtopic_ids')) {
            $subtopicIds = array_map('intval', $request->input('subtopic_ids', []));
            if (!empty($subtopicIds)) {
                $questionsQuery->whereIn('subtopic_id', $subtopicIds);
            }
        }

        $questionIds = $questionsQuery->pluck('id')->toArray();

        if (count($questionIds) === 0) {
            return back()->with('error', 'No questions available for the selected topics.');
        }

        // Shuffle questions
        shuffle($questionIds);

        // Determine question count
        $requestedCount = (int) $request->input('question_count', $exam->total_questions);
        if ($mode === 'practice' && $requestedCount > 0) {
            $questionIds = array_slice($questionIds, 0, min($requestedCount, count($questionIds)));
        } else {
            $questionIds = array_slice($questionIds, 0, $exam->total_questions);
        }

        // Timer: mock = timed, relaxed/practice = no timer
        $timeLimit = $mode === 'mock' ? $exam->time_limit_seconds : 0;
        $expiresAt = $timeLimit > 0 ? now()->addSeconds($timeLimit) : null;

        // Create session
        $session = ExamSession::create([
            'user_id' => $user?->id,
            'guest_token' => $guestToken,
            'exam_id' => $exam->id,
            'mode' => $mode,
            'started_at' => now(),
            'expires_at' => $expiresAt,
            'time_limit_seconds' => $timeLimit,
            'current_question_index' => 0,
            'status' => 'in_progress',
            'total_questions' => count($questionIds),
            'question_order_json' => $questionIds,
            'subtopic_ids_json' => $subtopicIds,
        ]);

        // Pre-create answer rows
        foreach ($questionIds as $index => $qId) {
            $session->answers()->create([
                'question_id' => $qId,
                'is_correct' => null,
            ]);
        }

        return redirect()->route('exam.take', $session);
    }
}
