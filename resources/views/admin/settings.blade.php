<x-admin-layout title="System Settings">

    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">System Settings</h2>
        <p class="text-sm text-slate-500 dark:text-slate-400">Configure all platform features. Changes take effect immediately.</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        {{-- Group Tabs --}}
        <div x-data="{ activeTab: '{{ $groups[0] }}' }" class="space-y-6">
            <div class="flex flex-wrap gap-2">
                @foreach($groups as $group)
                <button type="button" @click="activeTab = '{{ $group }}'"
                        :class="activeTab === '{{ $group }}' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'"
                        class="px-4 py-2 rounded-lg text-xs font-bold border capitalize transition">
                    {{ $group }}
                </button>
                @endforeach
            </div>

            @foreach($groups as $group)
            <div x-show="activeTab === '{{ $group }}'" x-transition class="card p-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 capitalize flex items-center gap-2">
                    <i class="fa-solid fa-gear text-blue-500"></i> {{ $group }} Settings
                </h3>

                <div class="space-y-5">
                    @foreach($settings[$group] as $setting)
                    <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6 pb-5 border-b border-slate-200 dark:border-slate-700/50 last:border-0 last:pb-0">
                        <div class="sm:w-1/3">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $setting->label ?? $setting->key }}</label>
                            @if($setting->description)
                            <p class="text-xs text-slate-500 mt-0.5">{{ $setting->description }}</p>
                            @endif
                        </div>
                        <div class="sm:w-2/3">
                            @if($setting->type === 'bool')
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="hidden" name="settings[{{ $setting->key }}]" value="false">
                                    <input type="checkbox" name="settings[{{ $setting->key }}]" value="true"
                                           {{ filter_var($setting->value, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-slate-700 dark:text-slate-300">{{ filter_var($setting->value, FILTER_VALIDATE_BOOLEAN) ? 'Enabled' : 'Disabled' }}</span>
                                </label>
                            @elseif($setting->type === 'text' || $setting->type === 'json')
                                <textarea name="settings[{{ $setting->key }}]" rows="3"
                                          class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition font-mono">{{ $setting->value }}</textarea>
                            @elseif($setting->type === 'int')
                                <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                       class="w-full sm:w-48 px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            @else
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                       class="w-full px-4 py-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        {{-- Save --}}
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-brand px-6 py-2.5 text-sm">
                <i class="fa-solid fa-check"></i> Save All Settings
            </button>
        </div>
    </form>

</x-admin-layout>
