<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\HealthJob;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_jobs' => HealthJob::count(),
                'total_facilities' => Facility::count(),
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
