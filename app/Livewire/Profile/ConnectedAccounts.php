<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use Filament\Notifications\Notification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConnectedAccounts extends Component
{
    public function disconnect(string $provider): void
    {
        $user = Auth::user() ?? throw new AuthenticationException;

        $user->socialAccounts()->where('provider', $provider)->delete();

        Notification::make()
            ->title("Disconnected from {$provider}.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        $user = Auth::user() ?? throw new AuthenticationException;

        return view('livewire.profile.connected-accounts', [
            'providers' => config('services.socialite.providers'),
            'connectedProviders' => $user->socialAccounts()->pluck('provider')->all(),
        ]);
    }
}
