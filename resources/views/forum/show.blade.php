<x-public-layout
    :metaTitle="$thread->title . ' — ' . $category->name . ' | ExamReady PH Community'"
    :metaDescription="Str::limit(strip_tags($thread->body), 160)"
>
    {{-- Breadcrumb --}}
    <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3">
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('forum.index') }}" class="hover:text-blue-600 transition">Community</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('forum.category', $category) }}" class="hover:text-blue-600 transition">{{ $category->name }}</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-slate-700 dark:text-slate-300 font-medium truncate max-w-[200px]">{{ $thread->title }}</span>
            </nav>
        </div>
    </div>

    <section class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">

            {{-- Thread Post --}}
            <div class="card flat-card overflow-hidden mb-8">
                <div class="p-6 sm:p-8">
                    {{-- Badges --}}
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        @if($thread->is_pinned)
                        <span class="px-2.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-thumbtack mr-0.5"></i> Pinned</span>
                        @endif
                        @if($thread->is_locked)
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-bold uppercase"><i class="fa-solid fa-lock mr-0.5"></i> Locked</span>
                        @endif
                        <span class="badge-blue text-[10px]">{{ $category->name }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-6">{{ $thread->title }}</h1>

                    {{-- Author Bar --}}
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                            {{ strtoupper(substr($thread->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $thread->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-500">{{ $thread->created_at->format('M d, Y \a\t g:i A') }} · {{ number_format($thread->views_count) }} views</div>
                        </div>
                        @auth
                        <div class="ml-auto">
                            <button onclick="document.getElementById('report-thread-modal').classList.remove('hidden')" class="text-xs text-slate-400 hover:text-rose-500 transition flex items-center gap-1">
                                <i class="fa-solid fa-flag"></i> Report
                            </button>
                        </div>
                        @endauth
                    </div>

                    {{-- Thread Body --}}
                    <div class="prose prose-slate dark:prose-invert max-w-none prose-p:text-base prose-p:leading-relaxed">
                        {!! nl2br(e($thread->body)) !!}
                    </div>
                </div>
            </div>

            {{-- Replies Section --}}
            <div class="mb-8">
                <h2 class="text-lg font-extrabold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-blue-500"></i> {{ $thread->replies_count }} {{ Str::plural('Reply', $thread->replies_count) }}
                </h2>

                @if($replies->isNotEmpty())
                <div class="space-y-4">
                    @foreach($replies as $reply)
                    {{-- Top-level reply --}}
                    <div class="card flat-card p-5" id="reply-{{ $reply->id }}">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm">
                                {{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $reply->user->name ?? 'Unknown' }}</span>
                                        <span class="text-xs text-slate-400">{{ $reply->formatted_date }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @auth
                                        @if(!$thread->is_locked)
                                        <button onclick="document.getElementById('reply-form-{{ $reply->id }}').classList.toggle('hidden')" class="text-xs text-blue-500 hover:text-blue-600 font-medium transition">
                                            <i class="fa-solid fa-reply"></i> Reply
                                        </button>
                                        @endif
                                        <button onclick="document.getElementById('report-reply-{{ $reply->id }}').classList.toggle('hidden')" class="text-xs text-slate-400 hover:text-rose-500 transition">
                                            <i class="fa-solid fa-flag"></i>
                                        </button>
                                        @endauth
                                    </div>
                                </div>
                                <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                    {!! nl2br(e($reply->body)) !!}
                                </div>

                                {{-- Inline report form --}}
                                @auth
                                <div id="report-reply-{{ $reply->id }}" class="hidden mt-3 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800">
                                    <form method="POST" action="{{ route('forum.report', ['reply', $reply->id]) }}" class="flex items-center gap-2">
                                        @csrf
                                        <select name="reason" required class="flex-1 text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                            <option value="">Select reason...</option>
                                            <option value="spam">Spam</option>
                                            <option value="offensive">Offensive content</option>
                                            <option value="misinformation">Misinformation</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <button type="submit" class="bg-rose-500 text-white text-xs font-bold px-3 py-2 rounded-lg hover:bg-rose-600 transition">Report</button>
                                    </form>
                                </div>
                                @endauth

                                {{-- Nested reply form --}}
                                @auth
                                @if(!$thread->is_locked)
                                <div id="reply-form-{{ $reply->id }}" class="hidden mt-3 p-3 rounded-xl bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800">
                                    <form method="POST" action="{{ route('forum.reply', [$category, $thread]) }}">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                        <textarea name="body" rows="2" required placeholder="Write a reply..." class="w-full text-sm px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white mb-2"></textarea>
                                        <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition">Post Reply</button>
                                    </form>
                                </div>
                                @endif
                                @endauth

                                {{-- Nested replies (1 level deep) --}}
                                @if($reply->children->isNotEmpty())
                                <div class="mt-4 space-y-3 pl-4 border-l-2 border-blue-200 dark:border-blue-800">
                                    @foreach($reply->children as $child)
                                    <div class="flex items-start gap-3" id="reply-{{ $child->id }}">
                                        <div class="w-7 h-7 rounded-full bg-slate-500 text-white font-bold flex items-center justify-center text-[10px] shrink-0">
                                            {{ strtoupper(substr($child->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-semibold text-slate-800 dark:text-slate-200 text-xs">{{ $child->user->name ?? 'Unknown' }}</span>
                                                <span class="text-[11px] text-slate-400">{{ $child->formatted_date }}</span>
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

                <div class="mt-6">{{ $replies->links() }}</div>
                @else
                <div class="text-center py-10 card flat-card">
                    <i class="fa-regular fa-comment-dots text-3xl text-slate-300 dark:text-slate-600 mb-3"></i>
                    <p class="text-sm text-slate-500">No replies yet. Be the first to respond!</p>
                </div>
                @endif
            </div>

            {{-- Reply Form --}}
            @if(!$thread->is_locked)
                @auth
                <div class="card flat-card p-6" id="reply-box">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-pen text-blue-500"></i> Post a Reply
                    </h3>
                    <form method="POST" action="{{ route('forum.reply', [$category, $thread]) }}">
                        @csrf
                        <textarea name="body" rows="4" required placeholder="Share your thoughts, tips, or answer the question..." class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm mb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('body') }}</textarea>
                        @error('body') <p class="text-rose-500 text-xs mb-2">{{ $message }}</p> @enderror
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-paper-plane mr-1"></i> Post Reply
                        </button>
                    </form>
                </div>
                @else
                <div class="card flat-card p-6 text-center">
                    <p class="text-sm text-slate-500 mb-3">You need to be logged in to reply.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                        <i class="fa-solid fa-right-to-bracket"></i> Login to Reply
                    </a>
                </div>
                @endauth
            @else
            <div class="card flat-card p-6 text-center bg-slate-50 dark:bg-slate-900">
                <i class="fa-solid fa-lock text-slate-400 text-xl mb-2"></i>
                <p class="text-sm text-slate-500 font-medium">This thread is locked. No new replies can be posted.</p>
            </div>
            @endif
        </div>
    </section>

    {{-- Report Thread Modal --}}
    @auth
    <div id="report-thread-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl w-full max-w-md p-6 mx-4 border border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-flag text-rose-500"></i> Report Thread
            </h3>
            <form method="POST" action="{{ route('forum.report', ['thread', $thread->id]) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Reason</label>
                    <select name="reason" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                        <option value="">Select a reason...</option>
                        <option value="spam">Spam or self-promotion</option>
                        <option value="offensive">Offensive or inappropriate</option>
                        <option value="misinformation">Misinformation or inaccurate</option>
                        <option value="exam_leak">Sharing actual exam questions</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Details (optional)</label>
                    <textarea name="description" rows="2" placeholder="Any additional context..." class="w-full px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('report-thread-modal').classList.add('hidden')" class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-bold py-2.5 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-rose-500 text-white text-sm font-bold py-2.5 rounded-xl hover:bg-rose-600 transition">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
    @endauth
</x-public-layout>
