<?php

use App\Http\Middleware\EnsureSessionIsValid;
use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/verify-2fa', 'pages::auth.verify-code')->name('verify-2fa');
Route::livewire('/forgot-password', 'pages::auth.forgot-password')->name('forgot-password');
Route::livewire('/reset-password/{token}', 'pages::auth.reset-password')->name('password.reset');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');



Route::group([
    'prefix' => 'dashboard',
    'middleware' => ['auth', EnsureSessionIsValid::class],
], function () {
        Route::livewire('/', 'pages::dashboard.index')->name('dashboard.index');
        Route::livewire('/profile', 'pages::dashboard.profile.index')->name('dashboard.profile');


        Route::livewire('/users', 'pages::dashboard.user.index')->name('dashboard.users');
});