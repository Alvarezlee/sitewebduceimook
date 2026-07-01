<?php

namespace App\Models\Business;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo_path',
        'description',
        'whatsapp',
        'facebook_url',
        'linkedin_url',
        'website_url',
        'address',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(BusinessService::class)->orderBy('order');
    }

    public function media(): HasMany
    {
        return $this->hasMany(BusinessMedia::class)->orderBy('order');
    }

    public function contactMessages(): HasMany
    {
        return $this->hasMany(BusinessContactMessage::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
