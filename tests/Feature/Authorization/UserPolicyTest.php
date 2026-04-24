<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('admin');
    Role::findOrCreate('user');
});

it('lets a user view, update and delete themselves', function (): void {
    $user = User::factory()->create();

    expect($user->can('view', $user))->toBeTrue();
    expect($user->can('update', $user))->toBeTrue();
    expect($user->can('delete', $user))->toBeTrue();
});

it('forbids one user from viewing, updating or deleting another user', function (): void {
    $alice = User::factory()->create();
    $bob = User::factory()->create();

    expect($alice->can('view', $bob))->toBeFalse();
    expect($alice->can('update', $bob))->toBeFalse();
    expect($alice->can('delete', $bob))->toBeFalse();
});

it('lets an admin view, update and delete any user via the before hook', function (): void {
    $admin = User::factory()->admin()->create();
    $someoneElse = User::factory()->create();

    expect($admin->can('view', $someoneElse))->toBeTrue();
    expect($admin->can('update', $someoneElse))->toBeTrue();
    expect($admin->can('delete', $someoneElse))->toBeTrue();
});

it('does not let any non-admin viewAny, create, restore or forceDelete', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();

    expect($user->can('viewAny', User::class))->toBeFalse();
    expect($user->can('create', User::class))->toBeFalse();
    expect($user->can('restore', $other))->toBeFalse();
    expect($user->can('forceDelete', $other))->toBeFalse();
});

it('lets an admin viewAny, create, restore and forceDelete via the before hook', function (): void {
    $admin = User::factory()->admin()->create();
    $other = User::factory()->create();

    expect($admin->can('viewAny', User::class))->toBeTrue();
    expect($admin->can('create', User::class))->toBeTrue();
    expect($admin->can('restore', $other))->toBeTrue();
    expect($admin->can('forceDelete', $other))->toBeTrue();
});
