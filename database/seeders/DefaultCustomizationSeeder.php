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
            'moods' => [
                ['name' => 'Happy', 'icon' => 'face-smile'],
                ['name' => 'Sad', 'icon' => 'face-frown'],
                ['name' => 'Energetic', 'icon' => 'bolt'],
            ],
            'routines' => [
                ['name' => 'Morning Routine', 'icon' => 'sun'],
                ['name' => 'Evening Routine', 'icon' => 'moon'],
            ],
            'activities' => [
                ['name' => 'Study', 'icon' => 'academic-cap'],
                ['name' => 'Draw', 'icon' => 'pencil-square'],
                ['name' => 'Read', 'icon' => 'book-open'],
            ],
        ];

        $users = User::all();

        foreach ($users as $user) {

        foreach ($defaults['moods'] as $mood) {
            Mood::firstOrCreate(
                ['user_id' => $user->id, 'name' => $mood['name']],
                ['icon' => $mood['icon']]
            );
        }

        foreach ($defaults['routines'] as $routine) {
            Routine::firstOrCreate(
                ['user_id' => $user->id, 'name' => $routine['name']],
                ['icon' => $routine['icon']]
            );
        }

        foreach ($defaults['activities'] as $activity) {
            Activity::firstOrCreate(
                ['user_id' => $user->id, 'name' => $activity['name']],
                ['icon' => $activity['icon']]
            );
        }
    }
}}