@forelse($threads as $thread)
<article class="card flat-card p-5 hover:border-blue-400 dark:hover:border-blue-600 transition-all duration-200 group">
    <div class="flex items-start gap-4">
        {{-- User Avatar --}}
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold flex items-center justify-center text-sm shrink-0 shadow-sm">
            {{ strtoupper(substr($thread->user->name ?? 'A', 0, 1)) }}
        </div>

        {{-- Discussion Main Info --}}
        <div class="flex-1 min-w-0">
            {{-- Meta Badges & Category --}}
            <div class="flex items-center gap-2 flex-wrap text-xs mb-1.5">
                @if($thread->is_pinned)
                <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold text-[10px] uppercase tracking-wide flex items-center gap-1 border border-amber-200 dark:border-amber-800">
                    <i class="fa-solid fa-thumbtack text-[9px]"></i> Pinned
                </span>
                @endif
                <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 font-semibold text-[11px] border border-blue-100 dark:border-blue-900/50">
                    <i class="{{ $thread->category->icon ?? 'fa-solid fa-folder' }} text-[10px] mr-1"></i> {{ $thread->category->name ?? 'General' }}
                </span>
                <span class="text-slate-400 dark:text-slate-500">by <strong class="text-slate-700 dark:text-slate-300">{{ $thread->user->name ?? 'Anonymous' }}</strong></span>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <span class="text-slate-400 dark:text-slate-500">{{ $thread->created_at->diffForHumans() }}</span>
            </div>

            {{-- Title --}}
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-snug mb-1.5">
                <a href="{{ route('forum.show', [$thread->category, $thread]) }}">
                    {{ $thread->title }}
                </a>
            </h2>

            {{-- Text Excerpt --}}
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed mb-3">
                {{ Str::limit(strip_tags($thread->body), 160) }}
            </p>

            {{-- Footer Info & Counters --}}
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                <div class="flex items-center gap-4">
                    @php $isThreadUpvoted = $thread->isUpvotedBy(auth()->user(), request()->ip()); @endphp
                    <button type="button" onclick="toggleFeedUpvote({{ $thread->id }}, this)"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold transition-all shadow-xs cursor-pointer {{ $isThreadUpvoted ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600' }}">
                        <i class="fa-solid fa-thumbs-up text-[10px]"></i>
                        <span class="upvote-count">{{ $thread->upvotes_count }}</span>
                    </button>

                    <span class="flex items-center gap-1.5 font-medium text-slate-600 dark:text-slate-300">
                        <i class="fa-regular fa-comment-dots text-blue-500"></i> {{ $thread->replies_count }} {{ Str::plural('Reply', $thread->replies_count) }}
                    </span>
                    <span class="hidden sm:flex items-center gap-1.5">
                        <i class="fa-regular fa-eye"></i> {{ number_format($thread->views_count) }} views
                    </span>
                    @if($thread->is_locked)
                    <span class="flex items-center gap-1 text-rose-500 font-semibold">
                        <i class="fa-solid fa-lock text-[10px]"></i> Locked
                    </span>
                    @endif
                </div>

                <a href="{{ route('forum.show', [$thread->category, $thread]) }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    Open <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</article>
@empty
<div class="text-center py-16 card flat-card">
    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center mx-auto mb-3 text-2xl">
        <i class="fa-solid fa-comments"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No discussions found</h3>
    <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">Be the first to start a conversation in this topic!</p>
    <a href="{{ route('forum.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md transition">
        <i class="fa-solid fa-plus text-xs"></i> Create New Discussion
    </a>
</div>
@endforelse

<div class="pt-4">{{ $threads->links() }}</div>
