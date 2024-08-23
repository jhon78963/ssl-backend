<?php

namespace App\Auth\Services;

use App\Auth\Enums\TokenAbility;
use App\Auth\Models\PersonalAccessToken;
use App\Auth\Requests\ChangePasswordRequest;
use App\Auth\Requests\DeleteTokenRequest;
use App\Auth\Requests\LoginRequest;
use App\Auth\Requests\RefreshTokenRequest;
use App\Auth\Requests\UpdateMeRequest;
use App\User\Models\User;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Hash;

class UserService
{
    public function validateUser(LoginRequest $request): User
    {
        $user = User::where("username", $request->username)->first();
        if ( !$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user;
    }

    public function updateMe(UpdateMeRequest $request): void {
        $request->user()->fill(
            $request->only(['username', 'email', 'name', 'surname'])
        )->save();
    }

    public function changePassword(ChangePasswordRequest $request): void {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();
    }

    public function createTokens(User $user): array
    {
        $user->tokens()->delete();
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.access_token_expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
        );

        return $this->generateTokenResponse($accessToken, $refreshToken);
    }

    public function deleteToken(DeleteTokenRequest $request): void
    {
        $request->user()->tokens()->delete();
    }

    public function validateRefreshToken(RefreshTokenRequest $request): User
    {
        $validateToken = PersonalAccessToken::findToken($request->refreshToken);
        if ( !$validateToken) {
            throw ValidationException::withMessages([
                'message' => 'Invalid token.',
            ]);
        }

        return $request->user();
    }

    public function generateTokenResponse($accessToken, $refreshToken): array
    {
        return [
            'token' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
            'expirationToken'=> $this->calculateExpirationInMilliseconds(
                config('sanctum.access_token_expiration')
            ),
            'expirationRefreshToken'=> $this->calculateExpirationInMilliseconds(
                config('sanctum.refresh_token_expiration')
            ),
        ];
    }

    private function calculateExpirationInMilliseconds(int $expirationInMinutes): int
    {
        return Carbon::now()->addMinutes($expirationInMinutes)->diffInMilliseconds();
    }
}
