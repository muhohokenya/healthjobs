<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index()
    {
        return Inertia::render('Events/Index', [
            'events' => Event::all(),
        ]);
    }


    public function create(){
        return Inertia::render('Events/Create');
    }
}
