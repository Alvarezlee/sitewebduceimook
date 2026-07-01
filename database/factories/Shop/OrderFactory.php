<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_number' => 'CMD-'.strtoupper(Str::random(8)),
            'user_id' => User::factory(),
            'status' => 'pending',
            'subtotal' => 0,
            'discount' => 0,
            'total' => 0,
            'invoice_path' => null,
        ];
    }
}
