<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class QuestionLimitService
{
    public function __construct(protected SettingsService $settings) {}

    /**
     * Get user's daily question limit.
     */
    public function getDailyLimit(): int
    {
        return (int) $this->settings->get('free_question_limit_per_day', 50);
    }

    /**
     * Count questions answered by user today.
     */
    public function getAnsweredTodayCount(User $user): int
    {
        if ($user->isPremium()) return 0; // Unlimited for premium

        return Cache::remember("user.{$user->id}.answered_today." . now()->format('Y-m-d'), 3600, function () use ($user) {
            return ExamSession::where('user_id', $user->id)
                ->whereDate('created_at', now())
                ->sum('correct_count') + ExamSession::where('user_id', $user->id)
                ->whereDate('created_at', now())
                ->sum('wrong_count');
        });
    }

    /**
     * Check if user has reached daily free question limit.
     */
    public function hasReachedDailyLimit(User $user): bool
    {
        if ($user->isPremium()) return false;

        $limit = $this->getDailyLimit();
        if ($limit === 0) return false; // 0 = unlimited

        return $this->getAnsweredTodayCount($user) >= $limit;
    }
}
