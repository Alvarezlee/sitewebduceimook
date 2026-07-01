<?php

namespace App\Providers;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Gateways\MonetbilGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function () {
            return new MonetbilGateway(
                serviceKey: (string) config('services.monetbil.service_key'),
                serviceSecret: (string) config('services.monetbil.service_secret'),
                widgetUrl: (string) config('services.monetbil.widget_url'),
                currency: (string) config('services.monetbil.currency'),
                country: (string) config('services.monetbil.country'),
            );
        });
    }
}
