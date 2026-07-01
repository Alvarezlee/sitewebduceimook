<?php

namespace Database\Factories\Business;

use App\Models\Business\Business;
use App\Models\Business\BusinessMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BusinessMedia>
 */
class BusinessMediaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'business_id' => Business::factory(),
            'type' => 'photo',
            'path_or_url' => 'business/media/sample.jpg',
            'caption' => fake()->sentence(),
            'order' => 0,
        ];
    }
}
