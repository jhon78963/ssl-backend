<?php

namespace App\Role\Seeders;

use App\Role\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = new Role();
        $role->name = "Admin";
        $role->save();

        $role = new Role();
        $role->name = "Employee";
        $role->save();
    }
}
