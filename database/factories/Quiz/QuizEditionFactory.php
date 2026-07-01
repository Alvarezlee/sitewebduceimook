<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\QuizEdition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<QuizEdition>
 */
class QuizEditionFactory extends Factory
{
    public function definition(): array
    {
        $name = 'MOUNGO TIC QUIZZ '.fake()->unique()->year();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'registration_price' => 2000,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
            'duration_minutes' => 30,
            'max_attempts' => 1,
            'questions_per_attempt' => 20,
            'is_active' => true,
        ];
    }
}
