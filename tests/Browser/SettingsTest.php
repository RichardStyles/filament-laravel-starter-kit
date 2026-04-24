<?php

declare(strict_types=1);

use App\Models\User;

it('renders the settings page with all six sections for a verified user', function (): void {
    $this->actingAs(User::factory()->create());

    $page = visit('/settings');

    $page->assertSee('Settings')
        ->assertSee('Personal information')
        ->assertSee('Update password')
        ->assertSee('Two-factor authentication')
        ->assertSee('Appearance')
        ->assertSee('Browser sessions')
        ->assertSee('Delete account')
        ->assertNoJavaScriptErrors();
});

it('updates the signed-in user name via the personal information card', function (): void {
    $user = User::factory()->create(['name' => 'Original Name']);
    $this->actingAs($user);

    $page = visit('/settings');

    $page->fill('[wire\\:model="data.name"]', 'Updated Name')
        ->click('Save')
        ->assertSee('Profile updated.')
        ->assertNoJavaScriptErrors();

    expect($user->fresh()->name)->toBe('Updated Name');
});

it('enables two-factor authentication and shows a QR code', function (): void {
    $this->actingAs(User::factory()->create());

    $page = visit('/settings');

    $page->click('Enable')
        ->assertSee('Scan the QR code')
        ->assertSee('Confirmation code')
        ->assertNoJavaScriptErrors();
});

it('redirects /profile to /settings for authenticated users', function (): void {
    $this->actingAs(User::factory()->create());

    $page = visit('/profile');

    $page->assertPathIs('/settings')
        ->assertNoJavaScriptErrors();
});

it('redirects guests from /settings to /login', function (): void {
    $page = visit('/settings');

    $page->assertPathIs('/login')
        ->assertNoJavaScriptErrors();
});
