<?php

namespace Database\Factories\Content;

use App\Models\Content\GalleryAlbum;
use App\Models\Content\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gallery_album_id' => GalleryAlbum::factory(),
            'path_or_url' => 'gallery/sample.jpg',
            'caption' => fake()->sentence(),
            'order' => 0,
        ];
    }
}
