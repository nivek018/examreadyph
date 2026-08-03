<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'slug', 'price', 'duration_days', 'features_json',
        'question_limit', 'ai_question_limit', 'is_active', 'sort_order', 'badge_color',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features_json' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
