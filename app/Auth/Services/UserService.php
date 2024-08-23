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
use Carbon\Carbon;
use Hash;

class UserService
{
    public function validateUser(LoginRequest $request): User
    {
        $user = User::where("username", $request->username)->first();

        if ( !$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 404);
        }

        return $user;
    }

    public function updateMe(UpdateMeRequest $request): array {
        $request->user()->fill($request->only([
            'username', 'email', 'name', 'surname'
        ]))->save();

        return [
            'message' => 'User updated successfully',
        ];
    }

    public function changePassword(ChangePasswordRequest $request): array {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return [
            'message' => 'Password changed successfully',
        ];
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

        return $this->respondWithToken([
            'accessToken' => $accessToken,
            'refreshToken'=> $refreshToken
        ]);
    }

    public function deleteToken(DeleteTokenRequest $request): array
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'Logout successfully',
        ];
    }

    public function validateRefreshToken(RefreshTokenRequest $request): User
    {
        $validateToken = PersonalAccessToken::findToken($request->refreshToken);
        if ( !$validateToken) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        return $request->user();
    }

    public function respondWithToken(array $token): array
    {
        return [
            'token' => $token['accessToken']->plainTextToken,
            'refreshToken' => $token['refreshToken']->plainTextToken,
            'expirationToken'=> $this->calculateExpirationInMilliseconds(
                config('sanctum.access_token_expiration')
            ),
            'expirationRefreshToken'=> $this->calculateExpirationInMilliseconds(
                config('sanctum.refresh_token_expiration')
            ),
        ];
    }

    private function calculateExpirationInMilliseconds($expiresAt): float
    {
        $expirationDate = Carbon::parse($expiresAt);
        $now = Carbon::now();
        $differenceInMilliseconds = $expirationDate->diffInMilliseconds($now);

        return round($differenceInMilliseconds);
    }
}
