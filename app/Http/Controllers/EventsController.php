<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventsController extends Controller
{
    public function index()
    {
        return Inertia::render('Events/Index', [
            'events' => Event::all(),
        ]);
    }


    public function create()
    {
        return Inertia::render('Events/Create');
    }

    public function store(EventRequest $request)
    {

        $filename = '';
        $slimPayload = $request->get('slim');
        if (is_array($slimPayload) && !empty($slimPayload[0])) {
            $decodedData = json_decode($slimPayload[0], true);

            if (is_array($decodedData) && isset($decodedData['output']['image'])) {
                // Get the base64 image data
                $imageData = $decodedData['output']['image'];
                $parts = explode(',', (string)$imageData, 2);
                if (count($parts) === 2) {
                    $base64Data = $parts[1];
                    $binaryData = base64_decode($base64Data, true);

                    if ($binaryData !== false) {
                        // Delete old avatar if it exists
                        // Generate filename with user ID for better organization
                        $filename = 'events/event' . '_' . uniqid('', true) . '.jpg';
                        $request->validated()['image_path'] = $filename;


//                        Storage::disk('public')->put($filename, $binaryData);

                        Storage::disk('public')->put($filename, $binaryData, ['visibility' => 'public']);

                        $decodedData['output']['url'] = Storage::url($filename);

                    }
                }
                $start_date = Carbon::parse($request->start_date);
                $end_date = Carbon::parse($request->end_date);

                $validatedData = $request->validated();
                $validatedData['start_date'] = $start_date;
                $validatedData['end_date'] = $end_date;
                $validatedData['image_path'] = $filename;


                Event::query()->create($validatedData);

                return Redirect::route('events.index');

            }
        }
    }
}
