<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FacilityController extends Controller
{
    public function index(){
        return Inertia::render('Facilities/Index',[
            'facilities' => Facility::all()
        ]);
    }


    public function create(){
        return Inertia::render('Facilities/Create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:facilities,email|max:255',
            'licence_number' => 'required',
            'location' => 'required',
            'contact_number' => 'required',
        ]);

        Facility::create($validated);
    }
}
