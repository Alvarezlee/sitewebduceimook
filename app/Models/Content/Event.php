<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'starts_at',
        'ends_at',
        'cover_path',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at->isFuture();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
