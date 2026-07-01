<?php

use App\Http\Controllers\Admin\Business\BusinessController;
use App\Http\Controllers\Admin\Content\ArticleController;
use App\Http\Controllers\Admin\Content\BannerController;
use App\Http\Controllers\Admin\Content\EventController;
use App\Http\Controllers\Admin\Content\FaqController;
use App\Http\Controllers\Admin\Content\GalleryAlbumController;
use App\Http\Controllers\Admin\Content\PageController;
use App\Http\Controllers\Admin\Content\PartnerController;
use App\Http\Controllers\Admin\Content\ReviewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Library\CategoryController as LibraryCategoryController;
use App\Http\Controllers\Admin\Library\DocumentController as LibraryDocumentController;
use App\Http\Controllers\Admin\Library\PlanController as LibraryPlanController;
use App\Http\Controllers\Admin\Logs\LogController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Quiz\CandidateController;
use App\Http\Controllers\Admin\Quiz\EditionController;
use App\Http\Controllers\Admin\Quiz\SubjectController;
use App\Http\Controllers\Admin\Settings\SettingController;
use App\Http\Controllers\Admin\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Admin\Shop\ProductCategoryController;
use App\Http\Controllers\Admin\Shop\ProductController;
use App\Http\Controllers\Admin\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:super_admin|admin|moderateur', 'require.2fa'])
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::middleware('role:super_admin|admin')->group(function (): void {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        });

        Route::prefix('bibliotheque')->name('library.')->group(function (): void {
            Route::resource('categories', LibraryCategoryController::class)->except(['show']);
            Route::resource('plans', LibraryPlanController::class)->except(['show']);
            Route::resource('documents', LibraryDocumentController::class)->except(['show']);
        });

        Route::prefix('quiz')->name('quiz.')->group(function (): void {
            Route::resource('subjects', SubjectController::class)->except(['show']);
            Route::resource('editions', EditionController::class)->except(['show']);
            Route::get('candidates', [CandidateController::class, 'index'])->name('candidates.index');
            Route::get('candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
            Route::post('attempt-answers/{answer}/grade', [CandidateController::class, 'gradeAnswer'])->name('answers.grade');
        });

        Route::prefix('boutique')->name('shop.')->group(function (): void {
            Route::resource('categories', ProductCategoryController::class)->except(['show']);
            Route::resource('products', ProductController::class)->except(['show']);
            Route::get('orders', [ShopOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [ShopOrderController::class, 'show'])->name('orders.show');
            Route::put('orders/{order}', [ShopOrderController::class, 'update'])->name('orders.update');
        });

        Route::get('entreprises', [BusinessController::class, 'index'])->name('businesses.index');
        Route::put('entreprises/{business}/toggle-publish', [BusinessController::class, 'togglePublish'])->name('businesses.toggle-publish');
        Route::delete('entreprises/{business}', [BusinessController::class, 'destroy'])->name('businesses.destroy');

        Route::prefix('contenus')->name('content.')->group(function (): void {
            Route::resource('articles', ArticleController::class)->except(['show']);
            Route::resource('events', EventController::class)->except(['show']);
            Route::resource('gallery-albums', GalleryAlbumController::class)
                ->except(['show'])
                ->parameters(['gallery-albums' => 'galleryAlbum']);
            Route::resource('pages', PageController::class)->except(['show']);
            Route::resource('faqs', FaqController::class)->except(['show']);
            Route::resource('partners', PartnerController::class)->except(['show']);
            Route::resource('banners', BannerController::class)->except(['show']);
            Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
            Route::put('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
            Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        });

        Route::get('paiements', [PaymentController::class, 'index'])->name('payments.index');
        Route::get('paiements/{payment}', [PaymentController::class, 'show'])->name('payments.show');

        Route::get('parametres', [SettingController::class, 'edit'])->name('settings.index');
        Route::put('parametres', [SettingController::class, 'update'])->name('settings.update');

        Route::get('journaux', [LogController::class, 'index'])->name('logs.index');
    });
