<?php

use App\Livewire\Auth\ForgotPassword;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
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
