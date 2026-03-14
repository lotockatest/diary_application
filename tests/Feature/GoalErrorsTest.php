<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class GoalErrorsTest extends TestCase
{
    use RefreshDatabase;
    public function test_goal_creation_fails_with_invalid_data()
    {
        $user = User::create([
            'username' => 'invalidgoal',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('goals.store'), [
            'name' => '',
            'target_count' => 0,
            'target_date' => '',
            'type' => '',
            'related_id' => '',
        ]);
        $response->assertSessionHasErrors([
            'name',
            'target_count',
            'target_date',
            'type',
            'related_id'
        ]);
        $this->assertDatabaseCount('routine_goals', 0);
        $this->assertDatabaseCount('activity_goals', 0);
    }
    public function test_goal_count_must_be_at_least_one()
    {
        $user = User::create([
            'username' => 'counttest',
            'email' => 'count@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('goals.store'), [
            'name' => 'Test Goal',
            'target_count' => 0,
            'target_date' => now()->addDays(5)->format('Y-m-d'),
            'type' => 'routine',
            'related_id' => 1,
        ]);
        $response->assertSessionHasErrors('target_count');
    }
}
