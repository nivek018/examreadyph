<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_banned',
        'ai_questions_used',
        'ai_questions_reset_at',
        'ai_bonus_credits',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'ai_questions_reset_at' => 'datetime',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has an active premium subscription.
     */
    public function isPremium(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Get user's active subscription.
     */
    public function activeSubscription()
    {
        return $this->hasOne(\App\Models\Subscription::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest();
    }

    /**
     * Get all subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    /**
     * Get user's exam sessions.
     */
    public function examSessions()
    {
        return $this->hasMany(\App\Models\ExamSession::class);
    }

    /**
     * Get user's progress records.
     */
    public function progress()
    {
        return $this->hasMany(\App\Models\UserProgress::class);
    }

    /**
     * Get user initials for avatar fallback.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    /**
     * Get user's avatar URL.
     * Defaults to a DiceBear SVG avatar using user name/email as seed.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }

        $seed = md5($this->email ?? $this->name ?? ('Examinee' . $this->id));
        return "https://api.dicebear.com/7.x/personas/svg?seed={$seed}";
    }

    // ─── AI Credit System ────────────────────────────────────────────

    /**
     * Get the monthly AI credit limit for this user.
     *
     * Guest (no account): 2 per session (handled client-side)
     * Registered (free, no subscription): 2 per month
     * Paid subscriber (Pro/VIP): 50 per month
     */
    public function getAiCreditLimit(): int
    {
        if ($this->isPremium()) {
            // Use the plan's ai_question_limit if available
            $sub = $this->activeSubscription()->with('plan')->first();
            if ($sub && $sub->plan && $sub->plan->ai_question_limit) {
                return (int) $sub->plan->ai_question_limit;
            }
            return 50;
        }

        return 2;
    }

    /**
     * Get remaining AI credits (monthly allocation + bonus purchased credits).
     */
    public function getRemainingAiCredits(): int
    {
        $this->resetAiCreditsIfNeeded();

        $monthlyLimit = $this->getAiCreditLimit();
        $used = (int) $this->ai_questions_used;
        $bonus = (int) $this->ai_bonus_credits;

        $remaining = ($monthlyLimit - $used) + $bonus;
        return max(0, $remaining);
    }

    /**
     * Get total AI credits used this period.
     */
    public function getAiCreditsUsed(): int
    {
        $this->resetAiCreditsIfNeeded();
        return (int) $this->ai_questions_used;
    }

    /**
     * Attempt to consume 1 AI credit.
     * Returns true if successful, false if no credits remaining.
     */
    public function consumeAiCredit(): bool
    {
        $this->resetAiCreditsIfNeeded();

        $monthlyLimit = $this->getAiCreditLimit();
        $used = (int) $this->ai_questions_used;
        $bonus = (int) $this->ai_bonus_credits;

        // First consume from monthly allocation
        if ($used < $monthlyLimit) {
            $this->increment('ai_questions_used');
            return true;
        }

        // Then consume from bonus credits
        if ($bonus > 0) {
            $this->decrement('ai_bonus_credits');
            return true;
        }

        return false;
    }

    /**
     * Check if monthly AI credits should be reset and reset if needed.
     *
     * For paid subscribers: resets on subscription renewal cycle (monthly from starts_at).
     * For free users: resets on the 1st of each month.
     */
    public function resetAiCreditsIfNeeded(): void
    {
        $resetAt = $this->ai_questions_reset_at;

        // First time using AI — set the initial reset date
        if (!$resetAt) {
            $this->update([
                'ai_questions_used' => 0,
                'ai_questions_reset_at' => now()->addMonth()->startOfMonth(),
            ]);
            return;
        }

        // If the reset date has passed, reset credits
        if (now()->gte($resetAt)) {
            // Calculate the next reset date
            $nextReset = $this->isPremium()
                ? now()->addMonth() // Paid: 30 days from now
                : now()->addMonth()->startOfMonth(); // Free: 1st of next month

            $this->update([
                'ai_questions_used' => 0,
                'ai_questions_reset_at' => $nextReset,
            ]);
        }
    }
}
