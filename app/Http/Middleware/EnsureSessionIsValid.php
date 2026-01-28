<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class EnsureSessionIsValid
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionId = session()->getId();

            $exists = DB::table('sessions')
                ->where('id', $sessionId)
                ->exists();

            if (! $exists) {
                // 🔥 logout completo
                Auth::logout();

                // 🧹 mata sessão local
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // ❌ remove remember-me cookie
                Cookie::queue(
                    Cookie::forget(Auth::getRecallerName())
                );

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'session' => 'Sua sessão foi encerrada em outro dispositivo.',
                    ]);
            }
        }

        return $next($request);
    }
}
