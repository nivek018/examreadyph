<x-public-layout
    :metaTitle="$post->meta_title"
    :metaDescription="$post->meta_description"
    ogType="article"
>
    {{-- Schema.org Article Structured Data --}}
    @push('head')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": @json($post->title),
        "description": @json($post->meta_description),
        "author": {
            "@type": "Person",
            "name": @json($post->author->name ?? 'ExamReady PH')
        },
        "publisher": {
            "@type": "Organization",
            "name": "ExamReady PH",
            "url": "{{ url('/') }}"
        },
        "datePublished": "{{ $post->published_at?->toISOString() }}",
        "dateModified": "{{ $post->updated_at->toISOString() }}",
        @if($post->featured_image)
        "image": "{{ asset('storage/' . $post->featured_image) }}",
        @endif
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ route('blog.show', $post) }}"
        }
    }
    </script>
    @endpush

    {{-- Breadcrumb --}}
    <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
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

    <article class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                {{-- Main Article --}}
                <div class="lg:col-span-2">
                    {{-- Header --}}
                    <header class="mb-8">
                        @if($post->category)
                        <a href="{{ route('blog.category', $post->category) }}" class="badge-blue text-[10px] mb-3 inline-block hover:opacity-80 transition">{{ $post->category->name }}</a>
                        @endif

                        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight mb-4">{{ $post->title }}</h1>

                        <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">{{ $post->author?->initials ?? 'A' }}</div>
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $post->author?->name ?? 'Admin' }}</span>
                            </div>
                            <span class="flex items-center gap-1"><i class="fa-regular fa-calendar"></i> {{ $post->formatted_date }}</span>
                            <span class="flex items-center gap-1"><i class="fa-regular fa-clock"></i> {{ $post->reading_time }} min read</span>
                            <span class="flex items-center gap-1"><i class="fa-regular fa-eye"></i> {{ number_format($post->view_count) }} views</span>
                        </div>
                    </header>

                    {{-- Featured Image --}}
                    @if($post->featured_image)
                    <div class="mb-8 rounded-2xl overflow-hidden shadow-md">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[400px] object-cover">
                    </div>
                    @endif

                    {{-- Article Body --}}
                    <div class="prose prose-slate dark:prose-invert max-w-none
                        prose-headings:font-extrabold prose-headings:tracking-tight
                        prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4
                        prose-h3:text-lg prose-h3:mt-8 prose-h3:mb-3
                        prose-p:text-base prose-p:leading-relaxed prose-p:text-slate-700 dark:prose-p:text-slate-300
                        prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:font-semibold
                        prose-li:text-slate-700 dark:prose-li:text-slate-300
                        prose-img:rounded-xl prose-img:shadow-md
                        prose-blockquote:border-blue-500 prose-blockquote:bg-blue-50/50 dark:prose-blockquote:bg-blue-950/20 prose-blockquote:rounded-r-xl prose-blockquote:py-1 prose-blockquote:px-4">
                        {!! $post->body !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <div class="flex flex-wrap items-center gap-2">
                            <i class="fa-solid fa-tags text-slate-400 text-sm"></i>
                            @foreach($post->tags as $tag)
                            <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-400">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Share Buttons --}}
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Share:</span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post)) }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center text-sm hover:bg-blue-700 transition">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post)) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-slate-800 text-white flex items-center justify-center text-sm hover:bg-slate-700 transition">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ route('blog.show', $post) }}'); this.innerHTML='<i class=\'fa-solid fa-check\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-solid fa-link\'></i>', 2000)" class="w-9 h-9 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition">
                                <i class="fa-solid fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="space-y-6">
                    {{-- Exam CTA --}}
                    <div class="card flat-card p-6 bg-gradient-to-br from-blue-50 to-teal-50/30 dark:from-blue-950/30 dark:to-teal-950/20 border-blue-200 dark:border-blue-800/50">
                        <div class="text-center">
                            <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl mx-auto mb-3">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-2">Ready to Practice?</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4">Test your knowledge with our free exam reviewers.</p>
                            <a href="{{ route('reviewers') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2.5 rounded-xl transition text-center">
                                Browse Reviewers
                            </a>
                        </div>
                    </div>

                    {{-- Related Posts --}}
                    @if($relatedPosts->isNotEmpty())
                    <div class="card flat-card p-6">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-arrow-trend-up text-emerald-500"></i> Related Articles
                        </h3>
                        <div class="space-y-4">
                            @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related) }}" class="flex items-start gap-3 group">
                                @if($related->featured_image)
                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="" class="w-16 h-16 rounded-lg object-cover shrink-0">
                                @else
                                <div class="w-16 h-16 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-newspaper text-slate-400"></i>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white group-hover:text-blue-600 transition line-clamp-2">{{ $related->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $related->formatted_date }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Popular Tags --}}
                    @if($popularTags->isNotEmpty())
                    <div class="card flat-card p-6">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-tags text-purple-500"></i> Popular Tags
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                            <span class="px-3 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 transition cursor-default">
                                {{ $tag->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Pro Upgrade CTA --}}
                    <div class="card flat-card p-6 bg-gradient-to-br from-amber-50 to-orange-50/30 dark:from-amber-950/20 dark:to-orange-950/10 border-amber-200 dark:border-amber-800/50">
                        <div class="text-center">
                            <div class="text-3xl mb-2">👑</div>
                            <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-2">Upgrade to Pro</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">Ad-free experience, 50 AI explanations/month, and premium mock exams.</p>
                            <a href="{{ route('pricing') }}" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">View Plans →</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>
</x-public-layout>
