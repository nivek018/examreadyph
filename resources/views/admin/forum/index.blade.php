@extends('layouts.admin')

@section('title', 'Forum Moderation')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Forum Moderation</h1>
        <p class="text-sm text-slate-500">Review reports, manage threads, and moderate community content.</p>
    </div>
    @if($pendingCount > 0)
    <span class="bg-rose-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">{{ $pendingCount }} pending</span>
    @endif
</div>

{{-- Pending Reports --}}
<div class="card flat-card overflow-hidden mb-8">
    <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-flag text-rose-500"></i> Pending Reports ({{ $pendingCount }})
        </h2>
    </div>

    @if($reports->isNotEmpty())
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase">Reported Content</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Reason</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Reporter</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Date</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @foreach($reports as $report)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                <td class="px-5 py-4">
                    @if($report->reportable)
                    <div class="max-w-[350px]">
                        @if($report->reportable_type === \App\Models\ForumThread::class)
                        <span class="badge-blue text-[10px] mb-1 inline-block">Thread</span>
                        <div class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $report->reportable->title }}</div>
                        @else
                        <span class="px-2 py-0.5 rounded bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-400 text-[10px] font-bold">Reply</span>
                        <div class="text-sm text-slate-700 dark:text-slate-300 truncate">{{ Str::limit($report->reportable->body, 80) }}</div>
                        @if($report->reportable->thread)
                        <div class="text-xs text-slate-400 mt-0.5">in: {{ $report->reportable->thread->title }}</div>
                        @endif
                        @endif
                        <div class="text-xs text-slate-500 mt-1">by {{ $report->reportable->user->name ?? 'Unknown' }}</div>
                    </div>
                    @else
                    <span class="text-xs text-slate-400 italic">Content deleted</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="px-2 py-0.5 rounded bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 text-[10px] font-bold">{{ ucfirst($report->reason) }}</span>
                    @if($report->description)
                    <div class="text-[11px] text-slate-400 mt-1 max-w-[150px] mx-auto truncate" title="{{ $report->description }}">{{ $report->description }}</div>
                    @endif
                </td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ $report->user->name ?? 'Unknown' }}</td>
                <td class="px-5 py-4 text-center text-xs text-slate-500">{{ $report->created_at->diffForHumans() }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($report->reportable)
                        <form method="POST" action="{{ route('admin.forum.spam', [$report->reportable_type === \App\Models\ForumThread::class ? 'thread' : 'reply', $report->reportable_id]) }}">
                            @csrf
                            <button class="text-xs text-rose-500 hover:text-rose-400 font-medium" title="Mark as spam"><i class="fa-solid fa-ban"></i> Spam</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.forum.resolve', $report) }}">
                            @csrf
                            <button class="text-xs text-emerald-600 hover:text-emerald-500 font-medium"><i class="fa-solid fa-check"></i> Resolve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.forum.dismiss', $report) }}">
                            @csrf
                            <button class="text-xs text-slate-500 hover:text-slate-400 font-medium"><i class="fa-solid fa-xmark"></i> Dismiss</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(method_exists($reports, 'links'))
    <div class="p-4">{{ $reports->links() }}</div>
    @endif
    @else
    <div class="px-5 py-10 text-center text-slate-500">
        <i class="fa-solid fa-shield-check text-emerald-500 text-2xl mb-2"></i>
        <p class="text-sm">No pending reports. The community is behaving well!</p>
    </div>
    @endif
</div>

{{-- Recent Threads --}}
<div class="card flat-card overflow-hidden">
    <div class="px-5 py-3 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-blue-500"></i> Recent Threads
        </h2>
    </div>
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase">Thread</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Category</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Replies</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-right">Manage</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($recentThreads as $thread)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                <td class="px-5 py-4">
                    <div class="max-w-[300px]">
                        <div class="font-semibold text-slate-900 dark:text-white text-sm truncate">{{ $thread->title }}</div>
                        <div class="text-xs text-slate-500">by {{ $thread->user->name ?? 'Unknown' }} · {{ $thread->created_at->diffForHumans() }}</div>
                    </div>
                </td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ $thread->category->name ?? '—' }}</td>
                <td class="px-5 py-4 text-center">
                    @if($thread->is_spam)
                    <span class="badge-rose text-[10px]">Spam</span>
                    @else
                    <span class="badge-emerald text-[10px]">Active</span>
                    @endif
                    @if($thread->is_pinned)
                    <span class="px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-[10px] font-bold ml-1"><i class="fa-solid fa-thumbtack"></i></span>
                    @endif
                    @if($thread->is_locked)
                    <span class="px-1.5 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-[10px] font-bold ml-1"><i class="fa-solid fa-lock"></i></span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ $thread->replies_count }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('forum.show', [$thread->category, $thread]) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-500 font-medium"><i class="fa-solid fa-eye"></i></a>
                        <form method="POST" action="{{ route('admin.forum.pin', $thread) }}" class="inline">
                            @csrf
                            <button class="text-xs {{ $thread->is_pinned ? 'text-amber-500' : 'text-slate-400 hover:text-amber-500' }} font-medium" title="{{ $thread->is_pinned ? 'Unpin' : 'Pin' }}">
                                <i class="fa-solid fa-thumbtack"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.forum.lock', $thread) }}" class="inline">
                            @csrf
                            <button class="text-xs {{ $thread->is_locked ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500' }} font-medium" title="{{ $thread->is_locked ? 'Unlock' : 'Lock' }}">
                                <i class="fa-solid fa-lock"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.forum.spam', ['thread', $thread->id]) }}" class="inline">
                            @csrf
                            @if($thread->is_spam)
                            <button class="text-xs text-emerald-600 hover:text-emerald-500 font-bold inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 rounded" title="Undo Spam / Restore Thread">
                                <i class="fa-solid fa-rotate-left"></i> Restore
                            </button>
                            @else
                            <button class="text-xs text-slate-400 hover:text-rose-500 font-medium inline-flex items-center gap-1" title="Mark as Spam">
                                <i class="fa-solid fa-ban"></i> Spam
                            </button>
                            @endif
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500 text-sm">No forum threads yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
