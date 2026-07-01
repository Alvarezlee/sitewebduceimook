<?php

use App\Http\Controllers\Api\DeviceTokenController;
use App\Http\Controllers\Site\ArticleController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\EventController;
use App\Http\Controllers\Site\FaqController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/pages/{slug}', PageController::class)->name('pages.show');

Route::get('/bureau-executif', [TeamController::class, 'bureau'])->name('team.bureau');
Route::get('/membres', [TeamController::class, 'members'])->name('team.members');

Route::get('/actualites', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/actualites/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/evenements', [EventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/galerie/{type}', [GalleryController::class, 'index'])
    ->whereIn('type', ['photo', 'video'])
    ->name('gallery.index');
Route::get('/galerie/album/{album:slug}', [GalleryController::class, 'show'])->name('gallery.show');

Route::get('/faq', FaqController::class)->name('faq.index');

Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

require __DIR__.'/library.php';
require __DIR__.'/quiz.php';
require __DIR__.'/shop.php';
require __DIR__.'/business.php';

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/notifications/device-token', [DeviceTokenController::class, 'store'])
    ->middleware('auth')
    ->name('notifications.device-token');

require __DIR__.'/auth.php';
