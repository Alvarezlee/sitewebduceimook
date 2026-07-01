<?php

namespace Database\Factories\Quiz;

use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizCandidate>
 */
class QuizCandidateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'quiz_edition_id' => QuizEdition::factory(),
            'photo_path' => null,
            'institution' => fake()->company().' - Lycée',
            'class_level' => fake()->randomElement(['3e', 'Tle C', 'Tle D']),
            'region' => 'Littoral',
            'department' => 'Moungo',
            'arrondissement' => fake()->randomElement(['Nkongsamba I', 'Loum', 'Manjo']),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->numerify('6#########'),
            'registration_status' => 'paid',
        ];
    }
}
