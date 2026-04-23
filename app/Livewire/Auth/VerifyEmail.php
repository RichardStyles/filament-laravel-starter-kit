<?php

namespace App\Livewire\Auth;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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
        Auth::user()->sendEmailVerificationNotification();

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
