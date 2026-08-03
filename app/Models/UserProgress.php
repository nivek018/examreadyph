<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'total_attempts', 'correct_count',
        'wrong_count', 'best_score', 'last_attempt_at',
    ];

    protected function casts(): array
    {
        return [
            'best_score' => 'decimal:2',
            'last_attempt_at' => 'datetime',
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

    /**
     * Get accuracy percentage.
     */
    public function getAccuracyAttribute(): float
    {
        $total = $this->correct_count + $this->wrong_count;
        if ($total === 0) return 0;
        return round(($this->correct_count / $total) * 100, 1);
    }
}
