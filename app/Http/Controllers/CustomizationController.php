<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mood;
use App\Models\Routine;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class CustomizationController extends Controller
{
    //
    public function index() {
        $moods = Mood::where('user_id', Auth::id())->get();
        $routines = Routine::where('user_id', Auth::id())->get();
        $activities = Activity::where('user_id', Auth::id())->get();

        return view('customization', compact('moods', 'routines', 'activities'));
    }

    public function storeMood(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);

        Mood::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'icon' => $request->icon 
        ]);

        return redirect()->route('customization');
    }

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

    public function destroyMood(Mood $mood) {
        if ($mood->user_id !== Auth::id()) {
            abort(403);
        }
        $mood->delete();
        return back()->with('success', 'Mood deleted successfully.');
    }

    public function destroyRoutine(Routine $routine) {
        if ($routine->user_id !== Auth::id()) {
            abort(403);
        }
        $routine->delete();
        return back()->with('success', 'Routine deleted successfully.');
    }

    public function destroyActivity(Activity $activity) {
        if ($activity->user_id !== Auth::id()) {
            abort(403);
        }
        $activity->delete();
        return back()->with('success', 'Activity deleted successfully.');
    }

    public function updateRoutine(Request $request, Routine $routine) {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|string'
        ]);
        if ($routine->user_id !== Auth::id()) {
            abort(403);
        }
        $routine->update([
            'name' => $request->name,
            'icon' => $request->icon
        ]);
        return redirect()->route('customization');
    }

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
