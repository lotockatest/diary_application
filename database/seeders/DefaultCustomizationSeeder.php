<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mood;
use App\Models\Routine;
use App\Models\Activity;
use App\Models\User;

class DefaultCustomizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //
        $defaults = [
        'moods' => ['Happy', 'Sad', 'Neutral'],
        'routines' => ['Morning Routine', 'Evening Routine'],
        'activities' => ['Study', 'Exercise', 'Read'],
        ];

        $users = User::all();

        foreach ($users as $user) {

        foreach ($defaults['moods'] as $mood) {
            Mood::firstOrCreate([
                'user_id' => $user->id,
                'name' => $mood
            ]);
        }

        foreach ($defaults['routines'] as $routine) {
            Routine::firstOrCreate([
                'user_id' => $user->id,
                'name' => $routine
            ]);
        }

         foreach ($defaults['activities'] as $activity) {
            Activity::firstOrCreate([
                'user_id' => $user->id,
                'name' => $activity
            ]);
        }
    }
}}