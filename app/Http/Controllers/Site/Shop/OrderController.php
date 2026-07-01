<?php

namespace App\Http\Controllers\Site\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load('items.product');

        return view('shop.orders.show', compact('order'));
    }

    public function invoice(Request $request, Order $order): StreamedResponse
    {
        abort_unless($request->hasValidSignature(), 403);
        abort_unless($order->user_id === $request->user()?->id, 403);
        abort_if(is_null($order->invoice_path), 404);

        return Storage::disk('local')->download($order->invoice_path, $order->order_number.'.pdf');
    }
}
