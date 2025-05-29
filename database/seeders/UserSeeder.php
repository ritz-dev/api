<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $role = Role::first();
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), // Use a secure password
            'role_id' => $role->id, // Assuming the role_id 1 is for 'Admin' role
            'employee_id' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Super Admin 1',
            'email' => 'superadmin1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), // Use a secure password
            'role_id' => $role->id, // Assuming the role_id 1 is for 'Admin' role
            'employee_id' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Super Admin 2',
            'email' => 'superadmin2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), // Use a secure password
            'role_id' => $role->id, // Assuming the role_id 1 is for 'Admin' role
            'employee_id' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
    }
}
