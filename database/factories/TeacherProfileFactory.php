<?php

namespace Database\Factories;

use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeacherProfile>
 */
class TeacherProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialty' => fake()->randomElement(['Réseaux', 'Développement Web', 'Bases de données', 'Algorithmique', 'Systèmes']),
            'institution' => fake()->company(),
            'bio' => fake()->paragraph(),
            'bureau_role' => null,
            'bureau_order' => null,
            'is_bureau_member' => false,
        ];
    }

    public function bureauMember(string $role, int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'bureau_role' => $role,
            'bureau_order' => $order,
            'is_bureau_member' => true,
        ]);
    }
}
