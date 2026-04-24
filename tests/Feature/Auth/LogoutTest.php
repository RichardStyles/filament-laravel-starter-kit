<?php

use App\Models\User;

it('logs the user out and redirects to /', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect('/');

    expect(auth()->check())->toBeFalse();
});

it('regenerates the session token on logout', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->withSession(['foo' => 'bar']);
    $before = session()->token();

    $this->post(route('logout'));

    expect(session()->token())->not->toBe($before);
});
