<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    //Show the page
    public function show(){
        //Check for currently logged in user and pass them the page
        return view('profile', ['user' => Auth::user()]);
    }
    //Update the information on the page
    public function update(Request $request){
        //Validation
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255'
        ]);
        //Find the user to update their information only
        $user = User::findOrFail(Auth::id());
        $user->update([
            'username' => $request->username,
            'email' => $request->email
        ]);
        return back()->with('success', 'Profile updated successfully.');
    }
    //Remove account (delete all data related to user)
    public function delete(){
        //Find the user and log them out
        $user = User::findOrFail(Auth::id());
        Auth::logout();
        //Delete all their activities, routines, moods, goals and other user information
        $user->delete();
        $user->routines()->delete();
        $user->activities()->delete();
        $user->routineGoals()->delete();
        $user->activityGoals()->delete();
        return redirect('/login')->with('success', 'Profile deleted.');
    }
}
