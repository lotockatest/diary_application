<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //Fields that can be filled
    protected $fillable = ['user_id', 'name', 'icon'];
    //Make sure activity belongs to the user (relationship)
    public function user() {
        return $this->belongsTo(User::class);
    }
    //Make sure an activity can belong to many goals (relationship)
    public function goals() {
        return $this->hasMany(ActivityGoal::class);
    }
}
