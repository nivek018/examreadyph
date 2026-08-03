<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'color_class', 'description', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'category_id');
    }

    public function activeExams(): HasMany
    {
        return $this->hasMany(Exam::class, 'category_id')->where('is_active', true);
    }

    /**
     * Get the total question count across all exams in this category.
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->exams->sum(fn($exam) => $exam->questions()->count());
    }
}
