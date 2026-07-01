<?php

namespace Database\Factories\Content;

use App\Models\Content\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => fake()->name(),
            'role_title' => fake()->randomElement(['Élève', 'Enseignant', 'Parent', 'Partenaire']),
            'photo_path' => null,
            'content' => fake()->paragraph(),
            'rating' => fake()->numberBetween(4, 5),
            'is_approved' => true,
        ];
    }
}
