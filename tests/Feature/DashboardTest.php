<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Notifications\Notification;

it('shows the welcome heading and the three stat cards', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee("Welcome back, {$user->name}")
        ->assertSee('Account age')
        ->assertSee('Active sessions')
        ->assertSee('Unread notifications');
});

it('reflects the unread notification count in the stat card', function (): void {
    $user = User::factory()->create();

    Notification::make()->title('Hello')->sendToDatabase($user);
    Notification::make()->title('Hello again')->sendToDatabase($user);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSeeInOrder(['Unread notifications', '2']);
});
