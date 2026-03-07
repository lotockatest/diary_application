<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    //
    public function show(){
        return view('profile', ['user' => Auth::user()]);
    }
    public function update(Request $request){
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ]);
        $user = User::findOrFail(Auth::id());
        $user->update([
            'username' => $request->username,
            'email' => $request->email
        ]);
        return back()->with('success', 'Profile updated successfully.');
    }

    public function delete(){
        $user = User::findOrFail(Auth::id());
        Auth::logout();
        $user->delete();
        $user->routines()->delete();
        $user->activities()->delete();
        $user->routineGoals()->delete();
        $user->activityGoals()->delete();
        return redirect('/login')->with('success', 'Profile deleted.');
    }
}
