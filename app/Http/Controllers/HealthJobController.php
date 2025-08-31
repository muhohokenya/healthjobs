<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthJobRequest;
use App\Models\Facility;
use App\Models\HealthJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = auth()->user();

        $isProfileComplete = $user?->isProfileComplete() ?? false;

        $jobs = HealthJob::query()
            ->with('facility')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('facility', function ($facilityQuery) use ($search) {
                        $facilityQuery->where('location', 'like', "%{$search}%");
                    });
            })
            ->when($request->job_type, fn($query, $jobType) => $query->where('job_type', $jobType))
            ->when($request->experience_level, fn($query, $level) => $query->where('experience_level', $level))

            // 👇 This part handles location filtering
            ->when($request->location, function ($query, $location) {
                $query->whereHas('facility', function ($facilityQuery) use ($location) {
                    $facilityQuery->where('location', 'like', "%{$location}%");
                });
            })
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();


        return Inertia::render('HealthJobs/Index', [
            'locations'=> Facility::query()->distinct()->get(['location']),
            'jobs' => $jobs,
            'filters' => $request->only(['search', 'job_type', 'experience_level']),
            'isProfileComplete' => $isProfileComplete,
        ]);
    }

    public function isProfileComplete()
    {

    }

    public function create()
    {
       return Inertia::render('HealthJobs/Create');
    }

    public function store(HealthJobRequest $request)
    {
        $healthJob = $request->validated();

//        Facility::query()->where('');
        $healthJob['facility_id'] = $request->user()->facility->id;
        $healthJob['user_id'] = Auth::id();
        $healthJob['requirements'] = $request->qualifications;



        // Create the health job record
        $healthJob = HealthJob::create($healthJob);


        return redirect()->route('health-jobs.index');

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
