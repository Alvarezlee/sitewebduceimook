<?php

namespace Database\Factories;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'institution' => fake()->company().' - Lycée',
            'class_level' => fake()->randomElement(['3e', 'Tle C', 'Tle D', '1ere C', '2nde C']),
            'region' => 'Littoral',
            'department' => 'Moungo',
            'arrondissement' => fake()->randomElement(['Nkongsamba I', 'Nkongsamba II', 'Nkongsamba III', 'Loum', 'Manjo']),
            'parent_name' => fake()->name(),
            'parent_phone' => fake()->numerify('6#########'),
        ];
    }
}
