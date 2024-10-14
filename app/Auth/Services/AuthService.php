<?php

namespace App\Auth\Services;

use App\Auth\Enums\TokenAbility;
use App\Auth\Exceptions\InvalidTokenException;
use App\Auth\Exceptions\InvalidUserCredentialsException;
use App\Auth\Models\PersonalAccessToken;
use App\Auth\Requests\RefreshTokenRequest;
use App\Auth\Requests\UpdateMeRequest;
use App\User\Models\User;
use Carbon\Carbon;
use Hash;

class AuthService
{
    public function validateUser(string $password, User $user): User
    {
        if (!$user || !Hash::check($password, $user->password)) {
            throw new InvalidUserCredentialsException();
        }
        return $user;
    }

    public function updateMe(UpdateMeRequest $request): void {
        $request->user()->fill(
            $request->only(['username', 'email', 'name', 'surname'])
        )->save();
    }

    public function changePassword(User $user, string $newPassword): void {
        $user->password = Hash::make($newPassword);
        $user->save();
    }

    public function createTokens(User $user): array
    {
        // $user->tokens()->delete();
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.access_token_expiration'))
        )->plainTextToken;

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
        )->plainTextToken;

        return $this->generateTokenResponse($accessToken, $refreshToken);
    }

    public function deleteToken(User $user): void
    {
        $user->tokens()->delete();
    }

    public function validateRefreshToken(RefreshTokenRequest $request): User
    {
        $validateToken = PersonalAccessToken::findToken($request->refreshToken);
        $user = User::find($validateToken->tokenable_id);
        if ( !$validateToken) throw new InvalidTokenException();
        return  $user;
    }

    public function generateTokenResponse(string $accessToken, string $refreshToken): array
    {
        return [
            'token' => $accessToken,
            'refreshToken' => $refreshToken,
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
        return Carbon::parse($expirationInMinutes)->diffInMilliseconds(Carbon::now());
    }
}
