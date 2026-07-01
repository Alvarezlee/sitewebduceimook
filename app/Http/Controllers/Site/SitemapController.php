<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Business\Business;
use App\Models\Content\Article;
use App\Models\Content\Event;
use App\Models\Content\Page;
use App\Models\Library\LibraryDocument;
use App\Models\Shop\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(6), function () {
            $sitemap = Sitemap::create()
                ->add(Url::create(route('home'))->setPriority(1.0))
                ->add(Url::create(route('articles.index'))->setPriority(0.7))
                ->add(Url::create(route('events.index'))->setPriority(0.6))
                ->add(Url::create(route('library.index'))->setPriority(0.8))
                ->add(Url::create(route('quiz.landing'))->setPriority(0.9))
                ->add(Url::create(route('shop.index'))->setPriority(0.8))
                ->add(Url::create(route('businesses.index'))->setPriority(0.6))
                ->add(Url::create(route('faq.index'))->setPriority(0.4))
                ->add(Url::create(route('contact'))->setPriority(0.4));

            Page::query()->published()->each(
                fn (Page $page) => $sitemap->add(Url::create(route('pages.show', $page->slug))->setPriority(0.5))
            );

            Article::query()->published()->each(
                fn (Article $article) => $sitemap->add(
                    Url::create(route('articles.show', $article))
                        ->setLastModificationDate($article->updated_at)
                        ->setPriority(0.6)
                )
            );

            Event::query()->published()->each(
                fn (Event $event) => $sitemap->add(Url::create(route('events.show', $event))->setPriority(0.5))
            );

            LibraryDocument::query()->published()->each(
                fn (LibraryDocument $document) => $sitemap->add(Url::create(route('library.show', $document))->setPriority(0.5))
            );

            Product::query()->published()->each(
                fn (Product $product) => $sitemap->add(Url::create(route('shop.show', $product))->setPriority(0.6))
            );

            Business::query()->published()->each(
                fn (Business $business) => $sitemap->add(Url::create(route('businesses.show', $business))->setPriority(0.5))
            );

            return $sitemap->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
