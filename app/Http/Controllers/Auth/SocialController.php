<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class SocialController extends Controller
{
    public function redirect(string $provider): SymfonyRedirectResponse
    {
        abort_unless(in_array($provider, config('services.socialite.providers'), true), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, config('services.socialite.providers'), true), 404);

        $oauthUser = Socialite::driver($provider)->user();

        $user = DB::transaction(function () use ($provider, $oauthUser): User {
            $existing = SocialAccount::query()
                ->where('provider', $provider)
                ->where('provider_id', $oauthUser->getId())
                ->first();

            if ($existing) {
                $existing->forceFill([
                    'provider_token' => $oauthUser->token ?? null,
                    'provider_refresh_token' => $oauthUser->refreshToken ?? null,
                ])->save();

                return $existing->user ?? throw new ModelNotFoundException;
            }

            $email = $oauthUser->getEmail();
            $user = $email ? User::query()->firstWhere('email', $email) : null;

            if (! $user) {
                $user = User::query()->create([
                    'name' => $oauthUser->getName() ?? $oauthUser->getNickname() ?? 'User',
                    'email' => $email ?? Str::random(16).'@'.$provider.'.local',
                    'password' => Hash::make(Str::random(64)),
                ]);
                $user->forceFill(['email_verified_at' => now()])->save();
                $user->assignRole('user');
            }

            $user->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $oauthUser->getId(),
                'provider_token' => $oauthUser->token ?? null,
                'provider_refresh_token' => $oauthUser->refreshToken ?? null,
            ]);

            return $user;
        });

        Auth::login($user, remember: true);

        return redirect()->intended(config('fortify.home', '/dashboard'));
    }
}
