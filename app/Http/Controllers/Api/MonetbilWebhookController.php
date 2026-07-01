<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MonetbilWebhookController extends Controller
{
    public function __invoke(Request $request, PaymentService $payments): JsonResponse
    {
        try {
            $payment = $payments->handleWebhook($request->all());
        } catch (RuntimeException $e) {
            Log::warning('Monetbil webhook rejeté : '.$e->getMessage(), $request->all());

            return response()->json(['message' => 'invalid signature'], 403);
        }

        return response()->json(['status' => $payment->status]);
    }
}
