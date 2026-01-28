<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserLogin;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        UserLogin::where('user_id', $event->user->id)
            ->where('session_id', session()->getId())
            ->whereNull('logged_out_at')
            ->latest('logged_in_at')
            ->limit(1)
            ->update([
                'logged_out_at' => now(),
            ]);
    }
}
