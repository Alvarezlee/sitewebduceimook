<?php

namespace Database\Factories\Content;

use App\Models\Content\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'image_path' => 'banners/sample.jpg',
            'link_url' => fake()->url(),
            'order' => 0,
            'position' => 'home_hero',
            'starts_at' => null,
            'ends_at' => null,
            'is_active' => true,
        ];
    }
}
