<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

it('renders password-reset emails through the published, indigo-branded template', function (): void {
    $user = User::factory()->create();
    $notification = new ResetPassword('test-token');

    /** @var MailMessage $mail */
    $mail = $notification->toMail($user);
    $rendered = (string) $mail->render();

    expect($rendered)
        ->toContain(config('app.name'))
        ->toContain('#4f46e5');
});

it('renders email-verification emails through the published, indigo-branded template', function (): void {
    $user = User::factory()->unverified()->create();
    $notification = new VerifyEmail;

    /** @var MailMessage $mail */
    $mail = $notification->toMail($user);
    $rendered = (string) $mail->render();

    expect($rendered)
        ->toContain(config('app.name'))
        ->toContain('#4f46e5');
});
