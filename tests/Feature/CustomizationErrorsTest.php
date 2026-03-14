<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class CustomizationErrorsTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_cannot_add_new_item_without_selecting_category()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post('/customization/add', [
            'name' => 'ShouldNotWork',
            'icon' => 'bolt',
        ]);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('routines', ['user_id' => $user->id, 'name' => 'ShouldNotWork']);
        $this->assertDatabaseMissing('moods', ['user_id' => $user->id, 'name' => 'ShouldNotWork']);
        $this->assertDatabaseMissing('activities', ['user_id' => $user->id, 'name' => 'ShouldNotWork']);
    }
    public function test_user_cannot_add_routine_without_name()
    {
        $user = User::create([
            'username' => 'invalidtest',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('customization.routine.store'), [
            'name' => '',
            'icon' => 'sun',
        ]);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('routines', [
            'user_id' => $user->id,
            'name' => '',
            'icon' => 'sun',
        ]);
    }
    public function test_user_cannot_add_routine_without_icon()
    {
        $user = User::create([
            'username' => 'invalidtest',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('customization.routine.store'), [
            'name' => 'Run',
            'icon' => '',
        ]);
        $response->assertSessionHasErrors('icon');
        $this->assertDatabaseMissing('routines', [
            'user_id' => $user->id,
            'name' => 'Run',
            'icon' => '',
        ]);
    }
    public function test_user_cannot_add_activity_without_name()
    {
        $user = User::create([
            'username' => 'invalidtest',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('customization.activity.store'), [
            'name' => '',
            'icon' => 'sun',
        ]);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('activities', [
            'user_id' => $user->id,
            'name' => '',
            'icon' => 'sun',
        ]);
    }
    public function test_user_cannot_add_activity_without_icon()
    {
        $user = User::create([
            'username' => 'invalidtest',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('customization.activity.store'), [
            'name' => 'Run',
            'icon' => '',
        ]);
        $response->assertSessionHasErrors('icon');
        $this->assertDatabaseMissing('activities', [
            'user_id' => $user->id,
            'name' => 'Run',
            'icon' => '',
        ]);
    }
    public function test_user_cannot_add_mood_without_name()
    {
        $user = User::create([
            'username' => 'invalidtest',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('customization.mood.store'), [
            'name' => '',
            'icon' => 'sun',
        ]);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('moods', [
            'user_id' => $user->id,
            'name' => '',
            'icon' => 'sun',
        ]);
    }
    public function test_user_cannot_add_mood_without_icon()
    {
        $user = User::create([
            'username' => 'invalidtest',
            'email' => 'invalid@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('customization.mood.store'), [
            'name' => 'Joy',
            'icon' => '',
        ]);
        $response->assertSessionHasErrors('icon');
        $this->assertDatabaseMissing('moods', [
            'user_id' => $user->id,
            'name' => 'Joy',
            'icon' => '',
        ]);
    }
}
