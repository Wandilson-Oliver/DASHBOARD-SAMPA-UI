<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = Auth::user();

        if (! $user || ! $user->hasRole($role)) {
            abort(403, 'Acesso restrito a este papel.');
        }

        return $next($request);
    }
}
