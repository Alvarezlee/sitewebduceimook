<?php

use App\Models\Content\Article;
use App\Models\Content\Event;
use App\Models\Content\GalleryAlbum;
use App\Models\Content\Page;

test('home page renders successfully', function () {
    $this->get(route('home'))->assertOk();
});

test('article listing and detail pages render', function () {
    $article = Article::factory()->create();

    $this->get(route('articles.index'))->assertOk();
    $this->get(route('articles.show', $article))->assertOk()->assertSee($article->title);
});

test('event listing and detail pages render', function () {
    $event = Event::factory()->create();

    $this->get(route('events.index'))->assertOk();
    $this->get(route('events.show', $event))->assertOk()->assertSee($event->title);
});

test('gallery listing and detail pages render', function () {
    $album = GalleryAlbum::factory()->create(['type' => 'photo']);

    $this->get(route('gallery.index', 'photo'))->assertOk();
    $this->get(route('gallery.show', $album))->assertOk();
});

test('static content pages render', function () {
    Page::factory()->create(['slug' => 'historique']);

    $this->get(route('pages.show', 'historique'))->assertOk();
});

test('faq page renders', function () {
    $this->get(route('faq.index'))->assertOk();
});

test('contact form can be submitted', function () {
    $this->get(route('contact'))->assertOk();

    $this->post(route('contact.store'), [
        'name' => 'Jean Test',
        'email' => 'jean@example.com',
        'subject' => 'Question',
        'message' => 'Bonjour, ceci est un test.',
    ])->assertRedirect();
});
