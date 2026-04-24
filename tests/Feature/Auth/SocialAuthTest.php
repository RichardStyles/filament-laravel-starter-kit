<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('admin');
    Role::findOrCreate('user');
});

function fakeSocialiteUser(string $email = 'oauth@example.com'): SocialiteUser
{
    $user = new SocialiteUser;
    $user->id = '12345';
    $user->name = 'Ada Lovelace';
    $user->email = $email;
    $user->token = 'access-token';
    $user->refreshToken = 'refresh-token';

    return $user;
}

it('redirects to the OAuth provider for an enabled provider', function (): void {
    $this->get('/auth/github/redirect')
        ->assertRedirect();
});

it('returns 404 for an unknown OAuth provider on redirect', function (): void {
    config()->set('services.socialite.providers', ['github']);

    $this->get('/auth/twitter/redirect')->assertNotFound();
});

it('creates a new user, links the social account, and signs them in on first callback', function (): void {
    Socialite::shouldReceive('driver->user')->once()->andReturn(fakeSocialiteUser());

    $this->get('/auth/github/callback')->assertRedirect('/dashboard');

    $user = User::query()->where('email', 'oauth@example.com')->firstOrFail();
    expect($user->socialAccounts()->where('provider', 'github')->exists())->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

it('reuses an existing user when their email matches and just links the new provider', function (): void {
    $existing = User::factory()->create(['email' => 'oauth@example.com']);

    Socialite::shouldReceive('driver->user')->once()->andReturn(fakeSocialiteUser());

    $this->get('/auth/github/callback')->assertRedirect('/dashboard');

    expect(User::query()->where('email', 'oauth@example.com')->count())->toBe(1);
    expect($existing->fresh()->socialAccounts()->where('provider', 'github')->exists())->toBeTrue();
    $this->assertAuthenticatedAs($existing);
});

it('signs in the same user on a return visit without creating a duplicate link', function (): void {
    $existing = User::factory()->create(['email' => 'oauth@example.com']);
    $existing->socialAccounts()->create([
        'provider' => 'github',
        'provider_id' => '12345',
        'provider_token' => 'old-token',
    ]);

    Socialite::shouldReceive('driver->user')->once()->andReturn(fakeSocialiteUser());

    $this->get('/auth/github/callback')->assertRedirect('/dashboard');

    expect($existing->fresh()->socialAccounts()->count())->toBe(1);
    $this->assertAuthenticatedAs($existing);
});
