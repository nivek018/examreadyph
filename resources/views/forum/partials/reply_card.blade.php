@php
    $isChild = $isChild ?? false;
    $isReplyUpvoted = $reply->isUpvotedBy(auth()->user(), request()->ip());
@endphp

@if($isChild)
{{-- Child / Sub-Comment Card --}}
<div class="flex items-start gap-3 group/child relative animate-in fade-in slide-in-from-bottom-2 duration-300" id="reply-{{ $reply->id }}">
    <img src="{{ $reply->user->avatar_url ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=Anonymous' }}" alt="{{ $reply->user->name ?? 'User' }}"
        class="w-7 h-7 rounded-lg object-cover bg-white dark:bg-slate-800 p-0.5 border border-slate-200 dark:border-slate-700 shrink-0">
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1 flex-wrap">
            <span class="font-bold text-slate-800 dark:text-slate-200 text-xs">{{ $reply->user->name ?? 'Anonymous' }}</span>
            <span class="text-[10px] text-slate-400">{{ $reply->formatted_date }}</span>

            <div class="flex items-center gap-1.5 ml-1">
                {{-- Sub-comment Upvote Button --}}
                <button type="button" onclick="toggleUpvote('reply', {{ $reply->id }}, this)"
                    class="upvote-btn text-[10px] font-bold transition-all flex items-center gap-1 px-2 py-0.5 rounded-md cursor-pointer {{ $isReplyUpvoted ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600' }}">
                    <i class="fa-solid fa-thumbs-up text-[9px]"></i>
                    <span class="upvote-count">{{ $reply->upvotes_count }}</span>
                </button>

                {{-- Sub-comment Report Button on Hover --}}
                <button type="button" onclick="toggleReplyBox('report-reply-{{ $reply->id }}')" class="text-xs text-slate-400 hover:text-rose-500 opacity-70 sm:opacity-0 group-hover/child:opacity-100 transition-opacity p-0.5 cursor-pointer" title="Report comment">
                    <i class="fa-solid fa-flag text-[9px]"></i>
                </button>
            </div>
        </div>

        <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
            {!! nl2br(e($reply->body)) !!}
        </div>

        {{-- Inline Report Box for Child --}}
        <div id="report-reply-{{ $reply->id }}" class="hidden mt-2 p-2.5 rounded-lg bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800">
            <form method="POST" action="{{ route('forum.report', ['reply', $reply->id]) }}" onsubmit="submitReportAjax(event, this, 'reply', {{ $reply->id }})">
                @csrf
                <div class="flex items-center gap-2">
                    <select name="reason" required class="flex-1 text-[11px] px-2 py-1 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        <option value="">Reason...</option>
                        <option value="spam">Spam</option>
                        <option value="offensive">Offensive</option>
                        <option value="other">Other</option>
                    </select>
                    <button type="submit" class="bg-rose-600 text-white text-[11px] font-bold px-3 py-1 rounded cursor-pointer">Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
{{-- Top-Level Reply Card --}}
<div class="card flat-card p-5 sm:p-6 transition-all duration-200 group/reply relative animate-in fade-in slide-in-from-bottom-2 duration-300" id="reply-{{ $reply->id }}">
    <div class="flex items-start gap-3.5">
        <img src="{{ $reply->user->avatar_url ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=Anonymous' }}" alt="{{ $reply->user->name ?? 'User' }}"
            class="w-9 h-9 rounded-xl object-cover bg-white dark:bg-slate-800 p-0.5 border border-slate-200 dark:border-slate-700 shrink-0 shadow-sm">

        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="flex items-center justify-between gap-2 mb-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-bold text-slate-900 dark:text-white text-sm">{{ $reply->user->name ?? 'Anonymous' }}</span>
                    @if(isset($thread) && ($reply->user_id ?? 0) === $thread->user_id)
                    <span class="px-1.5 py-0.2 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-[10px] font-bold">OP Author</span>
                    @endif
                    <span class="text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-xs text-slate-400">{{ $reply->formatted_date }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleUpvote('reply', {{ $reply->id }}, this)"
                        class="upvote-btn text-xs font-bold transition-all flex items-center gap-1 px-2.5 py-1 rounded-lg cursor-pointer {{ $isReplyUpvoted ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600' }}">
                        <i class="fa-solid fa-thumbs-up text-[10px]"></i>
                        <span class="upvote-count">{{ $reply->upvotes_count }}</span>
                    </button>

                    @auth
                    @if(!isset($thread) || !$thread->is_locked)
                    <button type="button" onclick="toggleReplyBox('reply-form-{{ $reply->id }}')" class="text-xs text-blue-600 hover:text-blue-700 font-bold transition flex items-center gap-1 bg-blue-50 dark:bg-blue-950/40 px-2.5 py-1 rounded-lg cursor-pointer">
                        <i class="fa-solid fa-reply text-[10px]"></i> Reply
                    </button>
                    @endif
                    @endauth

                    <button type="button" onclick="toggleReplyBox('report-reply-{{ $reply->id }}')"
                        class="text-xs text-slate-400 hover:text-rose-500 transition-all p-1.5 rounded-md hover:bg-rose-50 dark:hover:bg-rose-950/40 opacity-70 sm:opacity-0 group-hover/reply:opacity-100 cursor-pointer"
                        title="Report this comment">
                        <i class="fa-solid fa-flag text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed space-y-2">
                {!! nl2br(e($reply->body)) !!}
            </div>

            {{-- Inline Report Box --}}
            <div id="report-reply-{{ $reply->id }}" class="hidden mt-3 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800">
                <form method="POST" action="{{ route('forum.report', ['reply', $reply->id]) }}" onsubmit="submitReportAjax(event, this, 'reply', {{ $reply->id }})">
                    @csrf
                    <label class="block text-xs font-bold text-rose-700 dark:text-rose-300 mb-1.5">Report Comment</label>
                    <div class="flex items-center gap-2">
                        <select name="reason" required class="flex-1 text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                            <option value="">Select reason...</option>
                            <option value="spam">Spam or promotion</option>
                            <option value="offensive">Offensive content</option>
                            <option value="misinformation">Misinformation</option>
                            <option value="other">Other</option>
                        </select>
                        <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-lg transition shadow-xs cursor-pointer">Submit Report</button>
                    </div>
                </form>
            </div>

            {{-- Nested Reply Form --}}
            @auth
            @if(!isset($thread) || !$thread->is_locked)
            <div id="reply-form-{{ $reply->id }}" class="hidden mt-3 p-3.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800">
                <form method="POST" action="{{ route('forum.reply', [$category ?? $reply->thread->category, $thread ?? $reply->thread]) }}" onsubmit="submitReplyAjax(event, this)">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                    <label class="block text-xs font-bold text-blue-700 dark:text-blue-300 mb-1.5">Replying to {{ $reply->user->name ?? 'User' }}</label>
                    <textarea name="body" rows="2" required placeholder="Write a reply..." class="w-full text-xs px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white mb-2 focus:ring-2 focus:ring-blue-500"></textarea>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="toggleReplyBox('reply-form-{{ $reply->id }}')" class="text-xs text-slate-500 px-3 py-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-1.5 rounded-lg hover:bg-blue-500 transition cursor-pointer">Post Reply</button>
                    </div>
                </form>
            </div>
            @endif
            @endauth

            {{-- Nested Sub-Replies Container --}}
            <div id="children-container-{{ $reply->id }}" class="mt-4 space-y-3 pl-4 border-l-2 border-blue-200 dark:border-blue-800/60 {{ ($reply->relationLoaded('children') && $reply->children->isNotEmpty()) ? '' : 'hidden' }}">
                @if($reply->relationLoaded('children'))
                @foreach($reply->children as $child)
                    @include('forum.partials.reply_card', ['reply' => $child, 'isChild' => true])
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endif
