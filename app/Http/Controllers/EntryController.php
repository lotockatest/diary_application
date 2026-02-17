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
    //
    public function store(Request $request) {

        $request->validate([
            'date' => 'required|date',
            'mood' => 'required|string',
            'activities' => 'required|array',
            'routines' => 'required|array',
            'notes' => 'nullable|string',
        ]);

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

        return redirect()->back()->with('success', 'Entry saved!');
    }

    public function index() {

        $entries = Entry::where('user_id', Auth::id())->get();
        $moods = Mood::where('user_id', Auth::id())->get();
        $activities = Activity::where('user_id', Auth::id())->get();
        $routines = Routine::where('user_id', Auth::id())->get();

        return view('home', compact('entries','moods','activities','routines'));
    }
}
