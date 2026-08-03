<x-public-layout
    :metaTitle="$seoTitle"
    :metaDescription="$seoDescription"
>
    <section class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-8">
                <a href="{{ route('reviewers') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Reviewers</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('reviewer.show', $exam) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $exam->name }}</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-slate-900 dark:text-white font-semibold">{{ $subtopic->name }}</span>
            </nav>

            {{-- Header --}}
            <div class="text-center mb-10">
                <span class="{{ $exam->category->color_class ?? 'badge-blue' }} text-xs mb-3 inline-block">{{ $exam->name }}</span>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-3">
                    {{ $subtopic->name }} <span class="text-blue-600 dark:text-blue-400">Reviewer</span>
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    <i class="fa-solid fa-list-check mr-1"></i> {{ $subtopic->questions_count }} practice questions available
                </p>
            </div>

            {{-- Description --}}
            @if($subtopic->description)
            <div class="card flat-card p-6 mb-8">
                <article class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed">
                    {!! nl2br(e($subtopic->description)) !!}
                </article>
            </div>
            @endif

            {{-- CTA Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="card flat-card p-5 text-center hover:border-blue-500 transition">
                    @csrf
                    <input type="hidden" name="mode" value="mock">
                    <i class="fa-solid fa-stopwatch text-blue-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Mock Exam</h3>
                    <p class="text-xs text-slate-500 mb-3">Full timed exam covering all topics</p>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition w-full">
                        Start Mock Exam
                    </button>
                </form>

                <a href="{{ route('exam.practice-setup', $exam) }}" class="card flat-card p-5 text-center hover:border-amber-500 transition">
                    <i class="fa-solid fa-bullseye text-amber-500 text-2xl mb-3"></i>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">Practice This Topic</h3>
                    <p class="text-xs text-slate-500 mb-3">Focus on {{ $subtopic->name }} only</p>
                    <span class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition w-full inline-block">
                        Start Practice
                    </span>
                </a>
            </div>

            {{-- Back to exam --}}
            <div class="text-center">
                <a href="{{ route('reviewer.show', $exam) }}" class="text-sm text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition">
                    <i class="fa-solid fa-arrow-left mr-1"></i> View all {{ $exam->name }} subtopics
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
