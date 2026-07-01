<?php

namespace Database\Factories\Library;

use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LibraryDocument>
 */
class LibraryDocumentFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'library_category_id' => LibraryCategory::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 1000000),
            'type' => fake()->randomElement([
                'probatoire', 'baccalaureat', 'bepc', 'cap', 'bts',
                'concours', 'corrige', 'fascicule', 'livre',
            ]),
            'description' => fake()->paragraph(),
            'file_path' => 'library/documents/sample.pdf',
            'cover_path' => null,
            'file_size' => fake()->numberBetween(100000, 5000000),
            'year' => fake()->numberBetween(2018, 2026),
            'is_free' => fake()->boolean(20),
            'downloads_count' => 0,
            'is_published' => true,
        ];
    }
}
