<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeleteUserAccount
{
    /**
     * Validate the user's password, log them out, and delete the account.
     */
    public function delete(User $user, string $password): void
    {
        Validator::make(['password' => $password], [
            'password' => ['required', 'current_password:web'],
        ], [
            'password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('deleteAccount');

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $user->delete();
    }
}
