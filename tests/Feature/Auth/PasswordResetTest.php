<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword as ResetPasswordComponent;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;

it('dispatches a reset-password notification to a known user', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'ada@example.com']);

    Livewire::test(ForgotPassword::class)
        ->set('data.email', $user->email)
        ->call('sendResetLink')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

it('does not dispatch a reset-password notification for unknown emails', function () {
    Notification::fake();

    Livewire::test(ForgotPassword::class)
        ->set('data.email', 'nobody@example.com')
        ->call('sendResetLink')
        ->assertHasErrors(['data.email']);

    Notification::assertNothingSent();
});

it('resets the password with a valid token and fires PasswordReset', function () {
    Event::fake([PasswordReset::class]);

    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('old-password'),
    ]);

    $token = Password::createToken($user);

    Livewire::test(ResetPasswordComponent::class, ['token' => $token])
        ->set('data.email', $user->email)
        ->set('data.password', 'P@ssw0rd!newone')
        ->set('data.password_confirmation', 'P@ssw0rd!newone')
        ->call('resetPassword')
        ->assertHasNoErrors()
        ->assertRedirect(route('login'));

    expect(Hash::check('P@ssw0rd!newone', $user->fresh()->password))->toBeTrue();
    Event::assertDispatched(PasswordReset::class);
});

it('fails to reset with an invalid token', function () {
    $user = User::factory()->create([
        'email' => 'ada@example.com',
        'password' => Hash::make('old-password'),
    ]);

    Livewire::test(ResetPasswordComponent::class, ['token' => 'not-a-real-token'])
        ->set('data.email', $user->email)
        ->set('data.password', 'P@ssw0rd!newone')
        ->set('data.password_confirmation', 'P@ssw0rd!newone')
        ->call('resetPassword')
        ->assertHasErrors(['data.email']);

    expect(Hash::check('old-password', $user->fresh()->password))->toBeTrue();
});
