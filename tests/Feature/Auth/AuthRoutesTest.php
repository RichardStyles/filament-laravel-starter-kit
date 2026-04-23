<?php

use App\Models\User;

it('redirects guests from /dashboard to /login', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

it('redirects authenticated users away from guest routes', function (string $path) {
    $user = User::factory()->create();

    $this->actingAs($user)->get($path)->assertRedirect('/dashboard');
})->with([
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password/some-token',
]);
