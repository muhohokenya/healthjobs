<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HealthJobFactory extends Factory
{
    public function definition(): array
    {
        $jobTitles = [
            'Registered Nurse',
            'Physical Therapist',
            'Medical Assistant',
            'Pharmacist',
            'Radiologic Technologist',
            'Dental Hygienist',
            'Occupational Therapist',
            'Medical Laboratory Technician',
            'Respiratory Therapist',
            'Healthcare Administrator',
        ];

        $companies = [
            'City General Hospital',
            'HealthCare Plus',
            'Metro Medical Center',
            'Sunrise Clinic',
            'Community Health Services',
            'Advanced Medical Solutions',
            'Prime Healthcare',
            'Regional Medical Group',
        ];

        return [
            'title' => $this->faker->randomElement($jobTitles),
            'company' => $this->faker->randomElement($companies),
            'description' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->city().', '.$this->faker->stateAbbr(),
            'job_type' => $this->faker->randomElement(['full-time', 'part-time', 'contract']),
            'salary_min' => $this->faker->numberBetween(40000, 80000),
            'salary_max' => $this->faker->numberBetween(80000, 150000),
            'experience_level' => $this->faker->randomElement(['entry', 'mid', 'senior']),
            'requirements' => [
                $this->faker->sentence(),
                $this->faker->sentence(),
                $this->faker->sentence(),
            ],
            'is_active' => $this->faker->boolean(85), // 85% chance of being active
        ];
    }
}
