<?php

use App\Livewire\Profile\UpdateProfileInformation;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

it('requires authentication to render', function () {
    $this->get(route('profile'))->assertRedirect(route('login'));
});

it('prefills the form with the current user name and email', function () {
    $user = User::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->assertSet('data.name', 'Ada Lovelace')
        ->assertSet('data.email', 'ada@example.com');
});

it('updates the user name', function () {
    $user = User::factory()->create(['name' => 'Old Name']);

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->set('data.name', 'New Name')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('New Name');
});

it('clears email_verified_at and sends a verification notification when email changes', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email' => 'before@example.com',
        'email_verified_at' => now(),
    ]);

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->set('data.email', 'after@example.com')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $fresh = $user->fresh();
    expect($fresh->email)->toBe('after@example.com')
        ->and($fresh->email_verified_at)->toBeNull();

    Notification::assertSentTo($fresh, VerifyEmail::class);
});

it('validates name and email', function (array $data, array $errors) {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->set($data)
        ->call('updateProfileInformation')
        ->assertHasErrors($errors);
})->with([
    'name is required' => [['data.name' => ''], ['data.name' => 'required']],
    'email is required' => [['data.email' => ''], ['data.email' => 'required']],
    'email must be an email' => [['data.email' => 'not-an-email'], ['data.email' => 'email']],
]);

it('rejects an email already in use by another user', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->set('data.email', 'taken@example.com')
        ->call('updateProfileInformation')
        ->assertHasErrors(['data.email']);
});

it('resends the verification notification when requested', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->call('resendEmailVerification');

    Notification::assertSentTo($user, VerifyEmail::class);
});
