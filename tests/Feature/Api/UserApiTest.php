<?php

declare(strict_types=1);

use App\Models\User;

it('rejects /api/v1/user when no token is provided', function (): void {
    $this->getJson('/api/v1/user')->assertUnauthorized();
});

it('returns the authenticated user when a valid token is provided', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('integration-test')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/v1/user')
        ->assertOk()
        ->assertJson(['id' => $user->id, 'email' => $user->email]);
});

it('rejects requests after a token is revoked', function (): void {
    $user = User::factory()->create();
    $token = $user->createToken('integration-test');
    $plain = $token->plainTextToken;

    $token->accessToken->delete();

    $this->withHeader('Authorization', "Bearer {$plain}")
        ->getJson('/api/v1/user')
        ->assertUnauthorized();
});
