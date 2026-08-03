<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Cache duration in seconds (1 hour).
     */
    protected const CACHE_TTL = 3600;

    /**
     * Cache key prefix.
     */
    protected const CACHE_PREFIX = 'settings.';

    /**
     * Get a setting value (cached).
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember(
            self::CACHE_PREFIX . $key,
            self::CACHE_TTL,
            fn() => SystemSetting::get($key, $default)
        );
    }

    /**
     * Set a setting value and bust cache.
     */
    public function set(string $key, mixed $value): void
    {
        SystemSetting::set($key, $value);
        Cache::forget(self::CACHE_PREFIX . $key);
    }

    /**
     * Get all settings for a group (cached).
     */
    public function getGroup(string $group): array
    {
        return Cache::remember(
            self::CACHE_PREFIX . 'group.' . $group,
            self::CACHE_TTL,
            function () use ($group) {
                $settings = SystemSetting::where('group', $group)->get();
                $result = [];
                foreach ($settings as $setting) {
                    $result[$setting->key] = [
                        'value' => SystemSetting::get($setting->key),
                        'label' => $setting->label,
                        'description' => $setting->description,
                        'type' => $setting->type,
                    ];
                }
                return $result;
            }
        );
    }

    /**
     * Bust all caches for a group.
     */
    public function bustGroupCache(string $group): void
    {
        Cache::forget(self::CACHE_PREFIX . 'group.' . $group);

        $settings = SystemSetting::where('group', $group)->pluck('key');
        foreach ($settings as $key) {
            Cache::forget(self::CACHE_PREFIX . $key);
        }
    }

    /**
     * Bust all settings cache.
     */
    public function bustAllCache(): void
    {
        $groups = ['general', 'exam', 'freemium', 'payment', 'ai', 'seo', 'forum', 'ads'];
        foreach ($groups as $group) {
            $this->bustGroupCache($group);
        }
    }
}
