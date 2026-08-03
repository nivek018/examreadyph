<x-public-layout
    :metaTitle="'ExamReady PH — Free Philippine Exam Reviewer with AI Taglish Explanations'"
    :metaDescription="'Master UPCAT, Civil Service, LET, and NMAT with 15,000+ updated practice questions, step-by-step Taglish explanations, and real-time readiness tracking.'"
>

    {{-- ===== HERO SECTION WITH QUIZ DEMO ===== --}}
    <section class="py-12 md:py-20 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                {{-- Left Hero Content --}}
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-blue-700 dark:text-blue-400 border border-slate-300 dark:border-slate-700">
                        <i class="fa-solid fa-circle-check text-emerald-500 dark:text-emerald-400"></i>
                        <span>Updated for 2026-2027 PRC & CSC Syllabi</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl xl:text-5xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                        Free Philippine Exam Reviewer with <span class="text-blue-600 dark:text-blue-500">AI Taglish Explanations</span>
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
                        Master UPCAT, Civil Service, LET, and NMAT with 15,000+ updated practice questions, step-by-step Taglish explanations, and real-time readiness tracking.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-brand px-6 py-3.5 text-base">
                                <i class="fa-solid fa-bolt"></i>
                                <span>Go to Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-brand px-6 py-3.5 text-base">
                                <i class="fa-solid fa-bolt"></i>
                                <span>Start Free Practice Test</span>
                            </a>
                        @endauth

                        <a href="#categories" class="btn-brand-outline px-6 py-3.5 text-base">
                            <i class="fa-solid fa-layer-group"></i>
                            <span>Browse All Exams</span>
                        </a>
                    </div>

                    {{-- Key Stats Bar --}}
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <div>
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">120K+</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">Filipino Examinees</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">94.2%</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">Passing Rate</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">100%</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">Free Core Access</div>
                        </div>
                    </div>
                </div>

                {{-- Right Hero Interactive Quiz Demo --}}
                <div id="hero-quiz-demo" class="lg:col-span-6" x-data="quizDemo()">
                    <div class="card p-6 shadow-md">
                        {{-- Widget Header --}}
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-4 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">Interactive Quiz Demo</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                                <i class="fa-solid fa-clock text-amber-500 dark:text-amber-400"></i>
                                <span>Demo Mode</span>
                            </div>
                        </div>

                        {{-- Question Metadata --}}
                        <div class="flex items-center justify-between mb-3 text-xs">
                            <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-900 text-blue-700 dark:text-blue-400 font-semibold border border-slate-300 dark:border-slate-700" x-text="currentQuestion.category"></span>
                            <span class="text-slate-500 dark:text-slate-400" x-text="'Question ' + (currentIndex + 1) + ' of ' + questions.length"></span>
                        </div>

                        {{-- Question Text --}}
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mb-5 leading-snug" x-text="currentQuestion.question"></h3>

                        {{-- Options --}}
                        <div class="space-y-3 mb-5">
                            <template x-for="(opt, idx) in currentQuestion.options" :key="idx">
                                <button @click="selectOption(idx)"
                                    :class="getOptionClass(idx)"
                                    class="w-full text-left p-3.5 rounded-lg flex items-center justify-between text-xs sm:text-sm font-medium transition">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 rounded font-bold flex items-center justify-center text-xs"
                                              :class="selected === null ? 'bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300' : (opt.correct ? 'bg-emerald-500 text-white' : (idx === selected && !opt.correct ? 'bg-rose-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-400'))"
                                              x-text="opt.letter"></span>
                                        <span x-text="opt.text"></span>
                                    </div>
                                    <i :class="getIconClass(idx)"></i>
                                </button>
                            </template>
                        </div>

                        {{-- AI Explanation Box --}}
                        <div x-show="selected !== null" x-transition class="rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-4 mb-5">
                            <div class="flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wide mb-2">
                                <i class="fa-solid fa-robot"></i>
                                <span>AI Taglish Answer Explanation</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed" x-text="currentQuestion.explanation"></p>
                        </div>

                        {{-- Widget Footer --}}
                        <div class="flex items-center justify-between pt-2">
                            <button @click="reset()" class="text-xs font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition flex items-center gap-1">
                                <i class="fa-solid fa-rotate-left"></i> Reset
                            </button>
                            <button @click="next()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded transition flex items-center gap-2">
                                <span>Next Sample Question</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===== AI FEATURES SECTION ===== --}}
    <section class="py-16 bg-slate-100 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="section-eyebrow">Next-Gen EdTech Technology</h2>
                <p class="section-title">AI-Powered Reviewer Engine Built for Filipino Students</p>
                <p class="section-subtitle">Study smarter, eliminate guesswork, and pass your licensure or college admission exam on your first attempt.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="flat-card card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700/50 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl mb-5">
                            <i class="fa-solid fa-language"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Instant Taglish Explanations</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                            Clear, conversational explanations combining English and Tagalog concepts so complex formulas, laws, and theories are effortless to comprehend.
                        </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded-lg border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 font-mono">
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold"><i class="fa-solid fa-check"></i> Sample:</span> "Ang key concept dito ay Section 10 under Article II..."
                    </div>
                </div>

                {{-- Feature 2 --}}
                <div class="flat-card card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-700/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-xl mb-5">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">AI Readiness Index</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                            Predictive scoring algorithms that measure your probability of passing the actual PRC or CSC exam based on time-weighted response accuracy.
                        </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded-lg border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Estimated Pass Rate</span>
                        <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950 px-2 py-0.5 rounded border border-emerald-300 dark:border-emerald-800">88.5% - Ready</span>
                    </div>
                </div>

                {{-- Feature 3 --}}
                <div class="flat-card card p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-lg bg-amber-50 dark:bg-amber-900/50 border border-amber-200 dark:border-amber-700/50 flex items-center justify-center text-amber-600 dark:text-amber-400 text-xl mb-5">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Smart Weak-Area Targeting</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                            Our system detects sub-topics where you score below 75% and automatically generates custom 10-minute drill sessions to bridge knowledge gaps.
                        </p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900 p-3 rounded-lg border border-slate-200 dark:border-slate-700 space-y-1 text-xs">
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Numerical Reasoning</span>
                            <span class="text-rose-600 dark:text-rose-400 font-bold">62% (Drill Needed)</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-rose-500 h-1.5 rounded-full" style="width: 62%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PAIN-POINT COMPARISON ===== --}}
    <section id="comparison" class="py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="section-eyebrow">Why Switch to Online Review?</h2>
                <p class="section-title">Traditional Review Centers vs. ExamReady PH</p>
                <p class="section-subtitle">Get higher passing rates without expensive tuition fees or long jeepney commutes.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['title' => '1. Tuition & Expenses', 'bad' => '₱8,000 – ₱25,000 tuition fees plus daily commute, food, and printed reviewer costs.', 'good' => '100% Free Core Access on your phone 24/7. Practice anywhere with zero transport cost.'],
                    ['title' => '2. Answer Explanations', 'bad' => 'Generic answer keys (e.g. "Answer: B") without step-by-step reasoning or solutions.', 'good' => 'Instant AI Taglish explanations for every single question, explaining WHY it is correct.'],
                    ['title' => '3. Learning Pace', 'bad' => 'One-size-fits-all lecture pace for 60+ students in crowded weekend lecture halls.', 'good' => 'Adaptive personalized drills that focus 80% of practice time on your individual weak topics.'],
                ] as $item)
                <div class="card overflow-hidden">
                    <div class="bg-slate-100 dark:bg-slate-900 p-4 border-b border-slate-200 dark:border-slate-700 text-center font-bold text-slate-800 dark:text-slate-200">
                        {{ $item['title'] }}
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="p-3 rounded bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/40">
                            <div class="text-xs font-bold text-rose-700 dark:text-rose-400 uppercase tracking-wide flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-xmark"></i> Traditional Centers
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">{{ $item['bad'] }}</p>
                        </div>
                        <div class="p-3 rounded bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/40">
                            <div class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide flex items-center gap-1.5 mb-1">
                                <i class="fa-solid fa-check"></i> ExamReady PH
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300">{{ $item['good'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== EXAM CATEGORIES GRID ===== --}}
    <section id="categories" class="py-16 bg-slate-100 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <h2 class="section-eyebrow">Comprehensive Coverage</h2>
                    <p class="section-title">Select Your Exam Category</p>
                </div>
                <div class="flex flex-wrap gap-2" x-data="{ activeTab: 'all' }">
                    @foreach(['all' => 'All Exams', 'college' => 'College Entrance', 'civil' => 'Civil Service', 'teachers' => 'Teachers (LET)'] as $key => $label)
                    <button @click="activeTab = '{{ $key }}'; filterExams('{{ $key }}')"
                            :class="activeTab === '{{ $key }}' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                            class="tab-btn px-4 py-2 rounded-lg text-xs font-bold border hover:text-blue-600 dark:hover:text-white transition">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div id="exams-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['cat' => 'college', 'badge' => 'College Entrance', 'badgeClass' => 'badge-blue', 'count' => '3,200+', 'name' => 'UPCAT Reviewer', 'desc' => 'UP College Admission Test coverage: Language Proficiency, Science, Mathematics, and Reading Comprehension.', 'tag' => '2026-2027 Syllabus'],
                    ['cat' => 'civil', 'badge' => 'Civil Service', 'badgeClass' => 'badge-amber', 'count' => '4,500+', 'name' => 'CSE Professional Level', 'desc' => 'Numerical Reasoning, Analytical Thinking, Verbal Ability, General Information, and 1987 PH Constitution.', 'tag' => '80% Passing Mark Target'],
                    ['cat' => 'teachers', 'badge' => 'Teachers (LET)', 'badgeClass' => 'badge-purple', 'count' => '3,800+', 'name' => 'LET Licensure Exam', 'desc' => 'General Education (GenEd), Professional Education (ProfEd), and Specialization Majors for Elementary & Secondary.', 'tag' => 'PRC Compliant'],
                    ['cat' => 'college', 'badge' => 'College Entrance', 'badgeClass' => 'badge-blue', 'count' => '2,800+', 'name' => 'PUPCET & USTET Bundle', 'desc' => 'Practice drills for PUP CET, USTET, DCAT, and ACET entrance speed tests.', 'tag' => 'Fast-Paced Mode'],
                    ['cat' => 'civil', 'badge' => 'Civil Service', 'badgeClass' => 'badge-amber', 'count' => '2,100+', 'name' => 'CSE Subprofessional', 'desc' => 'Clerical Operations, Spelling, Vocabulary, Numerical Ability, and General Information.', 'tag' => 'Clerical Drills'],
                    ['cat' => 'college', 'badge' => 'Medical Prep', 'badgeClass' => 'badge-teal', 'count' => '1,900+', 'name' => 'NMAT Reviewer', 'desc' => 'National Medical Admission Test: Part 1 Aptitude & Part 2 Academic Sciences (Physics, Chem, Bio).', 'tag' => 'High Percentile Target'],
                ] as $exam)
                <div class="exam-card flat-card card p-6 flex flex-col justify-between" data-category="{{ $exam['cat'] }}">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="{{ $exam['badgeClass'] }}">{{ $exam['badge'] }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><i class="fa-solid fa-list-check mr-1"></i> {{ $exam['count'] }} Questions</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $exam['name'] }}</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-4">{{ $exam['desc'] }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold"><i class="fa-solid fa-check-double mr-1"></i> {{ $exam['tag'] }}</span>
                        <a href="{{ route('reviewers') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded transition flex items-center gap-1.5">
                            <span>Start Review</span> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section id="how-it-works" class="py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="section-eyebrow">3-Step Methodology</h2>
                <p class="section-title">Your Visual Roadmap to Board & College Success</p>
                <p class="section-subtitle">Designed by Philippine topnotchers to maximize score growth in minimum preparation time.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['num' => '1', 'color' => 'bg-blue-600', 'title' => 'Find Your Baseline', 'desc' => 'Take a 30-minute diagnostic test to establish your starting score and benchmark yourself against official passing cutoffs.'],
                    ['num' => '2', 'color' => 'bg-blue-600', 'title' => 'Drill Weak Topics', 'desc' => 'Practice weak sub-topics with instant AI Taglish explanations that break down difficult formulas and constitutional provisions.'],
                    ['num' => '3', 'color' => 'bg-emerald-600', 'title' => 'Pass Your Exam', 'desc' => 'Simulate full-length, timed actual board conditions and enter your examination center with 90%+ confidence.'],
                ] as $step)
                <div class="card p-8 text-center">
                    <div class="w-16 h-16 rounded-full {{ $step['color'] }} text-white font-extrabold text-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        {{ $step['num'] }}
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-3">{{ $step['title'] }}</h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== SEO ARTICLES ===== --}}
    <section id="articles" class="py-16 bg-slate-100 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="section-eyebrow">Topnotcher Insights</h2>
                <p class="section-title">Exam Strategies & Study Guides</p>
                <p class="section-subtitle">Proven techniques and shortcuts shared by Philippine educators and board exam topnotchers.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse(($latestPosts ?? collect()) as $post)
                <a href="{{ route('blog.show', $post) }}" class="flat-card card overflow-hidden flex flex-col justify-between group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    @if($post->featured_image)
                    <div class="h-40 overflow-hidden">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @endif
                    <div class="p-6 flex-1">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-3">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $post->category?->name ?? 'Guide' }}</span> • <span>{{ $post->reading_time }} min read</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 leading-snug group-hover:text-blue-600 transition line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">{{ $post->excerpt ?: Str::limit(strip_tags($post->body), 120) }}</p>
                    </div>
                    <div class="px-6 pb-6 pt-2 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">By {{ $post->author?->name ?? 'Admin' }}</span>
                        <span class="text-xs font-bold text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition flex items-center gap-1">
                            Read Guide <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </span>
                    </div>
                </a>
                @empty
                {{-- Fallback static cards when no blog posts exist --}}
                @foreach([
                    ['tag' => 'UPCAT Prep', 'time' => '5 min read', 'title' => 'How to Master UPCAT Science & Math without Memorizing Formulas', 'desc' => 'Learn pattern recognition techniques and elimination strategies for tough UPCAT physics and algebra items.', 'author' => 'By Prof. Santos, UP Diliman'],
                    ['tag' => 'Civil Service', 'time' => '6 min read', 'title' => 'Top 5 Civil Service Math Shortcuts You Need for 2026 CSE', 'desc' => 'Solve work problems, percentage calculations, and age ratios in under 30 seconds per item.', 'author' => 'By CSE Topnotcher #1'],
                    ['tag' => 'LET Board Exam', 'time' => '7 min read', 'title' => "Understanding Bloom's Taxonomy & Assessment Principles in LET", 'desc' => 'Deconstruct situational questions in Professional Education with practical Taglish scenario analysis.', 'author' => 'By Dr. Cruz, LPT'],
                ] as $article)
                <div class="flat-card card overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-3">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $article['tag'] }}</span> • <span>{{ $article['time'] }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 leading-snug">{{ $article['title'] }}</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $article['desc'] }}</p>
                    </div>
                    <div class="px-6 pb-6 pt-2 border-t border-slate-200 dark:border-slate-700/50 flex items-center justify-between">
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $article['author'] }}</span>
                        <a href="{{ route('blog.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition flex items-center gap-1">
                            Read Guide <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endforeach
                @endforelse
            </div>

            {{-- View All CTA --}}
            <div class="text-center mt-8">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold px-6 py-3 rounded-xl text-sm hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 transition">
                    <i class="fa-solid fa-book-open"></i> View All Study Guides
                </a>
            </div>
        </div>
    </section>

    {{-- ===== TESTIMONIALS ===== --}}
    <section id="reviews" class="py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 mb-3">
                    <i class="fa-brands fa-google text-blue-500"></i>
                    <span>Verified Google Reviews & Student Feedback</span>
                </div>
                <p class="section-title">Loved by 120,000+ Examinees Nationwide</p>
                <div class="flex items-center justify-center gap-2 mt-3">
                    <div class="flex text-amber-400 text-sm">
                        @for($i = 0; $i < 5; $i++) <i class="fa-solid fa-star"></i> @endfor
                    </div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">4.9 / 5.0</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">(1,840+ Google Rating Reviews)</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['initials' => 'MC', 'color' => 'bg-blue-600', 'name' => 'Maria Christine S.', 'badge' => 'UPCAT 2025 Passer (UP Diliman)', 'text' => '"Super helpful ng AI Taglish explanations! Instead of just giving the correct answer, pinaliliwanag \'yung concept in clear Tagalog and English. Passed UPCAT on my first attempt without spending ₱15k on traditional review centers!"', 'time' => '2 weeks ago'],
                    ['initials' => 'MR', 'color' => 'bg-emerald-600', 'name' => 'Mark Anthony R.', 'badge' => 'Civil Service Pro Passed (89.2%)', 'text' => '"The Math shortcuts and Constitutional Law drills are spot on. Visualizing my weak areas with the AI Readiness Index gave me confidence before exam day. Highly recommended para sa lahat ng CSE reviewees."', 'time' => '1 month ago'],
                    ['initials' => 'PF', 'color' => 'bg-purple-600', 'name' => 'Patricia May F., LPT', 'badge' => 'LET Topnotcher (Rank 8)', 'text' => '"As a working student reviewee, sobrang bitin sa oras. The 10-minute quick drills on my daily commute helped me master ProfEd and GenEd situational items fast. Super helpful talaga nitong platform!"', 'time' => '3 weeks ago'],
                ] as $review)
                <div class="flat-card card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full {{ $review['color'] }} text-white font-bold flex items-center justify-center text-sm">{{ $review['initials'] }}</div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                                        <span>{{ $review['name'] }}</span>
                                        <i class="fa-solid fa-circle-check text-blue-500 text-xs" title="Verified"></i>
                                    </div>
                                    <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">{{ $review['badge'] }}</div>
                                </div>
                            </div>
                            <i class="fa-brands fa-google text-slate-400 dark:text-slate-500 text-base"></i>
                        </div>
                        <div class="flex text-amber-400 text-xs mb-3">
                            @for($i = 0; $i < 5; $i++) <i class="fa-solid fa-star"></i> @endfor
                        </div>
                        <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $review['text'] }}</p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-200 dark:border-slate-700/60 text-[11px] text-slate-500 flex justify-between">
                        <span>Posted on Google Reviews</span>
                        <span>{{ $review['time'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="py-16 bg-blue-600 dark:bg-blue-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-4">Ready to Pass Your Exam?</h2>
            <p class="text-blue-100 text-sm sm:text-base mb-8 max-w-2xl mx-auto">
                Join 120,000+ Filipino examinees who passed their board and entrance exams using ExamReady PH. Register for free and start practicing today.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-white text-blue-700 hover:bg-blue-50 font-bold px-8 py-3.5 rounded-lg transition flex items-center gap-2 text-base shadow-sm">
                    <i class="fa-solid fa-user-plus"></i> Register Free Today
                </a>
                <a href="#categories" class="bg-blue-700 hover:bg-blue-800 dark:bg-blue-800 dark:hover:bg-blue-900 text-white font-semibold px-8 py-3.5 rounded-lg border border-blue-500 transition flex items-center gap-2 text-base">
                    <i class="fa-solid fa-layer-group"></i> Browse Reviewers
                </a>
            </div>
        </div>
    </section>

    {{-- ===== FAQ ===== --}}
    <section id="faq" class="py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="section-eyebrow">Got Questions?</h2>
                <p class="section-title">Frequently Asked Questions</p>
            </div>

            <div class="space-y-4" x-data="{ openFaq: null }">
                @foreach([
                    ['q' => 'Is ExamReady PH completely free to use?', 'a' => 'Yes! All core practice question sets, diagnostic tests, and basic Taglish rationales are 100% free. We believe every Filipino student deserves equal opportunity to pass their exams regardless of financial background.'],
                    ['q' => 'Does it work smoothly on mobile phones and low-bandwidth connections?', 'a' => 'Absolutely. ExamReady PH is lightweight, mobile-responsive, and optimized for 3G/4G networks. You can review anywhere on jeepneys, buses, or during study breaks without downloading heavy apps.'],
                    ['q' => 'How accurate are the AI Taglish explanations?', 'a' => 'Our AI Taglish explanations are curated and strictly vetted by licensed Philippine educators, UP professors, and board exam topnotchers to ensure alignment with PRC, CSC, and university admission syllabi.'],
                    ['q' => 'What payment methods are supported for Premium subscriptions?', 'a' => 'We support GCash, Maya, and Philippine Bank Transfers with instant auto-activation. No credit card required.'],
                    ['q' => 'Can I resume an exam if I accidentally close my browser?', 'a' => 'Yes! Your exam session is automatically saved. As long as the timer hasn\'t expired, you can resume from your dashboard exactly where you left off.'],
                ] as $idx => $faq)
                <div class="card overflow-hidden">
                    <button @click="openFaq === {{ $idx }} ? openFaq = null : openFaq = {{ $idx }}"
                            class="w-full text-left p-5 flex items-center justify-between gap-4 font-bold text-slate-900 dark:text-slate-100 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        <span>{{ $faq['q'] }}</span>
                        <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-200" :class="openFaq === {{ $idx }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openFaq === {{ $idx }}" x-collapse
                         class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-400 border-t border-slate-200 dark:border-slate-700/50 pt-3 leading-relaxed">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

</x-public-layout>
