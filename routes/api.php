<?php

use App\Http\Controllers\Api\MonetbilWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/webhooks/monetbil', MonetbilWebhookController::class)
    ->middleware('throttle:60,1')
    ->name('webhooks.monetbil');
