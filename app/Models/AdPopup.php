<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPopup extends Model
{
    protected $fillable = [
        'name', 'image_url', 'destination_url', 'alt_text', 'placement',
        'is_active', 'impressions_count', 'clicks_count', 'sort_order',
        'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Scope for active ads matching placement.
     */
    public function scopeActiveForPlacement($query, string $placement = 'all')
    {
        return $query->where('is_active', true)
            ->where(function ($q) use ($placement) {
                $q->where('placement', 'all')
                  ->orWhere('placement', $placement);
            })
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order');
    }

    /**
     * Calculate Click-Through-Rate (CTR %).
     */
    public function getCtrAttribute(): float
    {
        if ($this->impressions_count === 0) return 0;
        return round(($this->clicks_count / $this->impressions_count) * 100, 2);
    }
}
