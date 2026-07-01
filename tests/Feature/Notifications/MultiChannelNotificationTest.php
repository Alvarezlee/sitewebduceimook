<?php

use App\Models\Library\LibraryPlan;
use App\Models\Library\LibrarySubscription;
use App\Models\Payment\Payment;
use App\Models\User;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\Channels\WhatsAppChannel;
use App\Notifications\PaymentConfirmedNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

test('a payment confirmation notification is sent through mail, database, whatsapp and sms', function () {
    Notification::fake();

    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create();
    $subscription = LibrarySubscription::factory()->create(['user_id' => $user->id, 'library_plan_id' => $plan->id]);
    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'payable_type' => $subscription->getMorphClass(),
        'payable_id' => $subscription->id,
    ]);

    $user->notify(new PaymentConfirmedNotification($payment));

    Notification::assertSentTo($user, PaymentConfirmedNotification::class, function ($notification, $channels) {
        return in_array('mail', $channels)
            && in_array('database', $channels)
            && in_array(WhatsAppChannel::class, $channels)
            && in_array(SmsChannel::class, $channels);
    });
});

test('the whatsapp channel logs a failed attempt when the integration is not configured', function () {
    $user = User::factory()->create(['phone' => '650000000']);
    $plan = LibraryPlan::factory()->create();
    $subscription = LibrarySubscription::factory()->create(['user_id' => $user->id, 'library_plan_id' => $plan->id]);
    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'payable_type' => $subscription->getMorphClass(),
        'payable_id' => $subscription->id,
    ]);

    $user->notify(new PaymentConfirmedNotification($payment));

    $this->assertDatabaseHas('notification_logs', [
        'user_id' => $user->id,
        'channel' => 'whatsapp',
        'status' => 'failed',
    ]);

    $this->assertDatabaseHas('notification_logs', [
        'user_id' => $user->id,
        'channel' => 'sms',
        'status' => 'failed',
    ]);
});

test('the whatsapp channel sends successfully when configured', function () {
    config(['services.whatsapp.api_url' => 'https://graph.facebook.com/v18.0']);
    config(['services.whatsapp.api_token' => 'fake-token']);
    config(['services.whatsapp.phone_number_id' => '123456']);

    Http::fake(['graph.facebook.com/*' => Http::response(['messages' => [['id' => 'wamid.1']]], 200)]);

    $user = User::factory()->create(['phone' => '650000000']);
    $plan = LibraryPlan::factory()->create();
    $subscription = LibrarySubscription::factory()->create(['user_id' => $user->id, 'library_plan_id' => $plan->id]);
    $payment = Payment::factory()->create([
        'user_id' => $user->id,
        'payable_type' => $subscription->getMorphClass(),
        'payable_id' => $subscription->id,
    ]);

    $user->notify(new PaymentConfirmedNotification($payment));

    Http::assertSent(fn ($request) => str_contains($request->url(), 'graph.facebook.com'));

    $this->assertDatabaseHas('notification_logs', [
        'user_id' => $user->id,
        'channel' => 'whatsapp',
        'status' => 'sent',
    ]);
});

test('an authenticated user can register a device token for push notifications', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('notifications.device-token'), ['token' => 'fcm-token-abc', 'platform' => 'web'])
        ->assertOk();

    $this->assertDatabaseHas('device_tokens', [
        'user_id' => $user->id,
        'token' => 'fcm-token-abc',
        'platform' => 'web',
    ]);
});
