{{-- Navbar - Sticky header with desktop nav + mobile drawer --}}
<header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 border-b border-slate-200 dark:border-slate-800 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        {{-- Brand Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-black text-xl shadow-sm">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">ExamReady <span class="text-blue-500">PH</span></span>
                <span class="hidden sm:block text-[10px] text-slate-500 dark:text-slate-400 font-medium tracking-wide uppercase -mt-1">Philippine Reviewer Platform</span>
            </div>
        </a>

        {{-- Navigation Links (Desktop) --}}
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}#categories" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Reviewers</a>
            <a href="{{ route('pricing') }}" class="text-sm font-semibold text-amber-600 dark:text-amber-400 hover:text-amber-500 transition"><i class="fa-solid fa-crown mr-1"></i> Pricing</a>
            <a href="{{ route('home') }}#how-it-works" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">Practice Tests</a>
            <a href="{{ route('home') }}#faq" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">FAQ</a>
        </nav>

        {{-- Right Controls --}}
        <div class="flex items-center gap-3">
            {{-- Dark / Light Mode Switcher --}}
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white transition flex items-center justify-center" aria-label="Toggle theme">
                <i id="theme-toggle-icon" class="fa-solid fa-sun text-amber-400"></i>
            </button>

            @auth
                {{-- Authenticated User Menu --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-white transition">
                        <div class="w-7 h-7 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">
                            {{ auth()->user()->initials }}
                        </div>
                        <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-lg py-2 z-50">
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-amber-600 dark:text-amber-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700">
                            <i class="fa-solid fa-user-shield mr-2 w-4"></i> Admin Panel
                        </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <i class="fa-solid fa-gauge-high mr-2 w-4"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700">
                            <i class="fa-solid fa-user-gear mr-2 w-4"></i> Profile Settings
                        </a>
                        <div class="border-t border-slate-200 dark:border-slate-700 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 dark:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-700">
                                <i class="fa-solid fa-right-from-bracket mr-2 w-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Guest Buttons --}}
                <a href="{{ route('login') }}" class="hidden sm:flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition px-3 py-2">
                    Login
                </a>
                <a href="{{ route('register') }}" class="hidden sm:flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition shadow-sm">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Register Free</span>
                </a>
            @endauth

            {{-- Mobile Menu Button --}}
            <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

    </div>

    {{-- Mobile Menu Drawer --}}
    <div id="mobile-menu" class="hidden md:hidden border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-3 pb-6 space-y-3">
        <a href="{{ route('home') }}#categories" class="block text-slate-700 dark:text-slate-300 font-medium py-2 hover:text-blue-600 dark:hover:text-blue-400">Reviewers</a>
        <a href="{{ route('home') }}#how-it-works" class="block text-slate-700 dark:text-slate-300 font-medium py-2 hover:text-blue-600 dark:hover:text-blue-400">Practice Tests</a>
        <a href="{{ route('home') }}#articles" class="block text-slate-700 dark:text-slate-300 font-medium py-2 hover:text-blue-600 dark:hover:text-blue-400">Study Guides</a>
        <a href="{{ route('home') }}#faq" class="block text-slate-700 dark:text-slate-300 font-medium py-2 hover:text-blue-600 dark:hover:text-blue-400">FAQ</a>

        @guest
            <div class="flex gap-3 pt-2">
                <a href="{{ route('login') }}" class="flex-1 text-center bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 font-semibold py-2.5 rounded-lg border border-slate-300 dark:border-slate-700">Login</a>
                <a href="{{ route('register') }}" class="flex-1 text-center bg-blue-600 text-white font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Register
                </a>
            </div>
        @endguest
    </div>
</header>
