<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Gateway;
use App\Models\SystemModule;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cairo = City::create(['name' => 'Cairo']);
        $alex = City::create(['name' => 'Alexandria']);

        $ecommerce = SystemModule::create(['name' => 'E-Commerce']);
        $subscriptions = SystemModule::create(['name' => 'Subscriptions']);

        $stripe = Gateway::create(['name' => 'Stripe', 'identifier' => 'stripe', 'is_active' => true]);
        $paypal = Gateway::create(['name' => 'PayPal', 'identifier' => 'paypal', 'is_active' => true]);
        $paymob = Gateway::create(['name' => 'Paymob', 'identifier' => 'paymob', 'is_active' => true]);

        $stripe->cities()->attach([$cairo->id, $alex->id]);
        $stripe->systemModules()->attach([$ecommerce->id, $subscriptions->id]);

        $paypal->cities()->attach([$cairo->id]);
        $paypal->systemModules()->attach([$ecommerce->id]);

        $paymob->cities()->attach([$cairo->id, $alex->id]);
        $paymob->systemModules()->attach([$subscriptions->id]);


        $this->call(TrialSubscriptionSeeder::class);
    }
}
