<?php

namespace Database\Factories\Content;

use App\Models\Content\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question' => fake()->sentence().' ?',
            'answer' => fake()->paragraph(),
            'category' => fake()->randomElement(['Général', 'Bibliothèque', 'Quiz', 'Boutique']),
            'order' => 0,
            'is_published' => true,
        ];
    }
}
