<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryPlan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'max_downloads',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(LibrarySubscription::class);
    }

    public function hasUnlimitedDownloads(): bool
    {
        return $this->max_downloads === null;
    }
}
