<?php

namespace App\Http\Controllers\Site\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutRequest;
use App\Services\Payment\PaymentService;
use App\Services\Shop\CartService;
use App\Services\Shop\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RuntimeException;

class CheckoutController extends Controller
{
    public function create(CartService $cart): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('shop.cart')->with('error', 'Votre panier est vide.');
        }

        return view('shop.checkout', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
        ]);
    }

    public function store(CheckoutRequest $request, OrderService $orders, PaymentService $payments): RedirectResponse
    {
        try {
            $order = $orders->createFromCart(Auth::user());
        } catch (RuntimeException $e) {
            return redirect()->route('shop.cart')->with('error', $e->getMessage());
        }

        $payment = $payments->initiate(
            Auth::user(),
            $order,
            (float) $order->total,
            $request->validated('phone'),
        );

        return redirect()->away($payment->_payment_url);
    }
}
