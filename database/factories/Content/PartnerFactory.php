<?php

namespace Database\Factories\Content;

use App\Models\Content\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'logo_path' => 'partners/sample.png',
            'website_url' => fake()->url(),
            'order' => 0,
            'is_published' => true,
        ];
    }
}
