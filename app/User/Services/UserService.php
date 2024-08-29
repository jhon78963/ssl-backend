<?php

namespace App\User\Services;

use App\User\Models\User;
use App\User\Requests\UserCreateRequest;
use App\User\Requests\UserUpdateRequest;
use Auth;
use Hash;

class UserService
{
    public function uploadProfilePicture(UserCreateRequest | UserUpdateRequest $request): ?string
    {
        return ($request->hasFile("profilePicture"))
            ? $request->file("profilePicture")->store("public/profiles")
            : NULL;
    }
    public function createUser(array $newUSer)
    {
        $user = new User();
        $user->username = $newUSer["username"];
        $user->email = $newUSer["email"];
        $user->name = $newUSer["name"];
        $user->surname = $newUSer["surname"];
        $user->password = Hash::make($newUSer["password"]);
        $user->role_id = $newUSer["roleId"];
        $user->profile_picture = $newUSer["profilePicture"];
        $user->creator_user_id = Auth::id();
        $user->save();
    }

    public function updateUser(User $user, array $editUser): void
    {
        $user->name = $editUser['name'];
        $user->surname = $editUser['surname'];
        $user->profile_picture = $editUser["profilePicture"] ?? $user->profile_picture;
        $user->last_modification_time = now()->format('Y-m-d H:i:s');
        $user->last_modifier_user_id = Auth::id();
        $user->save();
    }

    public function checkUser(string $email, string $username): ?array
    {
        $emailExists = $this->userExistsByEmail($email);
        $usernameExists = $this->userExistsByUsername($username);
        return $this->generateErrorResponse($emailExists, $usernameExists);
    }

    public function userExistsByEmail(string $email): bool
    {
        return User::where('email', $email)->where('is_deleted', false)->exists();
    }

    public function userExistsByUsername(string $username): bool
    {
        return User::where('username', $username)->where('is_deleted', false)->exists();
    }

    public function generateErrorResponse(bool $emailExists, bool $usernameExists): ?array
    {
        if ($emailExists && $usernameExists) {
            return [
                'status' => 'error',
                'message' => 'The email and username already exist',
                'errors' => [
                    'email' => 'El email ya existe.',
                    'username' => 'El username ya existe.'
                ]
            ];
        }

        if ($emailExists) {
            return [
                'status'=> 'error',
                'message'=> 'The email already exists',
                'errors' => [
                    'email' => 'El email ya existe.',
                ]
            ];
        }

        if ($usernameExists) {
            return [
                'status'=> 'error',
                'message'=> 'The username already exists',
                'errors' => [
                    'username' => 'El username ya existe.'
                ]
            ];
        }

        return NULL;
    }
}
