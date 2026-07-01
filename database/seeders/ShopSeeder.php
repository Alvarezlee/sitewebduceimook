<?php

namespace Database\Seeders;

use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $categoryNames = ['Livres', 'Fascicules', 'Cours en ligne', 'Logiciels', 'Formations', 'Abonnements'];

        foreach ($categoryNames as $name) {
            $category = ProductCategory::query()->firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );

            Product::factory(4)->create([
                'product_category_id' => $category->id,
            ]);
        }
    }
}
