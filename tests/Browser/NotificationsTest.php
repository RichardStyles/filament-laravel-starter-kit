<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Notifications\Notification;

it('shows the unread notification badge in the navigation bell', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);

    Notification::make()
        ->title('Welcome aboard')
        ->sendToDatabase($user);

    $this->actingAs($user);

    $page = visit('/dashboard');

    $page->assertSee('1')
        ->assertNoJavaScriptErrors();
});

it('opens the Filament notifications drawer when the bell is clicked', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);

    Notification::make()
        ->title('Welcome aboard')
        ->body('Thanks for joining the kit.')
        ->sendToDatabase($user);

    $this->actingAs($user);

    $page = visit('/dashboard');

    $page->click('View notifications')
        ->assertSee('Welcome aboard')
        ->assertSee('Thanks for joining the kit.')
        ->assertNoJavaScriptErrors();
});
