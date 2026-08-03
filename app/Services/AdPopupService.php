<?php

namespace App\Services;

use App\Models\AdPopup;
use App\Models\User;

class AdPopupService
{
    public function __construct(protected SettingsService $settings) {}

    /**
     * Determine if ads should be displayed to a user.
     */
    public function shouldShowAds(?User $user): bool
    {
        if (!$this->settings->get('ads_enabled', true)) {
            return false;
        }

        $showToPremium = $this->settings->get('ads_show_to_premium_users', false);

        if ($user && $user->isPremium() && !$showToPremium) {
            return false;
        }

        return true;
    }

    /**
     * Get ad configuration settings for frontend Alpine JS engine.
     */
    public function getAdConfig(?User $user, string $placement = 'exam'): array
    {
        $shouldShow = $this->shouldShowAds($user);

        if (!$shouldShow) {
            return ['enabled' => false];
        }

        $ads = AdPopup::activeForPlacement($placement)->get();

        if ($ads->isEmpty()) {
            return ['enabled' => false];
        }

        return [
            'enabled' => true,
            'ads' => $ads->map(fn($ad) => [
                'id' => $ad->id,
                'name' => $ad->name,
                'image_url' => asset($ad->image_url),
                'destination_url' => $ad->destination_url,
                'alt_text' => $ad->alt_text ?? $ad->name,
            ])->values(),
            'show_after_questions' => (int) $this->settings->get('ads_show_after_questions', 5),
            'initial_delay_seconds' => (int) $this->settings->get('ads_initial_delay_seconds', 60),
            'interval_seconds' => (int) $this->settings->get('ads_interval_seconds', 180),
            'max_per_session' => (int) $this->settings->get('ads_max_per_session', 3),
            'auto_dismiss_seconds' => (int) $this->settings->get('ads_auto_dismiss_seconds', 10),
            'upgrade_cta_text' => $this->settings->get('ads_upgrade_cta_text', 'Upgrade to Premium for an ad-free experience!'),
        ];
    }

    /**
     * Record impression count for an ad.
     */
    public function recordImpression(int $adId): void
    {
        AdPopup::where('id', $adId)->increment('impressions_count');
    }

    /**
     * Record click count for an ad.
     */
    public function recordClick(int $adId): void
    {
        AdPopup::where('id', $adId)->increment('clicks_count');
    }
}
