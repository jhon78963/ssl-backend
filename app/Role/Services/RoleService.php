<?php

namespace App\Role\Services;

use App\Role\Models\Role;
use App\Shared\Services\ModelService;

class RoleService
{
    protected ModelService $modelService;

    public function __construct(ModelService $modelService)
    {
        $this->modelService = $modelService;
    }

    public function create(array $newRole): void
    {
        $this->modelService->create(new Role(), $newRole);
    }

    public function delete(Role $role): void
    {
        $this->modelService->delete($role);
    }

    public function update(Role $role, array $editRole): void
    {
        $this->modelService->update($role, $editRole);
    }

    public function validate(Role $role, string $modelName): Role
    {
        return $this->modelService->validate($role, $modelName);
    }
}
