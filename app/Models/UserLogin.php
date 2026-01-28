<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLogin extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'browser',
        'platform',
        'user_agent',
        'logged_in_at',
        'logged_out_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIsActiveAttribute(): bool
    {
        return is_null($this->logged_out_at);
    }
}
