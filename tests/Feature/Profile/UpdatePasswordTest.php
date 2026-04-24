<?php

use App\Livewire\Profile\UpdatePassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

it('updates the user password with valid input', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($user)
        ->test(UpdatePassword::class)
        ->set('data.current_password', 'old-password')
        ->set('data.password', 'brand-new-password')
        ->set('data.password_confirmation', 'brand-new-password')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('brand-new-password', $user->fresh()->password))->toBeTrue();
});

it('fails with the wrong current password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($user)
        ->test(UpdatePassword::class)
        ->set('data.current_password', 'not-the-right-one')
        ->set('data.password', 'brand-new-password')
        ->set('data.password_confirmation', 'brand-new-password')
        ->call('updatePassword')
        ->assertHasErrors(['data.current_password']);

    expect(Hash::check('old-password', $user->fresh()->password))->toBeTrue();
});

it('fails when the new password does not match its confirmation', function () {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($user)
        ->test(UpdatePassword::class)
        ->set('data.current_password', 'old-password')
        ->set('data.password', 'brand-new-password')
        ->set('data.password_confirmation', 'different-confirmation')
        ->call('updatePassword')
        ->assertHasErrors(['data.password']);
});

it('validates the form fields', function (array $data, array $errors) {
    $user = User::factory()->create([
        'password' => Hash::make('old-password'),
    ]);

    Livewire::actingAs($user)
        ->test(UpdatePassword::class)
        ->set('data.current_password', 'old-password')
        ->set('data.password', 'brand-new-password')
        ->set('data.password_confirmation', 'brand-new-password')
        ->set($data)
        ->call('updatePassword')
        ->assertHasErrors($errors);
})->with([
    'current_password is required' => [['data.current_password' => ''], ['data.current_password' => 'required']],
    'password is required' => [['data.password' => ''], ['data.password' => 'required']],
    'password must be at least 8 chars' => [['data.password' => 'short', 'data.password_confirmation' => 'short'], ['data.password']],
]);
