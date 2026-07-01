<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $user->forceFill(['last_login_at' => now()])->save();

        LoginLog::query()->create([
            'user_id' => $user->id,
            'email_attempted' => $user->email,
            'ip_address' => request()->ip() ?? '0.0.0.0',
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'status' => 'success',
        ]);
    }
}
