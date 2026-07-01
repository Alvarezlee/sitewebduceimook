<?php

namespace App\Notifications\Channels;

use App\Models\Notification\NotificationLog;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('sms', $notification);

        if (blank($to) || ! method_exists($notification, 'toSms')) {
            return;
        }

        $message = $notification->toSms($notifiable);
        $log = $this->log($notifiable, $notification);

        $gatewayUrl = config('services.sms.gateway_url');

        if (blank($gatewayUrl)) {
            $log?->update(['status' => 'failed', 'error_message' => 'Passerelle SMS non configurée.']);

            return;
        }

        try {
            $response = Http::timeout(15)->post($gatewayUrl, [
                'api_key' => config('services.sms.api_key'),
                'sender' => config('services.sms.sender'),
                'to' => $to,
                'message' => $message,
            ]);

            $response->throw();

            $log?->update(['status' => 'sent']);
        } catch (\Throwable $e) {
            Log::warning('Échec envoi SMS : '.$e->getMessage());
            $log?->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        }
    }

    private function log(mixed $notifiable, Notification $notification): ?NotificationLog
    {
        if (! method_exists($notifiable, 'getKey')) {
            return null;
        }

        return NotificationLog::query()->create([
            'user_id' => $notifiable->getKey(),
            'channel' => 'sms',
            'subject' => class_basename($notification),
            'status' => 'queued',
        ]);
    }
}
