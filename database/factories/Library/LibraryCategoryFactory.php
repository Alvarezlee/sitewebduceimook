<?php

namespace Database\Factories\Library;

use App\Models\Library\LibraryCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LibraryCategory>
 */
class LibraryCategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Probatoires', 'Baccalauréat', 'BEPC', 'CAP', 'BTS', 'Concours',
            'Corrigés', 'Fascicules', 'Livres',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 100000),
            'description' => fake()->sentence(),
            'parent_id' => null,
            'icon' => 'book-open',
        ];
    }
}
