<x-public-layout
    :metaTitle="$exam->name . ' Results — ExamReady PH'"
    :metaDescription="'Exam results for ' . $exam->name"
>
    <section class="py-10 bg-slate-50/50 dark:bg-slate-950/50 min-h-screen"
             x-data="resultsManager({
                 sessionId: '{{ $session->uuid }}',
                 isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
                 maxAllowed: {{ auth()->check() ? (auth()->user()->is_premium ? 9999 : 50) : 2 }}
             })">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Result Banner --}}
            <div class="card flat-card p-8 mb-8 text-center rounded-2xl border-2 {{ $passed ? 'bg-gradient-to-br from-emerald-50 via-teal-50/30 to-slate-50 dark:from-emerald-950/40 dark:via-teal-950/20 dark:to-slate-950 border-emerald-300 dark:border-emerald-700' : 'bg-gradient-to-br from-rose-50 via-red-50/30 to-slate-50 dark:from-rose-950/40 dark:via-red-950/20 dark:to-slate-950 border-rose-300 dark:border-rose-700' }}">
                <div class="text-6xl mb-4 animate-bounce">{{ $passed ? '🎉' : '📚' }}</div>
                <h1 class="text-3xl sm:text-4xl font-extrabold {{ $passed ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }} mb-2">
                    {{ $passed ? 'Congratulations! You Passed!' : 'Keep Practicing!' }}
                </h1>
                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400">{{ $exam->name }}</p>

                {{-- Score Circle --}}
                <div class="mt-6 inline-flex items-center justify-center w-36 h-36 rounded-full border-4 shadow-inner bg-white dark:bg-slate-900 {{ $passed ? 'border-emerald-500' : 'border-rose-500' }}">
                    <div class="text-center">
                        <div class="text-3xl font-black {{ $passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ number_format($session->score, 1) }}%</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-0.5">Final Score</div>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="card flat-card p-5 text-center bg-white dark:bg-slate-900">
                    <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ $session->correct_count }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">Correct</div>
                </div>
                <div class="card flat-card p-5 text-center bg-white dark:bg-slate-900">
                    <div class="text-2xl sm:text-3xl font-extrabold text-rose-600">{{ $session->wrong_count }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">Wrong</div>
                </div>
                <div class="card flat-card p-5 text-center bg-white dark:bg-slate-900">
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-500">{{ $session->unanswered_count }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">Unanswered</div>
                </div>
                <div class="card flat-card p-5 text-center bg-white dark:bg-slate-900">
                    <div class="text-2xl sm:text-3xl font-extrabold text-blue-600">{{ $session->total_questions }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mt-1">Total Questions</div>
                </div>
            </div>

            {{-- Passing Info Card --}}
            <div class="card flat-card p-4 sm:p-5 mb-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm bg-white dark:bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg shrink-0 {{ $passed ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50' : 'bg-rose-100 text-rose-600 dark:bg-rose-950/50' }}">
                        <i class="fa-solid {{ $passed ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white">Required Passing Score: {{ $exam->passing_score_percent }}%</div>
                        <div class="text-xs text-slate-500">Your Overall Rating: {{ number_format($session->score, 1) }}%</div>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $passed ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400' }}">
                    {{ $passed ? 'PASSED ELIGIBILITY' : 'DID NOT PASS' }}
                </span>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mb-10">
                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-xl transition text-sm flex items-center gap-2 shadow-md">
                    <i class="fa-solid fa-home"></i> Back to Dashboard
                </a>
                <form method="POST" action="{{ route('exam.start', $exam) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-blue-600 font-bold px-6 py-3 rounded-xl transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-rotate-right"></i> Retake Exam
                    </button>
                </form>
                <a href="{{ route('reviewer.show', $exam) }}" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold px-5 py-3 rounded-xl transition text-sm hover:bg-slate-200">
                    Reviewer Overview
                </a>
            </div>

            {{-- Question Review --}}
            @if($exam->allow_review)
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <h2 class="text-xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-blue-500"></i> Detailed Question Review
                </h2>

                {{-- AI Explanation Usage Counter --}}
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/60 text-xs text-purple-700 dark:text-purple-300 font-semibold">
                    <i class="fa-solid fa-robot"></i>
                    <span>AI Explanations Used: <strong x-text="unlockedCount"></strong> / <span x-text="maxAllowed === 9999 ? 'Unlimited' : maxAllowed"></span></span>
                </div>
            </div>

            <div class="space-y-5">
                @foreach($session->answers as $idx => $answer)
                @php
                    $q = $answer->question;
                    $isCorrect = $answer->is_correct;
                    $isAnswered = $answer->selected_option_id !== null;
                @endphp
                <div class="card flat-card p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl {{ $isCorrect ? 'border-l-4 border-l-emerald-500' : ($isAnswered ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-slate-400') }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-extrabold uppercase tracking-wider {{ $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : ($isAnswered ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500') }}">
                            Question {{ $idx + 1 }} — {{ $isCorrect ? '✓ Correct' : ($isAnswered ? '✗ Incorrect' : '⏭ Unanswered') }}
                        </span>
                        @if($answer->is_flagged)
                        <span class="text-xs text-amber-500 font-bold flex items-center gap-1"><i class="fa-solid fa-bookmark"></i> Bookmarked</span>
                        @endif
                    </div>

                    <p class="text-base font-bold text-slate-900 dark:text-white mb-4 leading-relaxed">{{ $q->question_text }}</p>

                    <div class="space-y-2.5 mb-4">
                        @foreach($q->options as $opt)
                        @php
                            $isSelected = $answer->selected_option_id === $opt->id;
                            $isCorrectOpt = $opt->is_correct;
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-xl text-sm transition
                            {{ $isCorrectOpt ? 'bg-emerald-50 dark:bg-emerald-950/30 border-2 border-emerald-400 dark:border-emerald-700' : ($isSelected && !$isCorrectOpt ? 'bg-rose-50 dark:bg-rose-950/30 border-2 border-rose-400 dark:border-rose-700' : 'bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800') }}">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-extrabold shrink-0
                                {{ $isCorrectOpt ? 'bg-emerald-600 text-white' : ($isSelected && !$isCorrectOpt ? 'bg-rose-600 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400') }}">
                                @if($isCorrectOpt)
                                    <i class="fa-solid fa-check"></i>
                                @elseif($isSelected && !$isCorrectOpt)
                                    <i class="fa-solid fa-xmark"></i>
                                @else
                                    {{ $opt->letter }}
                                @endif
                            </span>
                            <span class="{{ $isCorrectOpt ? 'font-bold text-emerald-950 dark:text-emerald-200' : ($isSelected && !$isCorrectOpt ? 'text-rose-900 dark:text-rose-200 line-through' : 'text-slate-700 dark:text-slate-300') }}">{{ $opt->text }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- AI Explanation Toggle Button --}}
                    @if($exam->show_explanations)
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        {{-- Hidden element storing raw explanation --}}
                        <div id="raw-exp-{{ $q->id }}" data-explanation="{{ $q->explanation_taglish }}" class="hidden"></div>

                        <button @click="toggleExplanation({{ $q->id }}, @js($q->explanation_taglish))"
                                class="text-xs font-bold px-3.5 py-2 rounded-xl border transition flex items-center gap-2 cursor-pointer"
                                :class="isOpen({{ $q->id }}) ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : (isUnlocked({{ $q->id }}) ? 'bg-purple-100 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 border-purple-300 dark:border-purple-700' : 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800/70 hover:bg-purple-100 dark:hover:bg-purple-900/60')">
                            <i class="fa-solid" :class="isOpen({{ $q->id }}) ? 'fa-eye-slash' : 'fa-robot'"></i>
                            <span x-text="isOpen({{ $q->id }}) ? 'Hide AI Explanation' : (isUnlocked({{ $q->id }}) ? 'Show Unlocked AI Explanation' : 'Show AI Explanation')"></span>
                        </button>

                        {{-- Collapsible Taglish Explanation with Typewriter Effect --}}
                        <div x-show="isOpen({{ $q->id }})" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             class="mt-3 p-4 sm:p-5 rounded-2xl bg-purple-50/80 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/60 text-xs sm:text-sm">
                            <div class="flex items-center justify-between mb-2 pb-2 border-b border-purple-200/60 dark:border-purple-900/50">
                                <div class="font-extrabold text-purple-700 dark:text-purple-300 flex items-center gap-2">
                                    <i class="fa-solid fa-robot text-purple-500"></i> AI Explanation
                                </div>
                                
                                {{-- Explain Again Link --}}
                                <button @click="explainAgain({{ $q->id }}, @js($q->explanation_taglish))"
                                        class="text-[11px] font-bold text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-200 underline flex items-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-arrows-rotate text-[10px]"></i> Explain Again
                                </button>
                            </div>

                            <div class="text-slate-700 dark:text-slate-200 leading-relaxed font-normal pt-1">
                                <span x-html="getTypedText({{ $q->id }}, @js($q->explanation_taglish))"></span>
                                <span x-show="isTyping({{ $q->id }})" class="inline-block w-1.5 h-4 bg-purple-600 animate-pulse ml-0.5 align-middle"></span>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @endforeach
            </div>
            @endif

        </div>

        {{-- Limit Reached Upgrade Modal --}}
        <div x-show="showLimitModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
            <div class="card flat-card p-6 max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-center" @click.outside="showLimitModal = false">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fa-solid fa-lock"></i>
                </div>

                <template x-if="!isLoggedIn">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Guest Limit Reached (2/2)</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                            You have used your 2 free AI explanations as a guest taker. Create a free account to unlock <strong>50 AI explanations</strong> per session!
                        </p>
                        <div class="space-y-2.5">
                            <a href="{{ route('register') }}" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md block">
                                Create Free Account (Unlock 50 AI)
                            </a>
                            <a href="{{ route('login') }}" class="w-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold py-3 rounded-xl text-sm hover:bg-slate-200 transition block">
                                Already Have an Account? Log In
                            </a>
                        </div>
                    </div>
                </template>

                <template x-if="isLoggedIn">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Daily Free Limit Reached (50/50)</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                            You've reached your free daily limit of 50 AI explanations. Upgrade to <strong>ExamReady Premium</strong> for unlimited AI explanations across all Civil Service reviewers!
                        </p>
                        <div class="space-y-2.5">
                            <a href="{{ route('pricing') }}" class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md block">
                                Upgrade to Premium (Unlimited AI)
                            </a>
                            <button @click="showLimitModal = false" class="w-full text-slate-500 font-semibold text-xs py-2">
                                Close
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </section>

    <script>
        function resultsManager(config) {
            return {
                sessionId: config.sessionId,
                isLoggedIn: config.isLoggedIn,
                maxAllowed: config.maxAllowed,
                unlockedMap: {},
                openMap: {},
                cachedTexts: {},
                typedTexts: {},
                typingTimers: {},
                showLimitModal: false,

                init() {
                    try {
                        const savedUnlocked = localStorage.getItem('ai_unlocked_' + this.sessionId);
                        const savedOpen = localStorage.getItem('ai_open_' + this.sessionId);
                        const savedCached = localStorage.getItem('ai_cached_' + this.sessionId);

                        if (savedUnlocked) this.unlockedMap = JSON.parse(savedUnlocked);
                        if (savedOpen) this.openMap = JSON.parse(savedOpen);
                        if (savedCached) this.cachedTexts = JSON.parse(savedCached);

                        Object.keys(this.unlockedMap).forEach(qId => {
                            if (this.cachedTexts[qId]) {
                                this.typedTexts[qId] = { text: this.cachedTexts[qId], isDone: true };
                            }
                        });
                    } catch(e) {}
                },

                get unlockedCount() {
                    return Object.keys(this.unlockedMap).length;
                },

                isUnlocked(qId) {
                    return !!this.unlockedMap[qId];
                },

                isOpen(qId) {
                    return !!this.openMap[qId];
                },

                saveState() {
                    try {
                        localStorage.setItem('ai_unlocked_' + this.sessionId, JSON.stringify(this.unlockedMap));
                        localStorage.setItem('ai_open_' + this.sessionId, JSON.stringify(this.openMap));
                        localStorage.setItem('ai_cached_' + this.sessionId, JSON.stringify(this.cachedTexts));
                    } catch(e) {}
                },

                getTypedText(qId, fallbackText) {
                    if (!this.typedTexts[qId]) return '';
                    let text = this.typedTexts[qId].text;
                    text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    return text.replace(/\n/g, '<br>');
                },

                isTyping(qId) {
                    return this.typedTexts[qId] && !this.typedTexts[qId].isDone;
                },

                async fetchExplanation(qId, fallbackText, forceRegenerate = false) {
                    if (this.typingTimers[qId]) clearInterval(this.typingTimers[qId]);
                    this.typedTexts[qId] = { text: 'Analyzing question and generating explanation...', isDone: false };

                    try {
                        const res = await fetch(`/exam/session/${this.sessionId}/explain-question`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ question_id: qId, force_regenerate: forceRegenerate }),
                        });
                        const data = await res.json();
                        const text = (data.success && data.explanation) ? data.explanation : fallbackText;
                        
                        this.cachedTexts[qId] = text;
                        this.saveState();

                        this.startTypewriter(qId, text);
                    } catch(e) {
                        this.startTypewriter(qId, fallbackText);
                    }
                },

                toggleExplanation(qId, fallbackText) {
                    if (this.isOpen(qId)) {
                        this.openMap[qId] = false;
                        this.openMap = { ...this.openMap };
                        this.saveState();
                        return;
                    }

                    if (!this.isUnlocked(qId)) {
                        if (this.unlockedCount >= this.maxAllowed) {
                            this.showLimitModal = true;
                            return;
                        }
                        this.unlockedMap[qId] = true;
                        this.unlockedMap = { ...this.unlockedMap };
                    }

                    this.openMap[qId] = true;
                    this.openMap = { ...this.openMap };
                    this.saveState();

                    if (this.cachedTexts[qId]) {
                        if (!this.typedTexts[qId] || !this.typedTexts[qId].text) {
                            this.startTypewriter(qId, this.cachedTexts[qId]);
                        }
                    } else {
                        this.fetchExplanation(qId, fallbackText, false);
                    }
                },

                explainAgain(qId, fallbackText) {
                    this.fetchExplanation(qId, fallbackText, true);
                },

                startTypewriter(qId, fullText) {
                    if (this.typingTimers[qId]) clearInterval(this.typingTimers[qId]);
                    this.typedTexts[qId] = { text: '', isDone: false };

                    let i = 0;
                    const speed = 6;
                    this.typingTimers[qId] = setInterval(() => {
                        if (i < fullText.length) {
                            this.typedTexts[qId].text += fullText.charAt(i);
                            i++;
                        } else {
                            this.typedTexts[qId].isDone = true;
                            clearInterval(this.typingTimers[qId]);
                        }
                    }, speed);
                }
            };
        }
    </script>
</x-public-layout>
