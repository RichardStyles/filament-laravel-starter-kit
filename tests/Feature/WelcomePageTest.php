<?php

declare(strict_types=1);

use App\Models\User;

it('renders the marketing landing for guests with sign-in and get-started CTAs', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee(config('app.name'))
        ->assertSee('Ship your next Laravel app in an afternoon.')
        ->assertSee('Create your account')
        ->assertSee('Sign in');
});

it('swaps the guest CTAs for a Dashboard link when the user is signed in', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertOk()
        ->assertSee('Go to dashboard')
        ->assertDontSee('Create your account');
});
