<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Entry;
use Carbon\Carbon;
use App\Models\ActivityGoal;
use App\Models\RoutineGoal;
use App\Models\Activity;
use App\Models\Routine;

class ViewStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_statistics_page_loads_with_default_timeframe()
    {
        $user1 = User::create([
            'username' => 'user1',
            'email' => 'user1@test.com',
            'password' => bcrypt('password'),
        ]);
        $user2 = User::create([
            'username' => 'user2',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
        ]);
        for ($i = 0; $i < 4; $i++) {
            Entry::create([
                'user_id' => $user1->id,
                'date' => now()->addSeconds($i),
                'mood' => 'Happy',
                'activities' => ['Study'],
                'routines' => ['Morning Routine'],
                'notes' => 'Test note',
            ]);
        }
        for ($i = 0; $i < 2; $i++) {
            Entry::create([
                'user_id' => $user2->id,
                'date' => now()->addSeconds($i + 10),
                'mood' => 'Neutral',
                'activities' => ['Exercise'],
                'routines' => ['Evening Routine'],
                'notes' => 'Test note',
            ]);
        }
        $this->actingAs($user1);
        $response = $this->get(route('statistics'));
        $response->assertStatus(200);
        $response->assertViewHas('timeSpan', 'week');
        $response->assertViewHas('moodsCount');
        $response->assertViewHas('routinesCount');
        $response->assertViewHas('activitiesCount');
        $response->assertViewHas('avgEntriesPerUser', 3);
    }
    public function test_statistics_page_with_custom_timeframe()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $start = Carbon::now()->subDays(7)->format('Y-m-d');
        $end = Carbon::now()->format('Y-m-d');
        $response = $this->get(route('statistics', [
            'time_span' => 'custom',
            'start_date' => $start,
            'end_date' => $end
        ]));
        $response->assertStatus(200);
        $response->assertViewHas('timeSpan', 'custom');
        $response->assertViewHas('start', function($value) use ($start) {
            return $value->format('Y-m-d') === $start;
        });
        $response->assertViewHas('end', function($value) use ($end) {
            return $value->format('Y-m-d') === $end;
        });
        $response->assertViewHas('moodsCount');
        $response->assertViewHas('routinesCount');
        $response->assertViewHas('activitiesCount');
    }
    public function test_statistics_page_displays_data_for_entries()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        Entry::create([
            'user_id' => $user->id,
            'date' => now(),
            'mood' => 'Happy',
            'activities' => ['Study'],
            'routines' => ['Morning Routine'],
            'notes' => 'Test note',
        ]);
        $activity = Activity::create([
            'name' => 'Write',
            'user_id' => $user->id,
            'icon' => 'bolt',
        ]);
        ActivityGoal::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'target_count' => 1,
            'name' => 'Goal',
            'target_date' => Carbon::now()->addDays(5),
        ]);
        $routine = Routine::create([
            'name' => 'Routine',
            'user_id' => $user->id,
            'icon' => 'bolt',
        ]);
        RoutineGoal::create([
            'user_id' => $user->id,
            'routine_id' => $routine->id,
            'target_count' => 1,
            'name' => 'Goal1',
            'target_date' => Carbon::now()->addDays(5),
        ]);
        $response = $this->get(route('statistics'));
        $response->assertStatus(200);
        $viewData = $response->viewData('moodsCount');
        $this->assertArrayHasKey('Happy', $viewData);
        $viewRoutines = $response->viewData('routinesCount');
        $this->assertArrayHasKey('Morning Routine', $viewRoutines);
        $viewActivities = $response->viewData('activitiesCount');
        $this->assertArrayHasKey('Study', $viewActivities);
        $response->assertViewHas('totalEntries', 1);
        $response->assertViewHas('entriesInRange', 1);
        $response->assertViewHas('totalGoals', 2);
        $response->assertViewHas('completedGoals', 0);
    }
}