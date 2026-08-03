<x-public-layout
    :metaTitle="'Practice Mode — ' . $exam->name . ' | ExamReady PH'"
    :metaDescription="'Select subtopics and question count for targeted ' . $exam->name . ' practice.'"
>
    <section class="py-16" x-data="practiceSetup()">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Back Link --}}
            <a href="{{ route('exam.setup', $exam) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition mb-8">
                <i class="fa-solid fa-arrow-left"></i> Back to Mode Selection
            </a>

            <div class="text-center mb-10">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mb-4 mx-auto">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-2">Practice Mode Setup</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400">{{ $exam->name }} — Pick subtopics to focus on and choose your question count.</p>
            </div>

            <form method="POST" action="{{ route('exam.start-session', $exam) }}">
                @csrf
                <input type="hidden" name="mode" value="practice">

                {{-- Subtopic Selection --}}
                <div class="card flat-card p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Select Subtopics</h2>
                        <div class="flex gap-2">
                            <button type="button" @click="selectAll()" class="text-xs text-blue-600 dark:text-blue-400 font-semibold hover:underline">Select All</button>
                            <span class="text-xs text-slate-400">|</span>
                            <button type="button" @click="clearAll()" class="text-xs text-slate-500 hover:text-rose-500 font-semibold hover:underline">Clear All</button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        @foreach($subtopics as $subtopic)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 transition cursor-pointer"
                               :class="selectedSubtopics.includes({{ $subtopic->id }}) ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-400 dark:border-blue-600' : 'bg-white dark:bg-slate-800/50'">
                            <input type="checkbox" name="subtopic_ids[]" value="{{ $subtopic->id }}"
                                   x-model.number="selectedSubtopics"
                                   class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $subtopic->name }}</span>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium flex-shrink-0">{{ $subtopic->questions_count }} Qs</span>
                        </label>
                        @endforeach
                    </div>

                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                        <span class="font-semibold text-blue-600 dark:text-blue-400" x-text="selectedSubtopics.length"></span> of {{ $subtopics->count() }} subtopics selected
                    </p>
                </div>

                {{-- Question Count --}}
                <div class="card flat-card p-6 mb-6">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white mb-4">Number of Questions</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach([5, 10, 20, 50] as $count)
                        <button type="button" @click="questionCount = {{ $count }}"
                                :class="questionCount === {{ $count }} ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                                class="px-5 py-2.5 rounded-lg text-sm font-bold border transition hover:border-blue-500">
                            {{ $count }}
                        </button>
                        @endforeach
                        <div class="flex items-center gap-2">
                            <button type="button" @click="questionCount = customCount || 15"
                                    :class="![5,10,20,50].includes(questionCount) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                                    class="px-4 py-2.5 rounded-lg text-sm font-bold border transition hover:border-blue-500">
                                Custom
                            </button>
                            <input type="number" x-model.number="customCount" @input="questionCount = customCount"
                                   min="1" max="500" placeholder="e.g. 15"
                                   x-show="![5,10,20,50].includes(questionCount)"
                                   class="w-20 px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                        </div>
                    </div>
                    <input type="hidden" name="question_count" :value="questionCount">
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="selectedSubtopics.length === 0"
                        class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-slate-400 disabled:cursor-not-allowed text-white text-sm font-bold px-6 py-4 rounded-lg transition shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-play"></i>
                    Start Practice (<span x-text="questionCount"></span> questions from <span x-text="selectedSubtopics.length"></span> subtopics)
                </button>
            </form>
        </div>

        {{-- Auth Modal (shown when guest tries to start) --}}
        <div x-show="showAuthModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAuthModal = false"></div>

            {{-- Modal Card --}}
            <div class="relative w-full max-w-sm card flat-card p-8 text-center z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                {{-- Close button --}}
                <button @click="showAuthModal = false" class="absolute top-3 right-3 w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                {{-- Icon --}}
                <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-3xl mb-5 mx-auto">
                    <i class="fa-solid fa-user-plus"></i>
                </div>

                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Create a Free Account</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-6">
                    Practice Mode tracks your weak topics over time so you know exactly what to study. Sign up in 30 seconds — it's <strong class="text-emerald-600 dark:text-emerald-400">100% free</strong>.
                </p>

                {{-- Sign up button --}}
                <a href="{{ route('register') }}" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold px-6 py-3.5 rounded-lg transition shadow-lg flex items-center justify-center gap-2 mb-3">
                    <i class="fa-solid fa-user-plus"></i> Sign Up Free
                </a>

                {{-- Login link --}}
                <a href="{{ route('login') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition font-medium">
                    I have an account <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>

                {{-- Benefits list --}}
                <div class="mt-5 pt-5 border-t border-slate-200 dark:border-slate-700 text-left">
                    <p class="text-xs font-bold text-slate-500 uppercase mb-2">What you get:</p>
                    <ul class="space-y-1.5 text-xs text-slate-600 dark:text-slate-400">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Track weak subtopics over time</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Custom practice sessions saved</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> Progress dashboard & stats</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-500"></i> No payment required — ever</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        function practiceSetup() {
            return {
                selectedSubtopics: [],
                questionCount: 10,
                customCount: 15,
                showAuthModal: {{ session('showAuthModal') ? 'true' : 'false' }},
                selectAll() {
                    this.selectedSubtopics = [
                        @foreach($subtopics as $st){{ $st->id }},@endforeach
                    ];
                },
                clearAll() {
                    this.selectedSubtopics = [];
                }
            };
        }
    </script>
</x-public-layout>
