<x-public-layout
    metaTitle="ExamReady Community — Discuss & Prep for Philippine Exams"
    metaDescription="Join the ExamReady PH community forum. Share study tips, discuss practice questions, and prepare together for Civil Service, LET, UPCAT, and board exams."
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Community Header --}}
        <div class="card flat-card p-6 sm:p-8 bg-gradient-to-r from-blue-900 via-slate-900 to-indigo-950 text-white relative overflow-hidden mb-8 shadow-xl border-0">
            <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -top-10 w-48 h-48 rounded-full bg-indigo-500/10 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 text-xs font-semibold uppercase tracking-wider mb-3 border border-blue-400/20">
                        <i class="fa-solid fa-users text-xs"></i> ExamReady Forum
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-white mb-2">
                        Community Discussion Hub
                    </h1>
                    <p class="text-sm sm:text-base text-slate-300">
                        Connect with fellow examinees, ask questions, debate rationales, and share proven review strategies for Philippine exams.
                    </p>
                </div>

                {{-- Quick Actions --}}
                <div class="flex items-center gap-3 shrink-0">
                    @auth
                    <a href="{{ route('forum.create', $categories->first()) }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm px-5 py-3 rounded-xl transition-all shadow-lg hover:shadow-blue-500/25 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> Start Discussion
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm px-5 py-3 rounded-xl transition-all shadow-lg hover:shadow-blue-500/25 flex items-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Sign In to Post
                    </a>
                    @endauth
                </div>
            </div>

            {{-- Search Bar Integrated --}}
            <form method="GET" action="{{ route('forum.index') }}" class="relative mt-6 max-w-2xl">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search topics, questions, or subjects..."
                    class="w-full h-11 pl-11 pr-24 rounded-xl border-0 bg-white/10 dark:bg-slate-800/80 backdrop-blur-md text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-blue-400 focus:bg-white/20 transition">
                @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-lg transition">
                    Search
                </button>
            </form>
        </div>

        {{-- Category Pills & Filter Ribbon --}}
        <div class="mb-6 pb-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">

            {{-- Scrollable Category Pills with Controls --}}
            <div class="relative flex-1 min-w-0 flex items-center group">
                {{-- Left Scroll Arrow --}}
                <button type="button" onclick="document.getElementById('cat-ribbon').scrollBy({left: -200, behavior: 'smooth'})"
                    class="hidden sm:flex items-center justify-center w-7 h-7 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 shadow-md border border-slate-200 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition z-20 shrink-0 mr-1.5 opacity-80 hover:opacity-100">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </button>

                {{-- Scroll Container --}}
                <div id="cat-ribbon" class="flex items-center gap-2 overflow-x-auto scrollbar-none scroll-smooth py-1 px-0.5 max-w-full">
                    <a href="{{ route('forum.index', request()->except('category')) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 {{ !request('category') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <i class="fa-solid fa-layer-group"></i> All Topics
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('forum.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 whitespace-nowrap {{ request('category') === $cat->slug ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <i class="{{ $cat->icon }} text-xs"></i> {{ $cat->name }}
                    </a>
                    @endforeach
                </div>

                {{-- Right Scroll Arrow --}}
                <button type="button" onclick="document.getElementById('cat-ribbon').scrollBy({left: 200, behavior: 'smooth'})"
                    class="hidden sm:flex items-center justify-center w-7 h-7 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 shadow-md border border-slate-200 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition z-20 shrink-0 ml-1.5 opacity-80 hover:opacity-100">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>

            {{-- Feed Sort Toggles --}}
            <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 p-1 rounded-xl shrink-0 self-start sm:self-auto">
                <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $sort === 'newest' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <i class="fa-solid fa-clock mr-1"></i> Latest
                </a>
                <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'trending'])) }}"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $sort === 'trending' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    <i class="fa-solid fa-fire mr-1"></i> Popular
                </a>
            </div>
        </div>

        {{-- 2-Column Main Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">

            {{-- Primary Column: Discussion Feed --}}
            <main class="space-y-4">
                @if(request('search'))
                <div class="flex items-center justify-between text-xs text-slate-500 pb-2">
                    <span>Search results for <strong class="text-slate-800 dark:text-slate-200">"{{ request('search') }}"</strong></span>
                    <a href="{{ route('forum.index') }}" class="text-blue-600 hover:underline">Clear Search</a>
                </div>
                @endif

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
                                    @php $isThreadUpvoted = $thread->isUpvotedBy(auth()->user()); @endphp
                                    @auth
                                    <button type="button" onclick="toggleFeedUpvote({{ $thread->id }}, this)"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold transition-all shadow-xs {{ $isThreadUpvoted ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600' }}">
                                        <i class="fa-solid fa-thumbs-up text-[10px]"></i>
                                        <span class="upvote-count">{{ $thread->upvotes_count }}</span>
                                    </button>
                                    @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600">
                                        <i class="fa-solid fa-thumbs-up text-[10px]"></i>
                                        <span>{{ $thread->upvotes_count }}</span>
                                    </a>
                                    @endauth

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
                <div class="card flat-card p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No discussions found</h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto mb-5">
                        No threads match your search or filter. Start a new topic to kickstart the conversation!
                    </p>
                    @auth
                    <a href="{{ route('forum.create', $categories->first()) }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition">
                        <i class="fa-solid fa-plus"></i> Post First Thread
                    </a>
                    @endauth
                </div>
                @endforelse

                {{-- Pagination --}}
                @if($threads->hasPages())
                <div class="pt-4">{{ $threads->links() }}</div>
                @endif
            </main>

            {{-- Sidebar Column --}}
            <aside class="space-y-6">
                {{-- Forum Overview Stats Card --}}
                <div class="card flat-card p-5">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4 flex items-center justify-between">
                        <span>Community Overview</span>
                        <i class="fa-solid fa-chart-line text-blue-500"></i>
                    </h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="block text-xl font-extrabold text-slate-900 dark:text-white">{{ number_format($totalPosts) }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Threads</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <span class="block text-xl font-extrabold text-blue-600 dark:text-blue-400">{{ number_format($weeklyReplies) }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Replies / wk</span>
                        </div>
                    </div>
                </div>

                {{-- Categories Breakdown List --}}
                <div class="card flat-card p-5">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-3">Explore Categories</h3>
                    <div class="space-y-1">
                        @foreach($categories as $cat)
                        <a href="{{ route('forum.index', ['category' => $cat->slug]) }}"
                            class="flex items-center justify-between p-2 rounded-lg text-xs font-medium transition-colors {{ request('category') === $cat->slug ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            <div class="flex items-center gap-2 min-w-0">
                                <i class="{{ $cat->icon }} w-4 text-center text-blue-500"></i>
                                <span class="truncate">{{ $cat->name }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] text-slate-500 font-bold">
                                {{ $cat->threads_count ?? $cat->visibleThreads()->count() }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Practice Exam CTA --}}
                <div class="card flat-card p-5 bg-gradient-to-br from-blue-600 to-indigo-700 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <i class="fa-solid fa-lightbulb text-blue-200 text-2xl mb-2 block"></i>
                        <h3 class="font-bold text-white text-base mb-1">Boost Your Review</h3>
                        <p class="text-xs text-blue-100 mb-4 leading-relaxed">
                            Take timed mock exams with instant AI explanations tailored to your subject.
                        </p>
                        <a href="{{ route('reviewers') }}" class="inline-block w-full text-center bg-white text-blue-700 font-bold text-xs py-2.5 px-4 rounded-xl hover:bg-blue-50 transition shadow">
                            Start Mock Review
                        </a>
                    </div>
                </div>

                {{-- Forum Conduct Guidelines --}}
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-shield-halved text-emerald-500"></i> Community Code
                    </h4>
                    <ul class="space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-emerald-500 text-xs mt-0.5"></i> Be helpful and constructive.</li>
                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-emerald-500 text-xs mt-0.5"></i> No posting of unverified exam leaks.</li>
                        <li class="flex items-start gap-1.5"><i class="fa-solid fa-check text-emerald-500 text-xs mt-0.5"></i> Keep discussions exam-focused.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <script>
        function toggleFeedUpvote(id, btn) {
            fetch(`/community/upvote/thread/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const countSpan = btn.querySelector('.upvote-count');
                    if (countSpan) {
                        countSpan.innerText = data.upvotes_count;
                    }
                    if (data.upvoted) {
                        btn.classList.add('bg-blue-600', 'text-white');
                        btn.classList.remove('bg-slate-100', 'text-slate-600', 'dark:bg-slate-800', 'dark:text-slate-300');
                    } else {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-slate-100', 'text-slate-600');
                    }
                }
            })
            .catch(err => console.error('Upvote failed', err));
        }
    </script>
</x-public-layout>
