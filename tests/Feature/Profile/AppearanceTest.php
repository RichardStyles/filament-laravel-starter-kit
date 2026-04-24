<?php

declare(strict_types=1);

use App\Livewire\Profile\Appearance;
use App\Models\User;
use Livewire\Livewire;

it('defaults to the system appearance preference', function (): void {
    $user = User::factory()->create();

    expect($user->appearance_preference)->toBe('system');
});

it('renders with the user current preference selected', function (): void {
    $user = User::factory()->create(['appearance_preference' => 'dark']);

    Livewire::actingAs($user)
        ->test(Appearance::class)
        ->assertSet('data.appearance_preference', 'dark');
});

it('persists a new appearance preference and dispatches a sync event', function (string $preference): void {
    $user = User::factory()->create(['appearance_preference' => 'system']);

    Livewire::actingAs($user)
        ->test(Appearance::class)
        ->set('data.appearance_preference', $preference)
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('appearance-updated', preference: $preference);

    expect($user->refresh()->appearance_preference)->toBe($preference);
})->with(['light', 'dark', 'system']);

it('rejects an unknown appearance preference', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Appearance::class)
        ->set('data.appearance_preference', 'high-contrast')
        ->call('save')
        ->assertHasErrors(['data.appearance_preference']);

    expect($user->refresh()->appearance_preference)->toBe('system');
});
