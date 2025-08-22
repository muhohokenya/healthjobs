<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'healthcare-recruiter',
                'medical-administrator',
                'department-head',
                'hiring-manager',
                'clinical-coordinator',
                'medical-director',
                'talent-acquisition',
                'compliance-officer'
            ]),
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'super-admin',
        ]);
    }

    public function employer(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'employer',
        ]);
    }

    public function jobSeeker(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'job-seeker',
        ]);
    }

    public function recruiter(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'recruiter',
        ]);
    }

    public function medicalInstitution(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'medical-institution',
        ]);
    }
}
