<?php

namespace App\Listeners\Payment;

use App\Events\PaymentConfirmed;
use App\Notifications\PaymentConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentConfirmationNotification implements ShouldQueue
{
    public function handle(PaymentConfirmed $event): void
    {
        $event->payment->user->notify(new PaymentConfirmedNotification($event->payment));
    }
}
