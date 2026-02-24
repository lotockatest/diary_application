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

    public function routineGoals() {
        return $this->hasMany(RoutineGoal::class);
    }

    public function activityGoals() {
        return $this->hasMany(ActivityGoal::class);
    }

    public function routines() {
        return $this->hasMany(Routine::class);
    }

    public function activities() {
        return $this->hasMany(Activity::class);
    }
}