<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Services\PharmacyBoardVerificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\DomCrawler\Crawler;


class FacilityController extends Controller
{
    public function __construct(
        private PharmacyBoardVerificationService $verificationService
    ) {

    }
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
            'licence_number' => [
                'required',
                'size:11',
                'unique:facilities,licence_number'
            ],
            'location'=>'required',
            'name'=>'required',
            'contact_number'=>['required','size:11','unique:facilities,contact_number'],
            'email'=>['required','email','unique:facilities,email'],
        ]);


        $facilityData = [
            'licence_number' => $validated['licence_number'],
            'name' => $request->name,
            'location' => $validated['location'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'licence_expiry_date' => $validated['email'],
        ];



        // Use the verification service
        $verification = $this->verificationService->verifyFacility($facilityData);


        if($verification['success']===false){
            return redirect(route('facilities.create'),303)->with([
                'myVariable' => $verification['message'],
            ]);
        }else if ($verification['success']===true){
//            Facility::query()->create($verification['data']);
            return to_route('facilities.index','',303);
        }


    }
}
