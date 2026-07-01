<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\QuizQuestion;
use App\Models\Quiz\QuizQuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizQuestionOption>
 */
class QuizQuestionOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'quiz_question_id' => QuizQuestion::factory(),
            'label' => fake()->words(3, true),
            'is_correct' => false,
            'order' => 0,
        ];
    }
}
