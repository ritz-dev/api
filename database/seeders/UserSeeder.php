<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), 
            'role_slug' => "10000000000000000000000000000000000",
            'employee_slug' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminpassword'), 
            'role_slug' => "10000000000000000000000000000000001",
            'employee_slug' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('editorpassword'), 
            'role_slug' => "10000000000000000000000000000000002",
            'employee_slug' => 'editor',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Viewer',
            'email' => 'viewer@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('viewerpassword'), 
            'role_slug' => "10000000000000000000000000000000003",
            'employee_slug' => 'viewer',
            'remember_token' => \Illuminate\Support\Str::random(10),
            'status' => 'inactive',
        ]);

        User::create([
            'name' => 'Yar Yar',
            'email' => 'teacher3@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), 
            'role_slug' => "10000000000000000000000000000000005",
            'employee_slug' => "10000000000000000000000000000000002",
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

    }
}
