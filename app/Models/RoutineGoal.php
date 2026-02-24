<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\Entry;

class RoutineGoal extends Model
{
    //
    use HasFactory;

    protected $fillable = ['user_id', 'routine_id', 'name', 'progress', 'target_count', 'target_date', 'status'];
    protected $casts = ['target_date' => 'date'];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function routine() {
        return $this->belongsTo(Routine::class);
    }

    public function getPercentageAttribute() {
        if (!$this->target_count || $this->target_count == 0) {
            return 0;
        }
        if (!$this->routine) {
            return 0;
        }
        $routineName = $this->routine->name;
        $completedCount = Entry::where('user_id', $this->user_id)
            ->where('created_at', '>=', $this->created_at)//
            ->whereJsonContains('routines', $routineName)
            ->count();
        return min(100, round(($completedCount / $this->target_count) * 100));
    }
}
