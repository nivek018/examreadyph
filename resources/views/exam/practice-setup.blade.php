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
    </section>

    <script>
        function practiceSetup() {
            return {
                selectedSubtopics: [],
                questionCount: 10,
                customCount: 15,
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
