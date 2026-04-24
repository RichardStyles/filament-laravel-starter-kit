<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('registers a new user and lands on the verify-email page', function () {
    $page = visit('/register');

    $page->assertSee('Create your account')
        ->fill('[wire\\:model="data.name"]', 'Ada Lovelace')
        ->fill('[wire\\:model="data.email"]', 'ada@example.com')
        ->fill('[wire\\:model="data.password"]', 'P@ssw0rd!secret')
        ->fill('[wire\\:model="data.password_confirmation"]', 'P@ssw0rd!secret')
        ->click('button[type="submit"]')
        ->assertPathIs('/verify-email')
        ->assertSee('Verify your email')
        ->assertNoJavaScriptErrors();

    expect(User::where('email', 'ada@example.com')->exists())->toBeTrue();
    $this->assertAuthenticated();
});

it('logs a verified user in and lands on /dashboard', function () {
    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('correct-horse-battery-staple'),
    ]);

    $page = visit('/login');

    $page->assertSee('Sign in')
        ->fill('[wire\\:model="data.email"]', 'ada@example.com')
        ->fill('[wire\\:model="data.password"]', 'correct-horse-battery-staple')
        ->click('button[type="submit"]')
        ->assertPathIs('/dashboard')
        ->assertSee('Welcome back, ')
        ->assertNoJavaScriptErrors();

    $this->assertAuthenticated();
});

it('rejects login with bad credentials and surfaces a Filament form error', function () {
    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('correct-horse-battery-staple'),
    ]);

    $page = visit('/login');

    $page->fill('[wire\\:model="data.email"]', 'ada@example.com')
        ->fill('[wire\\:model="data.password"]', 'wrong-password')
        ->click('button[type="submit"]')
        ->assertPathIs('/login')
        ->assertSee('credentials do not match')
        ->assertNoJavaScriptErrors();

    $this->assertGuest();
});

it('logs the user out from the dashboard via the profile dropdown', function () {
    $this->actingAs(User::factory()->create());

    $page = visit('/dashboard');

    $page->assertSee('Dashboard')
        ->click('Open user menu')
        ->click('Sign out')
        ->assertPathIs('/')
        ->assertNoJavaScriptErrors();

    $this->assertGuest();
});

it('sends a password reset link and shows the success toast', function () {
    User::factory()->create(['email' => 'ada@example.com']);

    $page = visit('/forgot-password');

    $page->assertSee('Reset your password')
        ->fill('[wire\\:model="data.email"]', 'ada@example.com')
        ->click('button[type="submit"]')
        ->assertSee('We have emailed your password reset link')
        ->assertNoJavaScriptErrors();
});

it('redirects guests from /dashboard to /login', function () {
    $page = visit('/dashboard');

    $page->assertPathIs('/login')
        ->assertSee('Sign in')
        ->assertNoJavaScriptErrors();
});
