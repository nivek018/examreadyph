<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\ReportedQuestion;
use App\Models\UserProgress;
use App\Services\AdPopupService;
use App\Services\AiExplanationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamSessionController extends Controller
{
    public function __construct(protected AdPopupService $adService) {}
    /**
     * Start a new exam session or resume an existing one.
     */
    public function start(Exam $exam)
    {
        $user = auth()->user();
        $isPremium = $user ? $user->isPremium() : false;

        if ($exam->is_premium && !$isPremium) {
            return redirect()->route('pricing')
                ->with('error', 'This is a Premium exam. Please upgrade to Pro to unlock.');
        }

        $guestToken = null;
        if (!$user) {
            $guestToken = session()->get('guest_token');
            if (!$guestToken) {
                $guestToken = \Illuminate\Support\Str::random(32);
                session()->put('guest_token', $guestToken);
            }
        }

        // Check for in-progress session to resume
        $sessionQuery = ExamSession::where('exam_id', $exam->id)->where('status', 'in_progress');
        if ($user) {
            $sessionQuery->where('user_id', $user->id);
        } else {
            $sessionQuery->where('guest_token', $guestToken);
        }
        $existingSession = $sessionQuery->first();

        if ($existingSession && $existingSession->isActive()) {
            return redirect()->route('exam.take', $existingSession);
        }

        // If expired in-progress session exists, auto-submit it
        if ($existingSession && !$existingSession->isActive()) {
            $this->autoSubmitSession($existingSession);
        }

        // Get active questions for this exam
        $questionsQuery = $exam->activeQuestions();

        if (!$isPremium) {
            $questionsQuery->where('is_premium', false);
        }

        $questionIds = $questionsQuery->pluck('id')->toArray();

        if (count($questionIds) === 0) {
            return back()->with('error', 'No questions available for this exam yet.');
        }

        // Shuffle if enabled
        if ($exam->shuffle_questions) {
            shuffle($questionIds);
        }

        // Limit to exam's total_questions count
        $questionIds = array_slice($questionIds, 0, $exam->total_questions);

        // Calculate expiry
        $expiresAt = $exam->time_limit_seconds > 0
            ? now()->addSeconds($exam->time_limit_seconds)
            : null;

        // Create session
        $session = ExamSession::create([
            'user_id' => $user?->id,
            'guest_token' => $guestToken,
            'exam_id' => $exam->id,
            'started_at' => now(),
            'expires_at' => $expiresAt,
            'time_limit_seconds' => $exam->time_limit_seconds,
            'current_question_index' => 0,
            'status' => 'in_progress',
            'total_questions' => count($questionIds),
            'question_order_json' => $questionIds,
        ]);

        // Pre-create answer rows for all questions
        foreach ($questionIds as $index => $qId) {
            ExamAnswer::create([
                'session_id' => $session->id,
                'question_id' => $qId,
            ]);
        }

        return redirect()->route('exam.take', $session);
    }

    /**
     * Display the exam-taking interface.
     */
    public function take(ExamSession $session)
    {
        $this->authorizeSession($session);

        // Check if session is still active
        if (!$session->isActive()) {
            if ($session->status === 'in_progress') {
                $this->autoSubmitSession($session);
            }
            return redirect()->route('exam.results', $session);
        }

        $questionOrder = $session->question_order_json;
        $currentIndex = $session->current_question_index;

        // Fetch all session questions eager loading options
        $allQuestions = Question::with('options')
            ->whereIn('id', $questionOrder)
            ->get()
            ->keyBy('id');

        // Build deterministic questions array following $questionOrder
        $questionsData = [];
        foreach ($questionOrder as $qId) {
            $q = $allQuestions->get($qId);
            if (!$q) continue;

            // Shuffle options deterministically per session & question
            $optionsArray = $q->options->all();
            $seed = crc32($session->uuid . '-' . $q->id);
            mt_srand($seed);
            for ($i = count($optionsArray) - 1; $i > 0; $i--) {
                $j = mt_rand(0, $i);
                $tmp = $optionsArray[$i];
                $optionsArray[$i] = $optionsArray[$j];
                $optionsArray[$j] = $tmp;
            }
            mt_srand();

            $formattedOptions = array_map(function ($opt) {
                return [
                    'id' => $opt->id,
                    'letter' => $opt->letter,
                    'text' => $opt->text,
                    'is_correct' => (bool) $opt->is_correct,
                ];
            }, $optionsArray);

            $questionsData[] = [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'section_name' => $q->section_name ?? ($q->subtopic->name ?? null),
                'explanation_taglish' => $q->explanation_taglish,
                'options' => $formattedOptions,
            ];
        }

        // Load answers for session
        $answers = $session->answers()
            ->get()
            ->keyBy('question_id');

        $adConfig = $this->adService->getAdConfig(auth()->user(), 'exam');

        return view('exam.take', [
            'session' => $session,
            'exam' => $session->exam,
            'questionsData' => $questionsData,
            'currentIndex' => $currentIndex,
            'totalQuestions' => $session->total_questions,
            'questionOrder' => $questionOrder,
            'answers' => $answers,
            'adConfig' => $adConfig,
        ]);
    }

    /**
     * Save an answer for a question (AJAX).
     */
    public function answer(Request $request, ExamSession $session)
    {
        $this->authorizeSession($session);

        if (!$session->isActive()) {
            return response()->json(['error' => 'Session expired'], 403);
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'nullable|exists:question_options,id',
            'is_flagged' => 'boolean',
            'is_saved' => 'boolean',
        ]);

        $answer = ExamAnswer::where('session_id', $session->id)
            ->where('question_id', $validated['question_id'])
            ->first();

        if (!$answer) {
            return response()->json(['error' => 'Question not in this session'], 404);
        }

        $updateData = [];
        $isCorrect = null;
        $correctOptionId = null;
        $explanationTaglish = null;

        if (isset($validated['option_id'])) {
            // Check if option is correct
            $question = Question::with('correctOption')->find($validated['question_id']);
            $correctOptionId = $question->correctOption?->id;
            $explanationTaglish = $question->explanation_taglish;
            $isCorrect = $correctOptionId && $correctOptionId === (int) $validated['option_id'];

            $updateData['selected_option_id'] = $validated['option_id'];
            $updateData['is_correct'] = $isCorrect;
            $updateData['answered_at'] = now();
        }

        if (isset($validated['is_flagged'])) {
            $updateData['is_flagged'] = $validated['is_flagged'];
        }

        if (isset($validated['is_saved'])) {
            $updateData['is_saved'] = $validated['is_saved'];
        }

        $answer->update($updateData);

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_option_id' => $correctOptionId,
            'explanation_taglish' => $explanationTaglish,
        ]);
    }

    /**
     * Navigate to a specific question (AJAX).
     */
    public function navigate(Request $request, ExamSession $session)
    {
        $this->authorizeSession($session);

        $validated = $request->validate([
            'index' => 'required|integer|min:0',
        ]);

        $index = min($validated['index'], $session->total_questions - 1);
        $session->update(['current_question_index' => $index]);

        return response()->json(['success' => true, 'index' => $index]);
    }

    /**
     * Report an issue with a question (AJAX).
     */
    public function reportQuestion(Request $request, ExamSession $session)
    {
        $this->authorizeSession($session);

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'reason' => 'required|string|in:incorrect_answer,incorrect_grammar,outdated,unclear,other',
            'description' => 'nullable|string|max:1000',
        ]);

        ReportedQuestion::create([
            'question_id' => $validated['question_id'],
            'user_id' => auth()->id() ?? $session->user_id,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status' => 'pending',
        ]);

        // Increment question reported count
        Question::where('id', $validated['question_id'])->increment('reported_count');

        return response()->json([
            'success' => true,
            'message' => 'Thank you! The issue has been reported to our moderation team.',
        ]);
    }

    /**
     * Generate or regenerate AI explanation for a question (AJAX).
     */
    public function explainQuestion(Request $request, ExamSession $session, AiExplanationService $aiService)
    {
        $this->authorizeSession($session);

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'force_regenerate' => 'nullable|boolean',
        ]);

        $question = Question::with(['options', 'exam', 'subtopic'])->findOrFail($validated['question_id']);
        $forceRegenerate = (bool) ($validated['force_regenerate'] ?? false);

        $explanation = $aiService->explainQuestion($question, $forceRegenerate);

        return response()->json([
            'success' => true,
            'explanation' => $explanation,
        ]);
    }

    /**
     * Submit the exam and calculate score.
     */
    public function submit(ExamSession $session)
    {
        $this->authorizeSession($session);

        if ($session->status !== 'in_progress') {
            return redirect()->route('exam.results', $session);
        }

        $this->calculateAndFinalizeSession($session);

        return redirect()->route('exam.results', $session)
            ->with('success', 'Exam submitted successfully! Check your results below.');
    }

    /**
     * Display exam results.
     */
    public function results(ExamSession $session)
    {
        $this->authorizeSession($session);

        if ($session->status === 'in_progress') {
            return redirect()->route('exam.take', $session);
        }

        $session->load(['exam.category', 'answers.question.options', 'answers.selectedOption']);

        $exam = $session->exam;
        $passed = $session->score >= $exam->passing_score_percent;

        return view('exam.results', [
            'session' => $session,
            'exam' => $exam,
            'passed' => $passed,
        ]);
    }

    /**
     * Auto-submit an expired session.
     */
    protected function autoSubmitSession(ExamSession $session): void
    {
        if ($session->status !== 'in_progress') return;
        $this->calculateAndFinalizeSession($session);
    }

    /**
     * Calculate score and finalize session.
     */
    protected function calculateAndFinalizeSession(ExamSession $session): void
    {
        $answers = $session->answers()->get();

        $correctCount = $answers->where('is_correct', true)->count();
        $answeredCount = $answers->whereNotNull('selected_option_id')->count();
        $wrongCount = $answeredCount - $correctCount;
        $unansweredCount = $session->total_questions - $answeredCount;
        $score = $session->total_questions > 0
            ? round(($correctCount / $session->total_questions) * 100, 2)
            : 0;

        $session->update([
            'status' => 'completed',
            'finished_at' => now(),
            'score' => $score,
            'correct_count' => $correctCount,
            'wrong_count' => $wrongCount,
            'unanswered_count' => $unansweredCount,
        ]);

        // Update user progress
        $progress = UserProgress::firstOrCreate(
            ['user_id' => $session->user_id, 'exam_id' => $session->exam_id],
            ['total_attempts' => 0, 'correct_count' => 0, 'wrong_count' => 0, 'best_score' => 0]
        );

        $progress->increment('total_attempts');
        $progress->increment('correct_count', $correctCount);
        $progress->increment('wrong_count', $wrongCount);
        $progress->update([
            'best_score' => max($progress->best_score, $score),
            'last_attempt_at' => now(),
        ]);
    }

    protected function authorizeSession(ExamSession $session): void
    {
        $userId = auth()->id();
        $guestToken = session()->get('guest_token');

        if ($session->user_id && $userId && $session->user_id === $userId) {
            return;
        }

        if ($session->guest_token && $guestToken && $session->guest_token === $guestToken) {
            return;
        }

        if (auth()->check() && auth()->user()->isAdmin()) {
            return;
        }

        abort(403, 'Unauthorized access. You do not own this exam session.');
    }
}
