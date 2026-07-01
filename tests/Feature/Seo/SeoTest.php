<?php

use App\Models\Content\Article;
use App\Models\Content\Faq;

test('the sitemap is generated as valid xml and includes published content', function () {
    $article = Article::factory()->create();

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/xml');
    $response->assertSee($article->slug, false);
});

test('robots.txt references the sitemap and blocks private areas', function () {
    $contents = file_get_contents(public_path('robots.txt'));

    expect($contents)->toContain('Sitemap:')
        ->toContain('Disallow: /admin');
});

test('article pages expose NewsArticle structured data', function () {
    $article = Article::factory()->create();

    $this->get(route('articles.show', $article))
        ->assertOk()
        ->assertSee('"@type":"NewsArticle"', false);
});

test('the faq page exposes FAQPage structured data', function () {
    Faq::factory()->create();

    $this->get(route('faq.index'))
        ->assertOk()
        ->assertSee('"@type":"FAQPage"', false);
});
