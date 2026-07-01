<?php

namespace App\Http\Controllers\Site\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use App\Services\Shop\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function show(): View
    {
        return view('shop.cart');
    }

    public function add(Request $request, Product $product, CartService $cart): RedirectResponse
    {
        abort_unless($product->is_published && $product->isInStock(), 404);

        $quantity = max(1, (int) $request->integer('quantity', 1));
        $cart->add($product, $quantity);

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function remove(Product $product, CartService $cart): RedirectResponse
    {
        $cart->remove($product->id);

        return back()->with('success', 'Produit retiré du panier.');
    }
}
