<?php

namespace Database\Factories\Shop;

use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence(3);

        return [
            'product_category_id' => ProductCategory::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 1000000),
            'type' => fake()->randomElement(['livre', 'fascicule', 'cours', 'pdf', 'logiciel', 'formation', 'abonnement']),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(500, 15000),
            'compare_at_price' => null,
            'stock' => null,
            'cover_path' => null,
            'file_path' => 'shop/products/sample.pdf',
            'is_digital' => true,
            'is_published' => true,
        ];
    }
}
