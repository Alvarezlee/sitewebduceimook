<?php

namespace Database\Factories\Business;

use App\Models\Business\Business;
use App\Models\Business\BusinessService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessService>
 */
class BusinessServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'icon' => 'briefcase',
            'order' => 0,
        ];
    }
}
