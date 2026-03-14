<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;

class AddNonCurrentDayEntryTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_cannot_add_entry_for_non_current_day()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'test@test',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);
        $nonCurrentDate = now()->subDay()->format('Y-m-d');
        $response = $this->post(route('entry.store'), [
            'date' => $nonCurrentDate,
            'mood' => 'Happy',
            'activities' => ['Study'],
            'routines' => ['Morning Routine'],
            'notes' => 'Should not be saved',
        ]);
        $response->assertRedirect('/');
        $this->assertDatabaseMissing('entries', [
            'user_id' => $user->id,
            'notes' => 'Should not be saved',
        ]);
    }
}
