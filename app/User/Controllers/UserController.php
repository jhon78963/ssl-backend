<?php

namespace App\User\Controllers;

use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use App\User\Models\User;
use App\User\Requests\UserCreateRequest;
use App\User\Requests\UserUpdateRequest;
use App\User\Resources\UserResource;
use App\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use DB;

class UserController extends Controller
{
    protected $userService;
    protected $sharedService;

    public function __construct(UserService $userService, SharedService $sharedService)
    {
        $this->userService = $userService;
        $this->sharedService = $sharedService;
    }
    public function create(UserCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            [$emailExists, $usernameExists] = $this->userService->checkUser(
                $request->email,
                $request->username,
            );

            if ($errorResponse = $this->generateErrorResponse(
                $emailExists,
                $usernameExists)
            ) {
                return response()->json($errorResponse);
            }

            $profilePicture = $this->userService->uploadProfilePicture($request);
            $newUser = $this->prepareNewUserData(
                $request->validated(),
                $profilePicture
            );
            $this->userService->create($newUser);
            DB::commit();
            return response()->json(['message' => 'User created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(User $user): JsonResponse {
        DB::beginTransaction();
        try {
            $userValidated = $this->userService->validate($user, 'User');
            $this->userService->delete($userValidated);
            DB::commit();
            return response()->json(['message' => 'User deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(User $user): JsonResponse
    {
        $userValidated = $this->userService->validate($user, 'User');
        return response()->json(new UserResource($userValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'User',
            'User',
            'name'
        );

        return response()->json(new GetAllCollection(
            UserResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        DB::beginTransaction();
        try {
            $userValidated = $this->userService->validate($user, 'User');
            $profilePicture = $this->userService->uploadProfilePicture($request);
            $editUser = $this->prepareNewUserData(
                $request->validated(),
                $profilePicture
            );
            $this->userService->update($userValidated, $editUser);
            DB::commit();
            return response()->json(['message' => 'User updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    private function generateErrorResponse(bool $emailExists, bool $usernameExists): ?array
    {
        $errors = [];

        if ($emailExists) {
            $errors['email'] = 'El email ya existe.';
        }

        if ($usernameExists) {
            $errors['username'] = 'El username ya existe.';
        }

        if (!empty($errors)) {
            return [
                'status' => 'error',
                'message' => 'El email y/o username ya existen.',
                'errors' => $errors
            ];
        }

        return null; // No hay errores
    }

    private function prepareNewUserData(array $validatedData, ?string $profilePicture): array
    {
        $userData = $validatedData + ['profilePicture' => $profilePicture];
        return $this->sharedService->convertCamelToSnake($userData);
    }
}
