<x-admin-layout title="Reported Questions">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Reported Questions</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Review issues reported by users regarding question content, answer keys, or typos.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="card p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <select name="status" class="px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending Reports</option>
            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved Reports</option>
            <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Dismissed Reports</option>
        </select>
        <button class="btn-brand px-4 py-2 text-sm"><i class="fa-solid fa-search"></i> Filter</button>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Question</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Issue Reported</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Reported By</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($reports as $report)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                    <td class="px-5 py-4 max-w-sm">
                        <div class="font-semibold text-slate-900 dark:text-white truncate">{{ Str::limit($report->question->question_text ?? 'Deleted Question', 70) }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $report->question->exam->name ?? '—' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge-amber text-[10px] mb-1 inline-block">{{ $report->formatted_reason }}</span>
                        @if($report->description)
                        <p class="text-xs text-slate-600 dark:text-slate-400 italic">"{{ $report->description }}"</p>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-xs font-medium text-slate-900 dark:text-white">{{ $report->user->name ?? 'User' }}</div>
                        <div class="text-[10px] text-slate-500">{{ $report->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($report->status === 'pending')
                            <span class="badge-amber text-[10px]">Pending</span>
                        @elseif($report->status === 'resolved')
                            <span class="badge-emerald text-[10px]">Resolved</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-[10px] font-bold">Dismissed</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($report->question)
                            <a href="{{ route('admin.questions.edit', $report->question) }}" target="_blank" class="text-blue-600 hover:text-blue-500 text-xs font-medium" title="Edit Question">
                                <i class="fa-solid fa-pen-to-square"></i> Edit Q
                            </a>
                            @endif

                            @if($report->status === 'pending')
                            <form method="POST" action="{{ route('admin.reported-questions.resolve', $report) }}" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="resolved">
                                <button class="text-xs font-medium text-emerald-600 hover:text-emerald-500"><i class="fa-solid fa-check"></i> Resolve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.reported-questions.resolve', $report) }}" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="dismissed">
                                <button class="text-xs font-medium text-slate-500 hover:text-slate-400"><i class="fa-solid fa-xmark"></i> Dismiss</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                        <i class="fa-solid fa-shield-check text-3xl mb-3 text-slate-400"></i>
                        <p class="font-medium">No {{ request('status', 'pending') }} reported questions found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reports->hasPages())
    <div class="mt-4">{{ $reports->withQueryString()->links() }}</div>
    @endif

</x-admin-layout>
