<?php

namespace App\Role\Controllers;

use App\Role\Models\Role;
use App\Role\Requests\RoleCreateRequest;
use App\Role\Requests\RoleUpdateRequest;
use App\Role\Resources\RoleCollection;
use App\Role\Resources\RoleResource;
use App\Role\Service\RoleService;
use App\Shared\Requests\GetAllRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use DB;

class RoleController extends Controller
{

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function create(RoleCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $this->roleService->createRole($request->validated());
            DB::commit();
            return response()->json(['message' => 'Role created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function delete(Role $role): JsonResponse
    {
        DB::beginTransaction();
        try {
            $roleValidated = $this->roleService->validateRole($role);
            $this->roleService->deleteRole($roleValidated);
            DB::commit();
            return response()->json(['message' => 'Role deleted.']);

        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }

    public function get(Role $role): JsonResponse
    {
        $roleValidated = $this->roleService->validateRole($role);
        return response()->json(new RoleResource($roleValidated));
    }

    public function getAll(GetAllRequest  $request): JsonResponse
    {
        $query = $this->roleService->queryRole($request);
        return response()->json(new RoleCollection($query['roles'], $query['total'], $query['pages']));
    }

    public function update(RoleUpdateRequest $request, Role $role): JsonResponse
    {
        DB::beginTransaction();
        try {
            $roleValidated = $this->roleService->validateRole($role);
            $this->roleService->updateRole($roleValidated, $request->validated());
            DB::commit();
            return response()->json(['message' => 'Role updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            throw new BadRequestException($e->getMessage());
        }
    }
}
