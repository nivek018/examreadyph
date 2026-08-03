<x-admin-layout title="Questions">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Question Bank</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manage questions across all exams. Import via CSV or add manually.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="document.getElementById('import-modal').classList.toggle('hidden')" class="btn-brand-outline px-4 py-2.5 text-sm">
                <i class="fa-solid fa-file-csv"></i> Import CSV
            </button>
            <a href="{{ route('admin.questions.create', request()->only('exam_id')) }}" class="btn-brand px-4 py-2.5 text-sm">
                <i class="fa-solid fa-plus"></i> New Question
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="card p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search questions..."
               class="flex-1 px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
        <select name="exam_id" class="px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            <option value="">All Exams</option>
            @foreach($exams as $exam)
            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
            @endforeach
        </select>
        <select name="difficulty" class="px-4 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
            <option value="">All Difficulties</option>
            @foreach(['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $val => $label)
            <option value="{{ $val }}" {{ request('difficulty') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn-brand px-4 py-2 text-sm"><i class="fa-solid fa-search"></i> Filter</button>
    </form>

    {{-- Questions Table --}}
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Question</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Exam</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Difficulty</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($questions as $q)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                    <td class="px-5 py-4 max-w-md">
                        <div class="font-medium text-slate-900 dark:text-white truncate">{{ Str::limit($q->question_text, 80) }}</div>
                        @if($q->section_name)
                        <div class="text-xs text-slate-500 mt-0.5">Section: {{ $q->section_name }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-xs text-slate-600 dark:text-slate-400">
                        {{ $q->exam->name ?? '—' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        @php $diffColors = ['easy' => 'badge-emerald', 'medium' => 'badge-amber', 'hard' => 'badge-purple']; @endphp
                        <span class="{{ $diffColors[$q->difficulty] ?? 'badge-blue' }} text-[10px]">{{ ucfirst($q->difficulty) }}</span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($q->is_active)
                            <span class="badge-emerald text-[10px]">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-[10px] font-bold">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.questions.edit', $q) }}" class="text-blue-600 hover:text-blue-500 text-xs font-medium"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form method="POST" action="{{ route('admin.questions.destroy', $q) }}" onsubmit="return confirm('Delete this question?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-500 hover:text-rose-400 text-xs font-medium"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                        <i class="fa-solid fa-circle-question text-3xl mb-3 text-slate-400"></i>
                        <p class="font-medium">No questions found. <a href="{{ route('admin.questions.create') }}" class="text-blue-600">Add one</a> or import via CSV.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($questions->hasPages())
    <div class="mt-4">{{ $questions->withQueryString()->links() }}</div>
    @endif

    {{-- CSV Import Modal --}}
    <div id="import-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="card p-6 max-w-lg w-full mx-4">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Import Questions from CSV</h3>
            <form method="POST" action="{{ route('admin.questions.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Target Exam *</label>
                    <select name="exam_id" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                        @foreach($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">CSV File *</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white">
                    <p class="text-xs text-slate-500 mt-1">Format: question_text, option_a, option_b, option_c, option_d, correct_letter, explanation, difficulty, section</p>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-brand px-4 py-2.5 text-sm"><i class="fa-solid fa-upload"></i> Import</button>
                    <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-sm text-slate-500 hover:text-slate-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
