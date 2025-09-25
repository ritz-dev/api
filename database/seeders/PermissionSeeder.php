<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ["name" => "user.view", "description" => "View user list and details"],
            ["name" => "user.create", "description" => "Create new users"],
            ["name" => "user.edit", "description" => "Edit existing users"],
            ["name" => "user.delete", "description" => "Delete users"],

            ["name" => "role.view", "description" => "View roles and permissions"],
            ["name" => "role.create", "description" => "Create new roles"],
            ["name" => "role.edit", "description" => "Edit roles"],
            ["name" => "role.delete", "description" => "Delete roles"],

            ["name" => "permission.view", "description" => "View all permissions"],
            ["name" => "permission.assign", "description" => "Assign permissions to roles"],
            ["name" => "permission.update", "description" => "Update permission details"],
            ["name" => "permission.delete", "description" => "Delete permissions"],

            ["name" => "student.create", "description" => "Create new student records"],

            ["name" => "dashboard.view", "description" => "Access dashboard analytics and reports"],
            ["name" => "audit.view", "description" => "View activity and audit logs"],
            ["name" => "profile.view", "description" => "View personal profile"],
            ["name" => "profile.eidt", "description" => "Edit personal profile"], // Typo in permission name
        ];

        foreach ($permissions as $permission) {
            Permission::insert([
                'slug' => Str::uuid(),
                'name' => $permission['name'],
                'description' => $permission['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
