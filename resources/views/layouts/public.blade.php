<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta --}}
    <title>{{ $metaTitle ?? 'ExamReady PH — Free Philippine Exam Reviewer with AI Taglish Explanations' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Master UPCAT, Civil Service, LET, and NMAT with 15,000+ updated practice questions, step-by-step Taglish explanations, and real-time readiness tracking.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'Philippine exam reviewer, UPCAT reviewer, civil service reviewer, LET reviewer, NMAT reviewer, free online reviewer Philippines' }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $metaTitle ?? 'ExamReady PH — Free Philippine Exam Reviewer' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Master UPCAT, Civil Service, LET, and NMAT with 15,000+ updated practice questions.' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="ExamReady PH">
    <meta property="og:locale" content="en_PH">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle ?? 'ExamReady PH' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Free Philippine Exam Reviewer with AI Taglish Explanations' }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">

    {{-- Google Fonts: Inter (preconnect for performance) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific head content --}}
    @stack('head')
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-100 min-h-screen flex flex-col transition-colors duration-200 selection:bg-blue-600 selection:text-white antialiased">

    {{-- Announcement Bar --}}
    @include('components.announcement-bar')

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Theme Toggle Script --}}
    <script>
        // Initialize theme from localStorage
        (function() {
            const saved = localStorage.getItem('theme');
            const html = document.documentElement;
            if (saved === 'light') {
                html.classList.remove('dark');
            } else {
                html.classList.add('dark');
            }
        })();

        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-toggle-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if (icon) icon.className = 'fa-solid fa-moon text-slate-700';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (icon) icon.className = 'fa-solid fa-sun text-amber-400';
            }
        }
    </script>

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
