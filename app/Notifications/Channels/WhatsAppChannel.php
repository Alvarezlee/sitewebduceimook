<?php

namespace App\Notifications\Channels;

use App\Models\Notification\NotificationLog;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('whatsapp', $notification);

        if (blank($to) || ! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);
        $log = $this->log($notifiable, $notification, $message);

        $apiUrl = config('services.whatsapp.api_url');
        $token = config('services.whatsapp.api_token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        if (blank($apiUrl) || blank($token)) {
            $log?->update(['status' => 'failed', 'error_message' => 'Intégration WhatsApp non configurée.']);

            return;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(15)
                ->post("{$apiUrl}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => ['body' => $message],
                ]);

            $response->throw();

            $log?->update(['status' => 'sent']);
        } catch (\Throwable $e) {
            Log::warning('Échec envoi WhatsApp : '.$e->getMessage());
            $log?->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
        }
    }

    private function log(mixed $notifiable, Notification $notification, string $message): ?NotificationLog
    {
        if (! property_exists($notifiable, 'id') && ! method_exists($notifiable, 'getKey')) {
            return null;
        }

        return NotificationLog::query()->create([
            'user_id' => $notifiable->getKey(),
            'channel' => 'whatsapp',
            'subject' => class_basename($notification),
            'status' => 'queued',
        ]);
    }
}
