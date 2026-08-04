<x-public-layout
    metaTitle="ExamReady PH Community — Exam Discussion Forum, Review Strategies & rationales"
    metaDescription="Join thousands of Civil Service, LET Board Exam, and College Entrance Exam takers sharing study tips, rationale discussions, and practice strategies."
>
    {{-- Community Banner --}}
    <section class="bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 text-white py-10 relative overflow-hidden border-b border-slate-800">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-600/20 via-transparent to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 font-bold text-xs uppercase tracking-wider mb-3 inline-block">
                        <i class="fa-solid fa-users text-xs mr-1"></i> Examinee Hub
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        ExamReady PH Community
                    </h1>
                    <p class="mt-2 text-slate-300 text-sm sm:text-base leading-relaxed">
                        Connect with fellow test-takers. Ask questions, discuss rationales, share study tips, and prepare together.
                    </p>
                </div>

                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('forum.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs px-5 py-3 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i> New Discussion
                    </a>
                </div>
            </div>

            {{-- Search Bar --}}
            <form id="forum-search-form" onsubmit="submitSearchFeedAjax(event, this)" class="relative mt-6 max-w-2xl">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="search" id="forum-search-input" name="search" value="{{ request('search') }}" placeholder="Search topics, questions, or subjects..."
                    class="w-full h-11 pl-11 pr-24 rounded-xl border-0 bg-white/10 dark:bg-slate-800/80 backdrop-blur-md text-white placeholder-slate-400 text-sm focus:ring-2 focus:ring-blue-400 focus:bg-white/20 transition">
                <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-lg transition cursor-pointer">
                    Search
                </button>
            </form>
        </div>
    </section>

    {{-- Main Community Layout --}}
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Category Pills & Filter Ribbon --}}
            <div class="mb-6 pb-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4">

                {{-- Scrollable Category Pills with Controls --}}
                <div class="relative w-full flex-1 min-w-0 flex items-center group overflow-hidden">
                    {{-- Left Scroll Arrow --}}
                    <button type="button" onclick="document.getElementById('cat-ribbon').scrollBy({left: -200, behavior: 'smooth'})"
                        class="hidden sm:flex items-center justify-center w-7 h-7 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 shadow-md border border-slate-200 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition z-20 shrink-0 mr-1.5 opacity-80 hover:opacity-100 cursor-pointer">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>

                    {{-- Scroll Container with Zero-Refresh Click Handling --}}
                    <div id="cat-ribbon" class="flex items-center gap-2 overflow-x-auto scrollbar-none touch-pan-x scroll-smooth py-1 px-0.5 w-full">
                        <a href="{{ route('forum.index', request()->except('category')) }}"
                            onclick="loadFeedAjax(this.href, event, this)"
                            data-cat=""
                            class="cat-pill px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 whitespace-nowrap cursor-pointer {{ !request('category') ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            <i class="fa-solid fa-layer-group"></i> All Topics
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('forum.index', array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                            onclick="loadFeedAjax(this.href, event, this)"
                            data-cat="{{ $cat->slug }}"
                            class="cat-pill px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 whitespace-nowrap cursor-pointer {{ request('category') === $cat->slug ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            <i class="{{ $cat->icon }} text-xs"></i> {{ $cat->name }}
                        </a>
                        @endforeach
                    </div>

                    {{-- Right Scroll Arrow --}}
                    <button type="button" onclick="document.getElementById('cat-ribbon').scrollBy({left: 200, behavior: 'smooth'})"
                        class="hidden sm:flex items-center justify-center w-7 h-7 rounded-full bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 shadow-md border border-slate-200 dark:border-slate-700 hover:bg-blue-600 hover:text-white transition z-20 shrink-0 ml-1.5 opacity-80 hover:opacity-100 cursor-pointer">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>

                {{-- Feed Sort Toggles --}}
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 p-1 rounded-xl shrink-0 self-start sm:self-auto">
                    <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                        onclick="loadFeedAjax(this.href, event, this)"
                        data-sort="newest"
                        class="sort-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $sort === 'newest' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                        <i class="fa-solid fa-clock mr-1"></i> Latest
                    </a>
                    <a href="{{ route('forum.index', array_merge(request()->except('sort'), ['sort' => 'trending'])) }}"
                        onclick="loadFeedAjax(this.href, event, this)"
                        data-sort="trending"
                        class="sort-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer {{ $sort === 'trending' ? 'bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200' }}">
                        <i class="fa-solid fa-fire mr-1"></i> Popular
                    </a>
                </div>
            </div>

            {{-- 2-Column Main Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">

                {{-- Primary Column: Discussion Feed (AJAX Swappable) --}}
                <main class="space-y-4">
                    <div id="threads-feed-container" class="transition-opacity duration-200">
                        @include('forum.partials.thread_feed')
                    </div>
                </main>

                {{-- Secondary Column: Community Stats & Review Callout --}}
                <aside class="space-y-6">

                    {{-- Community Quick Stats --}}
                    <div class="card flat-card p-5">
                        <h3 class="text-xs font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                            <i class="fa-solid fa-chart-line text-blue-500"></i> Community Activity
                        </h3>
                        <div class="grid grid-cols-2 gap-3 text-center">
                            <div class="p-3 rounded-xl bg-blue-50/60 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/40">
                                <div class="text-xl font-extrabold text-blue-600 dark:text-blue-400">{{ number_format($totalPosts) }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Discussions</div>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50/60 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/40">
                                <div class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($weeklyReplies) }}</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold mt-0.5">Replies This Week</div>
                            </div>
                        </div>
                    </div>

                    {{-- Exam Prep Callout (Light & Dark Mode Matching Card Theme) --}}
                    <div class="card flat-card p-6 bg-emerald-50/60 dark:bg-slate-800 border border-emerald-200/80 dark:border-slate-700 rounded-2xl shadow-sm">
                        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight leading-snug mb-2">
                            Test Your Exam Readiness
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-normal mb-5">
                            Take timed practice drills with step-by-step Taglish AI explanations tailored to your target exam.
                        </p>
                        <a href="{{ route('reviewers') }}" class="block w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-3 px-4 rounded-xl transition shadow-sm active:scale-95">
                            Start Free Practice Drill
                        </a>
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
    </section>

    <script>
        function loadFeedAjax(url, e, el) {
            if (e) e.preventDefault();

            const container = document.getElementById('threads-feed-container');
            if (container) container.style.opacity = '0.4';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                if (container) {
                    container.innerHTML = html;
                    container.style.opacity = '1';
                }

                // Update browser URL without reloading page
                window.history.pushState(null, '', url);

                // Update active pill UI if triggered by a category or sort element
                if (el && el.classList.contains('cat-pill')) {
                    document.querySelectorAll('.cat-pill').forEach(p => {
                        p.className = 'cat-pill px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
                    });
                    el.className = 'cat-pill px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer bg-blue-600 text-white shadow-md shadow-blue-500/20';
                } else if (el && el.classList.contains('sort-tab')) {
                    document.querySelectorAll('.sort-tab').forEach(t => {
                        t.className = 'sort-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200';
                    });
                    el.className = 'sort-tab px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 shadow-sm';
                }

                // Re-bind pagination clicks inside container
                bindPaginationClicks();
            })
            .catch(err => {
                if (container) container.style.opacity = '1';
                window.location.href = url;
            });
        }

        function submitSearchFeedAjax(e, form) {
            e.preventDefault();
            const val = document.getElementById('forum-search-input').value;
            const currentUrl = new URL(window.location.href);
            if (val) {
                currentUrl.searchParams.set('search', val);
            } else {
                currentUrl.searchParams.delete('search');
            }
            loadFeedAjax(currentUrl.toString(), null, null);
        }

        function bindPaginationClicks() {
            const container = document.getElementById('threads-feed-container');
            if (!container) return;
            container.querySelectorAll('.pagination a').forEach(a => {
                a.onclick = function(e) {
                    loadFeedAjax(this.href, e, null);
                };
            });
        }

        document.addEventListener('DOMContentLoaded', bindPaginationClicks);

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
                if (data && data.success) {
                    const countSpan = btn.querySelector('.upvote-count');
                    if (countSpan) {
                        countSpan.innerText = data.upvotes_count;
                    }
                    if (data.upvoted) {
                        btn.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
                        btn.classList.add('bg-blue-600', 'text-white');
                    } else {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
                    }
                }
            })
            .catch(err => console.error('Upvote failed', err));
        }
    </script>
</x-public-layout>
