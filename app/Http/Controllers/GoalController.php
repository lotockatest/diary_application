<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoutineGoal;
use App\Models\ActivityGoal;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoalController extends Controller
{
    //Show the goals page
    public function index(Request $request) {
        //Get the user that is currently logged in
        $user = $request->user();
        //Get the goals attached to routines
        $routineGoals = $user->routineGoals()->with('routine')->get()
            ->map(function ($goal) {
                $goal->type = 'routine';
                $goal->related_id = $goal->routine_id;
                return $goal;
            });
        //Get the goals attached to activities
        $activityGoals = $user->activityGoals()->with('activity')->get()
            ->map(function ($goal) {
                $goal->type = 'activity';
                $goal->related_id = $goal->activity_id;
                return $goal;
            });
        //Merge both activity and routine goals into one collection and sort by the date
        $goals = $routineGoals->merge($activityGoals)->sortBy('target_date');
        //Return the view
        return view('goals', compact('goals'));
    }
    //Store a new goal
    public function store(Request $request) {
        //Validate all of the input
        $request->validate([
            'name' => 'required|string|max:255',
            'target_count' => 'required|integer|min:1',
            'target_date' => 'required|date',
            'type' => 'required|in:routine,activity',
            'related_id' => 'required|integer'
        ]);
        //if it is a routine, store in the routine table
        if ($request->type === 'routine') {
            RoutineGoal::create([
                'user_id' => Auth::id(),
                'routine_id' => $request->related_id,
                'name' => $request->name,
                'target_count' => $request->target_count,
                'target_date' => $request->target_date,
                'status' => 'ongoing'
            ]);
        }
        //if it is an activity, store in the activity table
        if ($request->type === 'activity') {
            ActivityGoal::create([
                'user_id' => Auth::id(),
                'activity_id' => $request->related_id,
                'name' => $request->name,
                'target_count' => $request->target_count,
                'target_date' => $request->target_date,
                'status' => 'ongoing'
            ]);
        }
        return redirect()->route('goals.index');
    }
    //Used to update a goal that already exists (similar process to creation)
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_count' => 'required|integer|min:1',
            'target_date' => 'required|date',
            'type' => 'required|in:routine,activity',
            'related_id' => 'required|integer'
        ]);
        if ($request->type === 'routine') {
            $goal = RoutineGoal::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $goal->update([
                'routine_id' => $request->related_id,
                'name' => $request->name,
                'target_count' => $request->target_count,
                'target_date' => $request->target_date,
            ]);
        }
        if ($request->type === 'activity') {
            $goal = ActivityGoal::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            $goal->update([
                'activity_id' => $request->related_id,
                'name' => $request->name,
                'target_count' => $request->target_count,
                'target_date' => $request->target_date,
            ]);
        }
        return redirect()->route('goals.index');
    }
    //Used to delete a goal
    public function destroy($id) {
        $userId = Auth::id();
        //Try to find the goal in routine
        $routineGoal = RoutineGoal::where('id', $id)
            ->where('user_id', $userId)
            ->first();
        if ($routineGoal) {
            $routineGoal->delete();
            return redirect()->route('goals.index');
        }
        //If not found in routine, find in activity
        $activityGoal = ActivityGoal::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $activityGoal->delete();

        return redirect()->route('goals.index');
    }
}
