@extends('layouts.admin')

@section('title', $subtopic ? 'Edit Subtopic' : 'New Subtopic')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.subtopics.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Subtopics</a>
    </div>

    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">{{ $subtopic ? 'Edit Subtopic' : 'Create Subtopic' }}</h1>

    <form method="POST" action="{{ $subtopic ? route('admin.subtopics.update', $subtopic) : route('admin.subtopics.store') }}" class="card flat-card p-6 space-y-5">
        @csrf
        @if($subtopic) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Exam <span class="text-rose-500">*</span></label>
            <select name="exam_id" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                <option value="">Select an exam...</option>
                @foreach($exams as $exam)
                <option value="{{ $exam->id }}" {{ old('exam_id', $subtopic?->exam_id) == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                @endforeach
            </select>
            @error('exam_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Subtopic Name <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $subtopic?->name) }}" required placeholder="e.g. Numerical Reasoning" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Description <span class="text-slate-400">(SEO)</span></label>
            <textarea name="description" rows="3" placeholder="Brief description for this subtopic (shown on SEO page)..." class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">{{ old('description', $subtopic?->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Icon Class</label>
                <input type="text" name="icon" value="{{ old('icon', $subtopic?->icon ?? 'fa-solid fa-book-open') }}" placeholder="fa-solid fa-calculator" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $subtopic?->sort_order ?? 0) }}" min="0" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $subtopic?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <label for="is_active" class="text-sm font-medium text-slate-900 dark:text-white">Active</label>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition w-full">
            <i class="fa-solid fa-check mr-1"></i> {{ $subtopic ? 'Update Subtopic' : 'Create Subtopic' }}
        </button>
    </form>
</div>
@endsection
