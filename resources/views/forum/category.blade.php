<x-public-layout
    :metaTitle="$category->name . ' — ExamReady PH Community'"
    :metaDescription="'Browse ' . $category->name . ' discussions in the ExamReady PH community forum.'"
>
    {{-- Breadcrumb --}}
    <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3">
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('forum.index') }}" class="hover:text-blue-600 transition">Community</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $category->name }}</span>
            </nav>
        </div>
    </div>

    <section class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            {{-- Category Header --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                        <i class="{{ $category->icon }}"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ $category->name }}</h1>
                        @if($category->description)
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
                @auth
                <a href="{{ route('forum.create', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> New Thread
                </a>
                @else
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i> Login to Post
                </a>
                @endauth
            </div>

            {{-- Threads List --}}
            @if($threads->isNotEmpty())
            <div class="space-y-3">
                @foreach($threads as $thread)
                <a href="{{ route('forum.show', [$category, $thread]) }}" class="block group card flat-card p-4 sm:p-5 hover:shadow-md transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-700">
                    <div class="flex items-start gap-4">
                        {{-- Author Avatar --}}
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm">
                            {{ strtoupper(substr($thread->user->name ?? 'A', 0, 1)) }}
                        </div>
                        {{-- Thread Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                @if($thread->is_pinned)
                                <span class="px-2 py-0.5 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-thumbtack mr-0.5"></i> Pinned</span>
                                @endif
                                @if($thread->is_locked)
                                <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-lock mr-0.5"></i> Locked</span>
                                @endif
                                <h3 class="font-bold text-slate-900 dark:text-white text-sm group-hover:text-blue-600 transition line-clamp-1">{{ $thread->title }}</h3>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mb-2">{{ Str::limit(strip_tags($thread->body), 120) }}</p>
                            <div class="flex items-center gap-3 text-xs text-slate-400">
                                <span class="font-medium text-slate-600 dark:text-slate-300">{{ $thread->user->name ?? 'Unknown' }}</span>
                                <span>{{ $thread->formatted_date }}</span>
                            </div>
                        </div>
                        {{-- Stats --}}
                        <div class="hidden sm:flex items-center gap-5 text-xs text-slate-400 shrink-0">
                            <div class="text-center">
                                <div class="font-bold text-slate-700 dark:text-slate-200 text-sm">{{ $thread->replies_count }}</div>
                                <div>replies</div>
                            </div>
                            <div class="text-center">
                                <div class="font-bold text-slate-700 dark:text-slate-200 text-sm">{{ number_format($thread->views_count) }}</div>
                                <div>views</div>
                            </div>
                            <div class="text-right min-w-[80px]">
                                <div class="text-slate-600 dark:text-slate-300 font-medium">{{ $thread->lastReplyUser->name ?? $thread->user->name ?? '—' }}</div>
                                <div>{{ $thread->last_activity }}</div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-6">{{ $threads->links() }}</div>
            @else
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-message text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No threads yet</h3>
                <p class="text-sm text-slate-500 mb-4">Be the first to start a discussion in {{ $category->name }}!</p>
                @auth
                <a href="{{ route('forum.create', $category) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-plus"></i> Start a Thread
                </a>
                @endauth
            </div>
            @endif
        </div>
    </section>
</x-public-layout>
