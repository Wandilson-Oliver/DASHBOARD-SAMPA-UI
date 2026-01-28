<?php

namespace App\Console\Commands;

use App\Models\UserLogin;
use Illuminate\Console\Command;

class CloseExpiredLogins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-expired-logins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Closes expired user logins by marking them as logged out';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = config('session.lifetime') * 60;

        UserLogin::whereNull('logged_out_at')
            ->whereHas('user', function ($q) use ($timeout) {
                $q->whereDoesntHave('sessions', function ($s) use ($timeout) {
                    $s->where('last_activity', '>=', now()->subSeconds($timeout)->timestamp);
                });
            })
            ->update([
                'logged_out_at' => now(),
            ]);
    }

}
