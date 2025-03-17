<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            "slug" => Str::uuid(),
            "name" => "Admin",
            "description" => 'Administrator with full access to the system.',
        ]);

        Role::create([
            "slug" => Str::uuid(),
            "name" => "HR",
            "description" => 'Human Resource.',
        ]);

        Role::create([
            "slug" => Str::uuid(),
            "name" => "Teacher",
            "description" => 'Teacher authorize access.',
        ]);
    }
}
