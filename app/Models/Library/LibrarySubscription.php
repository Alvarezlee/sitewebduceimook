<?php

namespace App\Models\Library;

use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LibrarySubscription extends Model
{
    protected $fillable = [
        'user_id',
        'library_plan_id',
        'starts_at',
        'ends_at',
        'downloads_used',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(LibraryPlan::class, 'library_plan_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(LibraryDownload::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    public function hasRemainingDownloads(): bool
    {
        if ($this->plan->hasUnlimitedDownloads()) {
            return true;
        }

        return $this->downloads_used < $this->plan->max_downloads;
    }
}
