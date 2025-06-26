<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacherApiUrl = config('services.user.url') . '/teachers';

        // Fetch teacher info based on the section ID
        $responset = Http::withHeaders([
            'Accept' => 'application/json',
            // 'Authorization' => $request->header('Authorization'),
        ])->post($teacherApiUrl, ['limit' => 3]);

        if (!$responset->ok()) {
            $this->command->error('Failed to fetch teachers from user management service.');
            return;
        }

        $teachersArray = collect($responset->json('data') ?? []);

        $teachers = collect($teachersArray);
        
        $role = Role::first();

        $teacherRole = Role::where('name', 'Teacher')->first();

        foreach ($teachers as $teacher) {
            // Check if user already exists (to avoid duplicates)
            $existingUser = User::where('email', $teacher['email'])->first();
        
            if (!$existingUser) {
                User::create([
                    'name' => $teacher['teacher_name'],
                    'email' => $teacher['email'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('defaultpassword'), // You should force change on first login
                    'role_slug' => $teacherRole->slug,
                    'employee_slug' => $teacher['slug'],
                    'remember_token' => \Illuminate\Support\Str::random(10),
                ]);
            }
        }

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), // Use a secure password
            'role_slug' => $role->slug, // Assuming the role_slug 1 is for 'Admin' role
            'employee_slug' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Super Admin 1',
            'email' => 'superadmin1@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), // Use a secure password
            'role_slug' => $role->slug, // Assuming the role_slug 1 is for 'Admin' role
            'employee_slug' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'Super Admin 2',
            'email' => 'superadmin2@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('superpassword'), // Use a secure password
            'role_slug' => $role->slug, // Assuming the role_slug 1 is for 'Admin' role
            'employee_slug' => 'admin',
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

    }
}
