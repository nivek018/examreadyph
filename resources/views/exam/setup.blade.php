<x-public-layout
    :metaTitle="$exam->name . ' — Choose Review Mode | ExamReady PH'"
    :metaDescription="'Select your review mode for ' . $exam->name . ': Mock Exam, Relaxed Learning, or Practice Mode.'"
>
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Back Link --}}
            <a href="{{ route('reviewer.show', $exam) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition mb-8">
                <i class="fa-solid fa-arrow-left"></i> Back to {{ $exam->name }}
            </a>

            <div class="text-center mb-12">
                <span class="{{ $exam->category->color_class ?? 'badge-blue' }} text-xs mb-3 inline-block">{{ $exam->category->name ?? 'Reviewer' }}</span>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-3">{{ $exam->name }}</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">Choose how you want to study. All modes are completely free!</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Mock Exam --}}
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="card flat-card p-7 flex flex-col items-center text-center hover:border-blue-500 dark:hover:border-blue-500 transition group relative">
                    @csrf
                    <input type="hidden" name="mode" value="mock">
                    <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Subject Mock Exam</h2>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold mb-3">
                        {{ $exam->questions_count ?? $exam->total_questions }} questions · {{ $exam->formatted_time_limit }}
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Single-subject drill with timer. Simulates real exam conditions with official scoring and pass/fail result.</p>
                    <button type="submit" class="mt-auto bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition w-full flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-play"></i> Start Mock Exam
                    </button>
                    <span class="badge-emerald text-[9px] absolute top-4 right-4">FREE</span>
                </form>

                {{-- Relaxed Mode --}}
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="card flat-card p-7 flex flex-col items-center text-center hover:border-purple-500 dark:hover:border-purple-500 transition group relative">
                    @csrf
                    <input type="hidden" name="mode" value="relaxed">
                    <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Relaxed Learning</h2>
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold mb-3">
                        {{ $exam->questions_count ?? $exam->total_questions }} questions · No timer · AI Tutor
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Learn with AI Taglish explanations after each answer. No pressure, not counted in rankings.</p>
                    <button type="submit" class="mt-auto bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition w-full flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-mug-hot"></i> Start Relaxed Mode
                    </button>
                    <span class="badge-emerald text-[9px] absolute top-4 right-4">FREE</span>
                </form>

                {{-- Practice Mode --}}
                <a href="{{ route('exam.practice-setup', $exam) }}" class="card flat-card p-7 flex flex-col items-center text-center hover:border-amber-500 dark:hover:border-amber-500 transition group relative">
                    <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-3xl mb-5 group-hover:scale-110 transition">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Practice Mode</h2>
                    <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mb-3">
                        Pick topics · No time limit
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">Choose specific subtopics and number of questions. Check each answer as you go — perfect for weak areas.</p>
                    <span class="mt-auto bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition w-full flex items-center justify-center gap-2 shadow-sm">
                        <i class="fa-solid fa-bullseye"></i> Customize Practice
                    </span>
                    <span class="badge-emerald text-[9px] absolute top-4 right-4">FREE</span>
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
