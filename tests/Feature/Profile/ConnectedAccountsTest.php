<?php

declare(strict_types=1);

use App\Livewire\Profile\ConnectedAccounts;
use App\Models\User;
use Livewire\Livewire;

it('disconnects a linked provider when requested', function (): void {
    $user = User::factory()->create();
    $user->socialAccounts()->create([
        'provider' => 'github',
        'provider_id' => '12345',
    ]);

    Livewire::actingAs($user)
        ->test(ConnectedAccounts::class)
        ->call('disconnect', 'github');

    expect($user->fresh()->socialAccounts()->where('provider', 'github')->exists())->toBeFalse();
});

it('only deletes the social account row, not the user', function (): void {
    $user = User::factory()->create();
    $user->socialAccounts()->create([
        'provider' => 'github',
        'provider_id' => '12345',
    ]);

    Livewire::actingAs($user)
        ->test(ConnectedAccounts::class)
        ->call('disconnect', 'github');

    expect(User::query()->whereKey($user->id)->exists())->toBeTrue();
});
