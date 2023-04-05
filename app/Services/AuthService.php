<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class AuthService
{

    public function generateToken(): string
    {
        return Str::random(40);
    }

    public function getCurrentUser(): User
    {
        return app('auth')->user();
    }

    public function generatePasswordRecoveryToken(): string
    {
        return Str::random(60);
    }

}
