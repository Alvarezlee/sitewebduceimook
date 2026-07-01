<?php

namespace App\Services\Shop;

use App\Models\Shop\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generate(Order $order): string
    {
        $order->loadMissing('items.product', 'user');

        $pdf = Pdf::loadView('shop.invoice', compact('order'));

        $path = "invoices/{$order->order_number}.pdf";

        Storage::disk('local')->put($path, $pdf->output());

        $order->update(['invoice_path' => $path]);

        return $path;
    }
}
