<?php

namespace App\Http\Controllers;

use App\Models\HealthJob;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;


class HealthJobController extends Controller
{
    public function index(Request $request): \Inertia\Response
    {
//        Gate::authorize('viewAny', HealthJob::class);
        $jobs = HealthJob::query()
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($request->job_type, function ($query, $jobType) {
                $query->where('job_type', $jobType);
            })
            ->when($request->experience_level, function ($query, $level) {
                $query->where('experience_level', $level);
            })
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();


        return Inertia::render('HealthJobs/Index', [
            'jobs' => $jobs,
            'filters' => $request->only(['search', 'job_type', 'experience_level']),
        ]);
    }

    public function show(HealthJob $healthJob)
    {
        return Inertia::render('HealthJobs/Show', [
            'job' => $healthJob,
        ]);
    }
}
