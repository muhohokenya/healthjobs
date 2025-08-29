<?php

namespace App\Listeners;

use App\Events\FacilityVerifiedEvent;
use App\Models\Facility;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FacilityVerifiedListener
{
    public function __construct()
    {
        //
    }

    public function handle(FacilityVerifiedEvent $event): void
    {
        // Access the facility data
        $facility = $event->facility;



        if ($facility['verified']) {
            $response = Facility::query()
                ->where('name', $facility['data']['name'])
                ->where('licence_number', $facility['data']['license_number']);

            $payload = [];
            if (!$response->exists()){
                //Create new facility

                $facilityName = $facility['data']['name'];
                $county = Str::betweenFirst($facilityName, '(', ')');
                $payload['user_id'] = Auth::user()->id;
                $payload['location'] = $county;
                $payload['name'] = $facilityName;
                $payload['licence_number_validity'] = $facility['data']['license_number_validity'];
                $payload['licence_expiry_date'] = $facility['data']['licence_expiry_date'];
                $payload['licence_number'] = $facility['data']['license_number'];
                $payload['contact_number'] = $facility['data']['contact_number'];
                $payload['email'] = $facility['data']['email'];
            }else{
                dd("Facility already exists");
            }

            Facility::create($payload);
        }
    }
}
