<?php

declare(strict_types=1);

use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('admin');
    Role::findOrCreate('user');
});

it('redirects guests to the admin login when hitting /admin', function (): void {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('forbids a regular authenticated user from reaching /admin', function (): void {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('allows an admin user to reach /admin', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Dashboard');
});

it('allows admins to list users via the resource', function (): void {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertOk();
});

it('forbids non-admins from listing users via the resource', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin/users')
        ->assertForbidden();
});
