<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? View::yieldContent('title', 'Admin') }} — ExamReady PH Admin</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="w-64 bg-slate-900 dark:bg-slate-950 border-r border-slate-800 flex flex-col fixed inset-y-0 left-0 z-30 transition-transform -translate-x-full lg:translate-x-0">
            {{-- Brand --}}
            <div class="h-16 flex items-center px-5 border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-lg font-extrabold text-white">ExamReady <span class="text-blue-400">PH</span></span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3 mb-2">Main</div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard
                </a>

                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3 mt-5 mb-2">Content</div>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-layer-group w-5 text-center"></i> Exam Categories
                </a>

                <a href="{{ route('admin.exams.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.exams.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-book-open w-5 text-center"></i> Exams
                </a>

                <a href="{{ route('admin.questions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.questions.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-circle-question w-5 text-center"></i> Questions
                </a>

                <a href="{{ route('admin.subtopics.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.subtopics.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-puzzle-piece w-5 text-center text-teal-400"></i> Subtopics
                </a>

                <a href="{{ route('admin.reported-questions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reported-questions.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-triangle-exclamation w-5 text-center text-rose-400"></i> Reported Issues
                </a>

                <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.ads.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-rectangle-ad w-5 text-center text-amber-400"></i> Ad Campaigns
                </a>

                <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.blog.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-newspaper w-5 text-center text-emerald-400"></i> Blog Posts
                </a>

                <a href="{{ route('admin.blog-categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.blog-categories.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-folder-open w-5 text-center text-cyan-400"></i> Blog Categories
                </a>

                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider px-3 mt-5 mb-2">Management</div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-users w-5 text-center"></i> Users
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.settings') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition">
                    <i class="fa-solid fa-gear w-5 text-center"></i> Settings
                </a>
            </nav>

            {{-- User Info --}}
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-sm">{{ auth()->user()->initials }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 lg:ml-64">
            {{-- Top Bar --}}
            <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-4">
                    {{-- Mobile sidebar toggle --}}
                    <button onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="lg:hidden w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1 class="text-lg font-bold text-slate-900 dark:text-white">{{ $title ?? 'Admin Panel' }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="toggleTheme()" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-blue-600 transition">
                        <i id="theme-toggle-icon" class="fa-solid fa-sun text-amber-400"></i>
                    </button>
                    <a href="{{ route('home') }}" class="text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Site
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-rose-500 hover:text-rose-400 transition flex items-center gap-1">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mx-4 sm:mx-6 mt-4 p-4 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Page Content --}}
            <main class="p-4 sm:p-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Theme Toggle --}}
    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            if (saved === 'light') document.documentElement.classList.remove('dark');
            else document.documentElement.classList.add('dark');
        })();
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-toggle-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark'); localStorage.setItem('theme', 'light');
                if (icon) icon.className = 'fa-solid fa-moon text-slate-700';
            } else {
                html.classList.add('dark'); localStorage.setItem('theme', 'dark');
                if (icon) icon.className = 'fa-solid fa-sun text-amber-400';
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
