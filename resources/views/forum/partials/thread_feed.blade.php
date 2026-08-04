<div class="space-y-4">
@forelse($threads as $thread)
<article
    onclick="if(!event.target.closest('button, a, input, select')) { window.location.href='{{ route('forum.show', [$thread->category, $thread]) }}'; }"
    class="card flat-card p-4 sm:p-5 transition-all duration-200 border border-slate-200 dark:border-slate-800 hover:border-blue-400 dark:hover:border-blue-500/80 hover:bg-slate-50/50 dark:hover:bg-slate-800/80 rounded-2xl cursor-pointer group relative">

    <div class="flex items-start gap-3.5 sm:gap-4">
        {{-- LEFT COLUMN: Upvote / Like Button (Left Side) --}}
        @php $isThreadUpvoted = $thread->isUpvotedBy(auth()->user(), request()->ip()); @endphp
        <div class="flex flex-col items-center justify-center shrink-0 pt-0.5">
            <button type="button" onclick="toggleFeedUpvote({{ $thread->id }}, this); event.stopPropagation();"
                class="upvote-btn inline-flex flex-col items-center justify-center w-11 h-12 rounded-xl font-bold transition-all shadow-xs cursor-pointer border {{ $isThreadUpvoted ? 'bg-blue-600 text-white border-blue-600 shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-600' }}">
                <i class="fa-solid fa-thumbs-up text-xs"></i>
                <span class="upvote-count text-xs mt-0.5">{{ $thread->upvotes_count }}</span>
            </button>
        </div>

        {{-- RIGHT COLUMN: Discussion Main Info --}}
        <div class="flex-1 min-w-0">
            {{-- Meta Badges & Category --}}
            <div class="flex items-center gap-2 flex-wrap text-xs mb-1.5">
                @if($thread->is_pinned)
                <span class="px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold text-[10px] uppercase tracking-wide flex items-center gap-1 border border-amber-200 dark:border-amber-800">
                    <i class="fa-solid fa-thumbtack text-[9px]"></i> Pinned
                </span>
                @endif
                <span class="px-2.5 py-0.5 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold text-[11px] border border-blue-100 dark:border-blue-900/50">
                    <i class="{{ $thread->category->icon ?? 'fa-solid fa-folder' }} text-[10px] mr-1"></i> {{ $thread->category->name ?? 'General' }}
                </span>
                <span class="inline-flex items-center gap-1.5 text-slate-400 dark:text-slate-500 text-xs">
                    by
                    <img src="{{ $thread->user->avatar_url ?? 'https://api.dicebear.com/7.x/personas/svg?seed=Anonymous' }}" alt="{{ $thread->user->name ?? 'User' }}" class="w-5 h-5 rounded-md object-cover bg-white dark:bg-slate-800 p-0.5 border border-slate-200 dark:border-slate-700">
                    <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $thread->user->name ?? 'Anonymous' }}</strong>
                </span>
                <span class="text-slate-300 dark:text-slate-700">•</span>
                <span class="text-slate-400 dark:text-slate-500 text-xs">{{ $thread->created_at->diffForHumans() }}</span>
            </div>

            {{-- Title --}}
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-snug mb-1.5">
                <a href="{{ route('forum.show', [$thread->category, $thread]) }}" class="hover:underline">
                    {{ $thread->title }}
                </a>
            </h2>

            {{-- Text Excerpt --}}
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 line-clamp-2 leading-relaxed mb-3 font-normal">
                {{ Str::limit(strip_tags($thread->body), 160) }}
            </p>

            {{-- Footer Info & Counters --}}
            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pt-2.5 border-t border-slate-100 dark:border-slate-800/80">
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1.5 font-semibold text-slate-700 dark:text-slate-300">
                        <i class="fa-regular fa-comment-dots text-blue-500"></i> {{ $thread->replies_count }} {{ Str::plural('Reply', $thread->replies_count) }}
                    </span>
                    <span class="hidden sm:flex items-center gap-1.5 text-slate-400">
                        <i class="fa-regular fa-eye"></i> {{ number_format($thread->views_count) }} views
                    </span>
                    @if($thread->is_locked)
                    <span class="flex items-center gap-1 text-rose-500 font-semibold">
                        <i class="fa-solid fa-lock text-[10px]"></i> Locked
                    </span>
                    @endif
                </div>

                <a href="{{ route('forum.show', [$thread->category, $thread]) }}" class="font-bold text-blue-600 dark:text-blue-400 group-hover:translate-x-0.5 transition-transform flex items-center gap-1.5 text-xs shrink-0">
                    Join Discussion <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</article>
@empty
<div class="text-center py-16 card flat-card p-8 rounded-2xl border border-slate-200 dark:border-slate-800">
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
</div>

<div class="pt-4">{{ $threads->links() }}</div>
