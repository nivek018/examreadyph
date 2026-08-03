@extends('layouts.admin')

@section('title', 'Subtopic Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Subtopics</h1>
        <p class="text-sm text-slate-500">Manage subtopics for each exam reviewer.</p>
    </div>
    <a href="{{ route('admin.subtopics.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
        <i class="fa-solid fa-plus"></i> New Subtopic
    </a>
</div>

{{-- Filters --}}
<div class="card flat-card p-4 mb-6 flex flex-wrap gap-3 items-center">
    <form method="GET" class="flex flex-wrap gap-3 items-center w-full">
        <select name="exam_id" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            <option value="">All Exams</option>
            @foreach($exams as $exam)
            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search subtopic..." class="flex-1 min-w-[200px] px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
        <button type="submit" class="bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold"><i class="fa-solid fa-search"></i></button>
    </form>
</div>

{{-- Table --}}
<div class="card flat-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase">Subtopic</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Exam</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Questions</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($subtopics as $subtopic)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <i class="{{ $subtopic->icon }}"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ $subtopic->name }}</div>
                            <div class="text-xs text-slate-500">{{ $subtopic->slug }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ $subtopic->exam->name }}</td>
                <td class="px-5 py-4 text-center"><span class="badge-blue text-[10px]">{{ $subtopic->questions_count }}</span></td>
                <td class="px-5 py-4 text-center">
                    @if($subtopic->is_active)
                        <span class="badge-emerald text-[10px]">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-bold">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.subtopics.edit', $subtopic) }}" class="text-xs text-blue-600 hover:text-blue-500 font-medium"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.subtopics.destroy', $subtopic) }}" onsubmit="return confirm('Delete this subtopic?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-rose-500 hover:text-rose-400 font-medium"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-10 text-center text-slate-500">No subtopics found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($subtopics, 'links'))
<div class="mt-4">{{ $subtopics->withQueryString()->links() }}</div>
@endif
@endsection
