<x-admin-layout title="Exam Categories">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Exam Categories</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manage top-level exam groups (e.g., College Entrance, Civil Service).</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-brand px-4 py-2.5 text-sm">
            <i class="fa-solid fa-plus"></i> New Category
        </a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Icon</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Exams</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Order</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($categories as $cat)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                    <td class="px-5 py-4">
                        <div class="font-semibold text-slate-900 dark:text-white">{{ $cat->name }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $cat->slug }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <i class="{{ $cat->icon }} {{ $cat->color_class }} text-lg"></i>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="badge-blue">{{ $cat->exams_count }}</span>
                    </td>
                    <td class="px-5 py-4 text-center text-slate-500">{{ $cat->sort_order }}</td>
                    <td class="px-5 py-4 text-center">
                        @if($cat->is_active)
                            <span class="badge-emerald">Active</span>
                        @else
                            <span class="px-2.5 py-1 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-xs font-bold">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="text-blue-600 hover:text-blue-500 text-xs font-medium"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-500 hover:text-rose-400 text-xs font-medium"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-slate-500 dark:text-slate-400">
                        <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-400"></i>
                        <p class="font-medium">No categories yet. <a href="{{ route('admin.categories.create') }}" class="text-blue-600">Create one</a>.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
