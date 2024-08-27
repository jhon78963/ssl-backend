<?php

namespace App\Auth\Controllers;

use App\Auth\Requests\ChangePasswordRequest;
use App\Auth\Requests\LoginRequest;
use App\Auth\Requests\RefreshTokenRequest;
use App\Auth\Requests\DeleteTokenRequest;
use App\Auth\Requests\UpdateMeRequest;
use App\Auth\Resources\MeResource;
use App\Auth\Services\AuthService;
use App\User\Models\User;
use App\Shared\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where("username", $request->username)->first();
        $validatedUser = $this->authService->validateUser($request->password, $user);
        $getTokens = $this->authService->createTokens($validatedUser);
        return response()->json($getTokens);
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $user = $this->authService->validateRefreshToken($request);
        $this->authService->deleteToken($request->user());
        $getTokens = $this->authService->createTokens($user);
        return response()->json($getTokens);
    }

    public function getMe(): JsonResponse {
        return response()->json(new MeResource(Auth::user()));
    }

    public function updateMe(UpdateMeRequest $request): JsonResponse {
        $this->authService->updateMe($request);
        return response()->json(['message' => 'User updated successfully']);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse {
        $this->authService->changePassword($request->user(), $request->password);
        return response()->json(['message' => 'Password changed successfully']);
    }

    public function logout(DeleteTokenRequest $request): JsonResponse {
        $this->authService->deleteToken($request->user());
        return response()->json(['message' => 'Logout successfully']);
    }
}