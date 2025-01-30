<?php
namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials): ?string
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }

        $user = Auth::user();
        
        // Önceki tokenları temizle
        $user->tokens()->delete();
        
        // Yeni Sanctum token oluştur
        return $user->createToken('auth-token')->plainTextToken;
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function refresh(): ?string
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        
        // Önceki tokenları temizle
        $user->tokens()->delete();
        
        // Yeni token oluştur
        return $user->createToken('auth-token')->plainTextToken;
    }

    public function me(): ?User
    {
        return Auth::user();
    }
}
