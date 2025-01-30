<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function getAllUsers(): LengthAwarePaginator
    {
        return $this->userRepository->getAllUsers();
    }
} 