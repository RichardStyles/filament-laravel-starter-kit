<?php

namespace App\Actions\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LogoutOtherBrowserSessions
{
    /**
     * Validate the user's password and invalidate every other active session.
     */
    public function logout(string $password): void
    {
        Validator::make(['password' => $password], [
            'password' => ['required', 'current_password:web'],
        ], [
            'password.current_password' => __('The provided password does not match your current password.'),
        ])->validateWithBag('logoutOtherBrowserSessions');

        Auth::logoutOtherDevices($password);

        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();
    }
}
