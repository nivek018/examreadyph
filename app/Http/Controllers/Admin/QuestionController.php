<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = Question::with(['exam.category']);

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('search')) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(25);
        $exams = Exam::orderBy('name')->get();

        return view('admin.questions.index', compact('questions', 'exams'));
    }

    public function create(Request $request)
    {
        $exams = Exam::with('category')->orderBy('name')->get();
        $selectedExamId = $request->get('exam_id');
        $subtopics = Subtopic::where('is_active', true)->orderBy('exam_id')->orderBy('sort_order')->get();

        return view('admin.questions.form', [
            'question' => new Question(),
            'exams' => $exams,
            'selectedExamId' => $selectedExamId,
            'subtopics' => $subtopics,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'section_name' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'explanation_taglish' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'options' => 'required|array|min:2|max:6',
            'options.*.letter' => 'required|string|max:5',
            'options.*.text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $validated) {
            $question = Question::create([
                'exam_id' => $validated['exam_id'],
                'subtopic_id' => $validated['subtopic_id'] ?? null,
                'section_name' => $validated['section_name'] ?? null,
                'question_text' => $validated['question_text'],
                'explanation_taglish' => $validated['explanation_taglish'] ?? null,
                'difficulty' => $validated['difficulty'],
                'is_premium' => $request->has('is_premium'),
                'is_active' => $request->has('is_active'),
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['options'] as $index => $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'letter' => $opt['letter'],
                    'text' => $opt['text'],
                    'is_correct' => (int) $validated['correct_option'] === $index,
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()->route('admin.questions.index', ['exam_id' => $validated['exam_id']])
            ->with('success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $question->load('options');
        $exams = Exam::with('category')->orderBy('name')->get();
        $subtopics = Subtopic::where('is_active', true)->orderBy('exam_id')->orderBy('sort_order')->get();

        return view('admin.questions.form', [
            'question' => $question,
            'exams' => $exams,
            'selectedExamId' => $question->exam_id,
            'subtopics' => $subtopics,
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'section_name' => 'nullable|string|max:255',
            'question_text' => 'required|string',
            'explanation_taglish' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'options' => 'required|array|min:2|max:6',
            'options.*.letter' => 'required|string|max:5',
            'options.*.text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $validated, $question) {
            $question->update([
                'exam_id' => $validated['exam_id'],
                'subtopic_id' => $validated['subtopic_id'] ?? null,
                'section_name' => $validated['section_name'] ?? null,
                'question_text' => $validated['question_text'],
                'explanation_taglish' => $validated['explanation_taglish'] ?? null,
                'difficulty' => $validated['difficulty'],
                'is_premium' => $request->has('is_premium'),
                'is_active' => $request->has('is_active'),
            ]);

            // Delete old options and re-create
            $question->options()->delete();

            foreach ($validated['options'] as $index => $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'letter' => $opt['letter'],
                    'text' => $opt['text'],
                    'is_correct' => (int) $validated['correct_option'] === $index,
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()->route('admin.questions.index', ['exam_id' => $validated['exam_id']])
            ->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $examId = $question->exam_id;
        $question->delete();

        return redirect()->route('admin.questions.index', ['exam_id' => $examId])
            ->with('success', 'Question deleted.');
    }

    /**
     * Bulk import questions from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $exam = Exam::findOrFail($request->exam_id);
        $file = $request->file('csv_file');
        $csv = array_map('str_getcsv', file($file->getPathname()));

        // Expect CSV header: question_text, option_a, option_b, option_c, option_d, correct_letter, explanation, difficulty, section
        $header = array_shift($csv);
        $imported = 0;

        DB::transaction(function () use ($csv, $exam, &$imported) {
            foreach ($csv as $row) {
                if (count($row) < 6) continue;

                $questionText = trim($row[0] ?? '');
                if (empty($questionText)) continue;

                $question = Question::create([
                    'exam_id' => $exam->id,
                    'question_text' => $questionText,
                    'explanation_taglish' => trim($row[6] ?? ''),
                    'difficulty' => in_array(strtolower(trim($row[7] ?? '')), ['easy', 'medium', 'hard']) ? strtolower(trim($row[7])) : 'medium',
                    'section_name' => trim($row[8] ?? '') ?: null,
                    'is_active' => true,
                    'created_by' => auth()->id(),
                ]);

                $correctLetter = strtoupper(trim($row[5] ?? 'A'));
                $letters = ['A', 'B', 'C', 'D'];

                for ($i = 0; $i < 4; $i++) {
                    $letter = $letters[$i];
                    $text = trim($row[$i + 1] ?? '');
                    if (empty($text)) continue;

                    QuestionOption::create([
                        'question_id' => $question->id,
                        'letter' => $letter,
                        'text' => $text,
                        'is_correct' => $letter === $correctLetter,
                        'sort_order' => $i,
                    ]);
                }

                $imported++;
            }
        });

        return redirect()->route('admin.questions.index', ['exam_id' => $exam->id])
            ->with('success', "Successfully imported {$imported} questions.");
    }
}
