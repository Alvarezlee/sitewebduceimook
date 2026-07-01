<?php

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        LoginLog::query()->create([
            'user_id' => $event->user?->id,
            'email_attempted' => (string) ($event->credentials['email'] ?? ''),
            'ip_address' => request()->ip() ?? '0.0.0.0',
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'status' => 'failed',
        ]);
    }
}
