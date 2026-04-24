<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConnectedAccounts extends Component
{
    public function disconnect(string $provider): void
    {
        $user = Auth::user();

        $user->socialAccounts()->where('provider', $provider)->delete();

        Notification::make()
            ->title("Disconnected from {$provider}.")
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.profile.connected-accounts', [
            'providers' => config('services.socialite.providers'),
            'connectedProviders' => Auth::user()->socialAccounts()->pluck('provider')->all(),
        ]);
    }
}
