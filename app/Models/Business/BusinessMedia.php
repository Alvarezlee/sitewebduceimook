<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessMedia extends Model
{
    protected $fillable = [
        'business_id',
        'type',
        'path_or_url',
        'caption',
        'order',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
