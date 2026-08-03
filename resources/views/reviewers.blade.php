<x-public-layout
    metaTitle="Free Exam Reviewers — ExamReady PH"
    metaDescription="Browse all available free Philippine exam reviewers: UPCAT, Civil Service Professional & Subprofessional, LET, NMAT, and more."
>
    {{-- Page Header --}}
    <section class="py-12 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h1 class="section-eyebrow">Browse All Reviewers</h1>
                <p class="section-title">Free Philippine Exam Reviewers</p>
                <p class="section-subtitle">Select an exam below to start practicing right away. No registration required for free practice tests!</p>
            </div>

            {{-- Filter Tabs --}}
            <div class="flex flex-wrap justify-center gap-2 mb-10" x-data="{ activeTab: 'all' }">
                <button @click="activeTab = 'all'; filterExams('all')"
                        :class="activeTab === 'all' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold border hover:text-blue-600 dark:hover:text-white transition">
                    All Exams
                </button>
                @foreach($categories as $category)
                <button @click="activeTab = '{{ $category->slug }}'; filterExams('{{ $category->slug }}')"
                        :class="activeTab === '{{ $category->slug }}' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold border hover:text-blue-600 dark:hover:text-white transition">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>

            {{-- Exam Cards Grid --}}
            <div id="exams-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($exams as $exam)
                <a href="{{ route('reviewer.show', $exam) }}" class="exam-card flat-card card p-6 flex flex-col justify-between hover:border-blue-500 dark:hover:border-blue-500 transition group" data-category="{{ $exam->category->slug ?? 'all' }}">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="{{ $exam->category->color_class ?? 'badge-blue' }}">{{ $exam->category->name ?? 'General' }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium"><i class="fa-solid fa-list-check mr-1"></i> {{ $exam->questions_count }} Questions</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">{{ $exam->name }}</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-4">{{ Str::limit($exam->description, 120) }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            @if($exam->is_premium)
                            <span class="badge-amber text-[10px]"><i class="fa-solid fa-crown mr-1"></i> PRO</span>
                            @else
                            <span class="badge-emerald text-[10px]">FREE</span>
                            @endif
                            <span class="text-xs text-slate-500"><i class="fa-solid fa-clock mr-1"></i> {{ round($exam->time_limit_seconds / 60) }}m</span>
                        </div>
                        <span class="bg-blue-600 group-hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded transition flex items-center gap-1.5 shadow-sm group-hover:scale-105">
                            <span>View Reviewer</span> <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
                @empty
                <div class="col-span-3 text-center py-12 text-slate-500">
                    <i class="fa-solid fa-book-open text-4xl mb-3 text-slate-400"></i>
                    <p class="font-medium">No exam reviewers found at the moment.</p>
                </div>
                @endforelse
            </div>

            {{-- Filter Script --}}
            <script>
                function filterExams(category) {
                    const cards = document.querySelectorAll('.exam-card');
                    cards.forEach(card => {
                        if (category === 'all' || card.dataset.category === category) {
                            card.style.display = 'flex';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                }
            </script>
        </div>
    </section>

</x-public-layout>
