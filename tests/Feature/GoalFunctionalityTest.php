<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GoalFunctionalityTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_view_goals_page()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->get(route('goals.index'));
        $response->assertStatus(200);
        $response->assertSee('Goals');
        $response->assertSee('Add Goal');
    }
    public function test_user_can_create_new_goal_and_is_redirected()
    {
        $user = User::create([
            'username' => 'goaluser',
            'email' => 'goal@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $goalName = 'Goal Name';
        $targetCount = 5;
        $targetDate = Carbon::now()->addDays(7)->format('Y-m-d');
        $response = $this->post(route('goals.store'), [
            'name' => $goalName,
            'target_count' => $targetCount,
            'target_date' => $targetDate,
            'type' => 'routine',
            'related_id' => '1',
        ]);
        $this->assertDatabaseHas('routine_goals', [
            'user_id' => $user->id,
            'name' => $goalName,
            'target_count' => $targetCount,
        ]);
        $response->assertRedirect(route('goals.index'));
    }
    public function test_user_can_edit_goal()
    {
        $user = User::create([
            'username' => 'editgoaluser',
            'email' => 'editgoal@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $targetDate = Carbon::now()->addDays(7)->format('Y-m-d');
        $this->post(route('goals.store'), [
            'name' => 'Original Goal',
            'target_count' => 3,
            'target_date' => $targetDate,
            'type' => 'routine',
            'related_id' => 1,
        ]);
        $goal = DB::table('routine_goals')->first();
        $response = $this->put(route('goals.update', $goal->id), [
            'name' => 'Updated Goal',
            'target_count' => 10,
            'target_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'type' => 'routine',
            'related_id' => 1,
        ]);
        $response->assertRedirect(route('goals.index'));
        $this->assertDatabaseHas('routine_goals', [
            'id' => $goal->id,
            'name' => 'Updated Goal',
            'target_count' => 10,
        ]);
    }
    public function test_user_can_delete_goal()
    {
        $user = User::create([
            'username' => 'deletegoaluser',
            'email' => 'deletegoal@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $this->post(route('goals.store'), [
            'name' => 'Goal To Delete',
            'target_count' => 4,
            'target_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'type' => 'routine',
            'related_id' => 1,
        ]);
        $goal = DB::table('routine_goals')
            ->where('user_id', $user->id)
            ->first();
        $response = $this->delete(route('goals.destroy', $goal->id));
        $response->assertRedirect(route('goals.index'));
        $this->assertDatabaseMissing('routine_goals', [
            'id' => $goal->id
        ]);
    }
}
