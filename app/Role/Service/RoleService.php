<?php

namespace App\Role\Service;

use App\Role\Models\Role;
use App\Shared\Requests\GetAllRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class RoleService
{
    private int $limit = 10;
    private int $page = 1;
    private string $search = '';

    public function createRole(array $newRole): void
    {
        Role::create([
            'name' => $newRole['name'],
            'creator_user_id' => Auth::id(),
        ]);
    }

    public function deleteRole(Role $role): void
    {
        $role->is_deleted = true;
        $role->deleter_user_id = Auth::id();
        $role->deletion_time = now()->format('Y-m-d H:i:s');
        $role->save();
    }

    public function queryRole(GetAllRequest  $request): array {
        $limit = $request->query('limit', $this->limit);
        $page = $request->query('page', $this->page);
        $search = $request->query('search', $this->search);

        $query = Role::query();

        if ($search) {
            $query = $this->applySearchFilter($query, $search);
        }

        $roles = $query->where('is_deleted', false)->skip(($page - 1) * $limit)->take($limit)->get();
        $total = $query->where('is_deleted', false)->count();
        $pages = ceil($total / $limit);

        return [
            'roles' => $roles,
            'total'=> $total,
            'pages' => $pages,
        ];
    }

    public function updateRole(Role $role, array $editRole): void
    {
        $role->name = $editRole['name'];
        $role->last_modification_time = now()->format('Y-m-d H:i:s');
        $role->last_modifier_user_id = Auth::id();
        $role->save();
    }

    public function validateRole(Role $role): Role
    {
        if ($role->is_deleted == true) {
            throw new ModelNotFoundException('Role does not exists.');
        }

        return $role;
    }

    private function applySearchFilter($query, string $searchTerm)
    {
        $searchTerm = strtolower($searchTerm);
        return $query->where(function ($query) use ($searchTerm) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
        });
    }
}
