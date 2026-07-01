<?php

namespace App\Models\Quiz;

use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class QuizCandidate extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_edition_id',
        'photo_path',
        'institution',
        'class_level',
        'region',
        'department',
        'arrondissement',
        'parent_name',
        'parent_phone',
        'registration_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(QuizEdition::class, 'quiz_edition_id');
    }

    public function attempt(): HasOne
    {
        return $this->hasOne(QuizAttempt::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function isPaid(): bool
    {
        return $this->registration_status === 'paid';
    }
}
