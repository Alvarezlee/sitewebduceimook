<?php

namespace Database\Factories\Library;

use App\Models\Library\LibraryPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LibraryPlan>
 */
class LibraryPlanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Mensuel', 'Trimestriel', 'Annuel']),
            'price' => fake()->randomElement([1000, 2500, 8000]),
            'duration_days' => fake()->randomElement([30, 90, 365]),
            'max_downloads' => fake()->randomElement([10, 30, null]),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
