<x-public-layout
    :metaTitle="$seoTitle"
    :metaDescription="$seoDescription"
>
    {{-- Hero Section --}}
    <section class="relative overflow-hidden py-16 bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 dark:from-slate-950 dark:via-blue-950/20 dark:to-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                {{-- Category Badge --}}
                <span class="{{ $exam->category->color_class ?? 'badge-blue' }} text-xs mb-4 inline-block">
                    <i class="{{ $exam->category->icon ?? 'fa-solid fa-book' }} mr-1"></i> {{ $exam->category->name ?? 'Reviewer' }}
                </span>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 leading-tight">
                    {{ $exam->name }} <span class="text-blue-600 dark:text-blue-400">Reviewer</span>
                </h1>

                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 mb-6 max-w-2xl mx-auto">
                    {{ $exam->description }}
                </p>

                {{-- Quick Stats Row --}}
                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6 mb-8 text-sm">
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <i class="fa-solid fa-list-check text-blue-500"></i>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ number_format($totalQuestions) }}</span> Questions
                    </div>
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <i class="fa-solid fa-layer-group text-purple-500"></i>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $subtopics->count() }}</span> Subtopics
                    </div>
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <i class="fa-solid fa-clock text-amber-500"></i>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $exam->formatted_time_limit }}</span> Timer
                    </div>
                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <i class="fa-solid fa-trophy text-emerald-500"></i>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $exam->passing_score_percent }}%</span> Passing
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="#modes" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl hover:scale-105 flex items-center gap-2">
                        <i class="fa-solid fa-play"></i> Start Reviewing
                    </a>
                    <a href="#subtopics" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold px-6 py-3 rounded-lg transition hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group"></i> Browse Subtopics
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 3 Exam Mode Cards --}}
    <section id="modes" class="py-14 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="section-eyebrow">Choose Your Review Mode</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">How do you want to study?</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Mock Exam --}}
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="card flat-card p-6 flex flex-col items-center text-center hover:border-blue-500 dark:hover:border-blue-500 transition group cursor-pointer relative">
                    @csrf
                    <input type="hidden" name="mode" value="mock">
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Subject Mock Exam</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                        {{ $exam->total_questions }} questions · {{ $exam->formatted_time_limit }}
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">Full timed simulation. Single-subject drill with timer and official scoring.</p>
                    <button type="submit" class="mt-auto bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition w-full flex items-center justify-center gap-2">
                        <i class="fa-solid fa-play"></i> Start Mock Exam
                    </button>
                    <span class="badge-emerald text-[9px] absolute top-3 right-3">FREE</span>
                </form>

                {{-- Relaxed Mode --}}
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="card flat-card p-6 flex flex-col items-center text-center hover:border-purple-500 dark:hover:border-purple-500 transition group cursor-pointer relative">
                    @csrf
                    <input type="hidden" name="mode" value="relaxed">
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Relaxed Learning</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                        {{ $exam->total_questions }} questions · No timer · AI Tutor
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">Learn at your own pace. Instant AI Taglish explanations after each answer.</p>
                    <button type="submit" class="mt-auto bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition w-full flex items-center justify-center gap-2">
                        <i class="fa-solid fa-mug-hot"></i> Start Relaxed Mode
                    </button>
                    <span class="badge-emerald text-[9px] absolute top-3 right-3">FREE</span>
                </form>

                {{-- Practice Mode --}}
                <a href="{{ route('exam.practice-setup', $exam) }}" class="card flat-card p-6 flex flex-col items-center text-center hover:border-amber-500 dark:hover:border-amber-500 transition group cursor-pointer relative">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Practice Mode</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                        Pick topics · No time limit
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">Select specific subtopics and question count. Check each answer as you go.</p>
                    <span class="mt-auto bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-5 py-2.5 rounded-lg transition w-full flex items-center justify-center gap-2">
                        <i class="fa-solid fa-bullseye"></i> Customize Practice
                    </span>
                    <span class="badge-emerald text-[9px] absolute top-3 right-3">FREE</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Subtopics Grid --}}
    <section id="subtopics" class="py-14 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="section-eyebrow">Coverage & Topics</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">What's Covered in This Reviewer</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Browse all {{ $subtopics->count() }} subtopics included in the {{ $exam->name }}.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($subtopics as $subtopic)
                <a href="{{ route('reviewer.subtopic', [$exam, $subtopic->slug]) }}" class="card flat-card p-5 flex items-start gap-4 hover:border-blue-500 dark:hover:border-blue-500 transition group">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg flex-shrink-0 group-hover:scale-110 transition">
                        <i class="{{ $subtopic->icon }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $subtopic->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $subtopic->questions_count }} Questions</p>
                    </div>
                    <i class="fa-solid fa-chevron-right text-xs text-slate-400 mt-3 group-hover:translate-x-1 transition"></i>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Article / About Section (SEO Content) --}}
    <section class="py-14 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <p class="section-eyebrow">About This Reviewer</p>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $exam->name }} — Study Guide & Overview</h2>
            </div>

            <article class="prose prose-slate dark:prose-invert max-w-none text-sm sm:text-base leading-relaxed">
                @if($exam->long_description)
                    {!! nl2br(e($exam->long_description)) !!}
                @else
                    <p>
                        The <strong>{{ $exam->name }}</strong> is one of the most important exams for Filipino professionals and students.
                        This free online reviewer from <strong>ExamReady PH</strong> covers <strong>{{ number_format($totalQuestions) }}+ practice questions</strong>
                        across <strong>{{ $subtopics->count() }} key subtopics</strong> including:
                    </p>
                    <ul>
                        @foreach($subtopics->take(6) as $st)
                        <li><strong>{{ $st->name }}</strong> ({{ $st->questions_count }} questions)</li>
                        @endforeach
                        @if($subtopics->count() > 6)
                        <li>...and {{ $subtopics->count() - 6 }} more topics</li>
                        @endif
                    </ul>
                    <p>
                        Our reviewer features <strong>AI-powered Taglish answer explanations</strong>, timed mock exams, and targeted practice drills.
                        You can take free mock exams without an account, or register for free to unlock Practice Mode and track your weak areas over time.
                    </p>
                    <h3>How to Use This Reviewer</h3>
                    <ol>
                        <li><strong>Mock Exam:</strong> Take a full timed simulation with {{ $exam->total_questions }} questions in {{ $exam->formatted_time_limit }}. Best for realistic exam day preparation.</li>
                        <li><strong>Relaxed Mode:</strong> Same questions, no timer. Read the AI Taglish explanations after each answer to really learn the material.</li>
                        <li><strong>Practice Mode:</strong> Pick specific subtopics and choose how many questions (5, 10, 20, or 50). Perfect for targeting your weak areas.</li>
                    </ol>
                    <p>
                        All questions are regularly updated and aligned with the latest {{ date('Y') }}-{{ date('Y') + 1 }} exam syllabus.
                        Good luck on your review! 🎯
                    </p>
                @endif
            </article>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-14">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Ready to Start Reviewing?</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Choose a mode below and begin practicing right now. No registration required for mock exams!</p>
            <div class="flex flex-wrap justify-center gap-3">
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="inline">
                    @csrf
                    <input type="hidden" name="mode" value="mock">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
                        <i class="fa-solid fa-stopwatch"></i> Start Mock Exam
                    </button>
                </form>
                <a href="{{ route('exam.practice-setup', $exam) }}" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i class="fa-solid fa-bullseye"></i> Practice Mode
                </a>
                <a href="{{ route('reviewers') }}" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold px-6 py-3 rounded-lg transition hover:text-blue-600 flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Browse All Reviewers
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
