<?php

declare(strict_types=1);

use App\Livewire\Profile\ApiTokens;
use App\Models\User;
use Livewire\Livewire;

it('issues a new token, exposes the plain text value once, and stores it on the user', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ApiTokens::class)
        ->set('data.name', 'CLI deploy')
        ->call('createToken')
        ->assertHasNoErrors();

    expect($user->fresh()->tokens()->count())->toBe(1);
    expect($user->tokens()->first()->name)->toBe('CLI deploy');
});

it('rejects empty token names', function (): void {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ApiTokens::class)
        ->set('data.name', '')
        ->call('createToken')
        ->assertHasErrors(['data.name']);
});

it('revokes a token by id', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('temp');

    Livewire::actingAs($user)
        ->test(ApiTokens::class)
        ->call('revoke', $token->accessToken->id);

    expect($user->fresh()->tokens()->count())->toBe(0);
});

it('does not let a user revoke another users token', function (): void {
    $alice = User::factory()->create();
    $bob = User::factory()->create();
    $bobsToken = $bob->createToken('bobs');

    Livewire::actingAs($alice)
        ->test(ApiTokens::class)
        ->call('revoke', $bobsToken->accessToken->id);

    expect($bob->fresh()->tokens()->count())->toBe(1);
});
