<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Role & Permissions mapping
        $roles = [
            'Administrator' => [
                'manage_users',
                'manage_roles',
                'manage_courses',
                'manage_modules',
                'view_reports'
            ],
            'Instructor' => [
                'create_course',
                'edit_own_course',
                'delete_own_course',
                'create_module',
                'edit_own_module',
                'delete_own_module'
            ],
            'Student' => [
                'enroll_course',
                'view_course',
                'access_module'
            ],
        ];

        // Create Roles
        foreach ($roles as $roleName => $permissions) {
            Role::updateOrCreate(
                ['name' => $roleName],
                [
                    'id' => Str::uuid(),
                    'permissions' => json_encode($permissions)
                ]
            );
        }


        // Create Users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'role' => 'Administrator',
            ],
            [
                'name' => 'Instructor User',
                'email' => 'instructor@gmail.com',
                'role' => 'Instructor',
            ],
            [
                'name' => 'Student User',
                'email' => 'student@gmail.com',
                'role' => 'Student',
            ],
        ];

        foreach ($users as $userData) {
            $role = Role::where('name', $userData['role'])->first();

            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'id' => Str::uuid(),
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $role->id,
                ]
            );
        }
    }
}
