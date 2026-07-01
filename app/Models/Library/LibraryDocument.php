<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'library_category_id',
        'title',
        'slug',
        'type',
        'description',
        'file_path',
        'cover_path',
        'file_size',
        'year',
        'is_free',
        'downloads_count',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LibraryCategory::class, 'library_category_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(LibraryDownload::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
