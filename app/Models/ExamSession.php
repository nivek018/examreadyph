<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamSession extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'started_at', 'finished_at', 'expires_at',
        'time_limit_seconds', 'current_question_index', 'status',
        'score', 'total_questions', 'correct_count', 'wrong_count',
        'unanswered_count', 'question_order_json',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'expires_at' => 'datetime',
            'score' => 'decimal:2',
            'question_order_json' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class, 'session_id');
    }

    /**
     * Check if session is still active (not expired, not completed).
     */
    public function isActive(): bool
    {
        if ($this->status !== 'in_progress') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining seconds on the timer.
     */
    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->expires_at) {
            return 0;
        }

        $remaining = now()->diffInSeconds($this->expires_at, false);
        return max(0, (int) $remaining);
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentAttribute(): float
    {
        if ($this->total_questions === 0) return 0;
        $answered = $this->answers()->whereNotNull('selected_option_id')->count();
        return round(($answered / $this->total_questions) * 100, 1);
    }
}
