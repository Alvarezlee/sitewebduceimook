<?php

use App\Http\Controllers\Site\Library\DownloadController;
use App\Http\Controllers\Site\Library\LibraryController;
use App\Http\Controllers\Site\Library\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/bibliotheque', [LibraryController::class, 'index'])->name('library.index');
Route::get('/bibliotheque/categorie/{category:slug}', [LibraryController::class, 'category'])->name('library.category');
Route::get('/bibliotheque/document/{document:slug}', [LibraryController::class, 'show'])->name('library.show');

Route::middleware('auth')->group(function () {
    Route::get('/bibliotheque/abonnement', [SubscriptionController::class, 'create'])->name('library.subscribe');
    Route::post('/bibliotheque/abonnement/{plan}', [SubscriptionController::class, 'store'])->name('library.subscribe.store');

    Route::get('/bibliotheque/telecharger/{document}', DownloadController::class)
        ->middleware('signed')
        ->name('library.download');
});
