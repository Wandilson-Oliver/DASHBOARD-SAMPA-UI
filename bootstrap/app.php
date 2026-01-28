<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureSessionIsValid;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Middleware do grupo WEB
        |--------------------------------------------------------------------------
        | - Executa após auth
        | - Valida existência da sessão
        | - Sincroniza histórico de login
        */
        $middleware->appendToGroup(
            'web',
            EnsureSessionIsValid::class
        );

        /*
        |--------------------------------------------------------------------------
        | Aliases de Middleware (RBAC)
        |--------------------------------------------------------------------------
        | Substitui completamente o antigo Kernel.php
        */
        $middleware->alias([
            'permission' => CheckPermission::class,
            'role'       => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
