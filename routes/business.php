<?php

use App\Http\Controllers\Site\Business\BusinessDashboardController;
use App\Http\Controllers\Site\Business\BusinessDirectoryController;
use Illuminate\Support\Facades\Route;

Route::get('/entreprises-membres', [BusinessDirectoryController::class, 'index'])->name('businesses.index');
Route::get('/entreprises-membres/{business:slug}', [BusinessDirectoryController::class, 'show'])->name('businesses.show');
Route::post('/entreprises-membres/{business:slug}/contact', [BusinessDirectoryController::class, 'contact'])
    ->middleware('throttle:5,1')
    ->name('businesses.contact');

Route::middleware(['auth', 'role:entreprise'])->prefix('mon-entreprise')->name('business.dashboard.')->group(function () {
    Route::get('/', [BusinessDashboardController::class, 'edit'])->name('edit');
    Route::put('/', [BusinessDashboardController::class, 'update'])->name('update');
    Route::post('/services', [BusinessDashboardController::class, 'storeService'])->name('services.store');
    Route::delete('/services/{service}', [BusinessDashboardController::class, 'destroyService'])->name('services.destroy');
    Route::post('/medias', [BusinessDashboardController::class, 'storeMedia'])->name('medias.store');
    Route::delete('/medias/{media}', [BusinessDashboardController::class, 'destroyMedia'])->name('medias.destroy');
});
