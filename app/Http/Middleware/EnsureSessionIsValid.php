<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Models\UserLogin;

class EnsureSessionIsValid
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionId = session()->getId();
            $userId    = Auth::id();

            $exists = DB::table('sessions')
                ->where('id', $sessionId)
                ->exists();

            if (! $exists) {

                // 🧾 FECHA O HISTÓRICO DA SESSÃO (parte que faltava)
                UserLogin::where('user_id', $userId)
                    ->where('session_id', $sessionId)
                    ->whereNull('logged_out_at')
                    ->update([
                        'logged_out_at' => now(),
                    ]);

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
                        'session' => 'Sua sessão expirou ou foi encerrada.',
                    ]);
            }
        }

        return $next($request);
    }
}
