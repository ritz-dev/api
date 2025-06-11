<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::get();
        $permissions = Permission::get()->pluck('slug')->toArray();

        // Prepare an array to hold the role permission data
        $pivotData = [];

        // Loop through each role
        foreach($roles as $role) {
            // For each role, loop through all permissions
            foreach($permissions as $permission) {
                // Add the role permission data to the pivot array
                $pivotData[] = [
                    'slug' => (string) \Illuminate\Support\Str::uuid(),
                    'role_slug' => $role->slug,
                    'permission_slug' => $permission,
                    "description" => $role->name . ' has permission for ' . $permission,
                ];
            }
        }

        // Insert all the data at once
        RolePermission::insert($pivotData);
    }
}
