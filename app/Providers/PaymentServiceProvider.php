<?php

namespace App\Providers;

use App\Payments\GatewayFactory;
use App\Payments\Gateways\PaymobGateway;
use App\Payments\Gateways\PayPalGateway;
use App\Payments\Gateways\StripeGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GatewayFactory::class, function ($app) {
            $factory = new GatewayFactory();
            $factory->registerGateway(new StripeGateway());
            $factory->registerGateway(new PayPalGateway());
            $factory->registerGateway(new PaymobGateway());
            return $factory;
        });
    }

    public function boot(): void
    {
    }
}
