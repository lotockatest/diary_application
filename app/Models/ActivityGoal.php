<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Entry;

class ActivityGoal extends Model
{
    //Used for testing and filling database
    use HasFactory;
    //Fields that can be filled
    protected $fillable = ['user_id','activity_id','name','progress','target_count','target_date','status'];
    protected $casts = ['target_date' => 'date'];
    //A goal belongs to the user (relationship)
    public function user() {
        return $this->belongsTo(User::class);
    }
    //A goal is attatched to an activity (relationship)
    public function activity() {
        return $this->belongsTo(Activity::class);
    }
    //Count % complete
    public function getPercentageAttribute() {
        //Avoid dividing by 0
        if (!$this->target_count || $this->target_count == 0) {
            return 0;
        }
        //If it is missing do not count
        if (!$this->activity) {
                return 0;
        }
        //Get the name of the activity
        $activityName = $this->activity->name;
        //Count how often it appears in all entries
        $completedCount = Entry::where('user_id', $this->user_id)
            ->where('created_at', '>=', $this->created_at)
            ->whereJsonContains('activities', $activityName)
            ->count();
        //Return it as % (make sure it doesn't go over 100)
        return min(100, round(($completedCount / $this->target_count) * 100));
    }

}
