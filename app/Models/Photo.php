<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected $fillable = [
        'photo_session_id',
        'filename',
        'path',
        'url',
        'storage_driver',
        'public_id',
        'shot_number',
        'is_collage',
    ];

    protected $casts = [
        'is_collage' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PhotoSession::class, 'photo_session_id');
    }
}
