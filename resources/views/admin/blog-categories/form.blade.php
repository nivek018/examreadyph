@extends('layouts.admin')

@section('title', $category ? 'Edit Blog Category' : 'New Blog Category')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.blog-categories.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Categories</a>
    </div>

    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">{{ $category ? 'Edit Blog Category' : 'Create Blog Category' }}</h1>

    <form method="POST" action="{{ $category ? route('admin.blog-categories.update', $category) : route('admin.blog-categories.store') }}" class="card flat-card p-6 space-y-5">
        @csrf
        @if($category) @method('PUT') @endif

        <div>
            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Category Name <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $category?->name) }}" required placeholder="e.g. Civil Service Tips" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Slug <span class="text-slate-400">(auto-generated if empty)</span></label>
            <input type="text" name="slug" value="{{ old('slug', $category?->slug) }}" placeholder="civil-service-tips" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Description</label>
            <textarea name="description" rows="3" placeholder="Brief description of this blog category..." class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">{{ old('description', $category?->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" min="0" class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white text-sm">
            </div>
            <div class="flex items-end pb-1">
                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="is_active" class="text-sm font-medium text-slate-900 dark:text-white">Active</label>
                </div>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-lg transition w-full">
            <i class="fa-solid fa-check mr-1"></i> {{ $category ? 'Update Category' : 'Create Category' }}
        </button>
    </form>
</div>
@endsection
