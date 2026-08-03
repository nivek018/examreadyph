<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Banner --}}
            <div class="card p-6 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 border-0 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold mb-1">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                        <p class="text-blue-100 text-sm">Ready to continue your exam preparation? Let's keep the momentum going.</p>
                    </div>
                    <div class="hidden sm:block">
                        <i class="fa-solid fa-graduation-cap text-5xl text-blue-400/30"></i>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['label' => 'Exams Taken', 'value' => $stats['total_exams_taken'], 'icon' => 'fa-clipboard-check', 'color' => 'text-blue-500'],
                    ['label' => 'Average Score', 'value' => number_format($stats['average_score'], 1) . '%', 'icon' => 'fa-chart-simple', 'color' => 'text-emerald-500'],
                    ['label' => 'Best Score', 'value' => number_format($stats['best_score'], 1) . '%', 'icon' => 'fa-trophy', 'color' => 'text-amber-500'],
                    ['label' => 'Questions Answered', 'value' => number_format($stats['total_questions_answered']), 'icon' => 'fa-circle-check', 'color' => 'text-purple-500'],
                ] as $stat)
                <div class="card p-4">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid {{ $stat['icon'] }} {{ $stat['color'] }} text-lg"></i>
                        <div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ $stat['label'] }}</div>
                            <div class="text-lg font-bold text-slate-900 dark:text-white">{{ $stat['value'] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Resume In-Progress Exams --}}
            @if($inProgressSessions->count() > 0)
            <div class="card p-6 border-amber-300 dark:border-amber-700 bg-amber-50/50 dark:bg-amber-950/20">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-play-circle text-amber-500"></i> Resume In-Progress Exams
                </h3>
                <div class="space-y-3">
                    @foreach($inProgressSessions as $s)
                    <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div>
                            <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ $s->exam->name }}</div>
                            <div class="text-xs text-slate-500 flex items-center gap-3">
                                <span><i class="fa-solid fa-clock text-amber-500"></i> {{ $s->remaining_seconds > 0 ? gmdate('H:i:s', $s->remaining_seconds) . ' remaining' : 'No time limit' }}</span>
                                <span>{{ $s->progress_percent }}% complete</span>
                            </div>
                        </div>
                        <a href="{{ route('exam.take', $s) }}" class="btn-brand px-4 py-2 text-xs">
                            <i class="fa-solid fa-play"></i> Resume
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Available Exams --}}
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-blue-500"></i> Available Exams
                </h3>

                @forelse($categories as $category)
                @if($category->exams->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                        <i class="{{ $category->icon ?? 'fa-solid fa-layer-group' }} {{ str_replace('badge-', 'text-', $category->color_class ?? 'text-blue-500') }}"></i>
                        {{ $category->name }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($category->exams as $exam)
                        <div class="card p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="font-bold text-slate-900 dark:text-white text-sm">{{ $exam->name }}</h5>
                                    @if($exam->is_premium)
                                    <span class="badge-amber text-[10px]">Premium</span>
                                    @else
                                    <span class="badge-emerald text-[10px]">Free</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">{{ Str::limit($exam->description, 80) }}</p>
                                <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                                    <span><i class="fa-solid fa-list-check mr-1"></i>{{ $exam->questions_count ?? 0 }} Q's</span>
                                    <span><i class="fa-solid fa-clock mr-1"></i>{{ $exam->formatted_time_limit }}</span>
                                    <span><i class="fa-solid fa-bullseye mr-1"></i>{{ $exam->passing_score_percent }}%</span>
                                </div>
                            </div>
                            <div class="pt-3 mt-3 border-t border-slate-200 dark:border-slate-700">
                                <form method="POST" action="{{ route('exam.start', $exam) }}">
                                    @csrf
                                    <button type="submit" class="w-full btn-brand py-2 text-xs {{ ($exam->is_premium && !auth()->user()->isPremium()) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ ($exam->is_premium && !auth()->user()->isPremium()) ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-bolt"></i>
                                        {{ ($exam->is_premium && !auth()->user()->isPremium()) ? 'Premium Required' : 'Start Exam' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @empty
                <div class="card p-10 text-center">
                    <i class="fa-solid fa-folder-open text-3xl text-slate-400 mb-3"></i>
                    <p class="text-slate-500 dark:text-slate-400">No exams available yet. Check back soon!</p>
                </div>
                @endforelse
            </div>

            {{-- Recent Exam History --}}
            @if($recentSessions->count() > 0)
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-500"></i> Recent Exam History
                </h3>
                <div class="card overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Exam</th>
                                <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Score</th>
                                <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                                <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Date</th>
                                <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($recentSessions as $s)
                            <tr>
                                <td class="px-5 py-3 font-medium text-slate-900 dark:text-white">{{ $s->exam->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($s->status === 'completed')
                                    <span class="font-bold {{ $s->score >= ($s->exam->passing_score_percent ?? 75) ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($s->score, 1) }}%</span>
                                    @else
                                    <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($s->status === 'completed')
                                        @if($s->score >= ($s->exam->passing_score_percent ?? 75))
                                            <span class="badge-emerald text-[10px]">Passed</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-400 text-[10px] font-bold border border-rose-200 dark:border-rose-800">Failed</span>
                                        @endif
                                    @elseif($s->status === 'in_progress')
                                        <span class="badge-amber text-[10px]">In Progress</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-[10px] font-bold">{{ ucfirst($s->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center text-xs text-slate-500">{{ $s->created_at->format('M d, Y') }}</td>
                                <td class="px-5 py-3 text-right">
                                    @if($s->status === 'completed')
                                    <a href="{{ route('exam.results', $s) }}" class="text-xs text-blue-600 hover:text-blue-500 font-medium">View Results</a>
                                    @elseif($s->status === 'in_progress' && $s->isActive())
                                    <a href="{{ route('exam.take', $s) }}" class="text-xs text-amber-600 hover:text-amber-500 font-medium">Resume</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
