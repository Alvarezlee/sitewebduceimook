<?php

use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryDocument;
use App\Models\Library\LibraryPlan;
use App\Models\Library\LibrarySubscription;
use App\Models\User;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

test('library index, category and document pages render', function () {
    $category = LibraryCategory::factory()->create();
    $document = LibraryDocument::factory()->create(['library_category_id' => $category->id]);

    $this->get(route('library.index'))->assertOk();
    $this->get(route('library.category', $category))->assertOk()->assertSee($document->title);
    $this->get(route('library.show', $document))->assertOk()->assertSee($document->title);
});

test('a guest is prompted to log in before downloading', function () {
    $document = LibraryDocument::factory()->create(['is_free' => true]);

    $this->get(route('library.show', $document))->assertOk()->assertSee('Se connecter pour télécharger');
});

test('subscribing redirects to the monetbil payment page', function () {
    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create();

    $response = $this->actingAs($user)->post(route('library.subscribe.store', $plan), [
        'phone' => '650000000',
    ]);

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain(config('services.monetbil.service_key'));

    $this->assertDatabaseHas('library_subscriptions', [
        'user_id' => $user->id,
        'library_plan_id' => $plan->id,
        'status' => 'pending',
    ]);
});

test('a user without an active subscription cannot download a paid document', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $document = LibraryDocument::factory()->create(['is_free' => false]);
    Storage::disk('local')->put($document->file_path, 'fake-pdf-content');

    $url = URL::temporarySignedRoute('library.download', now()->addMinutes(5), ['document' => $document->id]);

    $this->actingAs($user)->get($url)->assertForbidden();
});

test('a user with an active subscription and remaining quota can download a paid document', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create(['max_downloads' => 5]);
    LibrarySubscription::factory()->create([
        'user_id' => $user->id,
        'library_plan_id' => $plan->id,
        'status' => 'active',
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDays(29),
        'downloads_used' => 0,
    ]);

    $document = LibraryDocument::factory()->create(['is_free' => false]);
    Storage::disk('local')->put($document->file_path, 'fake-pdf-content');

    $url = URL::temporarySignedRoute('library.download', now()->addMinutes(5), ['document' => $document->id]);

    $this->actingAs($user)->get($url)->assertOk();

    $this->assertDatabaseHas('library_subscriptions', [
        'user_id' => $user->id,
        'downloads_used' => 1,
    ]);
    $this->assertDatabaseHas('library_downloads', [
        'user_id' => $user->id,
        'library_document_id' => $document->id,
    ]);
});

test('a payment confirmation activates the subscription and unlocks download', function () {
    $user = User::factory()->create();
    $plan = LibraryPlan::factory()->create(['duration_days' => 30, 'max_downloads' => 5]);
    $subscription = LibrarySubscription::factory()->create([
        'user_id' => $user->id,
        'library_plan_id' => $plan->id,
        'status' => 'pending',
    ]);

    $payment = app(PaymentService::class)->initiate($user, $subscription, (float) $plan->price, '650000000');

    $payload = [
        'payment_ref' => (string) $payment->id,
        'transaction_id' => 'MTB-LIB-1',
        'status' => 'success',
    ];
    unset($payload['sign']);
    ksort($payload);
    $signParams = $payload;
    $payload['sign'] = md5(config('services.monetbil.service_secret').implode('', $signParams));

    app(PaymentService::class)->handleWebhook($payload);

    expect($subscription->fresh()->isActive())->toBeTrue();
});
