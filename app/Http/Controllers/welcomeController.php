<?php

namespace App\Http\Controllers;

use App\Models\HealthJob;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class welcomeController extends Controller
{
    public function index()
    {
        // Provide safe defaults when database tables are not yet migrated (e.g., simple smoke tests)
        $featuredJobs = collect();
        $totalJobs = 0;
        $totalFacilities = 0;

        if (Schema::hasTable('health_jobs')) {
            // Fetch 3-6 recent active jobs for the landing page showcase
            $featuredJobs = HealthJob::with(['facility'])
                ->where('is_active', true)
                ->latest()
                ->take(6)
                ->get()
                ->map(function ($job) {
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'description' => $job->description,
                        'job_type' => $job->job_type,
                        'salary_min' => $job->salary_min,
                        'salary_max' => $job->salary_max,
                        'experience_level' => $job->experience_level,
                        'qualifications' => $job->qualifications ?? [],
                        'facility' => [
                            'name' => $job->facility?->name,
                            'location' => $job->facility?->location,
                        ],
                        'created_at' => optional($job->created_at)->format('M d, Y'),
                    ];
                });

            $totalJobs = HealthJob::where('is_active', true)->count();
        }

        if (Schema::hasTable('facilities')) {
            $totalFacilities = \App\Models\Facility::count();
        }

        return Inertia::render('Welcome', [
            'featuredJobs' => $featuredJobs,
            'jobStats' => [
                'total_jobs' => $totalJobs,
                'total_facilities' => $totalFacilities,
                'success_rate' => 98, // You can calculate this based on your metrics
            ],
        ]);
    }
}
