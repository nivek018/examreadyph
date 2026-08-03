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
                    {{ $exam->category->name ?? 'Reviewer' }}
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
                        <span>Start Reviewing</span>
                    </a>
                    <a href="#subtopics" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold px-6 py-3 rounded-lg transition hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-2">
                        <span>Browse Subtopics</span>
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
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">How do you want to study?</h2>
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
                        <span>Start Mock Exam</span>
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
                        <span>Start Relaxed Mode</span>
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
                        <span>Customize Practice</span>
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
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">What's Covered in This Reviewer</h2>
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

    {{-- Article / About Section (SEO Study Guide) --}}
    <section class="py-16 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="badge-blue text-xs mb-3 inline-block">Official Exam Guide</span>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white sm:text-4xl">
                    {{ $exam->name }} — Complete Study Guide & Coverage
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400 mt-3 max-w-2xl mx-auto">
                    Everything you need to know about the Civil Service Examination Professional Level: coverage, passing rate, tips, and free practice reviewers.
                </p>
            </div>

            {{-- Quick Spec Cards Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
                <div class="card flat-card p-4 text-center bg-white dark:bg-slate-800">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Passing Rating</div>
                    <div class="text-lg font-extrabold text-slate-900 dark:text-white mt-1">80.00%</div>
                </div>

                <div class="card flat-card p-4 text-center bg-white dark:bg-slate-800">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Time Limit</div>
                    <div class="text-lg font-extrabold text-slate-900 dark:text-white mt-1">3h 10m (170 Qs)</div>
                </div>

                <div class="card flat-card p-4 text-center bg-white dark:bg-slate-800">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Calculators</div>
                    <div class="text-lg font-extrabold text-rose-600 dark:text-rose-400 mt-1">Prohibited</div>
                </div>

                <div class="card flat-card p-4 text-center bg-white dark:bg-slate-800">
                    <div class="text-xs text-slate-500 dark:text-slate-400">Explanations</div>
                    <div class="text-lg font-extrabold text-purple-600 dark:text-purple-400 mt-1">Taglish AI</div>
                </div>
            </div>

            {{-- Main Study Guide Article --}}
            <div class="card flat-card p-6 sm:p-10 mb-12 space-y-8 bg-white dark:bg-slate-800/80">
                {{-- Section 1 --}}
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-3">
                        What is the Civil Service Exam Professional Level?
                    </h3>
                    <p class="text-base text-slate-700 dark:text-slate-300 leading-relaxed mb-4">
                        The <strong>Civil Service Examination (CSE) Professional Level</strong> is an eligibility examination administered nationwide by the <strong>Civil Service Commission (CSC)</strong> in the Philippines. Passing this exam grants successful examinees the <strong>Career Service Professional Eligibility</strong>, which is a key requirement for permanent appointment to 2nd level positions in government agencies, national offices, and local government units (LGUs).
                    </p>
                    <p class="text-base text-slate-700 dark:text-slate-300 leading-relaxed">
                        Positions that require Career Service Professional Eligibility include Administrative Officers, Information Officers, Statisticians, Economists, Program Analysts, and technical supervisory roles across government departments.
                    </p>
                </div>

                {{-- Pro Tip Callout --}}
                <div class="p-5 rounded-2xl bg-amber-500/10 dark:bg-amber-500/15 border border-amber-500/30 text-slate-900 dark:text-slate-100">
                    <div class="font-extrabold text-base mb-1.5 text-amber-700 dark:text-amber-400">ExamReady PH Pro Tip</div>
                    <p class="text-base leading-relaxed">The Civil Service Exam does NOT allow calculators! All numerical computations must be solved by hand or mental math. Practicing speed computation techniques is crucial to finishing the exam on time.</p>
                </div>

                {{-- Section 2: Coverage Breakdown --}}
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">
                        Detailed Subject Breakdown & Weight Distribution
                    </h3>
                    <p class="text-base text-slate-700 dark:text-slate-300 leading-relaxed mb-6">
                        The Professional Level test consists of 170 multiple-choice items divided into 5 major sub-tests. Here is the full breakdown of subjects covered:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="card flat-card p-5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-500 transition">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white">Numerical Reasoning</h4>
                                <span class="badge-blue text-xs font-semibold">~35-40 items</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Word problems, percentages, ratio & proportion, number series, basic algebra, data interpretation, and speed math.</p>
                        </div>

                        <div class="card flat-card p-5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-purple-500 transition">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white">Analytical Thinking</h4>
                                <span class="badge-purple text-xs font-semibold">~30-35 items</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">Logic puzzles, syllogisms, pattern recognition, data sufficiency, and critical deductive reasoning.</p>
                        </div>

                        <div class="card flat-card p-5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-emerald-500 transition">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white">Verbal Ability</h4>
                                <span class="badge-emerald text-xs font-semibold">~40-45 items</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">English & Filipino vocabulary, grammar and correct usage, sentence completion, paragraph organization, and reading comprehension.</p>
                        </div>

                        <div class="card flat-card p-5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-amber-500 transition">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-lg text-slate-900 dark:text-white">General Information & PH Constitution</h4>
                                <span class="badge-amber text-xs font-semibold">~20-25 items</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">1987 Philippine Constitution (Bill of Rights), RA 6713 (Code of Conduct for Public Officials), peace & human rights concepts, and environmental protection laws.</p>
                        </div>
                    </div>
                </div>

                {{-- Section 3: 5 Strategies --}}
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-5">
                        5 Proven Strategies to Pass the Civil Service Exam on Your 1st Try
                    </h3>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">1</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">Master Elimination & Estimation in Math</h4>
                                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed">Since calculators are forbidden, estimate answer choices before writing out lengthy equations. Eliminate options that are clearly out of range.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">2</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">Prioritize High-Weight Subtopics First</h4>
                                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed">Verbal Ability and Numerical Reasoning make up over 50% of the total score. Focus your early review hours on these two categories using our targeted Practice Mode.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">3</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">Memorize Key Provisions of RA 6713 & Bill of Rights</h4>
                                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed">General Information items are easy points if you memorize the 8 Norms of Conduct under RA 6713 and Article III of the 1987 Philippine Constitution.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">4</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">Simulate Real Exam Conditions with Timed Mock Exams</h4>
                                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed">You have roughly 67 seconds per question in the actual test. Taking our online Mock Exam trains your pacing so you don't get stuck on difficult questions.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-extrabold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">5</div>
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white mb-1">Learn from Mistakes via AI Taglish Answer Explanations</h4>
                                <p class="text-base text-slate-600 dark:text-slate-300 leading-relaxed">Don't just check if your answer is right or wrong. Read the step-by-step Taglish explanation after every practice question to understand *why* the option is correct.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Interactive FAQ Section --}}
            <div class="mb-8" x-data="{ openFaq: null }">
                <div class="text-center mb-8">
                    <span class="badge-purple text-xs mb-2 inline-block">Common Questions</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Frequently Asked Questions (FAQ)</h2>
                    <p class="text-base text-slate-600 dark:text-slate-400 mt-2">Everything candidates ask about the Civil Service Exam Professional Level</p>
                </div>

                <div class="space-y-4">
                    {{-- FAQ 1 --}}
                    <div class="card flat-card overflow-hidden transition bg-white dark:bg-slate-800">
                        <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base sm:text-lg text-slate-900 dark:text-white">
                            <span>What is the passing score for the Civil Service Exam Professional Level?</span>
                            <i class="fa-solid text-sm text-slate-400 transition-transform duration-200" :class="openFaq === 1 ? 'fa-minus rotate-180 text-blue-600' : 'fa-plus'"></i>
                        </button>
                        <div x-show="openFaq === 1" x-collapse class="px-6 pb-6 text-base text-slate-700 dark:text-slate-300 border-t border-slate-100 dark:border-slate-700/50 pt-4 leading-relaxed">
                            The official passing score for the Civil Service Examination (both Professional and Subprofessional levels) is <strong>80.00% General Rating</strong>. There is no individual passing score per subject, but you must reach an overall rating of 80% or higher to be declared eligible.
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="card flat-card overflow-hidden transition bg-white dark:bg-slate-800">
                        <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base sm:text-lg text-slate-900 dark:text-white">
                            <span>How many items are in the actual CSE Professional Exam and what is the time limit?</span>
                            <i class="fa-solid text-sm text-slate-400 transition-transform duration-200" :class="openFaq === 2 ? 'fa-minus rotate-180 text-blue-600' : 'fa-plus'"></i>
                        </button>
                        <div x-show="openFaq === 2" x-collapse class="px-6 pb-6 text-base text-slate-700 dark:text-slate-300 border-t border-slate-100 dark:border-slate-700/50 pt-4 leading-relaxed">
                            The Paper and Pencil Test (CSE-PPT) Professional Level consists of <strong>170 multiple-choice test items</strong> (150 exam core items + 20 examinee descriptive items) with a total time allotment of <strong>3 hours and 10 minutes (190 minutes)</strong>.
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="card flat-card overflow-hidden transition bg-white dark:bg-slate-800">
                        <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base sm:text-lg text-slate-900 dark:text-white">
                            <span>Are calculators allowed during the Civil Service Examination?</span>
                            <i class="fa-solid text-sm text-slate-400 transition-transform duration-200" :class="openFaq === 3 ? 'fa-minus rotate-180 text-blue-600' : 'fa-plus'"></i>
                        </button>
                        <div x-show="openFaq === 3" x-collapse class="px-6 pb-6 text-base text-slate-700 dark:text-slate-300 border-t border-slate-100 dark:border-slate-700/50 pt-4 leading-relaxed">
                            <strong>No, calculators are strictly prohibited</strong> during the Civil Service Exam according to CSC guidelines. Scratch papers are provided at the testing center for mental math computations.
                        </div>
                    </div>

                    {{-- FAQ 4 --}}
                    <div class="card flat-card overflow-hidden transition bg-white dark:bg-slate-800">
                        <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base sm:text-lg text-slate-900 dark:text-white">
                            <span>What is the difference between Professional and Subprofessional Level eligibility?</span>
                            <i class="fa-solid text-sm text-slate-400 transition-transform duration-200" :class="openFaq === 4 ? 'fa-minus rotate-180 text-blue-600' : 'fa-plus'"></i>
                        </button>
                        <div x-show="openFaq === 4" x-collapse class="px-6 pb-6 text-base text-slate-700 dark:text-slate-300 border-t border-slate-100 dark:border-slate-700/50 pt-4 leading-relaxed">
                            <strong>Professional Eligibility</strong> qualifies you for both 1st level (clerical/custodial) and 2nd level (technical/supervisory/executive) government positions. <strong>Subprofessional Eligibility</strong> qualifies you ONLY for 1st level clerical positions. Bachelor's degree graduates are encouraged to take the Professional level directly.
                        </div>
                    </div>

                    {{-- FAQ 5 --}}
                    <div class="card flat-card overflow-hidden transition bg-white dark:bg-slate-800">
                        <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full p-6 text-left flex items-center justify-between gap-4 font-bold text-base sm:text-lg text-slate-900 dark:text-white">
                            <span>Is this online CSE reviewer 100% free?</span>
                            <i class="fa-solid text-sm text-slate-400 transition-transform duration-200" :class="openFaq === 5 ? 'fa-minus rotate-180 text-blue-600' : 'fa-plus'"></i>
                        </button>
                        <div x-show="openFaq === 5" x-collapse class="px-6 pb-6 text-base text-slate-700 dark:text-slate-300 border-t border-slate-100 dark:border-slate-700/50 pt-4 leading-relaxed">
                            <strong>Yes, 100% free!</strong> On ExamReady PH, you can take unlimited timed Mock Exams and Relaxed Learning drills without paying a single cent. Creating a free account unlocks Practice Mode to filter specific subtopics and track weak areas over time.
                        </div>
                    </div>

                    {{-- FAQ 6 --}}
                    <div class="card flat-card overflow-hidden transition bg-white dark:bg-slate-800">
                        <button @click="openFaq = openFaq === 6 ? null : 6" class="w-full p-6 text-left flex items-center justify-between gap-4 font-semibold text-base sm:text-lg text-slate-900 dark:text-white">
                            <span>How do the AI Taglish explanations work?</span>
                            <i class="fa-solid text-sm text-slate-400 transition-transform duration-200" :class="openFaq === 6 ? 'fa-minus rotate-180 text-blue-600' : 'fa-plus'"></i>
                        </button>
                        <div x-show="openFaq === 6" x-collapse class="px-6 pb-6 text-base text-slate-700 dark:text-slate-300 border-t border-slate-100 dark:border-slate-700/50 pt-4 leading-relaxed">
                            Every question in our database comes with a step-by-step answer explanation written in clear, conversational <strong>Taglish (Tagalog-English)</strong>. Instead of confusing textbook jargon, our explanations walk you through formulas, logic shortcuts, and grammar rules in a way that is easy to remember on exam day.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Schema.org JSON-LD Structured Data for Google rich snippets --}}
            @verbatim
            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "FAQPage",
              "mainEntity": [
                {
                  "@type": "Question",
                  "name": "What is the passing score for the Civil Service Exam Professional Level?",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "The official passing score for the Civil Service Examination (CSE) Professional Level is an 80.00% General Rating."
                  }
                },
                {
                  "@type": "Question",
                  "name": "How many items are in the actual CSE Professional Exam and what is the time limit?",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "The CSE Professional Level paper-and-pencil test consists of 170 multiple-choice items with a total time allotment of 3 hours and 10 minutes."
                  }
                },
                {
                  "@type": "Question",
                  "name": "Are calculators allowed during the Civil Service Examination?",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "No, calculators are strictly prohibited during the Civil Service Examination per Civil Service Commission rules."
                  }
                },
                {
                  "@type": "Question",
                  "name": "What is the difference between Professional and Subprofessional Level eligibility?",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Professional Eligibility qualifies you for 1st level (clerical) and 2nd level (technical/supervisory) government positions, whereas Subprofessional Level qualifies only for 1st level positions."
                  }
                },
                {
                  "@type": "Question",
                  "name": "Is this online CSE reviewer 100% free?",
                  "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes, ExamReady PH provides free online mock exams, practice drills, and AI Taglish explanations for Civil Service Exam takers."
                  }
                }
              ]
            }
            </script>
            @endverbatim
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-14">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mb-4">Ready to Start Reviewing?</h2>
            <p class="text-base text-slate-600 dark:text-slate-400 mb-6">Choose a mode below and begin practicing right now. No registration required for mock exams!</p>
            <div class="flex flex-wrap justify-center gap-3">
                <form method="POST" action="{{ route('exam.start-session', $exam) }}" class="inline">
                    @csrf
                    <input type="hidden" name="mode" value="mock">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
                        <span>Start Mock Exam</span>
                    </button>
                </form>
                <a href="{{ route('exam.practice-setup', $exam) }}" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition shadow-lg hover:shadow-xl flex items-center gap-2">
                    <span>Practice Mode</span>
                </a>
                <a href="{{ route('reviewers') }}" class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold px-6 py-3 rounded-lg transition hover:text-blue-600 flex items-center gap-2">
                    <span>Browse All Reviewers</span>
                </a>
            </div>
        </div>
    </section>

</x-public-layout>
