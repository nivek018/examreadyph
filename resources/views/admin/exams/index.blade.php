<x-admin-layout title="Exams">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Exams</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manage individual exam modules under each category.</p>
        </div>
        <a href="{{ route('admin.exams.create') }}" class="btn-brand px-4 py-2.5 text-sm">
            <i class="fa-solid fa-plus"></i> New Exam
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="card p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search exams..."
               class="flex-1 px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
        <select name="category" class="px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button class="btn-brand px-4 py-2 text-sm"><i class="fa-solid fa-search"></i> Filter</button>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Exam</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Category</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Questions</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Time</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Pass%</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($exams as $exam)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-900 dark:text-white">{{ $exam->name }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ Str::limit($exam->description, 60) }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="{{ $exam->category->color_class ?? 'badge-blue' }}">{{ $exam->category->name ?? '—' }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white">{{ $exam->questions_count ?? 0 }}</span>
                            <span class="text-slate-400">/ {{ $exam->total_questions }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-600 dark:text-slate-400">{{ $exam->formatted_time_limit }}</td>
                    <td class="px-5 py-4 text-center font-semibold text-slate-900 dark:text-white">{{ $exam->passing_score_percent }}%</td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex flex-col items-center gap-1">
                            @if($exam->is_active)
                                <span class="badge-emerald text-[10px]">Active</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-[10px] font-bold">Inactive</span>
                            @endif
                            @if($exam->is_premium)
                                <span class="badge-amber text-[10px]">Premium</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.questions.index', ['exam_id' => $exam->id]) }}" class="text-emerald-600 hover:text-emerald-500 text-xs font-medium" title="View Questions"><i class="fa-solid fa-list"></i></a>
                            <a href="{{ route('admin.exams.edit', $exam) }}" class="text-blue-600 hover:text-blue-500 text-xs font-medium"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}" onsubmit="return confirm('Delete this exam and all its questions?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-500 hover:text-rose-400 text-xs font-medium"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                        <i class="fa-solid fa-book-open text-3xl mb-3 text-slate-400"></i>
                        <p class="font-medium">No exams yet. <a href="{{ route('admin.exams.create') }}" class="text-blue-600">Create one</a>.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($exams->hasPages())
    <div class="mt-4">{{ $exams->withQueryString()->links() }}</div>
    @endif

</x-admin-layout>
