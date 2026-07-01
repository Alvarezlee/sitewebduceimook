<?php

namespace App\Notifications\Channels;

use App\Models\Notification\NotificationLog;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Canal de notification Push via Firebase Cloud Messaging.
 *
 * Utilise l'API legacy FCM (clé serveur) pour rester simple à configurer :
 * FCM_SERVER_KEY dans .env. Pour une intégration production robuste avec
 * l'API HTTP v1 (OAuth2 via compte de service), remplacer l'appel HTTP
 * ci-dessous en conservant la même interface de canal.
 */
class FcmChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! $notifiable instanceof User || ! method_exists($notification, 'toFcm')) {
            return;
        }

        $tokens = $notifiable->deviceTokens()->pluck('token');

        if ($tokens->isEmpty()) {
            return;
        }

        $payload = $notification->toFcm($notifiable);
        $serverKey = config('services.fcm.server_key');

        foreach ($tokens as $token) {
            $log = NotificationLog::query()->create([
                'user_id' => $notifiable->id,
                'channel' => 'fcm',
                'subject' => class_basename($notification),
                'status' => 'queued',
            ]);

            if (blank($serverKey)) {
                $log->update(['status' => 'failed', 'error_message' => 'FCM non configuré (FCM_SERVER_KEY).']);

                continue;
            }

            try {
                $response = Http::withHeaders(['Authorization' => "key={$serverKey}"])
                    ->timeout(15)
                    ->post('https://fcm.googleapis.com/fcm/send', [
                        'to' => $token,
                        'notification' => $payload,
                    ]);

                $response->throw();

                $log->update(['status' => 'sent']);
            } catch (\Throwable $e) {
                Log::warning('Échec envoi FCM : '.$e->getMessage());
                $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
        }
    }
}
