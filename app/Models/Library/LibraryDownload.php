<?php

namespace App\Models\Library;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryDownload extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'library_document_id',
        'library_subscription_id',
        'ip_address',
        'downloaded_at',
    ];

    protected function casts(): array
    {
        return [
            'downloaded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(LibraryDocument::class, 'library_document_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(LibrarySubscription::class, 'library_subscription_id');
    }
}
