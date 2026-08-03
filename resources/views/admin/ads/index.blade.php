<x-admin-layout title="Ad Campaigns">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Ad Campaigns & Popups</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manage promotional banners and Lazada/Shopee ad popups displayed to users.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.settings') }}#ads" class="btn-brand-outline px-4 py-2.5 text-sm">
                <i class="fa-solid fa-sliders"></i> Ad Timing Settings
            </a>
            <a href="{{ route('admin.ads.create') }}" class="btn-brand px-4 py-2.5 text-sm">
                <i class="fa-solid fa-plus"></i> New Ad Campaign
            </a>
        </div>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Ad Campaign</th>
                    <th class="text-left px-5 py-3 text-xs font-bold text-slate-500 uppercase">Placement</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Impressions</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Clicks</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">CTR %</th>
                    <th class="text-center px-5 py-3 text-xs font-bold text-slate-500 uppercase">Status</th>
                    <th class="text-right px-5 py-3 text-xs font-bold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($ads as $ad)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                    <td class="px-5 py-4 max-w-xs">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($ad->image_url) }}" alt="{{ $ad->name }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200 dark:border-slate-700 shrink-0">
                            <div class="truncate">
                                <div class="font-bold text-slate-900 dark:text-white truncate">{{ $ad->name }}</div>
                                <a href="{{ $ad->destination_url }}" target="_blank" class="text-xs text-blue-500 hover:underline truncate block max-w-[200px]">{{ $ad->destination_url }}</a>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-xs font-semibold text-slate-700 dark:text-slate-300">
                        <span class="badge-blue uppercase text-[10px]">{{ $ad->placement }}</span>
                    </td>
                    <td class="px-5 py-4 text-center font-semibold text-slate-900 dark:text-white">{{ number_format($ad->impressions_count) }}</td>
                    <td class="px-5 py-4 text-center font-semibold text-slate-900 dark:text-white">{{ number_format($ad->clicks_count) }}</td>
                    <td class="px-5 py-4 text-center font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($ad->ctr, 2) }}%</td>
                    <td class="px-5 py-4 text-center">
                        @if($ad->is_active)
                            <span class="badge-emerald text-[10px]">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-slate-200 dark:bg-slate-700 text-slate-500 text-[10px] font-bold">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.ads.edit', $ad) }}" class="text-blue-600 hover:text-blue-500 text-xs font-medium"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                            <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" onsubmit="return confirm('Delete this ad campaign?')">
                                @csrf @method('DELETE')
                                <button class="text-rose-500 hover:text-rose-400 text-xs font-medium"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-slate-500">
                        <i class="fa-solid fa-rectangle-ad text-3xl mb-3 text-slate-400"></i>
                        <p class="font-medium">No ad campaigns found. <a href="{{ route('admin.ads.create') }}" class="text-blue-600">Create one</a> to start serving ads.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin-layout>
