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
    //Fields that can be filled
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
    //Run when a new user is created
    protected static function booted() {
        static::created(function ($user) {
            //Create default moods, routines and activities
            $moods = [
                ['name' => 'Happy', 'icon' => 'face-smile'],
                ['name' => 'Sad', 'icon' => 'face-frown'],
                ['name' => 'Energetic', 'icon' => 'bolt'],
            ];
            $routines = [
                ['name' => 'Morning Routine', 'icon' => 'sun'],
                ['name' => 'Evening Routine', 'icon' => 'moon'],
            ];
            $activities = [
                ['name' => 'Study', 'icon' => 'academic-cap'],
                ['name' => 'Draw', 'icon' => 'pencil-square'],
                ['name' => 'Read', 'icon' => 'book-open'],
            ];

            foreach ($moods as $mood) {
                Mood::create([
                    'user_id' => $user->id,
                    'name' => $mood['name'],
                    'icon' => $mood['icon'],
                ]);
            }

            foreach ($routines as $routine) {
                Routine::create([
                    'user_id' => $user->id,
                    'name' => $routine['name'],
                    'icon' => $routine['icon'],
                ]);
            }

            foreach ($activities as $activity) {
                Activity::create([
                    'user_id' => $user->id,
                    'name' => $activity['name'],
                    'icon' => $activity['icon'],
                ]);
            }
        });
    }
    //Each user has many goals connected to routines (relationship)
    public function routineGoals() {
        return $this->hasMany(RoutineGoal::class);
    }
    //Each user has many goals connected to activities (relationship)
    public function activityGoals() {
        return $this->hasMany(ActivityGoal::class);
    }
    //Each user has many routines (relationship)
    public function routines() {
        return $this->hasMany(Routine::class);
    }
    //Each user has many activities (relationship)
    public function activities() {
        return $this->hasMany(Activity::class);
    }
}