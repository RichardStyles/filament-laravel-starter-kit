<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\PasswordReset as PasswordResetEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Set a new password')]
class ResetPassword extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public string $token = '';

    public ?array $data = [];

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->form->fill([
            'email' => request('email', ''),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('New password')
                    ->password()
                    ->revealable()
                    ->required()
                    ->rule(PasswordRule::default())
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->label('Confirm new password')
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function resetPassword(ResetsUserPasswords $resetter): void
    {
        $data = $this->form->getState();

        $status = Password::reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
                'token' => $this->token,
            ],
            function (User $user, string $password) use ($resetter, $data): void {
                $resetter->reset($user, [
                    'password' => $password,
                    'password_confirmation' => $data['password_confirmation'],
                ]);

                $user->forceFill([
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordResetEvent($user));
            },
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'data.email' => [__($status)],
            ]);
        }

        FilamentNotification::make()
            ->title(__($status))
            ->success()
            ->send();

        $this->redirect(route('login'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.reset-password');
    }
}
