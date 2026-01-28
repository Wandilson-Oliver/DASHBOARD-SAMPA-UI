<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Jenssegers\Agent\Agent;
use App\Models\UserLogin;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        // garante que a sessão final já existe
        if (! session()->isStarted()) {
            session()->start();
        }

        $agent = new Agent();

        UserLogin::create([
            'user_id'    => $event->user->id,
            'session_id' => session()->getId(), // 🔥 AGORA BATE COM sessions.id
            'ip_address' => request()->ip(),
            'browser'    => $agent->browser(),
            'platform'   => $agent->platform(),
            'user_agent' => request()->userAgent(),
            'logged_in_at' => now(),
        ]);
    }
}
