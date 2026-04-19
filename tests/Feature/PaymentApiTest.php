<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Gateway;
use App\Models\SystemModule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentApiTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->seed();
    }

    public function test_can_process_payment_with_valid_gateway() {
        $city = City::where('name', 'Cairo')->first();
        $module = SystemModule::where('name', 'E-Commerce')->first();
        $gateway = Gateway::where('identifier', 'stripe')->first();

        $response = $this->postJson('/api/v1/payments/process', [
            'gateway_id' => $gateway->id,
            'city_id' => $city->id,
            'system_module_id' => $module->id,
            'amount' => 100.50,
            'currency' => 'USD'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'transaction_uuid',
                'status',
                'reference'
            ]
        ]);

        $this->assertDatabaseHas('transactions', [
            'gateway_id' => $gateway->id,
            'amount' => 100.50,
        ]);
    }

    public function test_fails_when_gateway_not_available_for_city() {
        $alex = City::where('name', 'Alexandria')->first();
        $module = SystemModule::where('name', 'E-Commerce')->first();
        $paypal = Gateway::where('identifier', 'paypal')->first();

        $response = $this->postJson('/api/v1/payments/process', [
            'gateway_id' => $paypal->id,
            'city_id' => $alex->id,
            'system_module_id' => $module->id,
            'amount' => 50.00
        ]);

        $response->assertStatus(403);
        $this->assertEquals('The selected gateway is not available for this city, module, or is inactive.', $response->json('message'));
    }
}
