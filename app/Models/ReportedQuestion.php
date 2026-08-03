<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportedQuestion extends Model
{
    protected $fillable = [
        'question_id', 'user_id', 'reason', 'description',
        'status', 'resolved_by', 'admin_notes',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function getFormattedReasonAttribute(): string
    {
        return match ($this->reason) {
            'incorrect_answer' => 'Wrong Answer / Answer Key Error',
            'incorrect_grammar' => 'Incorrect Grammar / Typo',
            'outdated' => 'Outdated Information',
            'unclear' => 'Unclear / Confusing Question',
            'duplicate' => 'Duplicate Question',
            'other' => 'Other Issue',
            default => ucfirst(str_replace('_', ' ', $this->reason)),
        };
    }
}
