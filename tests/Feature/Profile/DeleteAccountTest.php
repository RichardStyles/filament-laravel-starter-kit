<?php

use App\Livewire\Profile\DeleteAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

it('rejects account deletion with the wrong password', function () {
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    Livewire::actingAs($user)
        ->test(DeleteAccount::class)
        ->set('data.password', 'wrong')
        ->call('deleteAccount')
        ->assertHasErrors(['data.password']);

    expect(User::find($user->id))->not->toBeNull();
});

it('deletes the account with the correct password and redirects', function () {
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    Livewire::actingAs($user)
        ->test(DeleteAccount::class)
        ->set('data.password', 'correct')
        ->call('deleteAccount')
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect(User::find($user->id))->toBeNull()
        ->and(auth()->check())->toBeFalse();
});
