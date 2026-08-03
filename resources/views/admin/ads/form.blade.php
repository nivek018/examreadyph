<x-admin-layout :title="$ad->exists ? 'Edit Ad Campaign' : 'Create Ad Campaign'">

    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('admin.ads.index') }}" class="text-sm text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Back to Ad Campaigns
            </a>
        </div>

        <div class="card p-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6">{{ $ad->exists ? 'Edit Ad Campaign' : 'Create New Ad Campaign' }}</h2>

            <form method="POST" action="{{ $ad->exists ? route('admin.ads.update', $ad) : route('admin.ads.store') }}" enctype="multipart/form-data">
                @csrf
                @if($ad->exists) @method('PUT') @endif

                {{-- Campaign Name --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Campaign Name *</label>
                    <input type="text" name="name" value="{{ old('name', $ad->name) }}" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 transition"
                           placeholder="e.g., Lazada 3.3 Super Sale Promo">
                </div>

                {{-- Image File / URL --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Ad Banner Image</label>
                    <div class="space-y-3">
                        <div>
                            <input type="file" name="image" accept="image/*"
                                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm">
                            <p class="text-xs text-slate-500 mt-1">Upload image file (PNG, JPG, WebP max 2MB).</p>
                        </div>
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider text-center">— OR —</div>
                        <div>
                            <input type="url" name="image_url_input" value="{{ old('image_url_input', $ad->image_url) }}"
                                   class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 transition"
                                   placeholder="e.g., https://example.com/images/banner.jpg">
                            <p class="text-xs text-slate-500 mt-1">Direct image URL link.</p>
                        </div>
                    </div>
                </div>

                {{-- Destination URL --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Destination URL (Lazada / Shopee Affiliate Link) *</label>
                    <input type="url" name="destination_url" value="{{ old('destination_url', $ad->destination_url) }}" required
                           class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-500 transition"
                           placeholder="https://c.lazada.com.ph/t/c.Yxxxx">
                </div>

                {{-- Placement --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Ad Placement *</label>
                    <select name="placement" required class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white text-sm">
                        @foreach(['all' => 'All Pages', 'exam' => 'Exam Engine Only', 'browse' => 'Browse Reviewers Only', 'forum' => 'Forum Pages Only'] as $val => $label)
                        <option value="{{ $val }}" {{ old('placement', $ad->placement ?? 'all') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Dates & Sort Order --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Start Date (Optional)</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at', $ad->starts_at ? $ad->starts_at->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">End Date (Optional)</label>
                        <input type="date" name="ends_at" value="{{ old('ends_at', $ad->ends_at ? $ad->ends_at->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $ad->sort_order ?? 0) }}" min="0"
                               class="w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs text-slate-900 dark:text-white">
                    </div>
                </div>

                {{-- Active Toggle --}}
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ad->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Active (serving to users)</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-brand px-6 py-2.5 text-sm">
                        <i class="fa-solid fa-check"></i> {{ $ad->exists ? 'Update Campaign' : 'Create Campaign' }}
                    </button>
                    <a href="{{ route('admin.ads.index') }}" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-admin-layout>
