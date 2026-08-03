<x-admin-layout :title="$exam->exists ? 'Edit Exam' : 'Create Exam'">

    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.exams.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Exams
            </a>
        </div>

        <div class="card p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">{{ $exam->exists ? 'Edit Exam' : 'Create New Exam' }}</h2>

            <form method="POST" action="{{ $exam->exists ? route('admin.exams.update', $exam) : route('admin.exams.store') }}">
                @csrf
                @if($exam->exists) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Category *</label>
                        <select name="category_id" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select category...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $exam->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Exam Name *</label>
                        <input type="text" name="name" value="{{ old('name', $exam->name) }}" required
                               class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                               placeholder="e.g., UPCAT Science & Math">
                    </div>

                    {{-- Total Questions (per session) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Questions Per Session *</label>
                        <input type="number" name="total_questions" value="{{ old('total_questions', $exam->total_questions ?? 50) }}" required min="1"
                               class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        <p class="text-xs text-slate-500 mt-1">How many questions to serve per exam session (from the pool).</p>
                    </div>

                    {{-- Time Limit --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Time Limit (seconds) *</label>
                        <input type="number" name="time_limit_seconds" value="{{ old('time_limit_seconds', $exam->time_limit_seconds ?? 3600) }}" required min="0"
                               class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        <p class="text-xs text-slate-500 mt-1">3600 = 1 hour. 0 = no time limit.</p>
                    </div>

                    {{-- Passing Score --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Passing Score (%) *</label>
                        <input type="number" name="passing_score_percent" value="{{ old('passing_score_percent', $exam->passing_score_percent ?? 75) }}" required min="0" max="100" step="0.1"
                               class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    {{-- Difficulty --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Difficulty *</label>
                        <select name="difficulty" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            @foreach(['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $val => $label)
                            <option value="{{ $val }}" {{ old('difficulty', $exam->difficulty ?? 'medium') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mt-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition"
                              placeholder="Brief description of this exam...">{{ old('description', $exam->description) }}</textarea>
                </div>

                {{-- Toggle Switches --}}
                <div class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach([
                        'is_active' => ['Active', $exam->is_active ?? true],
                        'is_premium' => ['Premium Only', $exam->is_premium ?? false],
                        'shuffle_questions' => ['Shuffle Questions', $exam->shuffle_questions ?? true],
                        'shuffle_options' => ['Shuffle Options', $exam->shuffle_options ?? false],
                        'show_explanations' => ['Show Explanations', $exam->show_explanations ?? true],
                        'allow_review' => ['Allow Review After', $exam->allow_review ?? true],
                    ] as $field => [$label, $default])
                    <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                        <input type="checkbox" name="{{ $field }}" value="1" {{ old($field, $default) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button type="submit" class="btn-brand px-6 py-2.5 text-sm">
                        <i class="fa-solid fa-check"></i> {{ $exam->exists ? 'Update Exam' : 'Create Exam' }}
                    </button>
                    <a href="{{ route('admin.exams.index') }}" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
