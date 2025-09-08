<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\HealthJob;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_jobs' => HealthJob::count(),
                'total_locums' => HealthJob::query()->where('job_type','=','part-time')->count(),
                'total_facilities' => Facility::count(),
                'total_users' => User::query()->where('selected_role','=','job-seeker')->count(),
            ],
            'recent_jobs' => HealthJob::with('facility:id,name')
                ->latest()
                ->take(5)
                ->get(['id', 'title', 'job_type', 'facility_id', 'created_at']),
            'top_facilities' => Facility::withCount('jobs')
                ->orderByDesc('jobs_count')
                ->take(5)
                ->get(['id', 'name', 'location']),
        ]);
    }
}
