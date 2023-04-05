<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository
{

    public function getByEmail(string $email): Model|User|null
    {
        return User::query()->where('email', $email)->first();
    }

    public function create(array $input): User
    {
        $user = new User($input);
        $user->save();

        return $user;
    }

    public function setApiToken(User $user, string $token): void
    {
        $user->setAttribute('api_token', $token);
        $user->save();
    }

    public function setPasswordRecoveryToken(User $user, string $token): void
    {
        $user->setAttribute('password_restore_token', $token);
        $user->save();
    }

    public function getUserByPasswordRestoreToken(string $token
    ): Model|User|null {
        return User::query()->where('password_restore_token', $token)->first();
    }

    public function updatePassword(User $user, string $password): void
    {
        $user->setAttribute('password', $password);
        $user->save();
    }

}
