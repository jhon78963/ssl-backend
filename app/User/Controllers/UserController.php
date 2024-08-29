<?php

namespace App\User\Controllers;

use App\User\Models\User;
use App\User\Requests\UserCreateRequest;
use App\User\Requests\UserUpdateRequest;
use App\User\Resources\UserResource;
use App\User\Services\UserService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
            $validatedUser = $this->userService->checkUser($request->email, $request->username);
            if ($validatedUser) return response()->json($validatedUser);
            $profilePicture = $this->userService->uploadProfilePicture($request);
            $this->userService->createUser($request->validated() + ['profilePicture' => $profilePicture]);
            DB::commit();
            return response()->json(['message' => 'User created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function delete(User $user): JsonResponse {
        DB::beginTransaction();
        try {
            $userValidated = $this->sharedService->validateModel($user, 'User');
            $this->sharedService->deleteModel($userValidated);
            DB::commit();
            return response()->json(['message' => 'User deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function get(User $user): JsonResponse
    {
        $userValidated = $this->sharedService->validateModel($user, 'User');
        return response()->json(new UserResource($userValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'User', 'User', 'name');
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
            $userValidated = $this->sharedService->validateModel($user, 'User');
            $profilePicture = $this->userService->uploadProfilePicture($request);
            $this->userService->updateUser(
                $userValidated,
                $request->validated() + ['profilePicture' => $profilePicture]
            );
            DB::commit();
            return response()->json(['message' => 'User updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }
}
