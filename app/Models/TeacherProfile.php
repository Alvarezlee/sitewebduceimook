<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'institution',
        'bio',
        'bureau_role',
        'bureau_order',
        'is_bureau_member',
    ];

    protected function casts(): array
    {
        return [
            'is_bureau_member' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
