<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Auth;

class EventController extends Controller
{
    public function index(){
        // Get the current date
        $currentDate = Carbon::today();

        // Get upcoming events for the logged-in user
        $upcomingEvents = Event::with('participants')->where('start_date', '>=', $currentDate)
                                ->whereHas('participants', function ($query) {
                                    $query->where('user_id', Auth::id());
                                })
                                ->orderBy('start_date')
                                ->get();
        return response()->json(['success' => true, 'events' => $upcomingEvents], 201);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'participants' => 'required|array',
        ]);

        // Create the event
        $event = Event::create([
            'title' => $validatedData['title'],
            'description' => $request->description,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
        ]);
    
        // Attach participants to the event
        $event->participants()->attach($validatedData['participants']);
    
        // Return the created event as JSON
        return response()->json(['success' => true, 'event' => $event], 201);
    }
}
