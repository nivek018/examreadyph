<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $query = Exam::with('category')->withCount('questions');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $exams = $query->orderBy('name')->paginate(20);
        $categories = ExamCategory::orderBy('name')->get();

        return view('admin.exams.index', compact('exams', 'categories'));
    }

    public function create()
    {
        $categories = ExamCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.exams.form', ['exam' => new Exam(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:exam_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'total_questions' => 'required|integer|min:1',
            'time_limit_seconds' => 'required|integer|min:0',
            'passing_score_percent' => 'required|numeric|min:0|max:100',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_explanations' => 'boolean',
            'allow_review' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_premium'] = $request->has('is_premium');
        $validated['is_active'] = $request->has('is_active');
        $validated['shuffle_questions'] = $request->has('shuffle_questions');
        $validated['shuffle_options'] = $request->has('shuffle_options');
        $validated['show_explanations'] = $request->has('show_explanations');
        $validated['allow_review'] = $request->has('allow_review');

        Exam::create($validated);

        return redirect()->route('admin.exams.index')->with('success', 'Exam created successfully.');
    }

    public function edit(Exam $exam)
    {
        $categories = ExamCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.exams.form', compact('exam', 'categories'));
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:exam_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'total_questions' => 'required|integer|min:1',
            'time_limit_seconds' => 'required|integer|min:0',
            'passing_score_percent' => 'required|numeric|min:0|max:100',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_explanations' => 'boolean',
            'allow_review' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_premium'] = $request->has('is_premium');
        $validated['is_active'] = $request->has('is_active');
        $validated['shuffle_questions'] = $request->has('shuffle_questions');
        $validated['shuffle_options'] = $request->has('shuffle_options');
        $validated['show_explanations'] = $request->has('show_explanations');
        $validated['allow_review'] = $request->has('allow_review');

        $exam->update($validated);

        return redirect()->route('admin.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted.');
    }
}
