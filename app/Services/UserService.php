<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;

class UserService
{

    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getByEmail(string $email): Model|User|null
    {
        return $this->userRepository->getByEmail($email);
    }

    public function create(array $input): User
    {
        return $this->userRepository->create($input);
    }

    public function setApiToken(User $user, string $token): void
    {
        $this->userRepository->setApiToken($user, $token);
    }

    public function setPasswordRecoveryToken(User $user, string $token): void
    {
        $this->userRepository->setPasswordRecoveryToken($user, $token);
    }

    public function getUserByPasswordRestoreToken(string $token): Model|User|null
    {
        return $this->userRepository->getUserByPasswordRestoreToken($token);
    }

    public function updatePassword(User $user, string $password): void
    {
        $this->userRepository->updatePassword($user, $password);
    }

}
