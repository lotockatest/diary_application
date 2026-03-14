<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AddCurrentDayEntryTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_add_entry_for_current_day()
    {
        $user = User::create([
        'username' => 'testuser',
        'email' => 'test@test',
        'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $response = $this->post(route('entry.store'), [
            'date' => now()->format('Y-m-d'),
            'mood' => 'Happy',
            'activities' => ['Study'],
            'routines' => ['Morning Routine'],
            'notes' => 'Hello World!',
        ]);
        $response->assertRedirect('/');
        $this->assertDatabaseHas('entries', [
            'user_id' => $user->id,
            'mood' => 'Happy',
            'notes' => 'Hello World!',
        ]);
    }
}
