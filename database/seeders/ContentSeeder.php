<?php

namespace Database\Seeders;

use App\Models\Content\Article;
use App\Models\Content\Banner;
use App\Models\Content\Event;
use App\Models\Content\Faq;
use App\Models\Content\GalleryAlbum;
use App\Models\Content\GalleryItem;
use App\Models\Content\Page;
use App\Models\Content\Partner;
use App\Models\Content\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            'Historique' => "Le CEIMO a été fondé pour rassembler les enseignants d'informatique du Moungo...",
            'Vision' => "Devenir la référence numérique du Moungo en matière d'éducation et de technologie.",
            'Mission' => "Former, informer et connecter les acteurs de l'écosystème numérique du Moungo.",
            'Politique de confidentialité' => 'Cette politique décrit comment le CEIMO collecte et protège vos données personnelles.',
            'Conditions d\'utilisation' => "En utilisant cette plateforme, vous acceptez les présentes conditions d'utilisation.",
        ];

        foreach ($pages as $title => $content) {
            Page::query()->firstOrCreate(
                ['slug' => Str::slug($title)],
                ['title' => $title, 'content' => $content, 'is_published' => true],
            );
        }

        Article::factory(10)->create();
        Event::factory(5)->create();

        GalleryAlbum::factory(3)->create()->each(function (GalleryAlbum $album) {
            GalleryItem::factory(6)->create(['gallery_album_id' => $album->id]);
        });

        Faq::factory(10)->create();
        Partner::factory(6)->create();
        Review::factory(8)->create();
        Banner::factory(3)->create();
    }
}
