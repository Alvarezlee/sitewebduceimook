<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizQuestion>
 */
class QuizQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_subject_id' => QuizSubject::factory(),
            'created_by' => null,
            'source' => 'teacher',
            'question' => fake()->sentence().' ?',
            'explanation' => fake()->sentence(),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
            'is_open_ended' => false,
            'is_validated' => true,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (QuizQuestion $question) {
            if ($question->is_open_ended) {
                return;
            }

            $correctIndex = fake()->numberBetween(0, 3);

            for ($i = 0; $i < 4; $i++) {
                $question->options()->create([
                    'label' => fake()->words(3, true),
                    'is_correct' => $i === $correctIndex,
                    'order' => $i,
                ]);
            }
        });
    }
}
