<?php

use App\Livewire\Navigation;
use App\Models\User;
use Filament\Notifications\Notification;
use Livewire\Livewire;

it('renders the authenticated user name and email in the mobile disclosure', function (): void {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $this->actingAs($user);

    Livewire::test(Navigation::class)
        ->assertSee('Ada Lovelace')
        ->assertSee('ada@example.com');
});

it('marks the Dashboard link as the current page when on /dashboard', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('aria-current="page"', escape: false)
        ->assertSee('Dashboard');
});

it('signs the user out and redirects to /', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Navigation::class)
        ->call('signOut')
        ->assertRedirect('/');

    expect(auth()->check())->toBeFalse();
});

it('regenerates the session token when signing out', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);
    $this->withSession(['foo' => 'bar']);
    $before = session()->token();

    Livewire::test(Navigation::class)->call('signOut');

    expect(session()->token())->not->toBe($before);
});

it('renders a Gravatar avatar URL derived from the user email', function (): void {
    $user = User::factory()->create(['email' => 'Ada@Example.com']);

    $this->actingAs($user);

    $expectedHash = md5('ada@example.com');

    Livewire::test(Navigation::class)
        ->assertSee("gravatar.com/avatar/{$expectedHash}");
});

it('wires the notifications bell to the Filament database notifications component', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('Notifications (coming soon)')
        ->assertSeeLivewire('database-notifications');
});

it('exposes the authenticated user unread notification count to the navigation view', function (): void {
    $user = User::factory()->create();

    Notification::make()
        ->title('Welcome aboard')
        ->sendToDatabase($user);

    $this->actingAs($user);

    Livewire::test(Navigation::class)
        ->assertViewHas('unreadNotificationsCount', 1);
});

it('reports zero unread notifications when the user has none', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Navigation::class)
        ->assertViewHas('unreadNotificationsCount', 0);
});
