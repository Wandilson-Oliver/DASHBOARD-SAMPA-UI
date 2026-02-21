<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureSessionIsValid;


Route::prefix('documentation')
    ->middleware([
        'auth',
        EnsureSessionIsValid::class,
    ])
    ->group(function () {

        // Documentação
        Route::livewire('/', 'pages::documentation.index')
            ->name('documentation.index');
        Route::livewire('/buttons', 'pages::documentation.buttons')
            ->name('documentation.buttons');
        Route::livewire('/input', 'pages::documentation.input')
            ->name('documentation.input');
    });