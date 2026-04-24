<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('logs in with valid credentials and redirects to /dashboard', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('correct-horse-battery-staple'),
    ]);

    Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'correct-horse-battery-staple')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(auth()->id())->toBe($user->id);
});

it('fails with the wrong password', function (): void {
    User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('correct-horse-battery-staple'),
    ]);

    Livewire::test(Login::class)
        ->set('data.email', 'ada@example.com')
        ->set('data.password', 'wrong-password')
        ->call('login')
        ->assertHasErrors(['data.email']);

    expect(auth()->check())->toBeFalse();
});

it('throttles after 5 failed attempts', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('correct-horse-battery-staple'),
    ]);

    $component = Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'wrong-password');

    foreach (range(1, 5) as $ignored) {
        $component->call('login')->assertHasErrors(['data.email']);
    }

    $component->call('login')->assertHasErrors(['data.email']);

    $throttleKey = Str::lower($user->email).'|'.request()->ip();
    expect(RateLimiter::tooManyAttempts($throttleKey, 5))->toBeTrue();
});

it('regenerates the session token on successful login', function (): void {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('correct-horse-battery-staple'),
    ]);

    $this->withSession(['foo' => 'bar']);
    $before = session()->token();

    Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'correct-horse-battery-staple')
        ->call('login');

    expect(session()->token())->not->toBe($before);
});
