<?php

namespace App\Payments\Gateways;

use App\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class PayPalGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $metadata): array
    {
        $success = rand(1, 100) > 15;

        if (!$success) {
            return [
                'status' => 'failed',
                'reference' => null,
                'data' => ['error' => 'PayPal account restricted']
            ];
        }

        return [
            'status' => 'success',
            'reference' => 'PAY-' . Str::random(20),
            'data' => ['message' => 'PayPal charge successful']
        ];
    }

    public function getIdentifier(): string
    {
        return 'paypal';
    }
}
