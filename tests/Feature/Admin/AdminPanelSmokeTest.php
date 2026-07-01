<?php

use App\Models\Business\Business;
use App\Models\Content\Article;
use App\Models\Content\Banner;
use App\Models\Content\Event;
use App\Models\Content\Faq;
use App\Models\Content\GalleryAlbum;
use App\Models\Content\Page;
use App\Models\Content\Partner;
use App\Models\Content\Review;
use App\Models\Library\LibraryCategory;
use App\Models\Library\LibraryDocument;
use App\Models\Library\LibraryPlan;
use App\Models\Payment\Payment;
use App\Models\Quiz\QuizCandidate;
use App\Models\Quiz\QuizEdition;
use App\Models\Quiz\QuizSubject;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use App\Models\User;
use App\Services\Auth\TwoFactorAuthenticationService;
use PragmaRX\Google2FA\Google2FA;

function asAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');

    $service = app(TwoFactorAuthenticationService::class);
    $secret = $service->generateSecretKey();
    $service->enable($admin, $secret);
    $service->confirm($admin, (new Google2FA)->getCurrentOtp($secret));

    return $admin;
}

test('the admin panel index and edit pages all render for a super admin', function () {
    $admin = asAdmin();

    $libraryCategory = LibraryCategory::factory()->create();
    $libraryPlan = LibraryPlan::factory()->create();
    $libraryDocument = LibraryDocument::factory()->create();
    $quizSubject = QuizSubject::factory()->create();
    $quizEdition = QuizEdition::factory()->create();
    $quizCandidate = QuizCandidate::factory()->create(['quiz_edition_id' => $quizEdition->id]);
    $productCategory = ProductCategory::factory()->create();
    $product = Product::factory()->create();
    $order = Order::factory()->create();
    $business = Business::factory()->create();
    $article = Article::factory()->create();
    $event = Event::factory()->create();
    $galleryAlbum = GalleryAlbum::factory()->create();
    $page = Page::factory()->create();
    $faq = Faq::factory()->create();
    $partner = Partner::factory()->create();
    $banner = Banner::factory()->create();
    Review::factory()->create();
    $payment = Payment::factory()->create();

    $getRoutes = [
        route('admin.dashboard'),
        route('admin.users.index'),
        route('admin.users.edit', $admin),
        route('admin.library.categories.index'),
        route('admin.library.categories.create'),
        route('admin.library.categories.edit', $libraryCategory),
        route('admin.library.plans.index'),
        route('admin.library.plans.create'),
        route('admin.library.plans.edit', $libraryPlan),
        route('admin.library.documents.index'),
        route('admin.library.documents.create'),
        route('admin.library.documents.edit', $libraryDocument),
        route('admin.quiz.subjects.index'),
        route('admin.quiz.subjects.create'),
        route('admin.quiz.subjects.edit', $quizSubject),
        route('admin.quiz.editions.index'),
        route('admin.quiz.editions.create'),
        route('admin.quiz.editions.edit', $quizEdition),
        route('admin.quiz.candidates.index'),
        route('admin.quiz.candidates.show', $quizCandidate),
        route('admin.shop.categories.index'),
        route('admin.shop.categories.create'),
        route('admin.shop.categories.edit', $productCategory),
        route('admin.shop.products.index'),
        route('admin.shop.products.create'),
        route('admin.shop.products.edit', $product),
        route('admin.shop.orders.index'),
        route('admin.shop.orders.show', $order),
        route('admin.businesses.index'),
        route('admin.content.articles.index'),
        route('admin.content.articles.create'),
        route('admin.content.articles.edit', $article),
        route('admin.content.events.index'),
        route('admin.content.events.create'),
        route('admin.content.events.edit', $event),
        route('admin.content.gallery-albums.index'),
        route('admin.content.gallery-albums.create'),
        route('admin.content.gallery-albums.edit', $galleryAlbum),
        route('admin.content.pages.index'),
        route('admin.content.pages.create'),
        route('admin.content.pages.edit', $page),
        route('admin.content.faqs.index'),
        route('admin.content.faqs.create'),
        route('admin.content.faqs.edit', $faq),
        route('admin.content.partners.index'),
        route('admin.content.partners.create'),
        route('admin.content.partners.edit', $partner),
        route('admin.content.banners.index'),
        route('admin.content.banners.create'),
        route('admin.content.banners.edit', $banner),
        route('admin.content.reviews.index'),
        route('admin.payments.index'),
        route('admin.payments.show', $payment),
        route('admin.settings.index'),
        route('admin.logs.index'),
    ];

    $this->actingAs($admin);

    foreach ($getRoutes as $url) {
        $this->get($url)->assertOk();
    }
});

test('a non-privileged user cannot access the admin panel', function () {
    $user = User::factory()->create();
    $user->assignRole('eleve');

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});
