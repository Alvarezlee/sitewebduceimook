<?php

namespace App\Livewire\Shop;

use App\Services\Shop\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CartPage extends Component
{
    public function updateQuantity(int $productId, int $quantity, CartService $cart): void
    {
        $cart->update($productId, $quantity);
    }

    public function remove(int $productId, CartService $cart): void
    {
        $cart->remove($productId);
    }

    public function render(CartService $cart): View
    {
        return view('livewire.shop.cart-page', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
        ]);
    }
}
