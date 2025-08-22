<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'view-jobs',
                'create-jobs',
                'edit-jobs',
                'delete-jobs',
                'view-applications',
                'manage-applications',
                'view-profiles',
                'edit-profiles',
                'manage-users',
                'view-analytics',
                'export-data'
            ]),
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function jobManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'create-job-postings',
                'edit-job-postings',
                'delete-job-postings',
                'view-job-postings',
                'publish-jobs',
                'archive-jobs'
            ]),
        ]);
    }

    public function applicationManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'view-job-applications',
                'review-applications',
                'shortlist-candidates',
                'schedule-interviews',
                'reject-applications',
                'hire-candidates'
            ]),
        ]);
    }

    public function userManagement(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->randomElement([
                'view-user-profiles',
                'edit-user-profiles',
                'suspend-users',
                'verify-credentials',
                'manage-subscriptions'
            ]),
        ]);
    }
}
