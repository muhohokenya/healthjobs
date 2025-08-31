<?php

namespace App\Http\Controllers;

use App\Models\HealthJob;
use Illuminate\Http\Request;
use Inertia\Inertia;

class welcomeController extends Controller
{
    public function index()
    {
        // Fetch 3-6 recent active jobs for the landing page showcase
        $featuredJobs = HealthJob::with(['facility'])
            ->where('is_active', true)
            ->latest()
            ->take(6) // Adjust number as needed
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
                    'created_at' => $job->created_at->format('M d, Y'),
                ];
            });

        return Inertia::render('Welcome', [
            'featuredJobs' => $featuredJobs,
            'jobStats' => [
                'total_jobs' => HealthJob::where('is_active', true)->count(),
                'total_facilities' => \App\Models\Facility::count(),
                'success_rate' => 98, // You can calculate this based on your metrics
            ]
        ]);
    }
}
