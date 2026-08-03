<x-public-layout
    metaTitle="{{ isset($currentCategory) ? $currentCategory->name . ' — Study Guides | ExamReady PH' : 'Study Guides & Exam Tips — ExamReady PH' }}"
    metaDescription="{{ isset($currentCategory) ? 'Browse ' . $currentCategory->name . ' study guides and exam tips on ExamReady PH.' : 'Free study guides, exam tips, and reviewer strategies for Civil Service, LET, UPCAT, and NMAT. Written by Filipino exam experts.' }}"
>
    {{-- Hero --}}
    <section class="py-14 bg-gradient-to-b from-blue-50/50 to-white dark:from-slate-900 dark:to-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="badge-blue uppercase text-xs font-bold px-3 py-1 mb-4 inline-block">Study Guides & Exam Tips</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-4">
                @if(isset($currentCategory))
                    {{ $currentCategory->name }}
                @else
                    Level Up Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-teal-500">Exam Prep</span>
                @endif
            </h1>
            <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                @if(isset($currentCategory))
                    {{ $currentCategory->description ?: 'Browse all articles in ' . $currentCategory->name . '.' }}
                @else
                    Expert strategies, study tips, and free reviewer guides for Philippine board exams and entrance tests.
                @endif
            </p>
        </div>
    </section>

    {{-- Category Filter Tabs --}}
    <section class="bg-white dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 sticky top-16 z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 overflow-x-auto py-3 scrollbar-hide">
                <a href="{{ route('blog.index') }}" class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold transition {{ !isset($currentCategory) ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700' }}">
                    All
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('blog.category', $cat) }}" class="shrink-0 px-4 py-2 rounded-full text-sm font-semibold transition {{ (isset($currentCategory) && $currentCategory->id === $cat->id) ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700' }}">
                    {{ $cat->name }} <span class="text-xs opacity-70">({{ $cat->posts_count }})</span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Posts (only on main index) --}}
    @if(!isset($currentCategory) && $featuredPosts->isNotEmpty())
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-star text-amber-500"></i> Featured Articles
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featuredPosts as $fp)
                <a href="{{ route('blog.show', $fp) }}" class="group card flat-card overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    @if($fp->featured_image)
                    <div class="h-44 overflow-hidden">
                        <img src="{{ asset('storage/' . $fp->featured_image) }}" alt="{{ $fp->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @else
                    <div class="h-44 bg-gradient-to-br from-blue-500 to-teal-500 flex items-center justify-center">
                        <i class="fa-solid fa-newspaper text-4xl text-white/40"></i>
                    </div>
                    @endif
                    <div class="p-5">
                        @if($fp->category)
                        <span class="badge-blue text-[10px] mb-2 inline-block">{{ $fp->category->name }}</span>
                        @endif
                        <h3 class="font-bold text-slate-900 dark:text-white text-base mb-2 group-hover:text-blue-600 transition line-clamp-2">{{ $fp->title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $fp->excerpt ?: Str::limit(strip_tags($fp->body), 120) }}</p>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-xs text-slate-500">{{ $fp->formatted_date }}</span>
                            <span class="text-xs text-slate-500">{{ $fp->reading_time }} min read</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- All Posts Grid --}}
    <section class="py-10 {{ (!isset($currentCategory) && $featuredPosts->isNotEmpty()) ? '' : 'pt-10' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(!isset($currentCategory) && $featuredPosts->isNotEmpty())
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-clock text-blue-500"></i> Latest Articles
            </h2>
            @endif

            @if($posts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                <a href="{{ route('blog.show', $post) }}" class="group card flat-card overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    @if($post->featured_image)
                    <div class="h-40 overflow-hidden">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @else
                    <div class="h-40 bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center">
                        <i class="fa-solid fa-newspaper text-3xl text-slate-400 dark:text-slate-600"></i>
                    </div>
                    @endif
                    <div class="p-5">
                        @if($post->category)
                        <span class="badge-blue text-[10px] mb-2 inline-block">{{ $post->category->name }}</span>
                        @endif
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-2 group-hover:text-blue-600 transition line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $post->excerpt ?: Str::limit(strip_tags($post->body), 120) }}</p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white text-[9px] font-bold flex items-center justify-center">{{ $post->author?->initials ?? 'A' }}</div>
                                <span class="text-xs text-slate-500">{{ $post->author?->name ?? 'Admin' }}</span>
                            </div>
                            <span class="text-xs text-slate-500">{{ $post->reading_time }} min</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-8">{{ $posts->links() }}</div>
            @else
            <div class="text-center py-20">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-newspaper text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No articles yet</h3>
                <p class="text-sm text-slate-500">Study guides and exam tips are coming soon. Check back later!</p>
            </div>
            @endif
        </div>
    </section>
</x-public-layout>
