<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map roles by slug for easy access
        $roles = Role::pluck('id', 'slug')->toArray();

        // Map permissions by name to get their slugs (UUIDs)
        $permissions = Permission::pluck('slug', 'name')->toArray();

        // Define which permissions each role should have (by permission 'name')
        $rolePermissionsMap = [
            // Super Admin: all permissions
            '10000000000000000000000000000000000' => array_values($permissions),

            // Admin: most permissions except maybe 'permission-delete'
            '10000000000000000000000000000000001' => [
                $permissions['user.view'] ?? null,
                $permissions['user.create'] ?? null,
                $permissions['user.edit'] ?? null,
                $permissions['user.delete'] ?? null,
                $permissions['role.view'] ?? null,
                $permissions['role.create'] ?? null,
                $permissions['role-edit'] ?? null,
                $permissions['role-delete'] ?? null,
                $permissions['permission.view'] ?? null,
                $permissions['permission.assign'] ?? null,
                $permissions['permission-update'] ?? null,
                $permissions['student-create'] ?? null,
                $permissions['dashboard.view'] ?? null,
                $permissions['audit.view'] ?? null,
                $permissions['profile.view'] ?? null,
                $permissions['profile.eidt'] ?? null,
            ],

            // Editor: limited permissions
            '10000000000000000000000000000000002' => [
                $permissions['user.view'] ?? null,
                $permissions['dashboard.view'] ?? null,
                $permissions['profile.view'] ?? null,
                $permissions['profile.eidt'] ?? null,
            ],

            // Viewer: mostly read-only
            '10000000000000000000000000000000003' => [
                $permissions['dashboard.view'] ?? null,
                $permissions['profile.view'] ?? null,
            ],

            // HR: some specific permissions
            '10000000000000000000000000000000004' => [
                $permissions['user.view'] ?? null,
                $permissions['student-create'] ?? null,
                $permissions['profile.view'] ?? null,
            ],

            // Teacher: very limited permissions
            '10000000000000000000000000000000005' => [
                $permissions['profile.view'] ?? null,
                $permissions['profile.eidt'] ?? null,
            ],
        ];

        foreach ($rolePermissionsMap as $roleSlug => $permissionSlugs) {
            foreach ($permissionSlugs as $permissionSlug) {
                if ($permissionSlug === null) {
                    // Skip missing permission
                    continue;
                }

                RolePermission::updateOrCreate(
                    [
                        'role_slug'       => $roleSlug,
                        'permission_slug' => $permissionSlug,
                    ],
                    []
                );
            }
        }
    }
}
