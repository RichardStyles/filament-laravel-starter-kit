<?php

declare(strict_types=1);

use App\Livewire\Profile\UpdateProfileInformation;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('falls back to Gravatar when no avatar is uploaded', function (): void {
    $user = User::factory()->create(['email' => 'Ada@Example.com', 'avatar_path' => null]);

    expect($user->avatar_url)
        ->toBe('https://www.gravatar.com/avatar/'.md5('ada@example.com').'?d=mp&s=256');
});

it('returns the public storage URL when an avatar is uploaded', function (): void {
    Storage::fake('public');

    $upload = UploadedFile::fake()->image('avatar.jpg');
    $path = $upload->store('avatars', 'public');

    $user = User::factory()->create(['avatar_path' => $path]);

    expect($user->avatar_url)->toBe(Storage::disk('public')->url($path));
});

it('persists an uploaded avatar through the profile form', function (): void {
    Storage::fake('public');

    $user = User::factory()->create(['avatar_path' => null]);
    $upload = UploadedFile::fake()->image('avatar.jpg', 200, 200);

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->set('data.avatar_path', [$upload])
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->avatar_path)->not->toBeNull();
    Storage::disk('public')->assertExists($user->avatar_path);
});

it('clears the avatar when the user removes it from the form', function (): void {
    Storage::fake('public');

    $existing = UploadedFile::fake()->image('old.jpg')->store('avatars', 'public');
    $user = User::factory()->create(['avatar_path' => $existing]);

    Livewire::actingAs($user)
        ->test(UpdateProfileInformation::class)
        ->set('data.avatar_path')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    expect($user->refresh()->avatar_path)->toBeNull();
});
