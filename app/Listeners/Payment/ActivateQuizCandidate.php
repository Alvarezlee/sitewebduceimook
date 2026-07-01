<?php

namespace App\Listeners\Payment;

use App\Events\PaymentConfirmed;
use App\Models\Quiz\QuizCandidate;

class ActivateQuizCandidate
{
    public function handle(PaymentConfirmed $event): void
    {
        $payable = $event->payment->payable;

        if (! $payable instanceof QuizCandidate) {
            return;
        }

        $payable->update(['registration_status' => 'paid']);
    }
}
