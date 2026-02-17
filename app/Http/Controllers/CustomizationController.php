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
            'name' => 'required|string|max:255'
        ]);

        Mood::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

        return redirect()->route('customization');
    }

    public function storeRoutine(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Routine::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

        return redirect()->route('customization');
    }

    public function storeActivity(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'name' => $request->name
        ]);

        return redirect()->route('customization');
    }
}
