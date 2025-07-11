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
            ["name" => "user-create"],
            ["name" => "user-read"],
            ["name" => "user-update"],
            ["name" => "user-delete"],
            ["name" => "role-create"],
            ["name" => "role-read"],
            ["name" => "role-update"],
            ["name" => "role-delete"],
            ["name" => "permission-create"],
            ["name" => "permission-read"],
            ["name" => "permission-update"],
            ["name" => "permission-delete"],
            ["name" => "student-create"],
            ["name" => "student-read"],
            ["name" => "student-update"],
            ["name" => "student-delete"],
            ["name" => "academic_year-create"],
            ["name" => "academic_year-read"],
            ["name" => "academic_year-update"],
            ["name" => "academic_year-delete"],
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
