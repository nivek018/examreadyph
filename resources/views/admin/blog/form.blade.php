@extends('layouts.admin')

@section('title', $post ? 'Edit Post' : 'New Post')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Posts</a>
    </div>

    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">{{ $post ? 'Edit Post' : 'Create Post' }}</h1>

    <form method="POST" action="{{ $post ? route('admin.blog.update', $post) : route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($post) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="card flat-card p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $post?->title) }}" required placeholder="e.g. 10 Tips to Pass the Civil Service Exam" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                        @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Slug <span class="text-slate-400">(auto-generated if empty)</span></label>
                        <input type="text" name="slug" value="{{ old('slug', $post?->slug) }}" placeholder="10-tips-to-pass-civil-service-exam" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Excerpt <span class="text-slate-400">(preview text)</span></label>
                        <textarea name="excerpt" rows="2" maxlength="500" placeholder="A brief summary that appears in blog cards and search results..." class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">{{ old('excerpt', $post?->excerpt) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Body <span class="text-rose-500">*</span></label>
                        <textarea name="body" id="blog-body" rows="16" required class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">{{ old('body', $post?->body) }}</textarea>
                        @error('body') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- SEO Fields --}}
                <div class="card flat-card p-6 space-y-5">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2"><i class="fa-solid fa-magnifying-glass text-emerald-500"></i> SEO Settings</h3>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">SEO Title <span class="text-slate-400">(overrides post title in search results)</span></label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $post?->seo_title) }}" maxlength="255" placeholder="Custom SEO title..." class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Meta Description <span class="text-slate-400">(max 300 chars)</span></label>
                        <textarea name="seo_description" rows="2" maxlength="300" placeholder="Describe this article for search engines..." class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">{{ old('seo_description', $post?->seo_description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-5">
                {{-- Publish Settings --}}
                <div class="card flat-card p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2"><i class="fa-solid fa-paper-plane text-blue-500"></i> Publish</h3>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                            <option value="draft" {{ old('status', $post?->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post?->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="scheduled" {{ old('status', $post?->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Publish Date</label>
                        <input type="datetime-local" name="published_at" value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $post?->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_featured" class="text-sm font-medium text-slate-900 dark:text-white">Featured Post</label>
                    </div>
                </div>

                {{-- Category --}}
                <div class="card flat-card p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2"><i class="fa-solid fa-folder text-amber-500"></i> Category</h3>
                    <select name="category_id" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                        <option value="">Uncategorized</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $post?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tags --}}
                <div class="card flat-card p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2"><i class="fa-solid fa-tags text-teal-500"></i> Tags</h3>
                    <input type="text" name="tags" value="{{ old('tags', $post ? $post->tags->pluck('name')->implode(', ') : '') }}" placeholder="e.g. civil service, tips, reviewer" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
                    <p class="text-xs text-slate-500">Separate tags with commas.</p>
                </div>

                {{-- Featured Image --}}
                <div class="card flat-card p-6 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2"><i class="fa-solid fa-image text-purple-500"></i> Featured Image</h3>
                    @if($post?->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Current featured image" class="w-full h-32 object-cover rounded-lg">
                    @endif
                    <input type="file" name="featured_image" accept="image/*" class="w-full text-sm text-slate-600 dark:text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/40 dark:file:text-blue-300 hover:file:bg-blue-100">
                </div>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-8 py-3 rounded-lg transition shadow-md">
            <i class="fa-solid fa-check mr-1"></i> {{ $post ? 'Update Post' : 'Create Post' }}
        </button>
    </form>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#blog-body',
        height: 450,
        menubar: false,
        skin: document.documentElement.classList.contains('dark') ? 'oxide-dark' : 'oxide',
        content_css: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
        plugins: 'lists link image table code wordcount autolink',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image table | code',
        branding: false,
    });
</script>
@endpush
@endsection
