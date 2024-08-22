<?php

namespace App\Http\Controllers;

use App\Enums\TokenAbility;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {

        $user = User::where("username", $request->username)->first();

        if ( !$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 404);
        }

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

        return $this->respondWithToken($accessToken, $refreshToken);
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {
        $refreshToken = PersonalAccessToken::findToken($request->refreshToken);
        if ( !$refreshToken) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        $request->user()->tokens()->delete();

        $accessToken = $request->user()->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value],
            Carbon::now()->addMinutes(config('sanctum.access_token_expiration'))
        );

        $refreshToken = $request->user()->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration'))
        );

        return $this->respondWithToken($accessToken, $refreshToken);
    }

    public function me() {
        $user = Auth::user();

        return response()->json([
            'username' => $user->username,
            'email' => $user->email,
            'name' => $user->name,
            'surname' => $user->surname,
            'profilePicture' => $user->profile_picture,
            'role' => $user->role->name,
        ]);
    }

    protected function respondWithToken($token, $refreshToken): JsonResponse
    {
        return response()->json([
            'token' => $token->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
            'expirationToken'=> $this->calculateExpirationInMilliseconds(config('sanctum.access_token_expiration')),
            'expirationRefreshToken'=> $this->calculateExpirationInMilliseconds(config('sanctum.refresh_token_expiration')),
        ]);
    }

    private function calculateExpirationInMilliseconds($expiresAt)
    {
        $expirationDate = Carbon::parse($expiresAt);
        $now = Carbon::now();
        $differenceInMilliseconds = $expirationDate->diffInMilliseconds($now);

        return round($differenceInMilliseconds);
    }
}
