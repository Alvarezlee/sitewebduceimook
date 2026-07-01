<?php

namespace Database\Factories\Payment;

use App\Models\Payment\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'gateway' => 'monetbil',
            'gateway_reference' => Str::uuid()->toString(),
            'amount' => fake()->numberBetween(1000, 10000),
            'currency' => 'XAF',
            'phone_number' => fake()->numerify('6#########'),
            'status' => 'success',
            'payload' => null,
            'paid_at' => now(),
        ];
    }
}
