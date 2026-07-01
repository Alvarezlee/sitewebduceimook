<?php

use App\Models\Library\LibraryPlan;
use App\Models\Library\LibrarySubscription;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Models\Shop\Order;
use App\Models\Shop\OrderItem;
use App\Models\User;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Storage;

function monetbilSign(array $params): string
{
    unset($params['sign']);
    ksort($params);

    return md5(config('services.monetbil.service_secret').implode('', $params));
}

test('initiating a payment returns a signed monetbil widget url', function () {
    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create(['duration_days' => 30]);
    $subscription = LibrarySubscription::factory()->create([
        'user_id' => $user->id,
        'library_plan_id' => $plan->id,
        'status' => 'pending',
    ]);

    $payment = app(PaymentService::class)->initiate($user, $subscription, 1000, '650000000');

    expect($payment->status)->toBe('pending')
        ->and($payment->_payment_url)->toContain(config('services.monetbil.service_key'))
        ->and($payment->_payment_url)->toContain('sign=');
});

test('a valid webhook activates the library subscription', function () {
    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create(['duration_days' => 30, 'max_downloads' => 10]);
    $subscription = LibrarySubscription::factory()->create([
        'user_id' => $user->id,
        'library_plan_id' => $plan->id,
        'status' => 'pending',
    ]);

    $payment = app(PaymentService::class)->initiate($user, $subscription, 1000, '650000000');

    $payload = [
        'payment_ref' => (string) $payment->id,
        'transaction_id' => 'MTB-123456',
        'status' => 'success',
        'phone' => '650000000',
        'amount' => '1000',
    ];
    $payload['sign'] = monetbilSign($payload);

    $updated = app(PaymentService::class)->handleWebhook($payload);

    expect($updated->status)->toBe('success')
        ->and($updated->gateway_reference)->toBe('MTB-123456');

    expect($subscription->fresh()->status)->toBe('active');
});

test('a webhook with an invalid signature is rejected', function () {
    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create();
    $subscription = LibrarySubscription::factory()->create([
        'user_id' => $user->id,
        'library_plan_id' => $plan->id,
        'status' => 'pending',
    ]);

    $payment = app(PaymentService::class)->initiate($user, $subscription, 1000, '650000000');

    $payload = [
        'payment_ref' => (string) $payment->id,
        'transaction_id' => 'MTB-000',
        'status' => 'success',
        'sign' => 'not-a-valid-signature',
    ];

    expect(fn () => app(PaymentService::class)->handleWebhook($payload))
        ->toThrow(RuntimeException::class);

    expect($payment->fresh()->status)->toBe('pending');
});

test('a valid webhook activates a quiz candidate registration', function () {
    $user = User::factory()->create();
    $edition = QuizEdition::factory()->create();
    $candidate = QuizCandidate::factory()->create([
        'user_id' => $user->id,
        'quiz_edition_id' => $edition->id,
        'registration_status' => 'pending',
    ]);

    $payment = app(PaymentService::class)->initiate($user, $candidate, 2000, '650000001');

    $payload = [
        'payment_ref' => (string) $payment->id,
        'transaction_id' => 'MTB-777',
        'status' => 'success',
    ];
    $payload['sign'] = monetbilSign($payload);

    app(PaymentService::class)->handleWebhook($payload);

    expect($candidate->fresh()->registration_status)->toBe('paid');
});

test('a valid webhook completes an order and generates an invoice', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'total' => 5000]);
    OrderItem::factory()->create(['order_id' => $order->id]);

    $payment = app(PaymentService::class)->initiate($user, $order, 5000, '650000002');

    $payload = [
        'payment_ref' => (string) $payment->id,
        'transaction_id' => 'MTB-999',
        'status' => 'success',
    ];
    $payload['sign'] = monetbilSign($payload);

    app(PaymentService::class)->handleWebhook($payload);

    $order->refresh();

    expect($order->status)->toBe('paid')
        ->and($order->invoice_path)->not->toBeNull();

    Storage::disk('local')->assertExists($order->invoice_path);
});

test('the webhook endpoint is reachable and enforces signature verification', function () {
    $this->postJson(route('webhooks.monetbil'), ['payment_ref' => '1'])
        ->assertStatus(403);
});
