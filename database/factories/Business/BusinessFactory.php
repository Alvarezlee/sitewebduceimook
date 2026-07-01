<?php

namespace Database\Factories\Business;

use App\Models\Business\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Business>
 */
class BusinessFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'logo_path' => null,
            'description' => fake()->paragraph(),
            'whatsapp' => fake()->numerify('6#########'),
            'facebook_url' => null,
            'linkedin_url' => null,
            'website_url' => fake()->url(),
            'address' => fake()->address(),
            'is_published' => true,
        ];
    }
}
