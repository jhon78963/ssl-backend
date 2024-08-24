<?php

namespace App\Role\Services;

use App\Role\Models\Role;
use Auth;

class RoleService
{
    public function createRole(array $newRole): void
    {
        $role = new Role();
        $role->name = $newRole["name"];
        $role->creator_user_id = Auth::id();
        $role->save();
    }

    public function updateRole(Role $role, array $editRole): void
    {
        $role->name = $editRole['name'];
        $role->last_modification_time = now()->format('Y-m-d H:i:s');
        $role->last_modifier_user_id = Auth::id();
        $role->save();
    }
}
