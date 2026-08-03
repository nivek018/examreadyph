<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exam->name }} — ExamReady PH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased"
      x-data="examEngine()"
      x-init="initTimer()">

    {{-- Top Bar --}}
    <header class="h-14 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-slate-900 dark:text-white truncate max-w-[200px] sm:max-w-none">{{ $exam->name }}</div>
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    {{ $exam->category->name ?? '' }}
                    @if(($session->mode ?? 'mock') === 'relaxed')
                        <span class="px-1.5 py-0.5 rounded bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 text-[9px] font-bold uppercase">Relaxed</span>
                    @elseif(($session->mode ?? 'mock') === 'practice')
                        <span class="px-1.5 py-0.5 rounded bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 text-[9px] font-bold uppercase">Practice</span>
                    @else
                        <span class="px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 text-[9px] font-bold uppercase">Mock</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            {{-- Timer --}}
            @if($session->time_limit_seconds > 0)
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg border"
                 :class="timerWarning ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-300 dark:border-rose-700' : 'bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-700'">
                <i class="fa-solid fa-clock" :class="timerWarning ? 'text-rose-500 animate-pulse' : 'text-blue-500'"></i>
                <span class="text-sm font-mono font-bold" :class="timerWarning ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white'" x-text="formatTime(remainingSeconds)"></span>
            </div>
            @endif

            {{-- Progress --}}
            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <span x-text="answeredCount"></span>/<span>{{ $totalQuestions }}</span> answered
            </div>

            {{-- Submit Button --}}
            <button @click="confirmSubmit()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition flex items-center gap-1.5">
                <i class="fa-solid fa-paper-plane"></i> Submit
            </button>
        </div>
    </header>

    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-56px)]">

        {{-- Question Navigator Sidebar --}}
        <aside class="order-2 lg:order-1 lg:w-72 bg-white dark:bg-slate-900 border-t lg:border-t-0 lg:border-r border-slate-200 dark:border-slate-800 p-4 lg:sticky lg:top-14 lg:h-[calc(100vh-56px)] lg:overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Question Navigator</h3>
                <span class="text-xs text-slate-400" x-text="answeredCount + '/' + {{ $totalQuestions }} + ' done'"></span>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap gap-3 mb-4 text-[10px] text-slate-500 dark:text-slate-400">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-600"></span> Current</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-emerald-500"></span> Answered</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-amber-500"></span> Bookmarked</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-slate-300 dark:bg-slate-600"></span> Unanswered</span>
            </div>

            {{-- Grid --}}
            <div class="grid grid-cols-8 gap-1.5">
                @foreach($questionOrder as $idx => $qId)
                <button @click="goToQuestion({{ $idx }})"
                        :class="getNavClass({{ $idx }}, {{ $qId }})"
                        class="w-8 h-8 rounded text-xs font-bold transition-all hover:scale-110 flex items-center justify-center">
                    {{ $idx + 1 }}
                </button>
                @endforeach
            </div>
        </aside>

        {{-- Main Question Area --}}
        <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto">

                {{-- Question Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-xs font-bold text-blue-600 dark:text-blue-400">Question <span x-text="currentIndex + 1"></span> of {{ $totalQuestions }}</span>
                        @if($question->section_name)
                        <span class="text-xs text-slate-500 ml-2">• {{ $question->section_name }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Bookmark Button --}}
                        <button @click="toggleFlag()" class="px-3 py-1.5 rounded-lg border text-xs font-medium transition"
                                :class="isFlagged ? 'bg-amber-50 dark:bg-amber-950/50 border-amber-300 dark:border-amber-700 text-amber-600 dark:text-amber-400 font-bold' : 'bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-slate-500 hover:text-amber-600'">
                            <i class="fa-solid fa-bookmark mr-1"></i> <span x-text="isFlagged ? 'Bookmarked' : 'Bookmark'"></span>
                        </button>

                        {{-- Report Question Button --}}
                        <button @click="showReportModal = true; reportSuccessMsg = ''" class="px-3 py-1.5 rounded-lg border border-rose-200 dark:border-rose-800/60 bg-rose-50/50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-xs font-medium transition flex items-center gap-1">
                            <i class="fa-solid fa-triangle-exclamation"></i> Report Issue
                        </button>
                    </div>
                </div>

                {{-- Question Card --}}
                <div class="card p-6 sm:p-8 mb-6">
                    <p class="text-base sm:text-lg font-semibold text-slate-900 dark:text-white leading-relaxed" id="question-text">
                        {{ $question->question_text }}
                    </p>
                </div>

                {{-- Options --}}
                <div class="space-y-3 mb-8" id="options-list">
                    @foreach($options as $option)
                    <button @click="selectOption({{ $option->id }})"
                            :class="selectedOptionId === {{ $option->id }}
                                ? 'border-blue-500 dark:border-blue-400 bg-blue-50 dark:bg-blue-950/30 ring-2 ring-blue-500/20'
                                : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-blue-400 dark:hover:border-blue-500'"
                            class="w-full text-left p-4 rounded-xl border-2 transition-all flex items-center gap-4 group">
                        <span class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-bold shrink-0 transition"
                              :class="selectedOptionId === {{ $option->id }}
                                  ? 'bg-blue-600 text-white'
                                  : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50 group-hover:text-blue-600'">
                            {{ $option->letter }}
                        </span>
                        <span class="text-sm sm:text-base text-slate-800 dark:text-slate-200">{{ $option->text }}</span>
                    </button>
                    @endforeach
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between">
                    <button @click="prevQuestion()" :disabled="currentIndex === 0"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition disabled:opacity-30 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-arrow-left"></i> Previous
                    </button>

                    <button @click="nextQuestion()" :disabled="currentIndex >= {{ $totalQuestions - 1 }}"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white transition disabled:opacity-30 disabled:cursor-not-allowed">
                        Next <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- Submit Confirmation Modal --}}
    <div x-show="showSubmitModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="card p-6 max-w-md w-full mx-4" @click.outside="showSubmitModal = false">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Submit Exam?
            </h3>
            <div class="space-y-2 mb-6 text-sm text-slate-600 dark:text-slate-400">
                <p>You have answered <strong class="text-slate-900 dark:text-white" x-text="answeredCount"></strong> out of <strong class="text-slate-900 dark:text-white">{{ $totalQuestions }}</strong> questions.</p>
                <p x-show="unansweredCount > 0" class="text-amber-600 dark:text-amber-400 font-medium">
                    <i class="fa-solid fa-exclamation-circle"></i> <span x-text="unansweredCount"></span> question(s) are still unanswered!
                </p>
                <p x-show="flaggedCount > 0" class="text-amber-600 dark:text-amber-400">
                    <i class="fa-solid fa-flag"></i> <span x-text="flaggedCount"></span> question(s) are flagged for review.
                </p>
            </div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('exam.submit', $session) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                        <i class="fa-solid fa-check"></i> Yes, Submit
                    </button>
                </form>
                <button @click="showSubmitModal = false" class="flex-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold py-2.5 rounded-lg text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Report Question Modal --}}
    <div x-show="showReportModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="card p-6 max-w-md w-full mx-4" @click.outside="showReportModal = false">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Report Question Issue
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Found an issue with Question <span class="font-bold text-slate-700 dark:text-slate-300" x-text="currentIndex + 1"></span>? Let us know so our team can fix it.</p>

            <template x-if="reportSuccessMsg">
                <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm text-center mb-4">
                    <i class="fa-solid fa-circle-check text-lg mb-1 block"></i>
                    <span x-text="reportSuccessMsg"></span>
                </div>
            </template>

            <form @submit.prevent="submitReport()" x-show="!reportSuccessMsg">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Issue Type *</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="incorrect_answer" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Wrong Answer / Incorrect Key</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="incorrect_grammar" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Incorrect Grammar / Typo</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="outdated" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Outdated Information</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="unclear" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Unclear or Confusing Question</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="other" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Other Issue</span>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Additional Details (Optional)</label>
                    <textarea x-model="reportDescription" rows="3" placeholder="Describe what's wrong (e.g., Option B should be the correct answer because...)"
                              class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-rose-500 transition"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" :disabled="isReporting" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 rounded-lg text-xs transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-paper-plane" x-show="!isReporting"></i>
                        <span x-text="isReporting ? 'Submitting...' : 'Submit Report'"></span>
                    </button>
                    <button type="button" @click="showReportModal = false" class="bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold px-4 py-2.5 rounded-lg text-xs hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ad Popup Modal (Free Users Only / Configurable) --}}
    @if(isset($adConfig) && $adConfig['enabled'])
    <div x-show="showAdModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md" style="display: none;">
        <div class="card p-5 max-w-lg w-full mx-4 relative overflow-hidden text-center border-2 border-amber-400 dark:border-amber-500 shadow-2xl">
            {{-- Close Button --}}
            <button @click="closeAd()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-500 hover:text-white transition flex items-center justify-center text-sm font-bold z-10">
                <i class="fa-solid fa-xmark"></i>
            </button>

            {{-- Sponsored Label --}}
            <div class="flex items-center justify-between mb-3 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                <span class="flex items-center gap-1.5 text-amber-500"><i class="fa-solid fa-rectangle-ad"></i> Sponsored Partner Ad</span>
                <span x-show="adDismissCountdown > 0" class="text-slate-400">Auto-close in <span x-text="adDismissCountdown" class="font-bold text-white"></span>s</span>
            </div>

            {{-- Ad Banner Image Link --}}
            <template x-if="currentAd">
                <a :href="currentAd.destination_url" target="_blank" @click="handleAdClick(currentAd.id)" class="block rounded-xl overflow-hidden group border border-slate-200 dark:border-slate-700 hover:border-amber-400 transition">
                    <img :src="currentAd.image_url" :alt="currentAd.alt_text" class="w-full h-auto max-h-[320px] object-cover group-hover:scale-105 transition duration-300">
                </a>
            </template>

            {{-- Upsell CTA --}}
            <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                <span class="text-slate-600 dark:text-slate-400 font-medium" x-text="adConfig.upgrade_cta_text"></span>
                <a href="{{ route('pricing') }}" target="_blank" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold px-3 py-1.5 rounded-lg transition shrink-0 flex items-center gap-1">
                    <i class="fa-solid fa-crown"></i> Go Premium
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Auto-submit form (hidden) --}}
    <form id="auto-submit-form" method="POST" action="{{ route('exam.submit', $session) }}" style="display: none;">
        @csrf
    </form>

    <script>
        function examEngine() {
            return {
                sessionId: '{{ $session->uuid }}',
                currentIndex: {{ $currentIndex }},
                totalQuestions: {{ $totalQuestions }},
                questionOrder: @json($questionOrder),
                remainingSeconds: {{ $session->remaining_seconds }},
                timerWarning: false,
                showSubmitModal: false,
                showReportModal: false,
                reportReason: 'incorrect_answer',
                reportDescription: '',
                reportSuccessMsg: '',
                isReporting: false,

                // Ad Engine State
                adConfig: @json($adConfig ?? ['enabled' => false]),
                showAdModal: false,
                currentAd: null,
                adPopupsShownCount: 0,
                questionsAnsweredSinceLastAd: 0,
                adDismissTimer: null,
                adDismissCountdown: 0,

                selectedOptionId: {{ $answers[$question->id]->selected_option_id ?? 'null' }},
                isFlagged: {{ ($answers[$question->id]->is_flagged ?? false) ? 'true' : 'false' }},

                // Track answer states for navigator
                @php
                    $answerStatesJson = $answers->map(function($a) {
                        return [
                            'question_id' => $a->question_id,
                            'answered' => $a->selected_option_id !== null,
                            'flagged' => (bool) $a->is_flagged,
                        ];
                    })->values();
                @endphp
                answerStates: @json($answerStatesJson),

                get answeredCount() {
                    return this.answerStates.filter(a => a.answered).length;
                },
                get unansweredCount() {
                    return this.totalQuestions - this.answeredCount;
                },
                get flaggedCount() {
                    return this.answerStates.filter(a => a.flagged).length;
                },

                initTimer() {
                    if (this.remainingSeconds <= 0) return;
                    setInterval(() => {
                        this.remainingSeconds--;
                        this.timerWarning = this.remainingSeconds <= 300; // 5 min warning
                        if (this.remainingSeconds <= 0) {
                            document.getElementById('auto-submit-form').submit();
                        }
                    }, 1000);
                },

                formatTime(secs) {
                    const h = Math.floor(secs / 3600);
                    const m = Math.floor((secs % 3600) / 60);
                    const s = secs % 60;
                    return (h > 0 ? h + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                },

                async selectOption(optionId) {
                    const wasAlreadyAnswered = this.selectedOptionId !== null;
                    this.selectedOptionId = optionId;

                    // Update local state
                    const stateIdx = this.answerStates.findIndex(a => a.question_id === this.questionOrder[this.currentIndex]);
                    if (stateIdx !== -1) this.answerStates[stateIdx].answered = true;

                    // Save to server
                    await fetch(`/exam/session/${this.sessionId}/answer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            question_id: this.questionOrder[this.currentIndex],
                            option_id: optionId,
                        }),
                    });

                    // Trigger Ad logic on new answer
                    if (!wasAlreadyAnswered) {
                        this.questionsAnsweredSinceLastAd++;
                        this.checkTriggerAd();
                    }
                },

                checkTriggerAd() {
                    if (!this.adConfig.enabled || !this.adConfig.ads || this.adConfig.ads.length === 0) return;
                    if (this.adPopupsShownCount >= this.adConfig.max_per_session) return;

                    if (this.questionsAnsweredSinceLastAd >= this.adConfig.show_after_questions) {
                        this.triggerAd();
                    }
                },

                triggerAd() {
                    this.questionsAnsweredSinceLastAd = 0;
                    this.adPopupsShownCount++;

                    // Pick next ad round-robin
                    const adIndex = (this.adPopupsShownCount - 1) % this.adConfig.ads.length;
                    this.currentAd = this.adConfig.ads[adIndex];
                    this.showAdModal = true;

                    // Track impression
                    fetch(`/ads/${this.currentAd.id}/impression`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    });

                    // Handle auto dismiss countdown
                    if (this.adConfig.auto_dismiss_seconds > 0) {
                        this.adDismissCountdown = this.adConfig.auto_dismiss_seconds;
                        clearInterval(this.adDismissTimer);
                        this.adDismissTimer = setInterval(() => {
                            this.adDismissCountdown--;
                            if (this.adDismissCountdown <= 0) {
                                this.closeAd();
                            }
                        }, 1000);
                    }
                },

                closeAd() {
                    this.showAdModal = false;
                    clearInterval(this.adDismissTimer);
                },

                handleAdClick(adId) {
                    fetch(`/ads/${adId}/click`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    });
                },

                async toggleFlag() {
                    this.isFlagged = !this.isFlagged;

                    const stateIdx = this.answerStates.findIndex(a => a.question_id === this.questionOrder[this.currentIndex]);
                    if (stateIdx !== -1) this.answerStates[stateIdx].flagged = this.isFlagged;

                    await fetch(`/exam/session/${this.sessionId}/answer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            question_id: this.questionOrder[this.currentIndex],
                            is_flagged: this.isFlagged,
                        }),
                    });
                },

                async submitReport() {
                    this.isReporting = true;
                    try {
                        const res = await fetch(`/exam/session/${this.sessionId}/report-question`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                question_id: this.questionOrder[this.currentIndex],
                                reason: this.reportReason,
                                description: this.reportDescription,
                            }),
                        });
                        const data = await res.json();
                        if (data.success) {
                            this.reportSuccessMsg = data.message;
                            this.reportDescription = '';
                            setTimeout(() => { this.showReportModal = false; }, 2000);
                        }
                    } catch (e) {
                        alert('Error submitting report.');
                    } finally {
                        this.isReporting = false;
                    }
                },

                async goToQuestion(index) {
                    await fetch(`/exam/session/${this.sessionId}/navigate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ index }),
                    });
                    window.location.reload();
                },

                prevQuestion() {
                    if (this.currentIndex > 0) this.goToQuestion(this.currentIndex - 1);
                },

                nextQuestion() {
                    if (this.currentIndex < this.totalQuestions - 1) this.goToQuestion(this.currentIndex + 1);
                },

                confirmSubmit() {
                    this.showSubmitModal = true;
                },

                getNavClass(index, questionId) {
                    const state = this.answerStates.find(a => a.question_id === questionId);
                    if (index === this.currentIndex) return 'bg-blue-600 text-white ring-2 ring-blue-400';
                    if (state && state.flagged) return 'bg-amber-500 text-white';
                    if (state && state.answered) return 'bg-emerald-500 text-white';
                    return 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300';
                },
            };
        }
    </script>
</body>
</html>
