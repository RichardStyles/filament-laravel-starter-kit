<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SocialController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Settings\Settings;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): Factory|View => view('welcome'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

    Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect'])->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])->name('socialite.callback');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/logout', LogoutController::class)->name('logout');
});

Route::middleware(['auth', 'verified', 'auth.session'])->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/settings', Settings::class)->name('settings');
    Route::redirect('/profile', '/settings', 301)->name('profile');
});
