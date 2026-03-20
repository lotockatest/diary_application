<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    //Fields that can be filled
    protected $fillable = ['user_id', 'name', 'icon'];
    //A mood belongs to a user (relationship)
    public function user() {
        return $this->belongsTo(User::class);
    }
}
