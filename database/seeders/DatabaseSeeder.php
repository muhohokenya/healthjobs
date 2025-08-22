<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run the roles and permissions seeder first
        $this->call([
            PermissionSeeder::class
//            RolePermissionSeeder::class,
            // Add other seeders here as needed
        ]);

        // Create test users with roles (optional - for development)
        if (app()->environment('local')) {
            $this->createTestUsers();
        }
    }

    /**
     * Create test users with different roles for development
     */
    private function createTestUsers(): void
    {
        $testUsers = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@medicaljs.com',
                'password' => bcrypt('password'),
                'role' => 'super-admin'
            ],
            [
                'name' => 'Hospital Admin',
                'email' => 'hospitaladmin@medicaljs.com',
                'password' => bcrypt('password'),
                'role' => 'medical-institution-admin'
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hrmanager@medicaljs.com',
                'password' => bcrypt('password'),
                'role' => 'hr-manager'
            ],
            [
                'name' => 'Recruiter',
                'email' => 'recruiter@medicaljs.com',
                'password' => bcrypt('password'),
                'role' => 'recruiter'
            ],
            [
                'name' => 'Dr. John Doe',
                'email' => 'doctor@medicaljs.com',
                'password' => bcrypt('password'),
                'role' => 'medical-professional'
            ],
            [
                'name' => 'Nurse Jane',
                'email' => 'nurse@medicaljs.com',
                'password' => bcrypt('password'),
                'role' => 'job-seeker'
            ],
        ];

        foreach ($testUsers as $userData) {
            $user = \App\Models\User::query()->firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );

            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

        $this->command->info('Test users created successfully!');
    }
}
