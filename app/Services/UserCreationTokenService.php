<?php

namespace App\Services;

use App\Models\UserCreationToken;
use Illuminate\Support\Str;

class UserCreationTokenService
{
    public function generate(): UserCreationToken
    {
        return UserCreationToken::create([
            'token' => Str::random(64),
            'expires_at' => now()->addMinutes(40),
        ]);
    }

    public function validate(string $token): bool
    {
        $tokenModel = UserCreationToken::where('token', $token)->first();

        if (!$tokenModel || !$tokenModel->isValid()) {
            return false;
        }
        
        $tokenModel->used = true;
        $tokenModel->save();
        
        return true;
    }
}
