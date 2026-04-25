<?php

namespace App\Livewire\Profile;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

class TwoFactorAuthentication extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    /** @var array{code?: string|null, password?: string|null}|null */
    public ?array $data = [];

    public bool $showingRecoveryCodes = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $user = Auth::user()?->fresh();
        $isPending = $user
            && $user->two_factor_secret !== null
            && $user->two_factor_confirmed_at === null;

        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'gap-6'])
            ->components([
                TextInput::make('code')
                    ->label('Confirmation code')
                    ->numeric()
                    ->length(6)
                    ->visible($isPending),
                TextInput::make('password')
                    ->label('Current password')
                    ->password()
                    ->revealable()
                    ->visible(! $isPending),
            ])
            ->statePath('data');
    }

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable): void
    {
        $enable(Auth::user());
    }

    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $confirm): void
    {
        $code = $this->data['code'] ?? '';
        $user = Auth::user() ?? throw new AuthenticationException;

        try {
            $confirm($user->fresh(), $code);
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'data.code' => $e->errors()['code'] ?? [__('The provided two factor authentication code was invalid.')],
            ]);
        }

        $this->showingRecoveryCodes = true;
        $this->data['code'] = null;

        Notification::make()
            ->title('Two-factor authentication confirmed.')
            ->success()
            ->send();
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate): void
    {
        $this->ensurePasswordMatches();

        $user = Auth::user() ?? throw new AuthenticationException;
        $generate($user->fresh());

        $this->showingRecoveryCodes = true;
        $this->data['password'] = null;

        Notification::make()
            ->title('Recovery codes regenerated.')
            ->success()
            ->send();
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable): void
    {
        $current = Auth::user() ?? throw new AuthenticationException;
        $user = $current->fresh() ?? throw new AuthenticationException;

        $isPendingConfirmation = $user->two_factor_secret !== null
            && $user->two_factor_confirmed_at === null;

        if (! $isPendingConfirmation) {
            $this->ensurePasswordMatches();
        }

        $disable($user);

        $this->showingRecoveryCodes = false;
        $this->data['password'] = null;

        if (! $isPendingConfirmation) {
            Notification::make()
                ->title('Two-factor authentication disabled.')
                ->success()
                ->send();
        }
    }

    private function ensurePasswordMatches(): void
    {
        $password = $this->data['password'] ?? '';
        $user = Auth::user() ?? throw new AuthenticationException;

        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'data.password' => [__('The provided password does not match your current password.')],
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.profile.two-factor-authentication');
    }
}
