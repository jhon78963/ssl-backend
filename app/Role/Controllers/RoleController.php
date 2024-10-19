<?php

namespace App\Role\Controllers;

use App\Role\Models\Role;
use App\Role\Requests\RoleCreateRequest;
use App\Role\Requests\RoleUpdateRequest;
use App\Role\Resources\RoleResource;
use App\Role\Services\RoleService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class RoleController extends Controller
{

    protected RoleService $roleService;
    protected SharedService $sharedService;

    public function __construct(RoleService $roleService, SharedService $sharedService)
    {
        $this->roleService = $roleService;
        $this->sharedService = $sharedService;
    }

    public function create(RoleCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newRole = $this->sharedService->convertCamelToSnake($request->validated());
            $this->roleService->create($newRole);
            DB::commit();
            return response()->json(['message' => 'Role created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Role $role): JsonResponse
    {
        DB::beginTransaction();
        try {
            $roleValidated = $this->roleService->validate($role, 'Role');
            $this->roleService->delete($roleValidated);
            DB::commit();
            return response()->json(['message' => 'Role deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Role $role): JsonResponse
    {
        $roleValidated = $this->roleService->validate($role, 'Role');
        return response()->json(new RoleResource($roleValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query(
            $request,
            'Role',
            'Role',
            'name'
        );

        return response()->json(new GetAllCollection(
            RoleResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(RoleUpdateRequest $request, Role $role): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editRole = $this->sharedService->convertCamelToSnake($request->validated());
            $roleValidated = $this->roleService->validate($role, 'Role');
            $this->roleService->update($roleValidated, $editRole);
            DB::commit();
            return response()->json(['message' => 'Role updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
