<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Entry;

class StatisticsController extends Controller
{
    //
    public function index(Request $request) {

        $userId = Auth::id();

        $timeSpan = $request->time_span ?? 'week';

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
                $start = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfWeek();
                $end = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfWeek();
                break;
            case 'week':
            default:
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
        }

        $entries = Entry::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->get();

        $moodsCount = [];
        $routinesCount = [];
        $activitiesCount = [];

        foreach ($entries as $entry) {

            $moodsCount[$entry->mood] = ($moodsCount[$entry->mood] ?? 0) + 1;

            foreach ($entry->routines as $routine) {
                $routinesCount[$routine] = ($routinesCount[$routine] ?? 0) + 1;
            }

            foreach ($entry->activities as $activity) {
                $activitiesCount[$activity] = ($activitiesCount[$activity] ?? 0) + 1;
            }
        }

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
