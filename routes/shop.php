<?php

use App\Http\Controllers\Site\Shop\CartController;
use App\Http\Controllers\Site\Shop\CheckoutController;
use App\Http\Controllers\Site\Shop\OrderController;
use App\Http\Controllers\Site\Shop\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/boutique', [ShopController::class, 'index'])->name('shop.index');
Route::get('/boutique/categorie/{category:slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/boutique/produit/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/panier', [CartController::class, 'show'])->name('shop.cart');
Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('shop.cart.add');
Route::delete('/panier/retirer/{product}', [CartController::class, 'remove'])->name('shop.cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/commande', [CheckoutController::class, 'create'])->name('shop.checkout');
    Route::post('/commande', [CheckoutController::class, 'store'])->name('shop.checkout.store');

    Route::get('/mes-commandes', [OrderController::class, 'index'])->name('shop.orders.index');
    Route::get('/mes-commandes/{order}', [OrderController::class, 'show'])->name('shop.orders.show');
    Route::get('/mes-commandes/{order}/facture', [OrderController::class, 'invoice'])
        ->middleware('signed')
        ->name('shop.orders.invoice');
});
