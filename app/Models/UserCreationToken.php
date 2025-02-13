<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCreationToken extends Model
{
    protected $fillable = [
        'token', 
        'used', 
        'expires_at'
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime'
    ];

    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }
}
