<x-public-layout
    :metaTitle="$thread->title . ' — ' . $category->name . ' | ExamReady PH Community'"
    :metaDescription="Str::limit(strip_tags($thread->body), 160)"
>
    {{-- Floating Toast Notification --}}
    <div id="ajax-toast" class="hidden fixed top-20 right-5 z-50 max-w-sm bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
        <i id="toast-icon" class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
        <div id="toast-message" class="text-xs font-semibold"></div>
    </div>

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
                                <button type="button" onclick="document.getElementById('report-thread-modal').classList.remove('hidden')" class="text-xs text-slate-400 hover:text-rose-500 transition flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer" title="Report Thread">
                                    <i class="fa-solid fa-flag"></i> <span class="hidden sm:inline">Report</span>
                                </button>
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
                                    @php $isThreadUpvoted = $thread->isUpvotedBy(auth()->user(), request()->ip()); @endphp
                                    <button type="button" onclick="toggleUpvote('thread', {{ $thread->id }}, this)"
                                        class="upvote-btn inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95 cursor-pointer {{ $isThreadUpvoted ? 'bg-blue-600 text-white shadow-blue-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 hover:text-blue-600' }}">
                                        <i class="fa-solid fa-thumbs-up text-xs"></i>
                                        <span>Upvote</span>
                                        <span class="upvote-count px-1.5 py-0.2 rounded-full {{ $isThreadUpvoted ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' }}">{{ $thread->upvotes_count }}</span>
                                    </button>

                                    {{-- Share Button --}}
                                    <button type="button" onclick="copyThreadUrl()" class="hover:text-blue-600 transition flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 px-3.5 py-2 rounded-xl font-bold border border-slate-200 dark:border-slate-700 shadow-sm active:scale-95 cursor-pointer">
                                        <i class="fa-solid fa-share-nodes text-blue-500"></i> Share
                                    </button>
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
                                <span id="replies-count-badge" class="px-2.5 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-xs font-extrabold">
                                    {{ $thread->replies_count }}
                                </span>
                            </h2>
                        </div>

                        <div id="replies-list-container" class="space-y-4">
                            @if($replies->isNotEmpty())
                            @foreach($replies as $reply)
                                @include('forum.partials.reply_card', ['reply' => $reply, 'isChild' => false])
                            @endforeach
                            @else
                            <div class="text-center py-12 card flat-card" id="no-replies-placeholder">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center mx-auto mb-3 text-xl">
                                    <i class="fa-regular fa-comments"></i>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">No responses yet</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Be the first examinee to reply and start the discussion!</p>
                            </div>
                            @endif
                        </div>

                        <div class="pt-2">{{ $replies->links() }}</div>
                    </div>

                    {{-- Post Reply Box --}}
                    @if(!$thread->is_locked)
                        @auth
                        <div class="card flat-card p-6" id="reply-box">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-blue-500"></i> Leave a Reply
                            </h3>
                            <form method="POST" action="{{ route('forum.reply', [$category, $thread]) }}" onsubmit="submitReplyAjax(event, this)">
                                @csrf
                                <textarea name="body" rows="4" required placeholder="Share your rationale, insights, or thoughts on this topic..."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm mb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('body') }}</textarea>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Be respectful and clear in your response.</span>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold px-6 py-2.5 rounded-xl transition shadow-md flex items-center gap-2 cursor-pointer">
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

                {{-- Right Sidebar Column --}}
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
                        <a href="{{ route('forum.index', ['category' => $category->slug]) }}" class="inline-flex items-center justify-center gap-1.5 w-full text-center bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs py-2 px-3 rounded-lg hover:bg-blue-600 hover:text-white transition">
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
    <div id="report-thread-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-200 dark:border-slate-700 animate-in fade-in zoom-in duration-200">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-flag text-rose-500"></i> Report Discussion Thread
            </h3>
            <form method="POST" action="{{ route('forum.report', ['thread', $thread->id]) }}" onsubmit="submitReportAjax(event, this, 'thread', {{ $thread->id }})">
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
                    <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow cursor-pointer">Submit Report</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('ajax-toast');
            const icon = document.getElementById('toast-icon');
            const message = document.getElementById('toast-message');
            if (toast && message) {
                message.innerText = msg;
                if (type === 'error') {
                    icon.className = 'fa-solid fa-circle-exclamation text-rose-400 text-lg';
                } else {
                    icon.className = 'fa-solid fa-circle-check text-emerald-400 text-lg';
                }
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 3500);
            }
        }

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
                navigator.clipboard.writeText(url).then(() => showToast('Thread URL copied to clipboard!'));
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = url;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    showToast('Thread URL copied to clipboard!');
                } catch (err) {
                    console.error('Copy failed', err);
                }
                document.body.removeChild(textarea);
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
            .then(res => res.json())
            .then(data => {
                if (data && data.success) {
                    const countSpan = btn.querySelector('.upvote-count');
                    if (countSpan) {
                        countSpan.innerText = data.upvotes_count;
                    }
                    if (data.upvoted) {
                        btn.classList.remove('bg-slate-100', 'dark:bg-slate-800', 'text-slate-700', 'text-slate-600', 'dark:text-slate-300');
                        btn.classList.add('bg-blue-600', 'text-white');
                        if (countSpan && countSpan.classList.contains('rounded-full')) {
                            countSpan.classList.remove('bg-slate-200', 'dark:bg-slate-700', 'text-slate-600', 'dark:text-slate-300');
                            countSpan.classList.add('bg-white/20', 'text-white');
                        }
                    } else {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-slate-100', 'dark:bg-slate-800', 'text-slate-700', 'dark:text-slate-300');
                        if (countSpan && countSpan.classList.contains('rounded-full')) {
                            countSpan.classList.remove('bg-white/20', 'text-white');
                            countSpan.classList.add('bg-slate-200', 'dark:bg-slate-700', 'text-slate-600', 'dark:text-slate-300');
                        }
                    }
                }
            })
            .catch(err => console.error('Upvote failed', err));
        }

        function submitReportAjax(e, form, type, id) {
            e.preventDefault();
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) submitBtn.disabled = false;

                // Close modals
                const modal = document.getElementById('report-thread-modal');
                if (modal) modal.classList.add('hidden');
                const inlineBox = document.getElementById(`report-reply-${id}`);
                if (inlineBox) inlineBox.classList.add('hidden');

                form.reset();
                showToast(data.message || 'Report submitted.', data.success ? 'success' : 'error');
            })
            .catch(err => {
                if (submitBtn) submitBtn.disabled = false;
                const modal = document.getElementById('report-thread-modal');
                if (modal) modal.classList.add('hidden');
                showToast('Report submitted. Our moderation team will review it shortly.', 'success');
            });
        }

        function submitReplyAjax(e, form) {
            e.preventDefault();
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (submitBtn) submitBtn.disabled = false;
                if (data.success && data.html) {
                    form.reset();

                    // Hide forms
                    if (data.parent_id) {
                        const replyForm = document.getElementById(`reply-form-${data.parent_id}`);
                        if (replyForm) replyForm.classList.add('hidden');

                        const targetContainer = document.getElementById(`children-container-${data.parent_id}`);
                        if (targetContainer) {
                            targetContainer.classList.remove('hidden');
                            targetContainer.insertAdjacentHTML('beforeend', data.html);
                        }
                    } else {
                        const feedContainer = document.getElementById('replies-list-container');
                        if (feedContainer) {
                            feedContainer.insertAdjacentHTML('beforeend', data.html);
                        }
                    }

                    // Remove placeholder if present
                    const placeholder = document.getElementById('no-replies-placeholder');
                    if (placeholder) placeholder.remove();

                    // Update replies count badge
                    const badge = document.getElementById('replies-count-badge');
                    if (badge) {
                        const currentVal = parseInt(badge.innerText.trim()) || 0;
                        badge.innerText = currentVal + 1;
                    }

                    showToast('Reply posted successfully!', 'success');
                } else {
                    showToast(data.message || 'Failed to post reply.', 'error');
                }
            })
            .catch(err => {
                if (submitBtn) submitBtn.disabled = false;
                showToast('Failed to post reply. Please try again.', 'error');
            });
        }
    </script>
</x-public-layout>
