<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Entry;
use App\Models\User;
use App\Models\ActivityGoal;
use App\Models\RoutineGoal;

class StatisticsController extends Controller
{
    //Show page
    public function index(Request $request) {
        //Get the current user id
        $userId = Auth::id();
        //Check timespan, default time range (week)
        $timeSpan = $request->time_span ?? 'week';
        //Determine range based on a selected timespan
        switch ($timeSpan) {
            case 'month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            case 'custom':
                //Use the custom dates if they are given otherwise can go back to default
                $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfWeek();
                $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfWeek();
                break;
            case 'week':
            default:
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
        }
        //Get entries within the selected date range for the specific user
        $entries = Entry::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->get();
        //Total entries count (for current user, all time)
        $totalEntries = Entry::where('user_id', $userId)->count();
        //Entries in the selected time (current user)
        $entriesInRange = $entries->count();
        //Total entries in range (all users)
        $totalEntriesAllUsers = Entry::whereBetween('date', [$start, $end])->count();
        //Total number of users
        $totalUsers = User::count();
        //Average entries per user (in selected time)
        $avgEntriesPerUser = $totalUsers > 0 ? round($totalEntriesAllUsers / $totalUsers, 2) : 0;
        //Total goals created (current user)
        $totalGoals = ActivityGoal::where('user_id', $userId)->count() + RoutineGoal::where('user_id', $userId)->count();
        //Get all goals for current user
        $activityGoals = ActivityGoal::where('user_id', $userId)->get();
        $routineGoals = RoutineGoal::where('user_id', $userId)->get();
        //Amount of completed goals
        $completedGoals = $activityGoals->filter(fn($goal) => $goal->percentage >= 100)->count() + $routineGoals->filter(fn($goal) => $goal->percentage >= 100)->count();
        //Variables to used to store counts
        $moodsCount = [];
        $routinesCount = [];
        $activitiesCount = [];
        //Simple loop through all entries and add count based on occurence
        foreach ($entries as $entry) {

            $moodsCount[$entry->mood] = ($moodsCount[$entry->mood] ?? 0) + 1;
            //Each entry may have multiple routines and activities
            foreach ($entry->routines as $routine) {
                $routinesCount[$routine] = ($routinesCount[$routine] ?? 0) + 1;
            }

            foreach ($entry->activities as $activity) {
                $activitiesCount[$activity] = ($activitiesCount[$activity] ?? 0) + 1;
            }
        }
        //Return the view with the calculated data
        return view('statistics', compact(
            'moodsCount', 
            'routinesCount', 
            'activitiesCount',
            'timeSpan',
            'start',
            'end',
            'totalEntries',
            'entriesInRange',
            'avgEntriesPerUser',
            'totalGoals',
            'completedGoals'
        ));
    }
}
