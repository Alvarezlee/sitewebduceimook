<?php

namespace Database\Factories\Content;

use App\Models\Content\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'category' => fake()->randomElement(['Actualité', 'Événement', 'Communiqué']),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 1000000),
            'excerpt' => fake()->sentence(),
            'content' => implode("\n\n", fake()->paragraphs(5)),
            'cover_path' => null,
            'author_id' => User::factory(),
            'published_at' => now(),
            'is_published' => true,
            'seo_title' => $title,
            'seo_description' => fake()->sentence(),
        ];
    }
}
