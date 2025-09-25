<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            "slug" => "10000000000000000000000000000000000",
            "name" => "Super Admin",
        ]);

        Role::create([
            "slug" => "10000000000000000000000000000000001",
            "name" => "Admin",
        ]);

        Role::create([
            "slug" => "10000000000000000000000000000000002",
            "name" => "Editor",
        ]);

        Role::create([
            "slug" => "10000000000000000000000000000000003",
            "name" => "Viewer",
        ]);

        Role::create([
            "slug" => "10000000000000000000000000000000004",
            "name" => "HR",
        ]);

        Role::create([
            "slug" => "10000000000000000000000000000000005",
            "name" => "Teacher",
        ]);
    }
}
