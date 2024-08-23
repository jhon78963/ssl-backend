<?php

namespace App\Auth\Controllers;

use App\Auth\Requests\ChangePasswordRequest;
use App\Auth\Requests\LoginRequest;
use App\Auth\Requests\RefreshTokenRequest;
use App\Auth\Requests\DeleteTokenRequest;
use App\Auth\Requests\UpdateMeRequest;
use App\Auth\Resources\MeResource;
use App\Auth\Services\UserService;
use Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->userService->validateUser($request);
        $getTokens = $this->userService->createTokens($user);
        return response()->json($getTokens);
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $user = $this->userService->validateRefreshToken($request);
        $getTokens = $this->userService->createTokens($user);
        return response()->json($getTokens);
    }

    public function getMe(): JsonResponse {
        return response()->json(new MeResource(Auth::user()));
    }

    public function updateMe(UpdateMeRequest $request): JsonResponse {
        $this->userService->updateMe($request);
        return response()->json(['message' => 'User updated successfully']);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse {
        $this->userService->changePassword($request);
        return response()->json(['message' => 'Password changed successfully']);
    }

    public function logout(DeleteTokenRequest $request): JsonResponse {
        $this->userService->deleteToken($request);
        return response()->json(['message' => 'Logout successfully']);
    }
}
