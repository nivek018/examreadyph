@extends('layouts.admin')

@section('title', 'Blog Categories')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Blog Categories</h1>
        <p class="text-sm text-slate-500">Manage categories for study guide articles.</p>
    </div>
    <a href="{{ route('admin.blog-categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg flex items-center gap-2 transition">
        <i class="fa-solid fa-plus"></i> New Category
    </a>
</div>

<div class="card flat-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase">Category</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Posts</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-center">Status</th>
                <th class="px-5 py-3 text-xs font-bold text-slate-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($categories as $category)
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                <td class="px-5 py-4">
                    <div class="font-semibold text-slate-900 dark:text-white text-sm">{{ $category->name }}</div>
                    <div class="text-xs text-slate-500">{{ $category->slug }}</div>
                </td>
                <td class="px-5 py-4 text-center"><span class="badge-blue text-[10px]">{{ $category->posts_count }}</span></td>
                <td class="px-5 py-4 text-center">
                    @if($category->is_active)
                        <span class="badge-emerald text-[10px]">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[10px] font-bold">Inactive</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.blog-categories.edit', $category) }}" class="text-xs text-blue-600 hover:text-blue-500 font-medium"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <form method="POST" action="{{ route('admin.blog-categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-rose-500 hover:text-rose-400 font-medium"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-10 text-center text-slate-500">No blog categories found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($categories, 'links'))
<div class="mt-4">{{ $categories->withQueryString()->links() }}</div>
@endif
@endsection
