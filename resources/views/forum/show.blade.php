<x-public-layout
    :metaTitle="$thread->title . ' — ' . $category->name . ' | ExamReady PH Community'"
    :metaDescription="Str::limit(strip_tags($thread->body), 160)"
>
    {{-- Breadcrumb Bar --}}
    <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between">
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <a href="{{ route('forum.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                        <i class="fa-solid fa-arrow-left text-[10px]"></i> Community
                    </a>
                    <i class="fa-solid fa-chevron-right text-[8px] opacity-40"></i>
                    <a href="{{ route('forum.index', ['category' => $category->slug]) }}" class="hover:text-blue-600 transition">{{ $category->name }}</a>
                    <i class="fa-solid fa-chevron-right text-[8px] opacity-40"></i>
                    <span class="text-slate-700 dark:text-slate-300 font-medium truncate max-w-[180px] sm:max-w-[300px]">{{ $thread->title }}</span>
                </nav>

                <a href="#replies-feed" class="hidden sm:inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-sm">
                    <i class="fa-solid fa-reply"></i> Reply
                </a>
            </div>
        </div>
    </div>

    {{-- Main 2-Column Content --}}
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">

                {{-- Left Primary Column: Thread & Comments --}}
                <main class="space-y-6">

                    {{-- Main Thread Card --}}
                    <article class="card flat-card overflow-hidden shadow-lg border-blue-100 dark:border-slate-800">
                        {{-- Topic Header Ribbon --}}
                        <div class="p-6 sm:p-8 bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-900/60 dark:to-slate-900 border-b border-slate-100 dark:border-slate-800">
                            {{-- Badges Row --}}
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                @if($thread->is_pinned)
                                <span class="px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10px] font-bold uppercase tracking-wider border border-amber-200 dark:border-amber-800 flex items-center gap-1">
                                    <i class="fa-solid fa-thumbtack text-[9px]"></i> Pinned
                                </span>
                                @endif
                                @if($thread->is_locked)
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10px] font-bold uppercase tracking-wider border border-rose-200 dark:border-rose-800 flex items-center gap-1">
                                    <i class="fa-solid fa-lock text-[9px]"></i> Locked
                                </span>
                                @endif
                                <a href="{{ route('forum.index', ['category' => $category->slug]) }}" class="px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 text-[11px] font-semibold border border-blue-200 dark:border-blue-900/50 hover:bg-blue-200 transition">
                                    <i class="{{ $category->icon }} text-[10px] mr-1"></i> {{ $category->name }}
                                </a>
                            </div>

                            {{-- Title --}}
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-5">
                                {{ $thread->title }}
                            </h1>

                            {{-- Author Meta Info --}}
                            <div class="flex items-center justify-between gap-4 pt-4 border-t border-slate-200/60 dark:border-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold flex items-center justify-center text-sm shadow-md ring-2 ring-blue-500/20">
                                        {{ strtoupper(substr($thread->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-sm flex items-center gap-1.5">
                                            <span>{{ $thread->user->name ?? 'Anonymous' }}</span>
                                            <span class="px-1.5 py-0.2 rounded bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-[10px] font-semibold">Author</span>
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2 mt-0.5">
                                            <span><i class="fa-regular fa-clock mr-1"></i> {{ $thread->created_at->format('M d, Y \a\t g:i A') }}</span>
                                            <span>•</span>
                                            <span><i class="fa-regular fa-eye mr-1"></i> {{ number_format($thread->views_count) }} views</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Report Action --}}
                                @auth
                                <button type="button" onclick="document.getElementById('report-thread-modal').classList.remove('hidden')" class="text-xs text-slate-400 hover:text-rose-500 transition flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
                                    <i class="fa-solid fa-flag"></i> <span class="hidden sm:inline">Report</span>
                                </button>
                                @endauth
                            </div>
                        </div>

                        {{-- Body Content --}}
                        <div class="p-6 sm:p-8">
                            <div class="prose prose-slate dark:prose-invert max-w-none prose-p:text-base prose-p:leading-relaxed prose-p:text-slate-700 dark:prose-p:text-slate-300">
                                {!! nl2br(e($thread->body)) !!}
                            </div>

                            {{-- Bottom Actions & Upvote Bar --}}
                            <div class="mt-8 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4 text-xs text-slate-500">
                                <div class="flex items-center gap-3">
                                    {{-- Upvote Button --}}
                                    @php $isThreadUpvoted = $thread->isUpvotedBy(auth()->user()); @endphp
                                    @auth
                                    <button type="button" onclick="toggleUpvote('thread', {{ $thread->id }}, this)"
                                        class="upvote-btn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95 {{ $isThreadUpvoted ? 'bg-blue-600 text-white shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-600' }}">
                                        <i class="fa-solid fa-thumbs-up text-xs"></i>
                                        <span>Upvote</span>
                                        <span class="upvote-count px-1.5 py-0.2 rounded-full {{ $isThreadUpvoted ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">{{ $thread->upvotes_count }}</span>
                                    </button>
                                    @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:text-blue-600 transition">
                                        <i class="fa-solid fa-thumbs-up text-xs"></i>
                                        <span>Upvote</span>
                                        <span class="px-1.5 py-0.2 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">{{ $thread->upvotes_count }}</span>
                                    </a>
                                    @endauth

                                    {{-- Share Button --}}
                                    <button type="button" onclick="copyThreadUrl()" class="hover:text-blue-600 transition flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-3.5 py-2 rounded-xl font-bold border border-slate-200 dark:border-slate-700 shadow-sm active:scale-95">
                                        <i class="fa-solid fa-share-nodes text-blue-500"></i> Share
                                    </button>
                                    <span id="copied-toast" class="hidden text-emerald-600 font-bold transition flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-lg border border-emerald-200 dark:border-emerald-800">
                                        <i class="fa-solid fa-circle-check text-xs"></i> Copied!
                                    </span>
                                </div>

                                <button type="button" onclick="scrollToComments()" class="text-blue-600 font-bold hover:underline flex items-center gap-1.5 cursor-pointer">
                                    <i class="fa-solid fa-arrow-down-long text-xs"></i> Jump to Comments
                                </button>
                            </div>
                        </div>
                    </article>

                    {{-- Replies Feed Section --}}
                    <div class="space-y-4" id="replies-feed">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="fa-solid fa-comments text-blue-500"></i>
                                <span>Replies & Rationales</span>
                                <span class="px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-xs font-extrabold">
                                    {{ $thread->replies_count }}
                                </span>
                            </h2>
                        </div>

                        @if($replies->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($replies as $reply)
                            @php $isReplyUpvoted = $reply->isUpvotedBy(auth()->user()); @endphp
                            {{-- Single Top-Level Reply Card --}}
                            <div class="card flat-card p-5 sm:p-6 transition-all duration-200" id="reply-{{ $reply->id }}">
                                <div class="flex items-start gap-3.5">
                                    {{-- Avatar --}}
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-600 to-emerald-700 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm">
                                        {{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        {{-- Header --}}
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $reply->user->name ?? 'Anonymous' }}</span>
                                                @if(($reply->user_id ?? 0) === $thread->user_id)
                                                <span class="px-1.5 py-0.2 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-[10px] font-bold">OP Author</span>
                                                @endif
                                                <span class="text-slate-300 dark:text-slate-700">•</span>
                                                <span class="text-xs text-slate-400">{{ $reply->formatted_date }}</span>
                                            </div>

                                            {{-- Reply, Upvote & Report Actions --}}
                                            <div class="flex items-center gap-2">
                                                @auth
                                                <button type="button" onclick="toggleUpvote('reply', {{ $reply->id }}, this)"
                                                    class="upvote-btn text-xs font-bold transition-all flex items-center gap-1 px-2.5 py-1 rounded-lg {{ $isReplyUpvoted ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600' }}">
                                                    <i class="fa-solid fa-thumbs-up text-[10px]"></i>
                                                    <span class="upvote-count">{{ $reply->upvotes_count }}</span>
                                                </button>
                                                @if(!$thread->is_locked)
                                                <button type="button" onclick="toggleReplyBox('reply-form-{{ $reply->id }}')" class="text-xs text-blue-600 hover:text-blue-700 font-bold transition flex items-center gap-1 bg-blue-50 dark:bg-blue-950/40 px-2.5 py-1 rounded-lg">
                                                    <i class="fa-solid fa-reply text-[10px]"></i> Reply
                                                </button>
                                                @endif
                                                <button type="button" onclick="toggleReplyBox('report-reply-{{ $reply->id }}')" class="text-xs text-slate-400 hover:text-rose-500 transition p-1" title="Report reply">
                                                    <i class="fa-solid fa-flag"></i>
                                                </button>
                                                @endauth
                                            </div>
                                        </div>

                                        {{-- Reply Body --}}
                                        <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed space-y-2">
                                            {!! nl2br(e($reply->body)) !!}
                                        </div>

                                        {{-- Inline Report Box --}}
                                        @auth
                                        <div id="report-reply-{{ $reply->id }}" class="hidden mt-3 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800">
                                            <form method="POST" action="{{ route('forum.report', ['reply', $reply->id]) }}">
                                                @csrf
                                                <label class="block text-xs font-bold text-rose-700 dark:text-rose-300 mb-1.5">Report Reply</label>
                                                <div class="flex items-center gap-2">
                                                    <select name="reason" required class="flex-1 text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                                        <option value="">Select reason...</option>
                                                        <option value="spam">Spam or promotion</option>
                                                        <option value="offensive">Offensive content</option>
                                                        <option value="misinformation">Misinformation</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endauth

                                        {{-- Nested Reply Form --}}
                                        @auth
                                        @if(!$thread->is_locked)
                                        <div id="reply-form-{{ $reply->id }}" class="hidden mt-3 p-3.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800">
                                            <form method="POST" action="{{ route('forum.reply', [$category, $thread]) }}">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                                <label class="block text-xs font-bold text-blue-700 dark:text-blue-300 mb-1.5">Replying to {{ $reply->user->name ?? 'User' }}</label>
                                                <textarea name="body" rows="2" required placeholder="Write a reply..." class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white mb-2 focus:ring-2 focus:ring-blue-500"></textarea>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="toggleReplyBox('reply-form-{{ $reply->id }}')" class="text-xs text-slate-500 px-3 py-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700">Cancel</button>
                                                    <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-lg hover:bg-blue-500 transition">Post Reply</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endif
                                        @endauth

                                        {{-- Nested Sub-Replies --}}
                                        @if($reply->children->isNotEmpty())
                                        <div class="mt-4 space-y-3 pl-4 border-l-2 border-blue-200 dark:border-blue-800/60">
                                            @foreach($reply->children as $child)
                                            <div class="flex items-start gap-3" id="reply-{{ $child->id }}">
                                                <div class="w-7 h-7 rounded-lg bg-slate-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0">
                                                    {{ strtoupper(substr($child->user->name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">{{ $child->user->name ?? 'Anonymous' }}</span>
                                                        <span class="text-[10px] text-slate-400">{{ $child->formatted_date }}</span>
                                                    </div>
                                                    <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                                                        {!! nl2br(e($child->body)) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="pt-2">{{ $replies->links() }}</div>
                        @else
                        <div class="text-center py-12 card flat-card">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center mx-auto mb-3 text-xl">
                                <i class="fa-regular fa-comments"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">No responses yet</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Be the first examinee to reply and start the discussion!</p>
                        </div>
                        @endif
                    </div>

                    {{-- Post Reply Box --}}
                    @if(!$thread->is_locked)
                        @auth
                        <div class="card flat-card p-6" id="reply-box">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-blue-500"></i> Leave a Reply
                            </h3>
                            <form method="POST" action="{{ route('forum.reply', [$category, $thread]) }}">
                                @csrf
                                <textarea name="body" rows="4" required placeholder="Share your rationale, insights, or thoughts on this topic..."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm mb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('body') }}</textarea>
                                @error('body') <p class="text-rose-500 text-xs mb-2">{{ $message }}</p> @enderror
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Be respectful and clear in your response.</span>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-md flex items-center gap-2">
                                        <i class="fa-solid fa-paper-plane"></i> Post Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="card flat-card p-6 text-center">
                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">Sign in to join the discussion and post a response.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                                <i class="fa-solid fa-right-to-bracket"></i> Sign In to Reply
                            </a>
                        </div>
                        @endauth
                    @else
                    <div class="card flat-card p-6 text-center bg-rose-50/50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/40">
                        <i class="fa-solid fa-lock text-rose-500 text-xl mb-2"></i>
                        <p class="text-sm text-slate-700 dark:text-slate-300 font-semibold">This thread is locked by moderators. New replies are disabled.</p>
                    </div>
                    @endif

                </main>

                {{-- Right Sidebar Column (Redesigned) --}}
                <aside class="space-y-6">

                    {{-- Author Profile Card --}}
                    <div class="card flat-card p-5 overflow-hidden relative">
                        <div class="flex items-center gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold flex items-center justify-center text-base shadow-md ring-2 ring-blue-500/20 shrink-0">
                                {{ strtoupper(substr($thread->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 block">Topic Starter</span>
                                <h4 class="font-bold text-slate-900 dark:text-white text-base truncate">{{ $thread->user->name ?? 'Anonymous' }}</h4>
                                <span class="text-[11px] text-slate-400 block">Member since {{ $thread->user->created_at->format('M Y') ?? '2026' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Category Summary Card --}}
                    <div class="card flat-card p-5">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-2 flex items-center gap-2">
                            <i class="{{ $category->icon }} text-blue-500"></i> {{ $category->name }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-4">
                            {{ $category->description ?? 'Explore discussions and study strategies in this category.' }}
                        </p>
                        <a href="{{ route('forum.index', ['category' => $category->slug]) }}" class="inline-flex items-center justify-center gap-1.5 w-full text-center bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs py-2 px-3 rounded-xl hover:bg-blue-600 hover:text-white transition">
                            <i class="fa-solid fa-folder-open text-xs"></i> Browse Category Threads
                        </a>
                    </div>

                    {{-- Related Discussions Widget --}}
                    @if(isset($relatedThreads) && $relatedThreads->isNotEmpty())
                    <div class="card flat-card p-5">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-3.5 flex items-center gap-2">
                            <i class="fa-solid fa-comments-dollar text-blue-500"></i> Related Topics
                        </h3>
                        <div class="space-y-3">
                            @foreach($relatedThreads as $rel)
                            <a href="{{ route('forum.show', [$category, $rel]) }}" class="block group">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 transition line-clamp-2 leading-snug mb-1">
                                    {{ $rel->title }}
                                </h4>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400">
                                    <span><i class="fa-regular fa-comment text-[10px]"></i> {{ $rel->replies_count }}</span>
                                    <span>•</span>
                                    <span>{{ $rel->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                            @if(!$loop->last)
                            <div class="border-t border-slate-100 dark:border-slate-800/80"></div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Practice Exam Widget --}}
                    <div class="card flat-card p-5 bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-md">
                        <i class="fa-solid fa-bullseye text-blue-200 text-2xl mb-2 block"></i>
                        <h3 class="font-bold text-white text-sm mb-1">Test Your Exam Readiness</h3>
                        <p class="text-xs text-blue-100 mb-4 leading-relaxed">
                            Drill practice questions with instant AI explanations for your target exam.
                        </p>
                        <a href="{{ route('reviewers') }}" class="block w-full text-center bg-white text-blue-700 font-bold text-xs py-2.5 px-4 rounded-xl hover:bg-blue-50 transition shadow">
                            Start Practice Drill
                        </a>
                    </div>

                </aside>
            </div>
        </div>
    </section>

    {{-- Report Thread Modal --}}
    @auth
    <div id="report-thread-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200 dark:border-slate-700 animate-in fade-in zoom-in duration-200">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-flag text-rose-500"></i> Report Discussion Thread
            </h3>
            <form method="POST" action="{{ route('forum.report', ['thread', $thread->id]) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Select Reason</label>
                    <select name="reason" required class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs">
                        <option value="">Select a reason...</option>
                        <option value="spam">Spam or self-promotion</option>
                        <option value="offensive">Offensive or inappropriate</option>
                        <option value="misinformation">Misinformation or inaccurate</option>
                        <option value="exam_leak">Sharing actual exam questions</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Details (optional)</label>
                    <textarea name="description" rows="3" placeholder="Provide additional details..." class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-xs"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('report-thread-modal').classList.add('hidden')" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold px-4 py-2.5 rounded-xl hover:bg-slate-200 transition">Cancel</button>
                    <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
    @endauth

    <script>
        function toggleReplyBox(id) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle('hidden');
        }

        function scrollToComments() {
            const target = document.getElementById('replies-feed');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function copyThreadUrl() {
            const url = window.location.href;
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(showCopyToast);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = url;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    showCopyToast();
                } catch (err) {
                    console.error('Copy failed', err);
                }
                document.body.removeChild(textarea);
            }
        }

        function showCopyToast() {
            const toast = document.getElementById('copied-toast');
            if (toast) {
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2500);
            }
        }

        function toggleUpvote(type, id, btn) {
            fetch(`/community/upvote/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (!data || !data.success) return;
                const countSpan = btn.querySelector('.upvote-count');
                if (countSpan) {
                    countSpan.innerText = data.upvotes_count;
                }
                if (data.upvoted) {
                    btn.classList.add('bg-blue-600', 'text-white');
                    btn.classList.remove('bg-slate-100', 'text-slate-700', 'dark:bg-slate-800', 'dark:text-slate-300');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-700');
                }
            })
            .catch(err => console.error('Upvote failed', err));
        }
    </script>
</x-public-layout>
