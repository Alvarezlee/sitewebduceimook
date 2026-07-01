<?php

namespace App\Providers;

use App\Events\PaymentConfirmed;
use App\Listeners\LogFailedLogin;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\Payment\ActivateLibrarySubscription;
use App\Listeners\Payment\ActivateQuizCandidate;
use App\Listeners\Payment\CompleteOrder;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, LogSuccessfulLogin::class);
        Event::listen(Failed::class, LogFailedLogin::class);

        Event::listen(PaymentConfirmed::class, ActivateLibrarySubscription::class);
        Event::listen(PaymentConfirmed::class, ActivateQuizCandidate::class);
        Event::listen(PaymentConfirmed::class, CompleteOrder::class);
    }
}
