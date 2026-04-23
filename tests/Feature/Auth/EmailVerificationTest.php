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
    $user = User::factory()->unverified()->create();

    Event::fake([Verified::class]);

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

it('returns 403 when one user hits another users verify URL (IDOR)', function () {
    $alice = User::factory()->unverified()->create(['email' => 'alice@example.com']);
    $bob = User::factory()->unverified()->create(['email' => 'bob@example.com']);

    $bobsSignedUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addHour(),
        ['id' => $bob->id, 'hash' => sha1($bob->email)],
    );

    $this->actingAs($alice)->get($bobsSignedUrl)->assertForbidden();

    expect($bob->fresh()->hasVerifiedEmail())->toBeFalse();
    expect($alice->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('redirects guests to login for a valid signed verification URL', function () {
    $user = User::factory()->unverified()->create();

    $signedUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addHour(),
        ['id' => $user->id, 'hash' => sha1($user->email)],
    );

    $this->get($signedUrl)->assertRedirect(route('login'));
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

it('throttles verification-notification resends to 6 per minute', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $component = Livewire::actingAs($user)->test(VerifyEmail::class);

    foreach (range(1, 6) as $ignored) {
        $component->call('resend');
    }

    $component->call('resend');
    Notification::assertSentToTimes($user, Illuminate\Auth\Notifications\VerifyEmail::class, 6);
});
