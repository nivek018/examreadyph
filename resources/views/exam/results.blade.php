<x-public-layout
    :metaTitle="$exam->name . ' Results — ExamReady PH'"
    :metaDescription="'Exam results for ' . $exam->name"
>
    <section class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Result Banner --}}
            <div class="card p-8 mb-8 text-center {{ $passed ? 'bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/30 border-emerald-200 dark:border-emerald-800' : 'bg-gradient-to-br from-rose-50 to-red-50 dark:from-rose-950/30 dark:to-red-950/30 border-rose-200 dark:border-rose-800' }}">
                <div class="text-6xl mb-4">{{ $passed ? '🎉' : '📚' }}</div>
                <h1 class="text-3xl font-extrabold {{ $passed ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }} mb-2">
                    {{ $passed ? 'Congratulations! You Passed!' : 'Keep Practicing!' }}
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-400">{{ $exam->name }}</p>

                {{-- Score Circle --}}
                <div class="mt-6 inline-flex items-center justify-center w-32 h-32 rounded-full border-4 {{ $passed ? 'border-emerald-500' : 'border-rose-500' }}">
                    <div>
                        <div class="text-3xl font-extrabold {{ $passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ number_format($session->score, 1) }}%</div>
                        <div class="text-xs text-slate-500">Score</div>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $session->correct_count }}</div>
                    <div class="text-xs text-slate-500 font-medium">Correct</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-rose-600">{{ $session->wrong_count }}</div>
                    <div class="text-xs text-slate-500 font-medium">Wrong</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-slate-500">{{ $session->unanswered_count }}</div>
                    <div class="text-xs text-slate-500 font-medium">Unanswered</div>
                </div>
                <div class="card p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $session->total_questions }}</div>
                    <div class="text-xs text-slate-500 font-medium">Total</div>
                </div>
            </div>

            {{-- Passing Info --}}
            <div class="card p-4 mb-8 flex items-center gap-4 text-sm">
                <i class="fa-solid fa-bullseye {{ $passed ? 'text-emerald-500' : 'text-rose-500' }} text-lg"></i>
                <div>
                    <span class="font-medium text-slate-700 dark:text-slate-300">Passing Score: {{ $exam->passing_score_percent }}%</span>
                    <span class="text-slate-500 mx-2">•</span>
                    <span class="font-medium {{ $passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        Your Score: {{ number_format($session->score, 1) }}%
                        {{ $passed ? '✓ PASSED' : '✗ NEEDS IMPROVEMENT' }}
                    </span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap gap-3 mb-10">
                <a href="{{ route('dashboard') }}" class="btn-brand px-5 py-2.5 text-sm">
                    <i class="fa-solid fa-home"></i> Back to Dashboard
                </a>
                <form method="POST" action="{{ route('exam.start', $exam) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-brand-outline px-5 py-2.5 text-sm">
                        <i class="fa-solid fa-redo"></i> Retake Exam
                    </button>
                </form>
            </div>

            {{-- Question Review --}}
            @if($exam->allow_review)
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-blue-500"></i> Question Review
            </h2>

            <div class="space-y-4">
                @foreach($session->answers as $idx => $answer)
                @php
                    $q = $answer->question;
                    $isCorrect = $answer->is_correct;
                    $isAnswered = $answer->selected_option_id !== null;
                @endphp
                <div class="card p-5 {{ $isCorrect ? 'border-l-4 border-l-emerald-500' : ($isAnswered ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-slate-400') }}">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-bold {{ $isCorrect ? 'text-emerald-600' : ($isAnswered ? 'text-rose-600' : 'text-slate-500') }}">
                            Q{{ $idx + 1 }} — {{ $isCorrect ? '✓ Correct' : ($isAnswered ? '✗ Wrong' : '⏭ Unanswered') }}
                        </span>
                        @if($answer->is_flagged)
                        <span class="text-xs text-amber-500 font-medium"><i class="fa-solid fa-flag"></i> Flagged</span>
                        @endif
                    </div>

                    <p class="text-sm font-medium text-slate-900 dark:text-white mb-4">{{ $q->question_text }}</p>

                    <div class="space-y-2 mb-4">
                        @foreach($q->options as $opt)
                        @php
                            $isSelected = $answer->selected_option_id === $opt->id;
                            $isCorrectOpt = $opt->is_correct;
                        @endphp
                        <div class="flex items-center gap-3 p-2.5 rounded-lg text-sm
                            {{ $isCorrectOpt ? 'bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-300 dark:border-emerald-700' : ($isSelected && !$isCorrectOpt ? 'bg-rose-50 dark:bg-rose-950/30 border border-rose-300 dark:border-rose-700' : 'bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700') }}">
                            <span class="w-7 h-7 rounded flex items-center justify-center text-xs font-bold shrink-0
                                {{ $isCorrectOpt ? 'bg-emerald-500 text-white' : ($isSelected && !$isCorrectOpt ? 'bg-rose-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400') }}">
                                @if($isCorrectOpt)
                                    <i class="fa-solid fa-check"></i>
                                @elseif($isSelected && !$isCorrectOpt)
                                    <i class="fa-solid fa-xmark"></i>
                                @else
                                    {{ $opt->letter }}
                                @endif
                            </span>
                            <span class="{{ $isCorrectOpt ? 'font-semibold text-emerald-800 dark:text-emerald-300' : ($isSelected && !$isCorrectOpt ? 'text-rose-800 dark:text-rose-300 line-through' : 'text-slate-700 dark:text-slate-300') }}">{{ $opt->text }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- Explanation --}}
                    @if($q->explanation_taglish && $exam->show_explanations)
                    <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 text-sm">
                        <div class="text-xs font-bold text-blue-600 dark:text-blue-400 mb-1 flex items-center gap-1">
                            <i class="fa-solid fa-robot"></i> AI TAGLISH EXPLANATION
                        </div>
                        <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $q->explanation_taglish }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </section>
</x-public-layout>
