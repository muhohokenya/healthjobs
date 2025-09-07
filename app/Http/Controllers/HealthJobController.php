<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthJobRequest;
use App\Models\Facility;
use App\Models\HealthJob;
use App\Models\User;
use App\Notifications\JobInterestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\DomCrawler\Crawler;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Exceptions\RateLimitException;
use OpenAI\Exceptions\ApiException;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;
use Gemini\Laravel\Facades\Gemini;

class HealthJobController extends Controller
{
    public function checkLicence(Request $request)
    {
        $validated = $request->validate([
            'licence_number' => [
                'required',
                'size:12',
                'regex:/^PT\d{4}[A-Z]\d{5}$/',
                'unique:facilities,licence_number',
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
        $crawler->filter('#datatable2 tbody tr')->each(function (Crawler $row) use (&$practitioner, $validated) {
            if ($practitioner) {
                return;
            } // Skip if we already found a match

            $name = trim($row->filter('td')->eq(0)->text());
            $license = trim($row->filter('td')->eq(1)->text());
            $statusValidity = trim($row->filter('td')->eq(2)->text());

            // Parse status and expiry date
            $words = explode(' ', $statusValidity);
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

        if (! $practitioner) {
            return back()->withErrors([
                'licence_number' => 'License number not found or invalid. Please check and try again.',
            ]);
        }

        // Check if license is valid/active
        if ($practitioner['status'] !== 'Active') {
            return back()->withErrors([
                'licence_number' => "License is {$practitioner['status']}. Only valid licenses can be used for registration.",
            ]);
        }

        dd($practitioner);

        return to_route('register', [
            'practitioner' => $practitioner,
            'licence_verified' => true,
        ]);

        // Return success with practitioner data
        return Inertia::render('auth/Register', [
            'practitioner' => $practitioner,
            'licence_verified' => true,
        ]);

    }

    public function index(Request $request): \Inertia\Response
    {


        $user = auth()->user();

        $isProfileComplete = $user?->isProfileComplete() ?? false;

        $jobs = HealthJob::query()
            ->with('user')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->when($request->job_type, fn ($query, $jobType) => $query->where('job_type', $jobType))
            ->when($request->location, fn ($query, $location) => $query->where('location', $location))
            ->where('is_active', true)
            ->when($request->time_filter, function ($query, $time_filter) {
                if ($time_filter === 'latest') {
                    $query->orderBy('created_at', 'desc');
                } elseif ($time_filter === 'oldest') {
                    $query->orderBy('created_at', 'asc');
                }
            }, function ($query) {
                // Default ordering when no time_filter is provided
                $query->orderBy('created_at', 'desc');
            })

            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('HealthJobs/Index', [
            'locations' => HealthJob::query()
                ->distinct()
                ->whereNotNull('location')
                ->get(['location']),
            'jobs' => $jobs,
            'filters' => $request->only(['search', 'job_type', 'experience_level']),
            'isProfileComplete' => $isProfileComplete,
        ]);
    }

    public function isProfileComplete() {}

    public function create()
    {
        return Inertia::render('HealthJobs/Create');
    }



    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $uploadedFile = $request->file('image');

        $prompt = "You are posting this job. Write in first person as the employer.

        Extract job info and return ONLY this JSON format (no markdown, no code blocks):

        {
            \"description\": \"<h3>About This Role</h3><p>We are looking for...</p><h3>What You'll Do</h3><ul><li>Task 1</li></ul><h3>What We Need</h3><ul><li>Requirement 1</li></ul>\",
            \"title\": \"job title\",
            \"location\": \"location\",
            \"job_type\": \"full-time or part-time\",
            \"salary_min\": number,
            \"salary_max\": number,
            \"qualifications\": [\"requirement 1\", \"requirement 2\"]
        }

        Write naturally as if you're the hiring manager. Use 'we', 'our team', 'you'll join us'. Return raw JSON only.";

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->generateContent([
                    $prompt,
                    new Blob(
                        mimeType: MimeType::IMAGE_JPEG,
                        data: base64_encode(file_get_contents($uploadedFile->getPathname()))
                    )
                ]);

            $responseText = $result->text();

            // Try to extract JSON from the response
            $jobData = $this->extractAndParseJson($responseText);

            // If no valid JSON found, create a basic structure with the response as description
            if (!$jobData) {
                $formattedDescription = $this->formatDescriptionAsHtml($responseText);
                $jobData = [
                    'description' => $formattedDescription,
                    'title' => $this->generateTitleFromDescription($responseText),
                    'location' => null,
                    'job_type' => null,
                    'salary_min' => null,
                    'salary_max' => null,
                    'experience_level' => null,
                    'qualifications' => null,
                    'is_active' => true
                ];
            } else {
                // Ensure we have at least a title and description
                if (empty($jobData['description'])) {
                    $jobData['description'] = $this->formatDescriptionAsHtml($responseText);
                }

                if (empty($jobData['title'])) {
                    $jobData['title'] = $this->generateTitleFromDescription($jobData['description']);
                }

                // Ensure is_active is set
                $jobData['is_active'] = true;

                // Clean up salary fields - ensure they're numbers or null
                $jobData['salary_min'] = $this->extractNumericValue($jobData['salary_min'] ?? null);
                $jobData['salary_max'] = $this->extractNumericValue($jobData['salary_max'] ?? null);
            }

            // Return the same view but with success data instead of redirecting
            return Inertia::render('HealthJobs/Create', [
                'success' => true,
                'data' => $jobData,
                'raw_response' => $responseText // Include raw response for debugging
            ]);

        } catch (\Exception $e) {
            // Handle errors gracefully
            return Inertia::render('HealthJobs/Create', [
                'success' => false,
                'error' => 'Failed to process image: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Format plain text description as HTML for rich text editor
     */
    private function formatDescriptionAsHtml($text)
    {
        // Clean up the text
        $text = trim($text);

        // Split into lines and remove empty ones
        $lines = array_filter(array_map('trim', explode("\n", $text)));

        $html = '';
        $inList = false;

        foreach ($lines as $line) {
            // Skip empty lines
            if (empty($line)) continue;

            // Check if line looks like a heading (short line, possibly with colons)
            if (strlen($line) < 50 && (str_contains($line, ':') || $this->looksLikeHeading($line))) {
                // Close any open list
                if ($inList) {
                    $html .= '</ul>';
                    $inList = false;
                }
                $html .= '<h3>' . htmlspecialchars(rtrim($line, ':')) . '</h3>';
            }
            // Check if line looks like a bullet point
            elseif (preg_match('/^[\-\*\•]\s*(.+)/', $line, $matches)) {
                if (!$inList) {
                    $html .= '<ul>';
                    $inList = true;
                }
                $html .= '<li>' . htmlspecialchars($matches[1]) . '</li>';
            }
            // Check if line starts with a number (numbered list)
            elseif (preg_match('/^\d+[\.\)]\s*(.+)/', $line, $matches)) {
                if (!$inList) {
                    $html .= '<ol>';
                    $inList = true;
                }
                $html .= '<li>' . htmlspecialchars($matches[1]) . '</li>';
            }
            // Regular paragraph
            else {
                // Close any open list
                if ($inList) {
                    $html .= '</ul>';
                    $inList = false;
                }
                $html .= '<p>' . htmlspecialchars($line) . '</p>';
            }
        }

        // Close any remaining open list
        if ($inList) {
            $html .= '</ul>';
        }

        // If we don't have any HTML, wrap the entire text in a paragraph
        if (empty($html)) {
            $html = '<p>' . htmlspecialchars($text) . '</p>';
        }

        return $html;
    }

    /**
     * Check if a line looks like a heading
     */
    /**
     * Check if a line looks like a heading
     */
    private function looksLikeHeading($line)
    {
        $headingKeywords = [
            'overview', 'description', 'responsibilities', 'requirements',
            'qualifications', 'benefits', 'duties', 'skills', 'experience',
            'job summary', 'about', 'role', 'position', 'what you', 'we offer'
        ];

        $lowerLine = strtolower($line);

        foreach ($headingKeywords as $keyword) {
            if (str_contains($lowerLine, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract numeric value from salary strings
     */
    private function extractNumericValue($value)
    {
        if (empty($value)) {
            return null;
        }

        // Remove non-numeric characters except decimals
        $numeric = preg_replace('/[^0-9.]/', '', $value);

        if (empty($numeric)) {
            return null;
        }

        return (float) $numeric;
    }

    /**
     * Enhanced title generation that considers healthcare roles
     */
    private function generateTitleFromDescription($description)
    {
        $description = strtolower(trim(strip_tags($description)));

        // Healthcare-specific job keywords with more specific titles
        $jobKeywords = [
            'registered nurse' => 'Registered Nurse',
            'staff nurse' => 'Staff Nurse',
            'charge nurse' => 'Charge Nurse',
            'head nurse' => 'Head Nurse',
            'nurse manager' => 'Nurse Manager',
            'clinical nurse' => 'Clinical Nurse',
            'icu nurse' => 'ICU Nurse',
            'er nurse' => 'Emergency Room Nurse',
            'theatre nurse' => 'Theatre Nurse',
            'pediatric nurse' => 'Pediatric Nurse',
            'maternity nurse' => 'Maternity Nurse',
            'nurse' => 'Nursing Position',

            'medical doctor' => 'Medical Doctor',
            'consultant' => 'Medical Consultant',
            'specialist' => 'Medical Specialist',
            'resident doctor' => 'Resident Doctor',
            'house officer' => 'House Officer',
            'medical officer' => 'Medical Officer',
            'doctor' => 'Medical Doctor',
            'physician' => 'Physician',
            'surgeon' => 'Surgeon',
            'anesthesiologist' => 'Anesthesiologist',
            'radiologist' => 'Radiologist',
            'pathologist' => 'Pathologist',
            'cardiologist' => 'Cardiologist',
            'neurologist' => 'Neurologist',
            'gynecologist' => 'Gynecologist',
            'pediatrician' => 'Pediatrician',

            'pharmacist' => 'Pharmacist',
            'clinical pharmacist' => 'Clinical Pharmacist',
            'pharmacy technician' => 'Pharmacy Technician',

            'lab technician' => 'Laboratory Technician',
            'medical technician' => 'Medical Technician',
            'radiology technician' => 'Radiology Technician',
            'technician' => 'Medical Technician',

            'physiotherapist' => 'Physiotherapist',
            'occupational therapist' => 'Occupational Therapist',
            'therapist' => 'Therapist',

            'medical assistant' => 'Medical Assistant',
            'nursing assistant' => 'Nursing Assistant',
            'healthcare assistant' => 'Healthcare Assistant',
            'assistant' => 'Healthcare Assistant',

            'hospital administrator' => 'Hospital Administrator',
            'health manager' => 'Health Manager',
            'medical records' => 'Medical Records Officer',
            'health information' => 'Health Information Officer',
            'manager' => 'Healthcare Manager',
            'coordinator' => 'Healthcare Coordinator',
            'supervisor' => 'Healthcare Supervisor',
        ];

        // Look for more specific matches first
        foreach ($jobKeywords as $keyword => $title) {
            if (str_contains($description, $keyword)) {
                return $title;
            }
        }

        // Default title
        return 'Healthcare Position';
    }

    private function extractAndParseJson($text)
    {
        // Remove markdown code blocks
        $text = preg_replace('/```json\s*/', '', $text);
        $text = preg_replace('/```\s*$/', '', $text);
        $text = trim($text);

        // Try to find JSON in the text
        if (preg_match('/\{.*\}/s', $text, $matches)) {
            $jsonString = $matches[0];
            $decoded = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        // Try decoding the entire text
        $decoded = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return null;
    }



    public function store(HealthJobRequest $request)
    {
        $healthJob = $request->validated();

        //        Facility::query()->where('');
//        $healthJob['facility_id'] = $request->user()->facility->id;
        $healthJob['user_id'] = Auth::id();
        $healthJob['uuid'] = Str::uuid();
        $healthJob['requirements'] = $request->qualifications;
        $healthJob['location'] = $request->location;

        // Create the health job record
        $healthJob = HealthJob::create($healthJob);

        return redirect()->route('health-jobs.index');

        return response()->json([
            'message' => 'Health job created successfully.',
            'data' => $healthJob,
        ], 201);
    }

    public function show($id)
    {
        $healthJob = HealthJob::query()
            ->where('uuid', $id)
            ->orWhere('id', $id)
            ->firstOrFail();

        return Inertia::render('HealthJobs/Show', [
            'job' => $healthJob,
        ]);
    }

    public function interested(Request $request)
    {
        $job = HealthJob::query()->where('uuid', $request->job)->with('user')->firstOrFail();

        $job->user->notify(new JobInterestNotification($request->user(), $job));
    }
}
