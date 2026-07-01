<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_candidate_id',
        'started_at',
        'finished_at',
        'expires_at',
        'status',
        'score',
        'rank',
        'question_order',
        'anti_cheat_flags',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'expires_at' => 'datetime',
            'score' => 'decimal:2',
            'question_order' => 'array',
            'anti_cheat_flags' => 'array',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(QuizCandidate::class, 'quiz_candidate_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(QuizCertificate::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
