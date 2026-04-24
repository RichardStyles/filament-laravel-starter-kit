<?php

use App\Livewire\Profile\TwoFactorAuthentication;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

it('starts in the "not enrolled" state', function () {
    $user = User::factory()->create();

    expect($user->two_factor_secret)->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();
});

it('enables two-factor authentication but leaves it unconfirmed', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TwoFactorAuthentication::class)
        ->call('enableTwoFactorAuthentication');

    $fresh = $user->fresh();
    expect($fresh->two_factor_secret)->not->toBeNull()
        ->and($fresh->two_factor_recovery_codes)->not->toBeNull()
        ->and($fresh->two_factor_confirmed_at)->toBeNull();
});

it('rejects an invalid confirmation code', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TwoFactorAuthentication::class)
        ->call('enableTwoFactorAuthentication')
        ->set('data.code', '000000')
        ->call('confirmTwoFactorAuthentication')
        ->assertHasErrors(['data.code']);

    expect($user->fresh()->two_factor_confirmed_at)->toBeNull();
});

it('confirms two-factor authentication with a valid code', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test(TwoFactorAuthentication::class)
        ->call('enableTwoFactorAuthentication');

    $code = currentTotp($user->fresh());

    $component->set('data.code', $code)
        ->call('confirmTwoFactorAuthentication')
        ->assertHasNoErrors()
        ->assertSet('showingRecoveryCodes', true);

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull();
});

it('rejects recovery code regeneration when the password is wrong', function () {
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    confirm2fa($user);

    $original = $user->fresh()->two_factor_recovery_codes;

    Livewire::actingAs($user->fresh())
        ->test(TwoFactorAuthentication::class)
        ->set('data.password', 'wrong')
        ->call('regenerateRecoveryCodes')
        ->assertHasErrors(['data.password']);

    expect($user->fresh()->two_factor_recovery_codes)->toBe($original);
});

it('regenerates recovery codes with the correct password', function () {
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    confirm2fa($user);

    $original = $user->fresh()->two_factor_recovery_codes;

    Livewire::actingAs($user->fresh())
        ->test(TwoFactorAuthentication::class)
        ->set('data.password', 'correct')
        ->call('regenerateRecoveryCodes')
        ->assertHasNoErrors();

    expect($user->fresh()->two_factor_recovery_codes)->not->toBe($original);
});

it('rejects disable when the password is wrong (post-confirmation state)', function () {
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    confirm2fa($user);

    Livewire::actingAs($user->fresh())
        ->test(TwoFactorAuthentication::class)
        ->set('data.password', 'wrong')
        ->call('disableTwoFactorAuthentication')
        ->assertHasErrors(['data.password']);

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull();
});

it('disables two-factor authentication with the correct password', function () {
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    confirm2fa($user);

    Livewire::actingAs($user->fresh())
        ->test(TwoFactorAuthentication::class)
        ->set('data.password', 'correct')
        ->call('disableTwoFactorAuthentication')
        ->assertHasNoErrors();

    $fresh = $user->fresh();
    expect($fresh->two_factor_secret)->toBeNull()
        ->and($fresh->two_factor_recovery_codes)->toBeNull()
        ->and($fresh->two_factor_confirmed_at)->toBeNull();
});

it('allows cancelling pending 2FA without a password', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(TwoFactorAuthentication::class)
        ->call('enableTwoFactorAuthentication')
        ->call('disableTwoFactorAuthentication')
        ->assertHasNoErrors();

    expect($user->fresh()->two_factor_secret)->toBeNull();
});

// Helpers -------------------------------------------------------------------

function currentTotp(User $user): string
{
    $secret = Crypt::decrypt($user->two_factor_secret);

    return (new Google2FA)->getCurrentOtp($secret);
}

function confirm2fa(User $user): void
{
    Livewire::actingAs($user)
        ->test(TwoFactorAuthentication::class)
        ->call('enableTwoFactorAuthentication')
        ->set('data.code', currentTotp($user->fresh()))
        ->call('confirmTwoFactorAuthentication')
        ->assertHasNoErrors();
}
