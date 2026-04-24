<?php

declare(strict_types=1);

use App\Livewire\AppearanceSwitcher;
use App\Models\User;
use Livewire\Livewire;

it('renders with the user current preference', function (): void {
    $user = User::factory()->create(['appearance_preference' => 'dark']);

    Livewire::actingAs($user)
        ->test(AppearanceSwitcher::class)
        ->assertSet('preference', 'dark');
});

it('persists a new appearance preference and dispatches a sync event', function (string $preference): void {
    $user = User::factory()->create(['appearance_preference' => 'system']);

    Livewire::actingAs($user)
        ->test(AppearanceSwitcher::class)
        ->call('setPreference', $preference)
        ->assertHasNoErrors()
        ->assertSet('preference', $preference)
        ->assertDispatched('appearance-updated', preference: $preference);

    expect($user->refresh()->appearance_preference)->toBe($preference);
})->with(['light', 'dark', 'system']);

it('rejects an unknown appearance preference', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(AppearanceSwitcher::class)
        ->call('setPreference', 'high-contrast')
        ->assertHasErrors(['preference']);

    expect($user->refresh()->appearance_preference)->toBe('system');
});

it('renders the switcher on an authenticated page', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSuccessful()
        ->assertSeeLivewire(AppearanceSwitcher::class);
});
