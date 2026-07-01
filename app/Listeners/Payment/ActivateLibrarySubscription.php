<?php

namespace App\Listeners\Payment;

use App\Events\PaymentConfirmed;
use App\Models\Library\LibrarySubscription;

class ActivateLibrarySubscription
{
    public function handle(PaymentConfirmed $event): void
    {
        $payable = $event->payment->payable;

        if (! $payable instanceof LibrarySubscription) {
            return;
        }

        $payable->loadMissing('plan');
        $startsAt = now();

        $payable->update([
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->copy()->addDays($payable->plan->duration_days),
            'downloads_used' => 0,
        ]);
    }
}
