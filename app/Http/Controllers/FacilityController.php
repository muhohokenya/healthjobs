<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Services\PharmacyBoardVerificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;


class FacilityController extends Controller
{
    public function __construct(
        private readonly PharmacyBoardVerificationService $verificationService
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
        $validator = \Validator::make($request->all(), [
            'licence_number' => [
                'required',
                'size:11',
                'unique:facilities,licence_number'
            ],
            'location' => 'required',
            'name' => 'required',
            'contact_number' => ['required','size:10','unique:facilities,contact_number'],
            'email' => ['required','email','unique:facilities,email'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $facilityData = $request->only(['licence_number','name','location','contact_number','email']);
            $verification = $this->verificationService->verifyFacility($facilityData);



            if ($verification['success'] === false) {
                $validator->errors()->add(
                    'licence_number',
                    'No matching facility found in the registry with the Licence Number '.$request->licence_number
                );
            }
        });



        $validated = $validator->validate();



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



        $verification['message'] = 'No matching facility found in the registry with the Licence Number '.$validated['licence_number'];
        if($verification['success']===false){
            return redirect(route('facilities.create'),303)->with([
                'myVariable' => $verification['message'] ,
            ]);
        }else if ($verification['success']===true){
            Facility::query()->create($verification['data']);
            return to_route('facilities.index','',303);
        }


    }
}
