<?php

namespace App\Listeners\Payment;

use App\Events\PaymentConfirmed;
use App\Models\Shop\Order;
use App\Services\Shop\InvoiceService;

class CompleteOrder
{
    public function __construct(private readonly InvoiceService $invoices) {}

    public function handle(PaymentConfirmed $event): void
    {
        $payable = $event->payment->payable;

        if (! $payable instanceof Order) {
            return;
        }

        $payable->update(['status' => 'paid']);

        $this->invoices->generate($payable);
    }
}
