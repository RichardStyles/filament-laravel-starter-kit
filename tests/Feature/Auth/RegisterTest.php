<?php

use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

it('registers a new user, fires Registered and logs them in', function (): void {
    Event::fake([Registered::class]);

    Livewire::test(Register::class)
        ->set('data.name', 'Ada Lovelace')
        ->set('data.email', 'ada@example.com')
        ->set('data.password', 'P@ssw0rd!secret')
        ->set('data.password_confirmation', 'P@ssw0rd!secret')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect(User::where('email', 'ada@example.com')->exists())->toBeTrue();
    expect(auth()->user()->email)->toBe('ada@example.com');
    Event::assertDispatched(Registered::class);
});

it('rejects a duplicate email', function (): void {
    User::factory()->create(['email' => 'taken@example.com']);

    Livewire::test(Register::class)
        ->set('data.name', 'Someone')
        ->set('data.email', 'taken@example.com')
        ->set('data.password', 'P@ssw0rd!secret')
        ->set('data.password_confirmation', 'P@ssw0rd!secret')
        ->call('register')
        ->assertHasErrors(['data.email']);

    expect(User::where('email', 'taken@example.com')->count())->toBe(1);
});

it('requires matching password confirmation', function (): void {
    Livewire::test(Register::class)
        ->set('data.name', 'Ada Lovelace')
        ->set('data.email', 'ada@example.com')
        ->set('data.password', 'P@ssw0rd!secret')
        ->set('data.password_confirmation', 'different-value')
        ->call('register')
        ->assertHasErrors(['data.password']);

    expect(User::where('email', 'ada@example.com')->exists())->toBeFalse();
});
