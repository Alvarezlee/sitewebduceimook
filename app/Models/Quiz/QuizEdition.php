<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizEdition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'registration_price',
        'starts_at',
        'ends_at',
        'duration_minutes',
        'max_attempts',
        'questions_per_attempt',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'registration_price' => 'decimal:2',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(QuizCandidate::class);
    }

    public function isOpenForRegistration(): bool
    {
        return $this->is_active && now()->between($this->starts_at, $this->ends_at);
    }
}
