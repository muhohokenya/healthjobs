<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\DomCrawler\Crawler;

class FacilityController extends Controller
{
    public function index()
    {
        return Inertia::render('Facilities/Index', [
            'facilities' => Facility::all()
        ]);
    }


    public function create()
    {
        return Inertia::render('Facilities/Create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:facilities,email|max:255',
            'licence_number' => [
                'required',
                'size:11',
                'regex:/^[A-Z]{2}\d{9}$/',
                'unique:facilities,licence_number'
            ],
            'location' => 'required',
            'contact_number' => 'required',
        ], [
            'licence_number.size' => 'The licence number must be exactly 11 characters.',
            'licence_number.regex' => 'The licence number must contain 2 letters followed by 9 digits (e.g., BU202502224).',
            'licence_number.unique' => 'This licence number is already registered.',
        ]);

        $response = Http::asForm()->post('https://practice.pharmacyboardkenya.org/ajax/public', [
            'search_register' => 1,
            'cadre_id' => 'Facilities',
            'search_text' => $validated['name'],
        ]);

        if (!$response->ok()) {
            return response()->json(['success' => false, 'message' => 'Source unavailable'], 502);
        }

        $crawler = new Crawler($response->body());

        $rows = $crawler->filter('table.facility_datatables tbody tr')->each(function (Crawler $row) {
            $cols = $row->filter('td')->each(fn(Crawler $c) => trim($c->text()));
            return [
                'facility_name' => $cols[0] ?? null,
                'license_number' => $cols[1] ?? null, // e.g. "BU202502358 - RETAIL"
                'status_validity' => $cols[2] ?? null, // e.g. "Status: Active  2025-12-31"
                'details' => optional($row->filter('a.popStatus'))->count() ?
                    $row->filter('a.popStatus')->attr('rel') : null,
            ];
        });

        // Helper functions
        $normalize = fn($s) => strtoupper(trim(preg_replace('/\s+/', ' ', (string)$s)));
        $licCore = function ($s) {
            $core = trim(explode('-', (string)$s)[0] ?? '');
            return strtoupper($core);
        };

        // Find exact match: name (without bracketed location), location (inside brackets), licence core
        $candidate = collect($rows)->first(function ($f) use ($validated, $normalize, $licCore) {
            $nameRaw = $f['facility_name'] ?? '';

            // Extract "NAME" and "(Location)"
            $nameOnly = trim(preg_replace('/\s*\(.+\)$/', '', $nameRaw)); // strip "(...)"
            preg_match('/\((.*?)\)/', $nameRaw, $m);
            $scrapedLoc = $normalize($m[1] ?? '');

            $nameMatch = $normalize($nameOnly) === $normalize($validated['name']);
            $locMatch = $scrapedLoc === $normalize($validated['location']);
            $licMatch = $licCore($f['license_number']) === $licCore($validated['licence_number']);

            return $nameMatch && $locMatch && $licMatch;
        });

        if (!$candidate) {

            return Inertia::render('Facilities/Create', [
                'error' => $request->only(
                    'id',
                    'title',
                    'start_date',
                    'description'
                ),
            ]);
        }

        // Parse status + expiry
        $statusValidity = $candidate['status_validity'] ?? '';
        $words = explode(" ", $statusValidity);
        $expiryDate = end($words);
        $status = $words[1];

        // Compute 3-check score (licence, active status, location)
        $score = 0;
        $total = 3;
        if ($licCore($candidate['license_number']) === $licCore($validated['licence_number'])) $score++;
        if ($status === 'Active') $score++;
        $score++; // location already matched in selection

        $percentage = round(($score / $total) * 100, 2);

        // Build payload (optional save on 100%)
        preg_match('/\((.*?)\)/', $candidate['facility_name'], $locM);
        $scrapedLocation = $locM[1] ?? '';

        $payload = [
            'name' => $candidate['facility_name'],
            'licence_number_validity' => $status,
            'licence_expiry_date' => $expiryDate,
            'licence_number' => $licCore($candidate['license_number']),
            'location' => $validated['location'] . ' (' . $scrapedLocation . ')',
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
        ];


        if ($percentage === 100.0) {
            Facility::query()->create($payload);
        }

        return Inertia::render('Facilities/Create', [
            'success' => $percentage === 100.0,
            'percentage' => $percentage,
            'checks' => [
                'licence_number_match' => true,
                'status_active' => $status === 'active',
                'location_match' => true,
            ],
            'matched_row' => $payload
        ]);

    }
}
