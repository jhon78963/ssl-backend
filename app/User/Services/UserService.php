<?php

namespace App\User\Services;

use App\Shared\Services\ModelService;
use App\User\Models\User;
use App\User\Requests\UserCreateRequest;
use App\User\Requests\UserUpdateRequest;
use Auth;
use Hash;

class UserService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function uploadProfilePicture(UserCreateRequest | UserUpdateRequest $request): ?string
    {
        return ($request->hasFile("profilePicture"))
            ? $request->file("profilePicture")->store("public/images/profiles")
            : NULL;
    }

    public function create(array $newUser)
    {
        $this->modelService->create(new User(), $newUser);
    }

    public function delete(User $user): void
    {
        $this->modelService->delete($user);
    }

    public function update(User $user, array $editUser): void
    {
        $this->modelService->update($user, $editUser);
    }

    public function checkUser(string $email, string $username): ?array
    {
        $emailExists = $this->userExistsByEmail($email);
        $usernameExists = $this->userExistsByUsername($username);
        return [$emailExists, $usernameExists];
    }

    public function userExistsByEmail(string $email): bool
    {
        return User::where('email', $email)
            ->where('is_deleted', false)
            ->exists();
    }

    public function userExistsByUsername(string $username): bool
    {
        return User::where('username', $username)
            ->where('is_deleted', false)
            ->exists();
    }

    public function validate(User $user, string $modelName): mixed
    {
        return $this->modelService->validate($user, $modelName);
    }
}
