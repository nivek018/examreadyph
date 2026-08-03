<x-admin-layout :title="$category->exists ? 'Edit Category' : 'Create Category'">

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Categories
            </a>
        </div>

        <div class="card p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">{{ $category->exists ? 'Edit Category' : 'Create New Category' }}</h2>

            <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
                @csrf
                @if($category->exists) @method('PUT') @endif

                {{-- Name --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Category Name *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="e.g., College Entrance">
                    @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Icon --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Font Awesome Icon Class *</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon ?? 'fa-solid fa-layer-group') }}" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="fa-solid fa-graduation-cap">
                    <p class="text-xs text-slate-500 mt-1">Find icons at <a href="https://fontawesome.com/icons" target="_blank" class="text-blue-500">fontawesome.com/icons</a></p>
                </div>

                {{-- Color Class --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Badge Color Class *</label>
                    <select name="color_class"
                            class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @foreach(['badge-blue' => 'Blue', 'badge-amber' => 'Amber', 'badge-purple' => 'Purple', 'badge-teal' => 'Teal', 'badge-emerald' => 'Emerald'] as $class => $label)
                        <option value="{{ $class }}" {{ old('color_class', $category->color_class) === $class ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Brief description of this category...">{{ old('description', $category->description) }}</textarea>
                </div>

                {{-- Sort Order --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" min="0"
                           class="w-32 px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                {{-- Active Toggle --}}
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active (visible to users)</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-brand px-6 py-2.5 text-sm">
                        <i class="fa-solid fa-check"></i> {{ $category->exists ? 'Update Category' : 'Create Category' }}
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
