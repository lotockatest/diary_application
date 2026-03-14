<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Entry;
use Carbon\Carbon;

class ViewStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_statistics_page_loads_with_default_timeframe()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->get(route('statistics'));
        $response->assertStatus(200);
        $response->assertViewHas('timeSpan', 'week');
        $response->assertViewHas('moodsCount');
        $response->assertViewHas('routinesCount');
        $response->assertViewHas('activitiesCount');
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
        $response = $this->get(route('statistics'));
        $response->assertStatus(200);
        $viewData = $response->viewData('moodsCount');
        $this->assertArrayHasKey('Happy', $viewData);
        $viewRoutines = $response->viewData('routinesCount');
        $this->assertArrayHasKey('Morning Routine', $viewRoutines);
        $viewActivities = $response->viewData('activitiesCount');
        $this->assertArrayHasKey('Study', $viewActivities);
    }
}