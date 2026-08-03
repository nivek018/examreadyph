<x-public-layout
    :metaTitle="'Free Exam Reviewers — ExamReady PH'"
    :metaDescription="'Browse all available free Philippine exam reviewers: UPCAT, Civil Service Professional & Subprofessional, LET, NMAT, and more.'"
>
    {{-- Page Header --}}
    <section class="py-12 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h1 class="section-eyebrow">Browse All Reviewers</h1>
                <p class="section-title">Free Philippine Exam Reviewers</p>
                <p class="section-subtitle">Select an exam below to start practicing. No registration required for free exams!</p>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex flex-wrap justify-center gap-2 mb-10" x-data="{ activeTab: 'all' }">
                @foreach(['all' => 'All Exams', 'college' => 'College Entrance', 'civil' => 'Civil Service', 'teachers' => 'Teachers (LET)'] as $key => $label)
                <button @click="activeTab = '{{ $key }}'; filterExams('{{ $key }}')"
                        :class="activeTab === '{{ $key }}' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                        class="tab-btn px-4 py-2 rounded-lg text-xs font-bold border hover:text-blue-600 dark:hover:text-white transition">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            {{-- Exam Cards Grid --}}
            <div id="exams-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['cat' => 'college', 'badge' => 'College Entrance', 'badgeClass' => 'badge-blue', 'count' => '3,200+', 'name' => 'UPCAT Reviewer', 'desc' => 'UP College Admission Test coverage: Language Proficiency, Science, Mathematics, and Reading Comprehension.', 'tag' => '2026-2027 Syllabus', 'free' => true],
                    ['cat' => 'civil', 'badge' => 'Civil Service', 'badgeClass' => 'badge-amber', 'count' => '4,500+', 'name' => 'CSE Professional Level', 'desc' => 'Numerical Reasoning, Analytical Thinking, Verbal Ability, General Information, and 1987 PH Constitution.', 'tag' => '80% Passing Mark Target', 'free' => true],
                    ['cat' => 'teachers', 'badge' => 'Teachers (LET)', 'badgeClass' => 'badge-purple', 'count' => '3,800+', 'name' => 'LET Licensure Exam', 'desc' => 'General Education (GenEd), Professional Education (ProfEd), and Specialization Majors for Elementary & Secondary.', 'tag' => 'PRC Compliant', 'free' => true],
                    ['cat' => 'college', 'badge' => 'College Entrance', 'badgeClass' => 'badge-blue', 'count' => '2,800+', 'name' => 'PUPCET & USTET Bundle', 'desc' => 'Practice drills for PUP CET, USTET, DCAT, and ACET entrance speed tests.', 'tag' => 'Fast-Paced Mode', 'free' => true],
                    ['cat' => 'civil', 'badge' => 'Civil Service', 'badgeClass' => 'badge-amber', 'count' => '2,100+', 'name' => 'CSE Subprofessional', 'desc' => 'Clerical Operations, Spelling, Vocabulary, Numerical Ability, and General Information.', 'tag' => 'Clerical Drills', 'free' => true],
                    ['cat' => 'college', 'badge' => 'Medical Prep', 'badgeClass' => 'badge-teal', 'count' => '1,900+', 'name' => 'NMAT Reviewer', 'desc' => 'National Medical Admission Test: Part 1 Aptitude & Part 2 Academic Sciences (Physics, Chem, Bio).', 'tag' => 'High Percentile Target', 'free' => true],
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
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold"><i class="fa-solid fa-check-double mr-1"></i> {{ $exam['tag'] }}</span>
                            @if($exam['free'])
                            <span class="badge-emerald text-[10px]">FREE</span>
                            @endif
                        </div>
                        {{-- This will link to the actual exam page once Phase 2 is built --}}
                        <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded transition flex items-center gap-1.5">
                            <span>Start Review</span> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Coming Soon Note --}}
            <div class="text-center mt-10">
                <div class="card inline-flex items-center gap-3 px-6 py-4">
                    <i class="fa-solid fa-circle-info text-blue-500 text-lg"></i>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        <span class="font-bold text-slate-900 dark:text-white">More exams coming soon!</span>
                        We're constantly adding new questions and exam categories.
                    </p>
                </div>
            </div>
        </div>
    </section>

</x-public-layout>
