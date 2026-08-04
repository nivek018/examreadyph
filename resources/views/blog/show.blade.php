<x-public-layout
    :metaTitle="$post->meta_title"
    :metaDescription="$post->meta_description"
    ogType="article"
>
    {{-- Schema.org Article Structured Data --}}
    @push('head')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Article",
        "headline": @json($post->title),
        "description": @json($post->meta_description),
        "author": {
            "@@type": "Person",
            "name": @json($post->author->name ?? 'ExamReady PH')
        },
        "publisher": {
            "@@type": "Organization",
            "name": "ExamReady PH",
            "url": "{{ url('/') }}"
        },
        "datePublished": "{{ $post->published_at?->toISOString() }}",
        "dateModified": "{{ $post->updated_at->toISOString() }}",
        @if($post->featured_image)
        "image": "{{ asset('storage/' . $post->featured_image) }}",
        @endif
        "mainEntityOfPage": {
            "@@type": "WebPage",
            "@id": "{{ route('blog.show', $post) }}"
        }
    }
    </script>
    @endpush

    {{-- Breadcrumb --}}
    <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-3">
            <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('blog.index') }}" class="hover:text-blue-600 transition">Study Guides</a>
                @if($post->category)
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('blog.category', $post->category) }}" class="hover:text-blue-600 transition">{{ $post->category->name }}</a>
                @endif
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-slate-700 dark:text-slate-300 font-medium truncate max-w-[200px]">{{ $post->title }}</span>
            </nav>
        </div>
    </div>

    {{-- Main Centered Article --}}
    <article class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            
            {{-- Article Header --}}
            <header class="mb-8 text-center sm:text-left">
                @if($post->category)
                <a href="{{ route('blog.category', $post->category) }}" class="badge-blue text-[11px] mb-3 inline-block hover:opacity-80 transition">{{ $post->category->name }}</a>
                @endif

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-6">
                    {{ $post->title }}
                </h1>

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-sm text-slate-500 dark:text-slate-400 border-y border-slate-200 dark:border-slate-800 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shadow-sm">
                            {{ $post->author?->initials ?? 'A' }}
                        </div>
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $post->author?->name ?? 'Admin' }}</span>
                    </div>
                    <span class="hidden sm:inline text-slate-300 dark:text-slate-700">•</span>
                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar text-blue-500"></i> {{ $post->formatted_date }}</span>
                    <span class="hidden sm:inline text-slate-300 dark:text-slate-700">•</span>
                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-clock text-teal-500"></i> {{ $post->reading_time }} min read</span>
                    <span class="hidden sm:inline text-slate-300 dark:text-slate-700">•</span>
                    <span class="flex items-center gap-1.5"><i class="fa-regular fa-eye text-purple-500"></i> {{ number_format($post->view_count) }} views</span>
                </div>
            </header>

            {{-- Featured Image --}}
            @if($post->featured_image)
            <div class="mb-10 rounded-2xl overflow-hidden shadow-lg border border-slate-200 dark:border-slate-800">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[450px] object-cover">
            </div>
            @endif

            {{-- Article Content --}}
            <div class="prose prose-slate dark:prose-invert max-w-none
                prose-headings:font-extrabold prose-headings:tracking-tight
                prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:text-slate-900 dark:prose-h2:text-white
                prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                prose-p:text-base sm:prose-p:text-lg prose-p:leading-relaxed prose-p:text-slate-700 dark:prose-p:text-slate-300
                prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:font-bold hover:prose-a:underline
                prose-li:text-base sm:prose-li:text-lg prose-li:text-slate-700 dark:prose-li:text-slate-300
                prose-img:rounded-2xl prose-img:shadow-md
                prose-blockquote:border-l-4 prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50/60 dark:prose-blockquote:bg-blue-950/30 prose-blockquote:rounded-r-2xl prose-blockquote:py-3 prose-blockquote:px-5 prose-blockquote:not-italic font-medium">
                {!! $post->body !!}
            </div>

            {{-- Post Tags --}}
            @if($post->tags->isNotEmpty())
            <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-800">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mr-1"><i class="fa-solid fa-tags mr-1"></i> Tags:</span>
                    @foreach($post->tags as $tag)
                    <span class="px-3.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Share Bar --}}
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Share Article:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post)) }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm hover:bg-blue-700 transition shadow-sm" aria-label="Share on Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post)) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-xl bg-slate-800 text-white flex items-center justify-center text-sm hover:bg-slate-700 transition shadow-sm" aria-label="Share on Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ route('blog.show', $post) }}'); this.innerHTML='<i class=\'fa-solid fa-check\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-solid fa-link\'></i>', 2000)" class="w-9 h-9 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 flex items-center justify-center text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition shadow-sm" aria-label="Copy link">
                        <i class="fa-solid fa-link"></i>
                    </button>
                </div>
                <a href="{{ route('blog.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Guides
                </a>
            </div>

            {{-- In-Article Practice CTA (Replaces Sidebar CTA) --}}
            <div class="mt-12 p-8 rounded-3xl bg-gradient-to-br from-blue-600 to-teal-600 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10 text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div>
                        <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-[11px] font-extrabold uppercase tracking-wider mb-2">Free Online Reviewer</span>
                        <h3 class="text-2xl font-extrabold text-white mb-2">Ready to Test Your Knowledge?</h3>
                        <p class="text-blue-100 text-sm max-w-lg">
                            Practice real exam questions with Taglish step-by-step explanations and instant readiness scoring.
                        </p>
                    </div>
                    <a href="{{ route('reviewers') }}" class="shrink-0 bg-white text-blue-700 hover:bg-blue-50 font-extrabold px-6 py-3.5 rounded-2xl shadow-lg transition text-sm flex items-center gap-2">
                        <i class="fa-solid fa-graduation-cap"></i> Start Practice Exam
                    </a>
                </div>
            </div>

        </div>
    </article>

    {{-- Related Articles Section (Bottom Horizontal Layout) --}}
    @if($relatedPosts->isNotEmpty())
    <section class="py-14 bg-slate-100/70 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <i class="fa-solid fa-arrow-trend-up text-blue-500"></i> Related Study Guides
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedPosts as $related)
                <a href="{{ route('blog.show', $related) }}" class="group card flat-card overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1 bg-white dark:bg-slate-900">
                    @if($related->featured_image)
                    <div class="h-36 overflow-hidden">
                        <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @else
                    <div class="h-36 bg-gradient-to-br from-blue-500 to-teal-500 flex items-center justify-center">
                        <i class="fa-solid fa-newspaper text-3xl text-white/40"></i>
                    </div>
                    @endif
                    <div class="p-5">
                        @if($related->category)
                        <span class="badge-blue text-[10px] mb-2 inline-block">{{ $related->category->name }}</span>
                        @endif
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-2 group-hover:text-blue-600 transition line-clamp-2">{{ $related->title }}</h3>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500">
                            <span>{{ $related->formatted_date }}</span>
                            <span>{{ $related->reading_time }} min</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-public-layout>
