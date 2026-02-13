<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    //
     public function store(Request $request)
    {
        $request->validate([
            'mood' => 'required|string',
            'activities' => 'required|array',
            'routines' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        Entry::create([
            'user_id' => Auth::id(),
            'mood' => $request->mood,
            'activities' => $request->activities ?? [],
            'routines' => $request->routines ?? [],
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Entry saved!');
    }
}
