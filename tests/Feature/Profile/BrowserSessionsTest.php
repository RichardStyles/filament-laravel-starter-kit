<?php

use App\Livewire\Profile\BrowserSessions;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

function seedSession(int $userId, string $id, int $lastActivity, ?string $ip = '10.0.0.1', ?string $agent = 'Mozilla/5.0'): void
{
    DB::table('sessions')->insert([
        'id' => $id,
        'user_id' => $userId,
        'ip_address' => $ip,
        'user_agent' => $agent,
        'payload' => base64_encode('x'),
        'last_activity' => $lastActivity,
    ]);
}

it('lists only the current user sessions, ordered by last activity', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();

    $currentId = session()->getId();
    seedSession($user->id, $currentId, now()->subMinute()->timestamp);
    seedSession($user->id, 'older-session-id', now()->subHour()->timestamp);
    seedSession($other->id, 'other-user-session', now()->timestamp);

    $component = Livewire::actingAs($user)->test(BrowserSessions::class);

    $sessions = $component->instance()->sessions;

    expect($sessions)->toHaveCount(2)
        ->and($sessions[0]->id)->toBe($currentId)
        ->and($sessions[0]->is_current_device)->toBeTrue()
        ->and($sessions[1]->id)->toBe('older-session-id')
        ->and($sessions[1]->is_current_device)->toBeFalse();
});

it('rejects logging out other sessions with the wrong password', function (): void {
    $user = User::factory()->create(['password' => Hash::make('correct')]);
    seedSession($user->id, session()->getId(), now()->timestamp);
    seedSession($user->id, 'other-session', now()->subMinute()->timestamp);

    Livewire::actingAs($user)
        ->test(BrowserSessions::class)
        ->set('data.password', 'wrong')
        ->call('logoutOtherBrowserSessions')
        ->assertHasErrors(['data.password']);

    expect(DB::table('sessions')->where('user_id', $user->id)->count())->toBe(2);
});

it('logs out other sessions when the password is correct', function (): void {
    $user = User::factory()->create(['password' => Hash::make('correct')]);
    $currentId = session()->getId();
    seedSession($user->id, $currentId, now()->timestamp);
    seedSession($user->id, 'other-session-1', now()->subMinute()->timestamp);
    seedSession($user->id, 'other-session-2', now()->subHour()->timestamp);

    Livewire::actingAs($user)
        ->test(BrowserSessions::class)
        ->set('data.password', 'correct')
        ->call('logoutOtherBrowserSessions')
        ->assertHasNoErrors();

    $remaining = DB::table('sessions')->where('user_id', $user->id)->pluck('id')->all();
    expect($remaining)->toBe([$currentId]);
});
