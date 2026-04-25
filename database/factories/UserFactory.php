<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'appearance_preference' => 'system',
        ];
    }

    /**
     * Refresh the model from storage so every column populated by the database
     * (defaults, columns added in later migrations) is loaded onto the in-memory
     * instance — required when Model::preventAccessingMissingAttributes() is on.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (User $user): void {
            $user->refresh();
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign the admin role after the user is created.
     */
    public function admin(): static
    {
        return $this->afterCreating(function (User $user): void {
            Role::findOrCreate('admin');
            $user->assignRole('admin');
        });
    }
}
