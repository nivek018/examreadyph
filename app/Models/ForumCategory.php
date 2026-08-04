<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order', 'threads_count', 'replies_count'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function threads(): HasMany
    {
        return $this->hasMany(ForumThread::class, 'category_id');
    }

    public function visibleThreads(): HasMany
    {
        return $this->threads()->where('is_spam', false);
    }

    /**
     * Get the latest thread that isn't spam.
     */
    public function latestThread()
    {
        return $this->hasOne(ForumThread::class, 'category_id')
            ->where('is_spam', false)
            ->latest();
    }
}
