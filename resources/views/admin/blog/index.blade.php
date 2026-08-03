@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Blog Posts</h1>
        <p class="text-sm text-slate-500">Manage study guide articles and SEO content.</p>
    </div>
    <a href="{{ route('admin.blog.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
        <i class="fa-solid fa-plus"></i> New Post
    </a>
</div>

{{-- Filters --}}
<div class="card flat-card p-4 mb-6 flex flex-wrap gap-3 items-center">
    <form method="GET" class="flex flex-wrap gap-3 items-center w-full">
        <select name="status" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            <option value="">All Statuses</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
        </select>
        <select name="category_id" onchange="this.form.submit()" class="px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="flex-1 min-w-[200px] px-3 py-2 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
        <button type="submit" class="bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold"><i class="fa-solid fa-search"></i></button>
    </form>
</div>

{{-- Table --}}
<div class="card flat-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase">Post</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Category</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Views</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Date</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($posts as $post)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0">
                        @else
                        <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        @endif
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-900 dark:text-white text-sm truncate max-w-[300px]">{{ $post->title }}</div>
                            <div class="text-xs text-slate-500">by {{ $post->author->name ?? 'Unknown' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ $post->category->name ?? '—' }}</td>
                <td class="px-5 py-4 text-center">
                    @if($post->status === 'published')
                        <span class="badge-emerald text-[10px]">Published</span>
                    @elseif($post->status === 'scheduled')
                        <span class="badge-amber text-[10px]">Scheduled</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-bold">Draft</span>
                    @endif
                    @if($post->is_featured)
                        <span class="badge-purple text-[10px] ml-1">Featured</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ number_format($post->view_count) }}</td>
                <td class="px-5 py-4 text-center text-xs text-slate-600 dark:text-slate-400">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        @if($post->status === 'published')
                        <a href="{{ route('blog.show', $post) }}" target="_blank" class="text-xs text-emerald-600 hover:text-emerald-500 font-medium"><i class="fa-solid fa-eye"></i></a>
                        @endif
                        <a href="{{ route('admin.blog.edit', $post) }}" class="text-xs text-blue-600 hover:text-blue-500 font-medium"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" onsubmit="return confirm('Delete this post?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-rose-500 hover:text-rose-400 font-medium"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-10 text-center text-slate-500">No blog posts found. <a href="{{ route('admin.blog.create') }}" class="text-blue-500 hover:underline">Create your first post.</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($posts, 'links'))
<div class="mt-4">{{ $posts->withQueryString()->links() }}</div>
@endif
@endsection
