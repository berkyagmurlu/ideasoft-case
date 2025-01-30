<?php
namespace App\Interfaces;

use App\Models\User;

interface AuthServiceInterface
{
    public function login(array $credentials): ?string;
    public function register(array $data): User;
    public function logout(): void;
    public function refresh(): ?string;
    public function me(): ?User;
}
