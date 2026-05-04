<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\AuthService;
use App\Traits\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ResponseHelper;

    public function __construct(private readonly AuthService $authService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $this->authService->register($request->validated());

        return $this->responseSucceed([
            'user' => new UserResource($payload['user']),
            'token' => $payload['token'],
            'token_type' => $payload['token_type'],
        ], 'Registered successfully.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $this->authService->login($request->validated());

        return $this->responseSucceed([
            'user' => new UserResource($payload['user']),
            'token' => $payload['token'],
            'token_type' => $payload['token_type'],
        ], 'Login successfully.');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->responseSucceed([
            'user' => new UserResource($request->user()->load('shopProfile')),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return $this->responseSucceed(message: 'Logged out successfully.');
    }
}
