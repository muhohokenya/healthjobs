<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Collection;

class PharmacyBoardVerificationService
{
    private const BASE_URL = 'https://practice.pharmacyboardkenya.org/ajax/public';
    private const FACILITY_CADRE = 'Facilities';
    private const PRACTITIONER_CADRE = 2;

    /**
     * Verify a facility against the pharmacy board registry
     *
     * @param array $facilityData
     * @return array
     */
    public function verifyFacility(array $facilityData): array
    {
        $response = $this->makeRequest([
            'search_register' => 1,
            'cadre_id' => self::FACILITY_CADRE,
            'search_text' => $facilityData['name'],
        ]);

        if (!$response['success']) {
            return $response;
        }

        $facilities = $this->parseFacilityResponse($response['data']);
        $candidate = $this->findMatchingFacility($facilities, $facilityData);

        if (!$candidate) {
            return [
                'success' => false,
                'message' => 'No matching facility found in the registry',
                'data' => null
            ];
        }

        return $this->processFacilityMatch($candidate, $facilityData);
    }

    function isNameMatch($givenName, $verifiedName, $threshold = 80) {
        $givenParts = array_filter(explode(' ', strtolower(trim($givenName))));
        $verifiedParts = array_filter(explode(' ', strtolower(trim($verifiedName))));

        $maxSimilarity = 0;

        foreach ($givenParts as $givenPart) {
            foreach ($verifiedParts as $verifiedPart) {
                $similarity = 0;
                similar_text($givenPart, $verifiedPart, $similarity);
                $maxSimilarity = max($maxSimilarity, $similarity);
            }
        }

        return $maxSimilarity >= $threshold;
    }

    /**
     * Verify a practitioner against the pharmacy board registry
     *
     * @param string $licenceNumber
     * @return array
     */
    public function verifyPractitioner(string $licenceNumber,Request $request,$strict = false): array
    {
        $response = $this->makeRequest([
            'search_register' => 1,
            'cadre_id' => self::PRACTITIONER_CADRE,
            'search_text' => $licenceNumber,
        ]);

        if (!$response['success']) {
            return $response;
        }

        $practitioner = $this->parsePractitionerResponse($response['data'], $licenceNumber);

        if (!$practitioner) {
            return [
                'success' => false,
                'message' => 'License number not found or invalid',
                'data' => null
            ];
        }

        //We can decide later if we want to reject expired licences
        if ($practitioner['status'] !== 'Active') {
            return [
                'success' => false,
                'message' => "License is {$practitioner['status']}. Only active licenses are valid",
                'data' => $practitioner
            ];
        }


        $given_name = strtolower($request->get('name'));
        $verified_name = strtolower($practitioner['name']);

        if ($this->isNameMatch($given_name, $verified_name)) {
            return [
                'success' => true,
                'message' => 'Practitioner verified successfully',
                'data' => $practitioner
            ];
        } else {
            return [
                'success' => false,
                'message' => "License number is valid but the name provided doesn't match the registered license holder. Please verify your details and try again.",
                'data' => $practitioner
            ];
        }
    }

    /**
     * Make HTTP request to pharmacy board API
     *
     * @param array $payload
     * @return array
     */
    private function makeRequest(array $payload): array
    {
        try {
            $response = Http::timeout(30)->asForm()->post(self::BASE_URL, $payload);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Verification service is currently unavailable',
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'data' => $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to connect to verification service',
                'data' => null
            ];
        }
    }

    /**
     * Parse facility response HTML
     *
     * @param string $html
     * @return Collection
     */
    private function parseFacilityResponse(string $html): Collection
    {
        $crawler = new Crawler($html);

        return collect($crawler->filter('table.facility_datatables tbody tr')->each(function (Crawler $row) {
            $cols = $row->filter('td')->each(fn(Crawler $c) => trim($c->text()));

            return [
                'facility_name' => $cols[0] ?? null,
                'license_number' => $cols[1] ?? null,
                'status_validity' => $cols[2] ?? null,
                'details' => $row->filter('a.popStatus')->count() ?
                    $row->filter('a.popStatus')->attr('rel') : null,
            ];
        }));
    }

    /**
     * Parse practitioner response HTML
     *
     * @param string $html
     * @param string $licenceNumber
     * @return array|null
     */
    private function parsePractitionerResponse(string $html, string $licenceNumber): ?array
    {
        $crawler = new Crawler($html);
        $practitioner = null;

        $crawler->filter('#datatable2 tbody tr')->each(function (Crawler $row) use (&$practitioner, $licenceNumber) {
            if ($practitioner) return;

            $name = trim($row->filter('td')->eq(0)->text());
            $license = trim($row->filter('td')->eq(1)->text());
            $statusValidity = trim($row->filter('td')->eq(2)->text());

            if ($license === $licenceNumber) {
                $words = explode(" ", $statusValidity);
                $expiryDate = end($words);
                $status = $words[1] ?? 'Unknown';

                $practitioner = [
                    'name' => $name,
                    'licence_number' => $license,
                    'expiry_date' => $expiryDate,
                    'status' => $status,
                ];
            }
        });

        return $practitioner;
    }

    /**
     * Find matching facility from parsed results
     *
     * @param Collection $facilities
     * @param array $facilityData
     * @return array|null
     */
    private function findMatchingFacility(Collection $facilities, array $facilityData): ?array
    {
        return $facilities->first(function ($facility) use ($facilityData) {
            $nameRaw = $facility['facility_name'] ?? '';

            // Extract name without location in brackets
            $nameOnly = trim(preg_replace('/\s*\(.+\)$/', '', $nameRaw));

            // Extract location from brackets
            preg_match('/\((.*?)\)/', $nameRaw, $matches);
            $scrapedLocation = $this->normalize($matches[1] ?? '');

            // Extract core license number (before dash)
            $facilityLicenseCore = $this->getLicenseCore($facility['license_number']);
            $inputLicenseCore = $this->getLicenseCore($facilityData['licence_number']);

            $nameMatch = $this->normalize($nameOnly) === $this->normalize($facilityData['name']);
            $locationMatch = $scrapedLocation === $this->normalize($facilityData['location']);
            $licenseMatch = $facilityLicenseCore === $inputLicenseCore;

            return $nameMatch && $locationMatch && $licenseMatch;
        });
    }

    /**
     * Process facility match and calculate verification score
     *
     * @param array $candidate
     * @param array $facilityData
     * @return array
     */
    private function processFacilityMatch(array $candidate, array $facilityData): array
    {
        // Parse status and expiry
        $statusValidity = $candidate['status_validity'] ?? '';
        $words = explode(" ", $statusValidity);
        $expiryDate = end($words);
        $status = $words[1] ?? 'Unknown';

        // Calculate verification score
        $score = 0;
        $total = 3;

        $licenseMatch = $this->getLicenseCore($candidate['license_number']) ===
            $this->getLicenseCore($facilityData['licence_number']);
        $statusActive = $status === 'Active';
        $locationMatch = true; // Already matched in selection

        if ($licenseMatch) $score++;
        if ($statusActive) $score++;
        if ($locationMatch) $score++;

        $percentage = round(($score / $total) * 100, 2);

        // Extract scraped location for payload
        preg_match('/\((.*?)\)/', $candidate['facility_name'], $locMatches);
        $scrapedLocation = $locMatches[1] ?? '';

        $payload = [
            'name' => $candidate['facility_name'],
            'licence_number_validity' => $status,
            'licence_expiry_date' => $expiryDate,
            'licence_number' => $this->getLicenseCore($candidate['license_number']),
            'location' => $facilityData['location'] . ' (' . $scrapedLocation . ')',
            'contact_number' => $facilityData['contact_number'],
            'email' => $facilityData['email'],
        ];

        return [
            'success' => true,
            'verified' => $percentage === 100.0,
            'percentage' => $percentage,
            'checks' => [
                'licence_number_match' => $licenseMatch,
                'status_active' => $statusActive,
                'location_match' => $locationMatch,
            ],
            'data' => $payload,
            'message' => $percentage === 100.0 ?
                'Facility fully verified' :
                'Facility partially verified'
        ];
    }

    /**
     * Normalize string for comparison
     *
     * @param string $string
     * @return string
     */
    private function normalize(string $string): string
    {
        return strtoupper(trim(preg_replace('/\s+/', ' ', $string)));
    }

    /**
     * Extract core license number (before dash)
     *
     * @param string $license
     * @return string
     */
    private function getLicenseCore(string $license): string
    {
        $core = trim(explode('-', $license)[0] ?? '');
        return strtoupper($core);
    }
}
