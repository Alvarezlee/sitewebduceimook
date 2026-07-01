<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_subject_id',
        'created_by',
        'source',
        'question',
        'explanation',
        'difficulty',
        'is_open_ended',
        'is_validated',
    ];

    protected function casts(): array
    {
        return [
            'is_open_ended' => 'boolean',
            'is_validated' => 'boolean',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(QuizSubject::class, 'quiz_subject_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizQuestionOption::class);
    }

    public function scopeValidated($query)
    {
        return $query->where('is_validated', true);
    }
}
