<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoutineGoal;
use App\Models\ActivityGoal;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoalController extends Controller
{
    //
    public function index(Request $request) {
        $user = $request->user();
        $routineGoals = $user->routineGoals()->with('routine')->get()
            ->map(function ($goal) {
                $goal->type = 'routine';
                $goal->related_id = $goal->routine_id;
                return $goal;
            });
        $activityGoals = $user->activityGoals()->with('activity')->get()
            ->map(function ($goal) {
                $goal->type = 'activity';
                $goal->related_id = $goal->activity_id;
                return $goal;
            });
        $goals = $routineGoals->merge($activityGoals)->sortBy('target_date');

        return view('goals', compact('goals'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'target_count' => 'required|integer|min:1',
            'target_date' => 'required|date',
            'type' => 'required|in:routine,activity',
            'related_id' => 'required|integer'
        ]);
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

    public function destroy($id) {
        $userId = Auth::id();
        $routineGoal = RoutineGoal::where('id', $id)
            ->where('user_id', $userId)
            ->first();
        if ($routineGoal) {
            $routineGoal->delete();
            return redirect()->route('goals.index');
        }
        $activityGoal = ActivityGoal::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $activityGoal->delete();

        return redirect()->route('goals.index');
    }
}
