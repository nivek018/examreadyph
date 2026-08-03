<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $exam->name }} — ExamReady PH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased flex flex-col font-sans"
      x-data="examEngine()"
      x-init="initTimer()">

    {{-- Top Bar Header --}}
    <header class="h-16 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6">
        {{-- Left Info: Logo + Exit Button + Exam Title --}}
        <div class="flex items-center gap-3 min-w-0">
            {{-- Brand Logo --}}
            <a href="{{ route('home') }}" @click.prevent="showExitModal = true" class="flex items-center gap-2.5 group shrink-0">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow-sm">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <span class="hidden sm:inline text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">ExamReady <span class="text-blue-500">PH</span></span>
            </a>

            <span class="text-slate-300 dark:text-slate-700">|</span>

            {{-- Exit Link (Option A) --}}
            <button @click="showExitModal = true" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1.5 transition shrink-0">
                <i class="fa-solid fa-arrow-left"></i> Exit Exam
            </button>

            <div class="min-w-0 hidden md:block border-l border-slate-200 dark:border-slate-800 pl-3">
                <div class="flex items-center gap-2">
                    <h1 class="text-sm font-extrabold text-slate-900 dark:text-white truncate">{{ $exam->name }}</h1>
                    @if(($session->mode ?? 'mock') === 'relaxed')
                        <span class="px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800 text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Relaxed</span>
                    @elseif(($session->mode ?? 'mock') === 'practice')
                        <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Practice</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Mock</span>
                    @endif
                </div>
                {{-- Progress percentage bar --}}
                <div class="w-32 bg-slate-200 dark:bg-slate-800 rounded-full h-1.5 mt-1 overflow-hidden">
                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + progressPercent + '%'"></div>
                </div>
            </div>
        </div>

        {{-- Right Controls --}}
        <div class="flex items-center gap-3 sm:gap-4">
            {{-- Timer --}}
            @if($session->time_limit_seconds > 0)
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl border text-xs sm:text-sm"
                 :class="timerWarning ? 'bg-rose-50 dark:bg-rose-950/50 border-rose-300 dark:border-rose-700' : 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700'">
                <i class="fa-solid fa-clock" :class="timerWarning ? 'text-rose-500 animate-pulse' : 'text-blue-500'"></i>
                <span class="font-mono font-bold" :class="timerWarning ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white'" x-text="formatTime(remainingSeconds)"></span>
            </div>
            @endif

            {{-- Progress Counter --}}
            <div class="hidden sm:block text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span x-text="answeredCount" class="font-bold text-slate-900 dark:text-white"></span>/<span>{{ $totalQuestions }}</span> answered
            </div>

            {{-- Mobile Navigator Toggle --}}
            <button @click="showMobileNav = !showMobileNav" class="lg:hidden p-2 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold flex items-center gap-1.5">
                <i class="fa-solid fa-grip"></i>
                <span x-text="answeredCount + '/' + {{ $totalQuestions }}"></span>
            </button>

            {{-- Submit Exam Button (Icon Removed) --}}
            <button @click="confirmSubmit()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-bold px-4 py-2 rounded-xl transition shadow-sm hover:shadow">
                Submit
            </button>
        </div>
    </header>

    {{-- Main Container --}}
    <div class="flex-1 flex flex-col lg:flex-row max-w-7xl w-full mx-auto">

        {{-- Question Navigator Sidebar (Desktop) --}}
        <aside class="hidden lg:block w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 p-5 sticky top-16 h-[calc(100vh-64px)] overflow-y-auto shrink-0">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Question Navigator</h2>
                <span class="text-xs font-bold text-blue-600 dark:text-blue-400" x-text="answeredCount + ' of ' + {{ $totalQuestions }} + ' done'"></span>
            </div>

            {{-- Legend --}}
            <div class="grid grid-cols-2 gap-2 mb-5 text-[11px] text-slate-600 dark:text-slate-400">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-600"></span> Current</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-500"></span> Answered</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-amber-500"></span> Bookmarked</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-slate-200 dark:bg-slate-700"></span> Unanswered</span>
            </div>

            {{-- Question Grid --}}
            <div class="grid grid-cols-5 gap-2">
                <template x-for="(qId, idx) in questionOrder" :key="idx">
                    <button @click="goToQuestion(idx)"
                            :class="getNavClass(idx, qId)"
                            class="h-9 rounded-lg text-xs font-bold transition-all hover:scale-105 flex items-center justify-center">
                        <span x-text="idx + 1"></span>
                    </button>
                </template>
            </div>
        </aside>

        {{-- Mobile Navigator Drawer --}}
        <div x-show="showMobileNav" x-cloak x-transition
             class="lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 p-4 sticky top-16 z-20 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Question Navigator</h2>
                <button @click="showMobileNav = false" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark"></i> Close
                </button>
            </div>
            <div class="grid grid-cols-8 gap-1.5 max-h-48 overflow-y-auto">
                <template x-for="(qId, idx) in questionOrder" :key="idx">
                    <button @click="goToQuestion(idx); showMobileNav = false"
                            :class="getNavClass(idx, qId)"
                            class="h-8 rounded text-xs font-bold flex items-center justify-center">
                        <span x-text="idx + 1"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Main Question Content Area --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-10 max-w-4xl mx-auto w-full">

            {{-- Question Header Bar --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-2">
                    <span class="badge-blue text-xs font-bold">
                        Question <span x-text="currentIndex + 1"></span> of {{ $totalQuestions }}
                    </span>
                    <template x-if="currentQuestion.section_name">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium" x-text="'• ' + currentQuestion.section_name"></span>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Bookmark Button --}}
                    <button @click="toggleFlag()" class="px-3 py-1.5 rounded-xl border text-xs font-medium transition flex items-center gap-1.5"
                            :class="isFlagged ? 'bg-amber-50 dark:bg-amber-950/50 border-amber-300 dark:border-amber-700 text-amber-600 dark:text-amber-400 font-bold' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:border-amber-400'">
                        <i class="fa-solid fa-bookmark" :class="isFlagged ? 'text-amber-500' : ''"></i>
                        <span x-text="isFlagged ? 'Bookmarked' : 'Bookmark'"></span>
                    </button>

                    {{-- Report Issue Button --}}
                    <button @click="showReportModal = true; reportSuccessMsg = ''" class="px-3 py-1.5 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50/50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 text-xs font-medium transition flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Report</span>
                    </button>
                </div>
            </div>

            {{-- Question Text Card --}}
            <div class="card flat-card p-6 sm:p-8 mb-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white leading-relaxed" x-text="currentQuestion.question_text"></h2>
            </div>

            {{-- Options List --}}
            <div class="space-y-3 mb-8">
                <template x-for="option in currentQuestion.options" :key="option.id">
                    <button @click="selectOption(option.id)"
                            :disabled="mode !== 'mock' && isCurrentAnswered"
                            :class="getOptionClass(option)"
                            class="w-full text-left p-4 rounded-xl border-2 transition-all flex items-start gap-4 group cursor-pointer disabled:cursor-default">
                        {{-- Letter Circle --}}
                        <span :class="getOptionLetterClass(option)"
                              class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold shrink-0 transition mt-0.5">
                            <span x-text="option.letter"></span>
                        </span>
                        {{-- Option Text --}}
                        <span class="text-sm sm:text-base text-slate-800 dark:text-slate-200 leading-normal pt-1" x-text="option.text"></span>
                    </button>
                </template>
            </div>

            {{-- Instant Explanation Box (Relaxed & Practice Mode) --}}
            <template x-if="mode !== 'mock' && isCurrentAnswered">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="card flat-card p-6 mb-8 border-2"
                     :class="currentAnswerIsCorrect ? 'bg-emerald-50/70 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-700' : 'bg-rose-50/70 dark:bg-rose-950/40 border-rose-300 dark:border-rose-700'">
                    
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm"
                             :class="currentAnswerIsCorrect ? 'bg-emerald-600' : 'bg-rose-600'">
                            <i class="fa-solid" :class="currentAnswerIsCorrect ? 'fa-check' : 'fa-xmark'"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold"
                                :class="currentAnswerIsCorrect ? 'text-emerald-900 dark:text-emerald-300' : 'text-rose-900 dark:text-rose-300'"
                                x-text="currentAnswerIsCorrect ? 'Tumpak! (Correct Answer)' : 'Mali! (Incorrect)'"></h3>
                             <p class="text-xs font-semibold"
                                :class="currentAnswerIsCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400'">
                                 AI Explanation & Solution
                             </p>
                        </div>
                    </div>

                    <div class="text-sm sm:text-base text-slate-700 dark:text-slate-200 leading-relaxed pt-2 border-t"
                         :class="currentAnswerIsCorrect ? 'border-emerald-200 dark:border-emerald-800' : 'border-rose-200 dark:border-rose-800'"
                         x-html="formattedExplanation">
                    </div>
                </div>
            </template>

            {{-- Bottom Navigation Bar --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-800">
                <button @click="prevQuestion()" :disabled="currentIndex === 0"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition disabled:opacity-30 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-arrow-left"></i> Previous
                </button>

                <div class="text-xs font-bold text-slate-400">
                    <span x-text="currentIndex + 1"></span> / {{ $totalQuestions }}
                </div>

                {{-- Show Next on questions 1 to N-1, Show Finish on last question --}}
                <template x-if="currentIndex < totalQuestions - 1">
                    <button @click="nextQuestion()"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white transition shadow-md">
                        Next <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </template>

                <template x-if="currentIndex === totalQuestions - 1">
                    <button @click="confirmSubmit()"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-md">
                        <span>Finish & View Results</span> <i class="fa-solid fa-check"></i>
                    </button>
                </template>
            </div>

        </main>
    </div>

    {{-- Exit Confirmation Modal --}}
    <div x-show="showExitModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="card flat-card p-6 max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" @click.outside="showExitModal = false">
            <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-door-open"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Exit Exam Session?</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                Your progress is saved automatically. You can resume this session anytime from your dashboard.
            </p>
            <div class="space-y-2.5">
                <a href="{{ route('reviewer.show', $exam) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Exit to Subject Page
                </a>
                <a href="{{ route('home') }}" class="w-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold py-3 rounded-xl text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-house"></i> Exit to Homepage
                </a>
                <button @click="showExitModal = false" class="w-full text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-semibold text-xs py-2 transition">
                    Keep Reviewing
                </button>
            </div>
        </div>
    </div>

    {{-- Submit Confirmation Modal --}}
    <div x-show="showSubmitModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="card flat-card p-6 max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" @click.outside="showSubmitModal = false">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-trophy"></i>
            </div>

            <template x-if="answeredCount === totalQuestions">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">All Questions Answered! 🎉</h3>
            </template>
            <template x-if="answeredCount < totalQuestions">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Submit Exam Session?</h3>
            </template>

            <div class="space-y-2 mb-6 text-sm text-slate-600 dark:text-slate-400">
                <p>You have answered <strong class="text-slate-900 dark:text-white" x-text="answeredCount"></strong> out of <strong class="text-slate-900 dark:text-white">{{ $totalQuestions }}</strong> questions.</p>
                <template x-if="unansweredCount > 0">
                    <p class="text-amber-600 dark:text-amber-400 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> <span x-text="unansweredCount"></span> question(s) are still unanswered!
                    </p>
                </template>
                <template x-if="flaggedCount > 0">
                    <p class="text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-bookmark mr-1"></i> <span x-text="flaggedCount"></span> question(s) are bookmarked.
                    </p>
                </template>
            </div>
            <div class="flex gap-3">
                <form method="POST" action="{{ route('exam.submit', $session) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                        View Results & Score
                    </button>
                </form>
                <button @click="showSubmitModal = false" class="flex-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold py-3 rounded-xl text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                    Keep Reviewing
                </button>
            </div>
        </div>
    </div>

    {{-- Report Question Modal --}}
    <div x-show="showReportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="card flat-card p-6 max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800" @click.outside="showReportModal = false">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i> Report Question Issue
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Found an issue with Question <span class="font-bold text-slate-700 dark:text-slate-300" x-text="currentIndex + 1"></span>? Let us know so our team can fix it.</p>

            <template x-if="reportSuccessMsg">
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm text-center mb-4">
                    <i class="fa-solid fa-circle-check text-xl mb-1 block"></i>
                    <span x-text="reportSuccessMsg"></span>
                </div>
            </template>

            <form @submit.prevent="submitReport()" x-show="!reportSuccessMsg">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Issue Type *</label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="incorrect_answer" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Wrong Answer / Incorrect Key</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="incorrect_grammar" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Incorrect Grammar / Typo</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="outdated" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Outdated Information</span>
                        </label>
                        <label class="flex items-center gap-3 p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 cursor-pointer text-xs">
                            <input type="radio" name="report_reason" value="unclear" x-model="reportReason" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">Unclear or Confusing Question</span>
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Additional Details (Optional)</label>
                    <textarea x-model="reportDescription" rows="3" placeholder="Describe what's wrong..."
                              class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-rose-500 transition"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" :disabled="isReporting" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 rounded-xl text-xs transition flex items-center justify-center gap-1.5">
                        <span x-text="isReporting ? 'Submitting...' : 'Submit Report'"></span>
                    </button>
                    <button type="button" @click="showReportModal = false" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold px-4 py-2.5 rounded-xl text-xs hover:bg-slate-200 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Ad Popup Modal --}}
    @if(isset($adConfig) && $adConfig['enabled'])
    <div x-show="showAdModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-md">
        <div class="card flat-card p-5 max-w-lg w-full bg-white dark:bg-slate-900 relative overflow-hidden text-center border-2 border-amber-400 shadow-2xl">
            <button @click="closeAd()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-rose-500 hover:text-white transition flex items-center justify-center text-sm font-bold z-10">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="flex items-center justify-between mb-3 text-xs text-slate-400 font-semibold uppercase tracking-wider">
                <span class="flex items-center gap-1.5 text-amber-500"><i class="fa-solid fa-rectangle-ad"></i> Sponsored Partner Ad</span>
                <span x-show="adDismissCountdown > 0">Auto-close in <span x-text="adDismissCountdown" class="font-bold text-white"></span>s</span>
            </div>
            <template x-if="currentAd">
                <a :href="currentAd.destination_url" target="_blank" @click="handleAdClick(currentAd.id)" class="block rounded-xl overflow-hidden group border border-slate-200 dark:border-slate-700 hover:border-amber-400 transition">
                    <img :src="currentAd.image_url" :alt="currentAd.alt_text" class="w-full h-auto max-h-[320px] object-cover group-hover:scale-105 transition duration-300">
                </a>
            </template>
        </div>
    </div>
    @endif

    {{-- Auto-submit form --}}
    <form id="auto-submit-form" method="POST" action="{{ route('exam.submit', $session) }}" style="display: none;">
        @csrf
    </form>

    <script>
        function examEngine() {
            return {
                sessionId: '{{ $session->uuid }}',
                mode: '{{ $session->mode ?? "mock" }}',
                currentIndex: {{ $currentIndex }},
                totalQuestions: {{ $totalQuestions }},
                questionOrder: @json($questionOrder),
                questionsData: @json($questionsData),
                remainingSeconds: {{ $session->remaining_seconds }},
                timerWarning: false,
                showSubmitModal: false,
                showExitModal: false,
                showReportModal: false,
                showMobileNav: false,
                reportReason: 'incorrect_answer',
                reportDescription: '',
                reportSuccessMsg: '',
                isReporting: false,

                // Ad Engine
                adConfig: @json($adConfig ?? ['enabled' => false]),
                showAdModal: false,
                currentAd: null,
                adPopupsShownCount: 0,
                questionsAnsweredSinceLastAd: 0,

                // Local Answers Map: { [questionId]: { optionId, isCorrect, isFlagged } }
                userAnswers: @json($answers->mapWithKeys(function($a) {
                    return [$a->question_id => [
                        'optionId' => $a->selected_option_id,
                        'isCorrect' => $a->is_correct !== null ? (bool)$a->is_correct : null,
                        'isFlagged' => (bool)$a->is_flagged
                    ]];
                })),

                get currentQuestion() {
                    return this.questionsData[this.currentIndex] || {};
                },
                get currentAnswer() {
                    return this.userAnswers[this.currentQuestion.id] || { optionId: null, isCorrect: null, isFlagged: false };
                },
                get isCurrentAnswered() {
                    return this.currentAnswer.optionId !== null && this.currentAnswer.optionId !== undefined;
                },
                get currentAnswerIsCorrect() {
                    if (this.currentAnswer.isCorrect !== null) return this.currentAnswer.isCorrect;
                    const selectedOpt = (this.currentQuestion.options || []).find(o => o.id === this.currentAnswer.optionId);
                    return selectedOpt ? selectedOpt.is_correct : false;
                },
                get isFlagged() {
                    return !!this.currentAnswer.isFlagged;
                },
                get answeredCount() {
                    return Object.values(this.userAnswers).filter(a => a.optionId !== null && a.optionId !== undefined).length;
                },
                get unansweredCount() {
                    return this.totalQuestions - this.answeredCount;
                },
                get flaggedCount() {
                    return Object.values(this.userAnswers).filter(a => a.isFlagged).length;
                },
                get progressPercent() {
                    return Math.round((this.answeredCount / this.totalQuestions) * 100);
                },
                get formattedExplanation() {
                    const exp = this.currentQuestion.explanation_taglish || 'No explanation available.';
                    return exp.replace(/\n/g, '<br>');
                },

                initTimer() {
                    if (this.remainingSeconds <= 0) return;
                    setInterval(() => {
                        this.remainingSeconds--;
                        this.timerWarning = this.remainingSeconds <= 300;
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
                    if (this.mode !== 'mock' && this.isCurrentAnswered) return;

                    const qId = this.currentQuestion.id;
                    const wasAlreadyAnswered = this.isCurrentAnswered;

                    const selectedOpt = (this.currentQuestion.options || []).find(o => o.id === optionId);
                    const isCorrect = selectedOpt ? selectedOpt.is_correct : null;

                    if (!this.userAnswers[qId]) {
                        this.userAnswers[qId] = { optionId: null, isCorrect: null, isFlagged: false };
                    }
                    this.userAnswers[qId].optionId = optionId;
                    this.userAnswers[qId].isCorrect = isCorrect;

                    // Background AJAX persist
                    fetch(`/exam/session/${this.sessionId}/answer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ question_id: qId, option_id: optionId }),
                    });

                    // Trigger submit modal automatically if all questions are now answered
                    if (this.answeredCount === this.totalQuestions) {
                        setTimeout(() => { this.showSubmitModal = true; }, 600);
                    }

                    if (!wasAlreadyAnswered) {
                        this.questionsAnsweredSinceLastAd++;
                        this.checkTriggerAd();
                    }
                },

                async toggleFlag() {
                    const qId = this.currentQuestion.id;
                    if (!this.userAnswers[qId]) {
                        this.userAnswers[qId] = { optionId: null, isCorrect: null, isFlagged: false };
                    }
                    this.userAnswers[qId].isFlagged = !this.userAnswers[qId].isFlagged;

                    fetch(`/exam/session/${this.sessionId}/answer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ question_id: qId, is_flagged: this.userAnswers[qId].isFlagged }),
                    });
                },

                goToQuestion(index) {
                    if (index >= 0 && index < this.totalQuestions) {
                        this.currentIndex = index;
                        fetch(`/exam/session/${this.sessionId}/navigate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ index }),
                        });
                    }
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

                getOptionClass(option) {
                    const selectedId = this.currentAnswer.optionId;
                    
                    if (this.mode !== 'mock' && this.isCurrentAnswered) {
                        if (option.is_correct) {
                            return 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-950 dark:text-emerald-200 ring-2 ring-emerald-500/30';
                        }
                        if (selectedId === option.id && !option.is_correct) {
                            return 'border-rose-500 bg-rose-50 dark:bg-rose-950/40 text-rose-950 dark:text-rose-200 ring-2 ring-rose-500/30';
                        }
                        return 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 opacity-60';
                    }

                    if (selectedId === option.id) {
                        return 'border-blue-500 dark:border-blue-400 bg-blue-50/80 dark:bg-blue-950/40 ring-2 ring-blue-500/20';
                    }

                    return 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-blue-400 dark:hover:border-blue-500';
                },

                getOptionLetterClass(option) {
                    const selectedId = this.currentAnswer.optionId;

                    if (this.mode !== 'mock' && this.isCurrentAnswered) {
                        if (option.is_correct) {
                            return 'bg-emerald-600 text-white';
                        }
                        if (selectedId === option.id && !option.is_correct) {
                            return 'bg-rose-600 text-white';
                        }
                        return 'bg-slate-100 dark:bg-slate-800 text-slate-400';
                    }

                    if (selectedId === option.id) {
                        return 'bg-blue-600 text-white';
                    }

                    return 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600';
                },

                getNavClass(index, questionId) {
                    const ans = this.userAnswers[questionId];
                    if (index === this.currentIndex) return 'bg-blue-600 text-white ring-2 ring-blue-400 scale-105 shadow';
                    if (ans && ans.isFlagged) return 'bg-amber-500 text-white';
                    if (ans && ans.optionId !== null && ans.optionId !== undefined) return 'bg-emerald-500 text-white';
                    return 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400';
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
                                question_id: this.currentQuestion.id,
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
                    const adIndex = (this.adPopupsShownCount - 1) % this.adConfig.ads.length;
                    this.currentAd = this.adConfig.ads[adIndex];
                    this.showAdModal = true;
                },

                closeAd() {
                    this.showAdModal = false;
                },

                handleAdClick(adId) {
                    fetch(`/ads/${adId}/click`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    });
                },
            };
        }
    </script>
</body>
</html>
