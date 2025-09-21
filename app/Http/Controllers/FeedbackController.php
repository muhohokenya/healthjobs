<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeedbackController extends Controller
{
    public function index(){
        return Inertia::render('Feedback/Index', [
            'feedbacks' => Feedback::all()
        ]);
    }

    public function create(Request $request){}

    public function store(Request $request){}
}
