<?php

namespace App\Livewire\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Sign in')]
class Login extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->autofocus(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required(),
                Checkbox::make('remember')
                    ->label('Remember me'),
            ])
            ->statePath('data');
    }

    public function login(): void
    {
        $data = $this->form->getState();

        $throttleKey = $this->throttleKey($data['email']);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'data.email' => __('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => (int) ceil($seconds / 60),
                ]),
            ]);
        }

        if (! Auth::attempt(
            ['email' => $data['email'], 'password' => $data['password']],
            (bool) ($data['remember'] ?? false),
        )) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'data.email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($throttleKey);
        Session::regenerate();

        $this->redirectIntended(route('dashboard'), navigate: true);
    }

    private function throttleKey(string $email): string
    {
        return Str::lower($email).'|'.request()->ip();
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
