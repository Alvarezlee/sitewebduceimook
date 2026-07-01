<?php

use App\Livewire\Shop\CartPage;
use App\Models\Payment\Payment;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Models\User;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('shop index, category and product pages render', function () {
    $category = ProductCategory::factory()->create();
    $product = Product::factory()->create(['product_category_id' => $category->id]);

    $this->get(route('shop.index'))->assertOk();
    $this->get(route('shop.category', $category))->assertOk()->assertSee($product->name);
    $this->get(route('shop.show', $product))->assertOk()->assertSee($product->name);
});

test('a visitor can add a product to the cart and see it on the cart page', function () {
    $product = Product::factory()->create();

    $this->post(route('shop.cart.add', $product), ['quantity' => 2])->assertRedirect();

    $this->get(route('shop.cart'))->assertOk()->assertSee($product->name);
});

test('the cart livewire component can update quantities and remove items', function () {
    $product = Product::factory()->create(['price' => 1000]);
    $this->post(route('shop.cart.add', $product), ['quantity' => 1]);

    $component = Livewire::test(CartPage::class);
    $component->assertSee($product->name);

    $component->call('updateQuantity', $product->id, 3);
    $component->assertSee('value="3"', false);

    $component->call('remove', $product->id);
    $component->assertDontSee($product->name);
});

test('checkout requires an authenticated user and creates an order redirected to monetbil', function () {
    $product = Product::factory()->create(['price' => 2500]);
    $this->post(route('shop.cart.add', $product), ['quantity' => 2]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('shop.checkout.store'), ['phone' => '650000000']);

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain(config('services.monetbil.service_key'));

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'status' => 'pending',
        'total' => 5000,
    ]);
});

test('a confirmed payment marks the order as paid and generates a downloadable invoice', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 1000]);
    $this->actingAs($user)->post(route('shop.cart.add', $product), ['quantity' => 1]);

    $this->post(route('shop.checkout.store'), ['phone' => '650000000']);

    $order = Order::query()->where('user_id', $user->id)->firstOrFail();

    $payment = Payment::query()->where('payable_id', $order->id)->firstOrFail();

    $payload = [
        'payment_ref' => (string) $payment->id,
        'transaction_id' => 'MTB-SHOP-1',
        'status' => 'success',
    ];
    unset($payload['sign']);
    ksort($payload);
    $sign = md5(config('services.monetbil.service_secret').implode('', $payload));
    $payload['sign'] = $sign;

    app(PaymentService::class)->handleWebhook($payload);

    $order->refresh();
    expect($order->status)->toBe('paid')->and($order->invoice_path)->not->toBeNull();

    $this->actingAs($user)
        ->get(route('shop.orders.show', $order))
        ->assertOk()
        ->assertSee('Télécharger la facture');
});
