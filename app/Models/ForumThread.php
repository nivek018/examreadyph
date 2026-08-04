<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ForumThread extends Model
{
    protected $fillable = [
        'category_id', 'user_id', 'title', 'slug', 'body',
        'is_pinned', 'is_locked', 'is_spam',
        'views_count', 'replies_count', 'upvotes_count',
        'last_reply_at', 'last_reply_user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'is_spam' => 'boolean',
            'last_reply_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (self $thread) {
            if (empty($thread->slug)) {
                $base = Str::slug($thread->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $thread->slug = $slug;
            }
        });
    }

    // ── Relationships ─────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'thread_id');
    }

    public function visibleReplies(): HasMany
    {
        return $this->replies()->where('is_spam', false);
    }

    public function lastReplyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_reply_user_id');
    }

    public function upvotes()
    {
        return $this->morphMany(ForumUpvote::class, 'upvotable');
    }

    public function isUpvotedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->upvotes()->where('user_id', $user->id)->exists();
    }

    // ── Scopes ────────────────────────────────────────────────

    public function scopeVisible($query)
    {
        return $query->where('is_spam', false);
    }

    public function scopePinnedFirst($query)
    {
        return $query->orderByDesc('is_pinned')->latest('last_reply_at')->latest('created_at');
    }

    // ── Accessors ─────────────────────────────────────────────

    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getLastActivityAttribute(): string
    {
        return ($this->last_reply_at ?? $this->created_at)->diffForHumans();
    }
}
