<?php

use App\Providers\AiServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\PaymentServiceProvider;
use App\Providers\VoltServiceProvider;

return [
    AppServiceProvider::class,
    VoltServiceProvider::class,
    PaymentServiceProvider::class,
    AiServiceProvider::class,
];
