<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
            'shop_name' => $data['role'] === 'user'
                ? null
                : ($data['shopName'] ?? $data['shop_name'] ?? "{$data['name']}'s Shop"),
        ]);

        if ($user->canSellBooks()) {
            $user->shopProfile()->create([
                'name' => $user->shop_name,
                'description' => null,
                'is_active' => true,
            ]);
        }

        return $this->tokenPayload($user->load('shopProfile'));
    }

    public function login(array $credentials): array
    {
        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->tokenPayload($user->load('shopProfile'));
    }

    public function logout(User $user): void
    {
        $user->token()?->revoke();
    }

    private function tokenPayload(User $user): array
    {
        return [
            'user' => $user,
            'token' => $user->createToken('dhamma-sphere')->accessToken,
            'token_type' => 'Bearer',
        ];
    }
}
