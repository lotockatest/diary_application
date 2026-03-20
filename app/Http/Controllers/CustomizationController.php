<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mood;
use App\Models\Routine;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class CustomizationController extends Controller
{
    //Show customization page
    public function index() {
        //Get all moods, routines, activities for the logged in user
        $moods = Mood::where('user_id', Auth::id())->get();
        $routines = Routine::where('user_id', Auth::id())->get();
        $activities = Activity::where('user_id', Auth::id())->get();
        //Return the view with the data
        return view('customization', compact('moods', 'routines', 'activities'));
    }
    //Store a new mood
    public function storeMood(Request $request) {
        //First validate the data
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);
        //Creare the mood (only for the current user)
        Mood::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'icon' => $request->icon 
        ]);
        //Return to the customiztion page
        return redirect()->route('customization');
    }
    //Same process for storing moods, but for routines
    public function storeRoutine(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);

        Routine::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return redirect()->route('customization');
    }
    //Same process as for storing moods and routines, but for activities
    public function storeActivity(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'icon' => $request->icon
        ]);

        return redirect()->route('customization');
    }
    //Delete a mood
    public function destroyMood(Mood $mood) {
        //Quick check if the user owns this mood
        if ($mood->user_id !== Auth::id()) {
            abort(403);
        }
        //Delete mood
        $mood->delete();
        return back()->with('success', 'Mood deleted successfully.');
    }
    //Same delete process for routine
    public function destroyRoutine(Routine $routine) {
        if ($routine->user_id !== Auth::id()) {
            abort(403);
        }
        $routine->delete();
        return back()->with('success', 'Routine deleted successfully.');
    }
    //Same delete process for activity
    public function destroyActivity(Activity $activity) {
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }
        $activity->delete();
        return back()->with('success', 'Activity deleted successfully.');
    }
    //Update a routine
    public function updateRoutine(Request $request, Routine $routine) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);
        //Check the user owns the routine
        if ($routine->user_id !== Auth::id()) {
            abort(403);
        }
        //Update parameters of the routine
        $routine->update([
            'name' => $request->name,
            'icon' => $request->icon
        ]);
        return redirect()->route('customization');
    }
    //Same process as updating routine
    public function updateActivity(Request $request, Activity $activity) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }
        $activity->update([
            'name' => $request->name,
            'icon' => $request->icon
        ]);
        return redirect()->route('customization');
    }
//Same process as updating routine
    public function updateMood(Request $request, Mood $mood) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);
        if ($mood->user_id !== Auth::id()) {
            abort(403);
        }
        $mood->update([
            'name' => $request->name,
            'icon' => $request->icon
        ]);
        return redirect()->route('customization');
    }
}
