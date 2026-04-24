<?php

use App\Models\User;

it('redirects guests from /dashboard to /login', function (): void {
    $this->get('/dashboard')->assertRedirect('/login');
});

it('redirects authenticated users away from guest routes', function (string $path): void {
    $user = User::factory()->create();

    $this->actingAs($user)->get($path)->assertRedirect('/dashboard');
})->with([
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password/some-token',
]);

it('renders guest-facing auth pages without blowing up', function (string $path): void {
    $this->get($path)->assertOk();
})->with([
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password/some-token',
]);

it('renders /verify-email for authenticated unverified users', function (): void {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get(route('verification.notice'))->assertOk();
});
