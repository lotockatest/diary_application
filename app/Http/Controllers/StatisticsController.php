<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Entry;

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
            'end'
        ));
    }
}
