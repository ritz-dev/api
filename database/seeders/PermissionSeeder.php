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
            ["name" => "user.view"],
            ["name" => "user.create"],
            ["name" => "user.edit"],
            ["name" => "user.delete"],
            ["name" => "role.view"],
            ["name" => "role.create"],
            ["name" => "role-edit"],
            ["name" => "role-delete"],
            ["name" => "permission.view"],
            ["name" => "permission.assign"],
            ["name" => "permission-update"],
            ["name" => "permission-delete"],
            ["name" => "student-create"],
            ["name" => "dashboard.view"],
            ["name" => "audit.view"],
            ["name" => "profile.view"],
            ["name" => "profile.eidt"]
        ];

        foreach($permissions as $permission){
            Permission::insert([
                'slug' => Str::uuid(),
                'name' => $permission['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
