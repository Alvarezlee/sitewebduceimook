<?php

namespace App\Services\Shop;

use App\Models\Shop\Order;
use App\Models\User;
use Illuminate\Support\Str;
use RuntimeException;

class OrderService
{
    public function __construct(private readonly CartService $cart) {}

    public function createFromCart(User $user): Order
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            throw new RuntimeException('Le panier est vide.');
        }

        $subtotal = (float) $items->sum('total');

        $order = Order::query()->create([
            'order_number' => 'CMD-'.now()->format('ymd').'-'.strtoupper(Str::random(6)),
            'user_id' => $user->id,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount' => 0,
            'total' => $subtotal,
        ]);

        foreach ($items as $entry) {
            $order->items()->create([
                'product_id' => $entry['product']->id,
                'quantity' => $entry['quantity'],
                'unit_price' => $entry['product']->price,
                'total_price' => $entry['total'],
            ]);
        }

        $this->cart->clear();

        return $order;
    }
}
