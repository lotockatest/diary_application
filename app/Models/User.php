<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Notifications\Notifiable;
use App\Models\Mood;
use App\Models\Routine;
use App\Models\Activity;

class User extends Authenticatable
{
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected static function booted() {
        static::created(function ($user) {

            $moods = ['Happy', 'Sad', 'Neutral'];
            $routines = ['Morning Routine', 'Evening Routine'];
            $activities = ['Study', 'Exercise', 'Read'];

            foreach ($moods as $mood) {
                Mood::create([
                    'user_id' => $user->id,
                    'name' => $mood,
                ]);
            }

            foreach ($routines as $routine) {
                Routine::create([
                    'user_id' => $user->id,
                    'name' => $routine,
                ]);
            }

            foreach ($activities as $activity) {
                Activity::create([
                    'user_id' => $user->id,
                    'name' => $activity,
                ]);
            }
        });
    }
}