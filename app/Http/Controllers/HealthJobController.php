<?php

namespace App\Http\Controllers;

use App\Models\HealthJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\DomCrawler\Crawler;


class HealthJobController extends Controller
{

    public function checkLicence(Request $request)
    {
        $validated = $request->validate([
            'licence_number' => [
                'required',
                'size:12',
                'regex:/^PT\d{4}[A-Z]\d{5}$/',
                'unique:facilities,licence_number'
            ],
        ]);
//        dd($validated);

        $response = Http::asForm()->post('https://practice.pharmacyboardkenya.org/ajax/public', [
            'search_register' => 1,
            'cadre_id' => 4,
            'search_text' => $validated['licence_number'],
        ]);

//        if (!$response->ok()) {
//            return back()->withErrors([
//                'licence_number' => 'License verification service is currently unavailable. Please try again later.'
//            ]);
//        }

        $crawler = new Crawler($response->body());
        $practitioner = null;

        // Extract data from the first matching row
        $crawler->filter('#datatable2 tbody tr')->each(function (Crawler $row) use (&$practitioner,$validated) {
            if ($practitioner) return; // Skip if we already found a match

            $name = trim($row->filter('td')->eq(0)->text());
            $license = trim($row->filter('td')->eq(1)->text());
            $statusValidity = trim($row->filter('td')->eq(2)->text());

            // Parse status and expiry date
            $words = explode(" ", $statusValidity);
            $expiryDate = end($words);
            $status = isset($words[1]) ? $words[1] : 'Unknown';


            // Verify this is the correct license
            if ($license === $validated['licence_number']) {
                $practitioner = [
                    'name' => $name,
                    'licence_number' => $license,
                    'expiry_date' => $expiryDate,
                    'status' => $status,
                ];
            }
        });

        if (!$practitioner) {
            return back()->withErrors([
                'licence_number' => 'License number not found or invalid. Please check and try again.'
            ]);
        }

        // Check if license is valid/active
        if ($practitioner['status'] !== 'Active') {
            return back()->withErrors([
                'licence_number' => "License is {$practitioner['status']}. Only valid licenses can be used for registration."
            ]);
        }

        dd($practitioner);

        return to_route('register',[
            'practitioner' => $practitioner,
            'licence_verified' => true
        ]);

        // Return success with practitioner data
        return Inertia::render('auth/Register',[
            'practitioner' => $practitioner,
            'licence_verified' => true
        ]);


    }


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

    public function create()
    {
       return Inertia::render('HealthJobs/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'company' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'description' => [
                'required',
                'string',
                'min:50',
                'max:10000'
            ],
            'location' => [
                'required',
                'string',
                'max:255'
            ],
            'job_type' => [
                'required',
                'string',
                'in:full-time,part-time,contract'
            ],
            'salary_min' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99'
            ],
            'salary_max' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99',
                'gte:salary_min' // salary_max must be greater than or equal to salary_min
            ],
            'experience_level' => [
                'required',
                'string',
                'in:entry,mid,senior'
            ],
            'requirements' => [
                'nullable',
                'array'
            ],
            'requirements.*' => [
                'string',
                'max:500'
            ],
            'is_active' => [
                'sometimes',
                'boolean'
            ]
        ], [
            // Custom error messages
            'title.required' => 'Job title is required.',
            'title.min' => 'Job title must be at least 3 characters long.',
            'company.required' => 'Company name is required.',
            'description.required' => 'Job description is required.',
            'description.min' => 'Job description must be at least 50 characters long.',
            'location.required' => 'Job location is required.',
            'job_type.required' => 'Job type is required.',
            'job_type.in' => 'Job type must be one of: full-time, part-time, or contract.',
            'salary_min.numeric' => 'Minimum salary must be a valid number.',
            'salary_min.min' => 'Minimum salary cannot be negative.',
            'salary_max.numeric' => 'Maximum salary must be a valid number.',
            'salary_max.min' => 'Maximum salary cannot be negative.',
            'salary_max.gte' => 'Maximum salary must be greater than or equal to minimum salary.',
            'experience_level.required' => 'Experience level is required.',
            'experience_level.in' => 'Experience level must be one of: entry, mid, or senior.',
            'requirements.array' => 'Requirements must be provided as an array.',
            'requirements.*.string' => 'Each requirement must be a string.',
            'requirements.*.max' => 'Each requirement cannot exceed 500 characters.'
        ]);

        // Additional custom validation logic
        $request->validate([], [], [], function($validator) use ($request) {
            // Custom rule: If salary_min is provided, salary_max should also be provided
            if ($request->filled('salary_min') && !$request->filled('salary_max')) {
                $validator->errors()->add('salary_max', 'Maximum salary is required when minimum salary is provided.');
            }

            // Custom rule: Both salaries should be provided together or not at all
            if (($request->filled('salary_min') && !$request->filled('salary_max')) ||
                (!$request->filled('salary_min') && $request->filled('salary_max'))) {
                $validator->errors()->add('salary', 'Both minimum and maximum salary should be provided together.');
            }
        });

        // Create the health job record
        $healthJob = HealthJob::create($validated);

        return response()->json([
            'message' => 'Health job created successfully.',
            'data' => $healthJob
        ], 201);
    }

    public function show(HealthJob $healthJob)
    {
        return Inertia::render('HealthJobs/Show', [
            'job' => $healthJob,
        ]);
    }
}
