<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    //Fields that can be filled
    protected $fillable = ['user_id', 'name', 'icon'];
    //A routine belongs to a user (relationship)
    public function user() {
        return $this->belongsTo(User::class);
    }
    //An activity can belong to many goals (relationship)
    public function goals() {
        return $this->hasMany(RoutineGoal::class);
    }
}
