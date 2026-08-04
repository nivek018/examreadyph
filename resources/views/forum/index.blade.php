<x-public-layout
    metaTitle="ExamReady Community — Pair Up with Filipino Exam Reviewers"
    metaDescription="Join the ExamReady PH community forum. Discuss exam strategies, share tips, and connect with fellow Filipino examinees preparing for Civil Service, LET, UPCAT, and more."
>
    {{-- Hero Banner (Desktop) --}}
    <div class="hidden sm:block px-4 pt-8">
        <div class="relative mx-auto max-w-7xl overflow-hidden rounded-2xl shadow-lg" style="background: linear-gradient(145deg, #2563eb 0%, #1e40af 100%)">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -right-20 -top-20 w-72 h-72 rounded-full bg-white/20"></div>
                <div class="absolute -left-10 bottom-0 w-40 h-40 rounded-full bg-white/10"></div>
            </div>
            <div class="relative z-10 max-w-2xl p-8 pr-40">
                <h1 class="text-3xl font-extrabold tracking-tight text-white">Community</h1>
                <p class="mt-2 text-sm leading-relaxed text-white/85 sm:text-base">
                    Ask questions, share study tips, and help fellow examinees. Let's conquer the board exam together! 💙
                </p>
                <form method="GET" action="{{ route('forum.index') }}" class="relative mt-5 max-w-xl">
                    <i class="fa-solid fa-search pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search discussions, topics, or subjects…"
                        class="h-11 w-full rounded-full border-0 bg-white pl-10 pr-4 text-sm text-slate-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-white/60 placeholder:text-slate-400">
                    @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </form>
            </div>
            <div class="pointer-events-none absolute bottom-4 right-8 h-24 w-24 rounded-full bg-white/10 flex items-center justify-center ring-4 ring-white/25">
                <i class="fa-solid fa-users text-white/80 text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Hero Banner (Mobile) --}}
    <div class="sm:hidden border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-5 pb-3 space-y-3">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">Community</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ask questions, share tips, and help fellow examinees!</p>
        </div>
        <form method="GET" action="{{ route('forum.index') }}" class="relative">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search discussions…"
                class="w-full h-9 rounded-full border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-9 pr-4 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
        </form>
        {{-- Mobile Category Pills --}}
        <div class="flex gap-2 overflow-x-auto pb-1 -mx-1 px-1 scrollbar-none">
            @auth
            <a href="{{ route('forum.create', $categories->first()) }}" class="flex items-center gap-1.5 bg-blue-600 text-white rounded-full px-4 py-2 text-sm font-semibold whitespace-nowrap shrink-0">
                <i class="fa-solid fa-plus text-xs"></i> New Post
            </a>
            <div class="w-px bg-slate-200 dark:bg-slate-700 shrink-0 self-stretch"></div>
            @else
            <a href="{{ route('login') }}" class="flex items-center gap-1.5 bg-blue-600 text-white rounded-full px-4 py-2 text-sm font-semibold whitespace-nowrap shrink-0">
                <i class="fa-solid fa-plus text-xs"></i> Sign in to Post
            </a>
            <div class="w-px bg-slate-200 dark:bg-slate-700 shrink-0 self-stretch"></div>
            @endauth
            <a href="{{ route('forum.index') }}" class="rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap shrink-0 border transition-colors {{ !request('category') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-700' : 'bg-white dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700' }}">All</a>
            @foreach($categories as $cat)
            <a href="{{ route('forum.index', ['category' => $cat->slug]) }}" class="rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap shrink-0 border transition-colors {{ request('category') === $cat->slug ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-700' : 'bg-white dark:bg-slate-800 text-slate-500 border-slate-200 dark:border-slate-700' }}">
                <i class="{{ $cat->icon }} mr-1 text-xs"></i> {{ $cat->name }}
            </a>
            @endforeach
        </div>
        {{-- Mobile Sort Tabs --}}
        <div class="flex border-b border-slate-200 dark:border-slate-700 -mx-4 px-4">
            <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" class="px-4 py-2 text-sm font-semibold border-b-2 transition-colors {{ $sort === 'newest' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400' }}">Recent</a>
            <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'trending'])) }}" class="px-4 py-2 text-sm font-semibold border-b-2 transition-colors {{ $sort === 'trending' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-400' }}">Popular</a>
        </div>
    </div>

    {{-- 3-Column Layout --}}
    <div class="max-w-7xl mx-auto px-4 py-4 sm:py-8 grid grid-cols-1 lg:grid-cols-[240px_1fr_240px] gap-6">

        {{-- LEFT SIDEBAR --}}
        <aside class="hidden lg:block space-y-5">
            {{-- New Post Button --}}
            @auth
            <a href="{{ route('forum.create', $categories->first()) }}" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition shadow-sm active:scale-[0.97]">
                <i class="fa-solid fa-plus"></i> New Discussion
            </a>
            @else
            <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 px-4 rounded-xl transition shadow-sm">
                <i class="fa-solid fa-plus"></i> Sign in to Post
            </a>
            @endauth

            {{-- Community Stats Card --}}
            <div class="card flat-card overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-chart-simple text-blue-500"></i> Community Stats
                    </h3>
                </div>
                <div class="px-4 py-3 space-y-3">
                    <div>
                        <div class="text-[11px] text-slate-400 uppercase tracking-wider font-medium">Total Posts</div>
                        <div class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalPosts) }}</div>
                    </div>
                    <div class="border-t border-slate-100 dark:border-slate-800"></div>
                    <div>
                        <div class="text-[11px] text-slate-400 uppercase tracking-wider font-medium">Weekly Replies</div>
                        <div class="text-2xl font-extrabold text-slate-900 dark:text-white">{{ number_format($weeklyReplies) }}</div>
                    </div>
                </div>
            </div>

            {{-- Filter by Category --}}
            <div class="card flat-card overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Filter by Category</h3>
                </div>
                <div class="px-4 py-3 space-y-1">
                    <a href="{{ route('forum.index', request()->except('category')) }}" class="w-full text-left px-2 py-1.5 rounded text-sm transition-colors block {{ !request('category') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500' }}">
                        All Categories
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('forum.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}" class="w-full text-left px-2 py-1.5 rounded text-sm transition-colors flex items-center gap-2 {{ request('category') === $cat->slug ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-medium' : 'hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-500' }}">
                        <i class="{{ $cat->icon }} text-xs w-4 text-center"></i> {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- CENTER — Discussion Feed --}}
        <main class="space-y-4">
            {{-- Desktop Sort Bar --}}
            <div class="hidden sm:flex items-center justify-between flex-wrap gap-2">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Discussions</h2>
                    @if(request('search'))
                    <span class="text-sm text-slate-400">for "{{ request('search') }}"</span>
                    @endif
                    @if(request('category'))
                    <span class="badge-blue text-[10px]">{{ request('category') }}</span>
                    @endif
                </div>
                <div class="flex gap-1 bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                    <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors {{ $sort === 'newest' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">Newest</a>
                    <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'trending'])) }}" class="px-4 py-1.5 rounded-md text-sm font-medium transition-colors {{ $sort === 'trending' ? 'bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white' : 'text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">Trending</a>
                </div>
            </div>

            {{-- Thread Cards --}}
            @forelse($threads as $thread)
            <a href="{{ route('forum.show', [$thread->category, $thread]) }}" class="block group">
                <div class="flex gap-4 p-5 border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition-all duration-200">
                    {{-- Upvote Column --}}
                    <div class="flex flex-col items-center gap-0.5 w-10 shrink-0 pt-1">
                        <i class="fa-solid fa-chevron-up text-slate-300 dark:text-slate-600 text-sm"></i>
                        <span class="text-sm font-bold text-slate-600 dark:text-slate-300">{{ $thread->replies_count }}</span>
                        <span class="text-[10px] text-slate-400">replies</span>
                    </div>

                    {{-- Thread Content --}}
                    <div class="flex-1 min-w-0">
                        {{-- Tags Row --}}
                        <div class="flex items-center gap-2 flex-wrap mb-1.5 text-xs">
                            @if($thread->is_pinned)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 font-bold text-[10px] uppercase">
                                <i class="fa-solid fa-thumbtack text-[8px]"></i> Pinned
                            </span>
                            @endif
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-medium">
                                <i class="{{ $thread->category->icon ?? 'fa-solid fa-folder' }} text-[10px]"></i> {{ $thread->category->name ?? 'General' }}
                            </span>
                            <span class="text-slate-400 dark:text-slate-500">Posted by <strong class="text-slate-600 dark:text-slate-300">{{ $thread->user->name ?? 'Unknown' }}</strong></span>
                            <span class="text-slate-300 dark:text-slate-600">·</span>
                            <span class="text-slate-400 dark:text-slate-500">{{ $thread->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Title --}}
                        <h3 class="font-bold text-slate-900 dark:text-white text-base leading-snug group-hover:text-blue-600 transition mb-1">{{ $thread->title }}</h3>

                        {{-- Preview --}}
                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-2">{{ Str::limit(strip_tags($thread->body), 150) }}</p>

                        {{-- Footer --}}
                        <div class="flex items-center gap-4 text-xs text-slate-400">
                            <span class="flex items-center gap-1">
                                <i class="fa-regular fa-comment"></i> {{ $thread->replies_count }} {{ Str::plural('Comment', $thread->replies_count) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fa-regular fa-eye"></i> {{ number_format($thread->views_count) }} views
                            </span>
                            @if($thread->is_locked)
                            <span class="flex items-center gap-1 text-slate-400">
                                <i class="fa-solid fa-lock text-[10px]"></i> Locked
                            </span>
                            @endif
                            <span class="ml-auto text-blue-600 dark:text-blue-400 font-semibold group-hover:underline">
                                Open <i class="fa-solid fa-chevron-right text-[9px]"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center py-16 border border-dashed border-slate-300 dark:border-slate-700 rounded-xl">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-message text-2xl text-slate-300 dark:text-slate-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No discussions yet</h3>
                <p class="text-sm text-slate-500 mb-4">Be the first to start a conversation!</p>
                @auth
                <a href="{{ route('forum.create', $categories->first()) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-plus"></i> Start a Discussion
                </a>
                @endauth
            </div>
            @endforelse

            {{-- Pagination --}}
            @if($threads->hasPages())
            <div class="pt-2">{{ $threads->links() }}</div>
            @endif
        </main>

        {{-- RIGHT SIDEBAR --}}
        <aside class="hidden lg:block space-y-5">
            {{-- Trending Topics --}}
            <div class="card flat-card overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Trending Topics</h3>
                </div>
                <div class="px-4 py-3 space-y-2">
                    @php
                        $trendingCategories = $categories->map(function($cat) {
                            $cat->active_count = \App\Models\ForumThread::visible()
                                ->where('category_id', $cat->id)
                                ->count();
                            return $cat;
                        })->sortByDesc('active_count')->take(5);
                    @endphp
                    @forelse($trendingCategories as $tc)
                    <div class="flex items-center justify-between text-sm">
                        <a href="{{ route('forum.index', ['category' => $tc->slug]) }}" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 transition truncate">{{ $tc->name }}</a>
                        <span class="text-xs text-slate-400 font-medium tabular-nums">{{ $tc->active_count }}</span>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400">No trending topics yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- House Rules --}}
            <div class="card flat-card overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-800">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">House Rules</h3>
                </div>
                <div class="px-4 py-3 space-y-2.5">
                    <div class="flex gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✓</span>
                        <span>Be respectful and helpful to fellow examinees.</span>
                    </div>
                    <div class="flex gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✓</span>
                        <span>No sharing of actual exam questions or leaks.</span>
                    </div>
                    <div class="flex gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✓</span>
                        <span>Verify sources where possible.</span>
                    </div>
                    <div class="flex gap-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="text-emerald-500 mt-0.5 shrink-0">✓</span>
                        <span>Keep posts relevant to exam review.</span>
                    </div>
                </div>
            </div>

            {{-- Start Practicing CTA --}}
            <div class="card flat-card overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-950/30 dark:to-blue-900/20 border-blue-200 dark:border-blue-800/50">
                <div class="p-4 text-center">
                    <div class="text-2xl mb-2">📝</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1">Ready to practice?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Take a mock exam with AI-powered explanations.</p>
                    <a href="{{ route('reviewers') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg transition">Start Review</a>
                </div>
            </div>
        </aside>
    </div>
</x-public-layout>
