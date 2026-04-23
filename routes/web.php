<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', fn () => abort(501))->name('password.request');
    Route::get('/reset-password/{token}', fn () => abort(501))->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', fn () => abort(501))->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', fn () => abort(501))->name('verification.verify');
    Route::post('/logout', LogoutController::class)->name('logout');

    Route::view('/dashboard', 'dashboard')->name('dashboard');
});
