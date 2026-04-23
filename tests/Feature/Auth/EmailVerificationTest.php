<?php

use App\Livewire\Auth\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;

it('redirects unverified users from /dashboard to /verify-email', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('verification.notice'));
});

it('marks the user verified when hitting the signed URL and fires Verified', function () {
    Event::fake([Verified::class]);

    $user = User::factory()->unverified()->create();

    $signedUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addHour(),
        ['id' => $user->id, 'hash' => sha1($user->email)],
    );

    $this->actingAs($user)->get($signedUrl)->assertRedirect();

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    Event::assertDispatched(Verified::class);
});

it('returns 403 for a tampered hash', function () {
    $user = User::factory()->unverified()->create();

    $tamperedUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addHour(),
        ['id' => $user->id, 'hash' => sha1('not-the-email@example.com')],
    );

    $this->actingAs($user)->get($tamperedUrl)->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('resends the verification notification when requested', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    Livewire::actingAs($user)
        ->test(VerifyEmail::class)
        ->call('resend');

    Notification::assertSentTo($user, Illuminate\Auth\Notifications\VerifyEmail::class);
});
