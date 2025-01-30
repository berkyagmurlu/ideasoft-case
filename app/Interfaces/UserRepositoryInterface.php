<?php
namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function getAllUsers(): \Illuminate\Pagination\LengthAwarePaginator;
}
