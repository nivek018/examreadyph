<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubtopicController extends Controller
{
    public function index(Request $request)
    {
        $query = Subtopic::with('exam')->withCount('questions');

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $subtopics = $query->orderBy('exam_id')->orderBy('sort_order')->paginate(25);
        $exams = Exam::where('is_active', true)->orderBy('name')->get();

        return view('admin.subtopics.index', compact('subtopics', 'exams'));
    }

    public function create()
    {
        $exams = Exam::where('is_active', true)->orderBy('name')->get();
        return view('admin.subtopics.form', ['subtopic' => null, 'exams' => $exams]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['icon'] = $validated['icon'] ?? 'fa-solid fa-book-open';
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        Subtopic::create($validated);

        return redirect()->route('admin.subtopics.index')->with('success', 'Subtopic created successfully.');
    }

    public function edit(Subtopic $subtopic)
    {
        $exams = Exam::where('is_active', true)->orderBy('name')->get();
        return view('admin.subtopics.form', compact('subtopic', 'exams'));
    }

    public function update(Request $request, Subtopic $subtopic)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $subtopic->update($validated);
        $subtopic->refreshQuestionCount();

        return redirect()->route('admin.subtopics.index')->with('success', 'Subtopic updated successfully.');
    }

    public function destroy(Subtopic $subtopic)
    {
        $subtopic->delete();
        return redirect()->route('admin.subtopics.index')->with('success', 'Subtopic deleted.');
    }
}
