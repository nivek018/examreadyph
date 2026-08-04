<x-public-layout
    metaTitle="ExamReady PH Community — Pair Up with Filipino Exam Reviewers"
    metaDescription="Join the ExamReady PH community forum. Discuss exam strategies, share tips, and connect with fellow Filipino examinees preparing for Civil Service, LET, UPCAT, and more."
>
    {{-- Hero --}}
    <section class="py-14 bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-900 dark:to-slate-950">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
            <span class="badge-blue uppercase text-xs font-bold px-3 py-1 mb-4 inline-block">Community Forum</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
                ExamReady <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">Community</span>
            </h1>
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                Ask questions, share study strategies, and connect with fellow examinees preparing for Philippine board exams and college entrance tests.
            </p>
        </div>
    </section>

    {{-- Categories Grid --}}
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($categories as $cat)
                <a href="{{ route('forum.category', $cat) }}" class="group card flat-card p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <i class="{{ $cat->icon }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-slate-900 dark:text-white text-base mb-1 group-hover:text-blue-600 transition">{{ $cat->name }}</h3>
                            @if($cat->description)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $cat->description }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-xs text-slate-400">
                                <span class="flex items-center gap-1"><i class="fa-solid fa-comments text-blue-500"></i> {{ number_format($cat->threads_count) }} threads</span>
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 dark:text-slate-600 group-hover:text-blue-500 transition mt-1"></i>
                    </div>
                </a>
                @endforeach
            </div>

            @if($categories->isEmpty())
            <div class="text-center py-20">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-comments text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Forum coming soon</h3>
                <p class="text-sm text-slate-500">The community forum is being set up. Check back soon!</p>
            </div>
            @endif

            {{-- Community Guidelines --}}
            <div class="mt-10 p-6 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i> Community Guidelines
                </h3>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600 dark:text-slate-400">
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-emerald-500 mt-0.5"></i> Be respectful and helpful to fellow examinees</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-emerald-500 mt-0.5"></i> Share study tips, materials, and experiences</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-xmark text-rose-500 mt-0.5"></i> No spam, promotions, or off-topic content</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-xmark text-rose-500 mt-0.5"></i> No sharing of actual exam questions or leaks</li>
                </ul>
            </div>
        </div>
    </section>
</x-public-layout>
