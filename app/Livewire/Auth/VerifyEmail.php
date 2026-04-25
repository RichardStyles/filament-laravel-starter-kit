<?php

namespace App\Livewire\Auth;

use Filament\Notifications\Notification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.auth')]
#[Title('Verify your email')]
class VerifyEmail extends Component
{
    public function mount(): void
    {
        if (Auth::user()?->hasVerifiedEmail()) {
            $this->redirectIntended(route('dashboard'), navigate: true);
        }
    }

    public function resend(): void
    {
        $user = Auth::user() ?? throw new AuthenticationException;
        $throttleKey = 'verify-email-resend|'.$user->id;

        if (RateLimiter::tooManyAttempts($throttleKey, 6)) {
            Notification::make()
                ->title(__('Please wait before requesting another verification email.'))
                ->danger()
                ->send();

            return;
        }

        RateLimiter::hit($throttleKey, 60);

        $user->sendEmailVerificationNotification();

        Notification::make()
            ->title(__('A new verification link has been sent to your email.'))
            ->success()
            ->send();
    }

    public function logout(): void
    {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.verify-email');
    }
}
