<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureSessionIsValid;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Auth)
|--------------------------------------------------------------------------
*/

Route::livewire('/login', 'pages::auth.login')
    ->name('login');

Route::livewire('/verify-2fa', 'pages::auth.verify-code')
    ->name('verify-2fa');

Route::livewire('/forgot-password', 'pages::auth.forgot-password')
    ->name('forgot-password');

Route::livewire('/reset-password/{token}', 'pages::auth.reset-password')
    ->name('password.reset');

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    auth()->logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard (Protegido)
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')
    ->middleware([
        'auth',
        EnsureSessionIsValid::class,
    ])
    ->group(function () {

        // Dashboard
        Route::livewire('/', 'pages::dashboard.index')
            ->name('dashboard.index');

        // Perfil do usuário (todos autenticados)
        Route::livewire('/profile', 'pages::dashboard.profile.index')
            ->name('dashboard.profile');

        // Usuários (exemplo com permissão)
        Route::livewire('/users', 'pages::dashboard.user.index')
            ->middleware('permission:users.view')
            ->name('dashboard.users');


        Route::livewire('/roles', 'pages::dashboard.user.roles-permissions')
            ->middleware('permission:roles.manage')
            ->name('dashboard.roles');
    });
    