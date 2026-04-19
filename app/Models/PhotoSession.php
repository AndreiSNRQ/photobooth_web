<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PhotoSession extends Model
{
    protected $fillable = [
        'session_id',
        'template',
        'total_shots',
        'status',
        'customer_email',
        'customer_name',
        'qr_code_url',
        'gallery_url',
        'email_sent',
        'expires_at',
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function collage()
    {
        return $this->photos()->where('is_collage', true)->first();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
