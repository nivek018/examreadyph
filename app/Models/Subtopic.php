<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subtopic extends Model
{
    protected $fillable = [
        'exam_id', 'name', 'slug', 'description', 'icon',
        'sort_order', 'question_count_cache', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function activeQuestions(): HasMany
    {
        return $this->hasMany(Question::class)->where('is_active', true);
    }

    /**
     * Refresh the cached question count.
     */
    public function refreshQuestionCount(): void
    {
        $this->update(['question_count_cache' => $this->activeQuestions()->count()]);
    }
}
