<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessService extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'description',
        'icon',
        'order',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
