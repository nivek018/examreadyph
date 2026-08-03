<x-admin-layout :title="$question->exists ? 'Edit Question' : 'Create Question'">

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.questions.index', request()->only('exam_id')) }}" class="text-sm text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Questions
            </a>
        </div>

        <div class="card p-6" x-data="questionForm()">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">{{ $question->exists ? 'Edit Question' : 'Create New Question' }}</h2>

            <form method="POST" action="{{ $question->exists ? route('admin.questions.update', $question) : route('admin.questions.store') }}">
                @csrf
                @if($question->exists) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Exam --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Exam *</label>
                        <select name="exam_id" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select exam...</option>
                            @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ old('exam_id', $selectedExamId ?? $question->exam_id) == $exam->id ? 'selected' : '' }}>{{ $exam->name }} ({{ $exam->category->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Section --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Subtopic</label>
                        <select name="subtopic_id" class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">No subtopic</option>
                            @foreach($subtopics as $subtopic)
                            <option value="{{ $subtopic->id }}" data-exam="{{ $subtopic->exam_id }}" {{ old('subtopic_id', $question->subtopic_id) == $subtopic->id ? 'selected' : '' }}>{{ $subtopic->name }} ({{ $subtopic->exam->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Section Name <span class="text-xs text-slate-400">(Legacy, optional)</span></label>
                    <input type="text" name="section_name" value="{{ old('section_name', $question->section_name) }}"
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                           placeholder="e.g., General Information, Numerical Reasoning">
                </div>

                {{-- Question Text --}}
                <div class="mt-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Question Text *</label>
                    <textarea name="question_text" rows="4" required
                              class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                              placeholder="Type your question here...">{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                {{-- Answer Options --}}
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Answer Options *</label>
                    <div class="space-y-3">
                        <template x-for="(opt, index) in options" :key="index">
                            <div class="flex items-center gap-3">
                                {{-- Radio for correct answer --}}
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="correct_option" :value="index" :checked="opt.is_correct"
                                           class="w-5 h-5 text-emerald-500 border-slate-300 dark:border-slate-600 focus:ring-emerald-500">
                                </label>
                                {{-- Letter --}}
                                <input type="text" :name="'options[' + index + '][letter]'" x-model="opt.letter"
                                       class="w-14 px-3 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-center font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition" readonly>
                                {{-- Option Text --}}
                                <input type="text" :name="'options[' + index + '][text]'" x-model="opt.text" required
                                       class="flex-1 px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                                       :placeholder="'Option ' + opt.letter + ' text...'">
                                {{-- Remove button (if more than 2) --}}
                                <button type="button" x-show="options.length > 2" @click="removeOption(index)"
                                        class="text-rose-500 hover:text-rose-400 text-sm"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addOption()" x-show="options.length < 6"
                            class="mt-3 text-sm font-medium text-blue-600 hover:text-blue-500 flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Add Option
                    </button>
                    <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-circle-check text-emerald-500"></i> Select the radio button next to the correct answer.</p>
                </div>

                {{-- AI Explanation --}}
                <div class="mt-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">AI Taglish Explanation</label>
                    <textarea name="explanation_taglish" rows="4"
                              class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                              placeholder="Explain the correct answer in Taglish...">{{ old('explanation_taglish', $question->explanation_taglish) }}</textarea>
                </div>

                {{-- Difficulty & Flags --}}
                <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Difficulty *</label>
                        <select name="difficulty" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            @foreach(['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $val => $label)
                            <option value="{{ $val }}" {{ old('difficulty', $question->difficulty ?? 'medium') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 self-end">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Active</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 self-end">
                        <input type="checkbox" name="is_premium" value="1" {{ old('is_premium', $question->is_premium ?? false) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-amber-600 focus:ring-amber-500">
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">Premium Only</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="btn-brand px-6 py-2.5 text-sm">
                        <i class="fa-solid fa-check"></i> {{ $question->exists ? 'Update Question' : 'Create Question' }}
                    </button>
                    <a href="{{ route('admin.questions.index', request()->only('exam_id')) }}" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function questionForm() {
            const letters = ['A', 'B', 'C', 'D', 'E', 'F'];
            @if($question->exists && $question->options->count() > 0)
            const existingOptions = @json($question->options->map(fn($o, $i) => ['letter' => $o->letter, 'text' => $o->text, 'is_correct' => $o->is_correct]));
            @else
            const existingOptions = [
                { letter: 'A', text: '', is_correct: true },
                { letter: 'B', text: '', is_correct: false },
                { letter: 'C', text: '', is_correct: false },
                { letter: 'D', text: '', is_correct: false },
            ];
            @endif

            return {
                options: existingOptions,
                addOption() {
                    if (this.options.length < 6) {
                        this.options.push({ letter: letters[this.options.length], text: '', is_correct: false });
                    }
                },
                removeOption(index) {
                    if (this.options.length > 2) {
                        this.options.splice(index, 1);
                        this.options.forEach((opt, i) => opt.letter = letters[i]);
                    }
                }
            };
        }
    </script>
    @endpush

</x-admin-layout>
