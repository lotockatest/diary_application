<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mood;
use App\Models\Routine;
use App\Models\Activity;

class EntryController extends Controller
{
    //Store or update entry in the calendar
    public function store(Request $request) {
        //Validate the data in the incoming request
        $request->validate([
            'date' => 'required|date',
            'mood' => 'required|string',
            'activities' => 'required|array',
            'routines' => 'required|array',
            'notes' => 'nullable|string',
        ]);
        //Only allow storing the entry for the current date
        if ($request->date !== now()->format('Y-m-d')) {
            return redirect()->back();
        }
        //Change entry for the current user for today
        Entry::updateOrCreate([
            'user_id' => Auth::id(),
            'date' => Carbon::parse($request->date)->format('Y-m-d'),
        ],
        [
            'mood' => $request->mood,
            'activities' => $request->activities ?? [],
            'routines' => $request->routines ?? [],
            'notes' => $request->notes,
        ]);
        //Redirect back to the calendar
        return redirect()->back()->with('success', 'Entry saved!');
    }
    //Show the homepage(the calendar page)
    public function index() {
        //Get all of the entries
        $entries = Entry::where('user_id', Auth::id())->get();
        //Get all moods, activities and routines
        $moods = Mood::where('user_id', Auth::id())->get();
        $activities = Activity::where('user_id', Auth::id())->get();
        $routines = Routine::where('user_id', Auth::id())->get();
        //Return the completed view with the data
        return view('home', compact('entries','moods','activities','routines'));
    }
}
