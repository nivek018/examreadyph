<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'total_questions',
        'time_limit_seconds', 'passing_score_percent', 'difficulty',
        'is_premium', 'is_active', 'shuffle_questions', 'shuffle_options',
        'show_explanations', 'allow_review', 'sections_json',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'is_premium' => 'boolean',
            'is_active' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_explanations' => 'boolean',
            'allow_review' => 'boolean',
            'sections_json' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExamCategory::class, 'category_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function activeQuestions(): HasMany
    {
        return $this->hasMany(Question::class)->where('is_active', true);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ExamSession::class);
    }

    /**
     * Get formatted time limit for display.
     */
    public function getFormattedTimeLimitAttribute(): string
    {
        if ($this->time_limit_seconds === 0) {
            return 'No Limit';
        }

        $minutes = floor($this->time_limit_seconds / 60);
        if ($minutes >= 60) {
            $hours = floor($minutes / 60);
            $remaining = $minutes % 60;
            return $remaining > 0 ? "{$hours}h {$remaining}m" : "{$hours}h";
        }

        return "{$minutes} min";
    }

    /**
     * Get the actual question count in the pool.
     */
    public function getPoolCountAttribute(): int
    {
        return $this->activeQuestions()->count();
    }
}
