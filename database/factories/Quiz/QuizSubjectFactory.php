<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\QuizSubject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QuizSubject>
 */
class QuizSubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Informatique', 'TIC', 'Algorithmique', 'Réseaux', 'Programmation', 'Culture numérique',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
        ];
    }
}
