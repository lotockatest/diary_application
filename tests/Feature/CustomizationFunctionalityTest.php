<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class CustomizationFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_new_routine()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->get(route('customization'));
        $response->assertStatus(200);
        $response->assertSee('Customization');
        $newRoutineName = 'Evening Walk';
        $response = $this->post(route('customization.routine.store'), [
            'name' => $newRoutineName,
            'icon' => 'sun',
        ]);
        $this->assertDatabaseHas('routines', [
            'user_id' => $user->id,
            'name' => $newRoutineName,
            'icon' => 'sun',
        ]);
        $response->assertRedirect(route('customization'));
    }

    public function test_user_can_add_new_mood()
    {
        $user = User::create([
            'username' => 'testuser2',
            'email' => 'test2@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $newMoodName = 'Joy';
        $response = $this->post(route('customization.mood.store'), [
            'name' => $newMoodName,
            'icon' => 'face-smile',
        ]);
        $this->assertDatabaseHas('moods', [
            'user_id' => $user->id,
            'name' => $newMoodName,
            'icon' => 'face-smile',
        ]);
        $response->assertRedirect(route('customization'));
    }

    public function test_user_can_add_new_activity()
    {
        $user = User::create([
            'username' => 'testuser3',
            'email' => 'test3@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $newActivityName = 'Workout';
        $response = $this->post(route('customization.activity.store'), [
            'name' => $newActivityName,
            'icon' => 'bolt',
        ]);
        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'name' => $newActivityName,
            'icon' => 'bolt',
        ]);
        $response->assertRedirect(route('customization'));
    }

    public function test_user_can_delete_routine()
    {
        $user = User::create([
            'username' => 'deleteroutine',
            'email' => 'delete@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $routine = \App\Models\Routine::create([
            'user_id' => $user->id,
            'name' => 'Morning Run',
            'icon' => 'bolt',
        ]);
        $response = $this->delete(route('customization.routine.destroy', $routine));
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('routines', [
            'id' => $routine->id,
        ]);
    }
    public function test_user_can_delete_activity()
    {
        $user = User::create([
            'username' => 'deleteactivity',
            'email' => 'delete@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $activity = \App\Models\Activity::create([
            'user_id' => $user->id,
            'name' => 'Workout',
            'icon' => 'bolt',
        ]);
        $response = $this->delete(route('customization.activity.destroy', $activity));
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('activities', [
            'id' => $activity->id,
        ]);
    }
    public function test_user_can_delete_mood()
    {
        $user = User::create([
            'username' => 'deletemood',
            'email' => 'delete@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $mood = \App\Models\Mood::create([
            'user_id' => $user->id,
            'name' => 'Motivated',
            'icon' => 'bolt',
        ]);
        $response = $this->delete(route('customization.mood.destroy', $mood));
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('moods', [
            'id' => $mood->id,
        ]);
    }
    public function test_user_can_edit_routine()
    {
        $user = User::create([
            'username' => 'editroutine',
            'email' => 'edit@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $routine = \App\Models\Routine::create([
            'user_id' => $user->id,
            'name' => 'Morning Run',
            'icon' => 'bolt',
        ]);
        $response = $this->put(route('customization.routine.update', $routine), [
            'name' => 'Evening Run',
            'icon' => 'moon',
        ]);
        $response->assertRedirect(route('customization'));
        $this->assertDatabaseHas('routines', [
            'id' => $routine->id,
            'name' => 'Evening Run',
            'icon' => 'moon',
        ]);
    }
    public function test_user_can_edit_activity()
    {
        $user = User::create([
            'username' => 'editactivity',
            'email' => 'edit@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $activity = \App\Models\Activity::create([
            'user_id' => $user->id,
            'name' => 'Workout',
            'icon' => 'bolt',
        ]);
        $response = $this->put(route('customization.activity.update', $activity), [
            'name' => 'Nap',
            'icon' => 'moon',
        ]);
        $response->assertRedirect(route('customization'));
        $this->assertDatabaseHas('activities', [
            'id' => $activity->id,
            'name' => 'Nap',
            'icon' => 'moon',
        ]);
    }
    public function test_user_can_edit_mood()
    {
        $user = User::create([
            'username' => 'editmood',
            'email' => 'edit@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $mood = \App\Models\Mood::create([
            'user_id' => $user->id,
            'name' => 'Excited',
            'icon' => 'bolt',
        ]);
        $response = $this->put(route('customization.mood.update', $mood), [
            'name' => 'Sleepy',
            'icon' => 'moon',
        ]);
        $response->assertRedirect(route('customization'));
        $this->assertDatabaseHas('moods', [
            'id' => $mood->id,
            'name' => 'Sleepy',
            'icon' => 'moon',
        ]);
    }
}
