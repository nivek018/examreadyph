<x-admin-layout title="Dashboard">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach([
            ['label' => 'Total Users', 'value' => number_format($stats['total_users']), 'icon' => 'fa-users', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50 dark:bg-blue-900/30'],
            ['label' => 'Total Exams', 'value' => number_format($stats['total_exams']), 'icon' => 'fa-book-open', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/30'],
            ['label' => 'Total Questions', 'value' => number_format($stats['total_questions']), 'icon' => 'fa-circle-question', 'color' => 'text-amber-500', 'bg' => 'bg-amber-50 dark:bg-amber-900/30'],
            ['label' => 'Active Sessions', 'value' => number_format($stats['active_sessions']), 'icon' => 'fa-play-circle', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50 dark:bg-purple-900/30'],
        ] as $stat)
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $stat['label'] }}</div>
                    <div class="text-3xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $stat['value'] }}</div>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $stat['bg'] }} flex items-center justify-center">
                    <i class="fa-solid {{ $stat['icon'] }} {{ $stat['color'] }} text-xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Quick Actions --}}
        <div class="card p-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fa-solid fa-plus text-blue-500"></i> New Category
                </a>
                <a href="{{ route('admin.exams.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fa-solid fa-plus text-emerald-500"></i> New Exam
                </a>
                <a href="{{ route('admin.questions.create') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fa-solid fa-plus text-amber-500"></i> New Question
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-blue-500 transition text-sm font-medium text-slate-700 dark:text-slate-300">
                    <i class="fa-solid fa-gear text-slate-500"></i> Settings
                </a>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-500">View All →</a>
            </div>
            <div class="space-y-3">
                @forelse($stats['recent_users'] as $user)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">{{ $user->initials }}</div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                        </div>
                    </div>
                    <span class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
                @empty
                <p class="text-sm text-slate-500 dark:text-slate-400">No users yet.</p>
                @endforelse
            </div>
        </div>
    </div>

</x-admin-layout>
