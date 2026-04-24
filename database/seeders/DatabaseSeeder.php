<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Role::findOrCreate('admin');
        Role::findOrCreate('user');

        User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
            ->assignRole('admin');

        User::factory(5)
            ->create()
            ->each(fn (User $user): User => $user->assignRole('user'));
    }
}
