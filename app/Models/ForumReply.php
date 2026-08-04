<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumReply extends Model
{
    protected $fillable = ['thread_id', 'user_id', 'body', 'is_spam', 'upvotes_count', 'parent_id'];

    protected function casts(): array
    {
        return ['is_spam' => 'boolean'];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_spam', false);
    }

    public function upvotes()
    {
        return $this->morphMany(ForumUpvote::class, 'upvotable');
    }

    public function isUpvotedBy(?User $user, ?string $ipAddress = null): bool
    {
        if ($user) {
            return $this->upvotes()->where('user_id', $user->id)->exists();
        }
        if ($ipAddress) {
            return $this->upvotes()->where('ip_address', $ipAddress)->exists();
        }
        return false;
    }

    public function scopeVisible($query)
    {
        return $query->where('is_spam', false);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
