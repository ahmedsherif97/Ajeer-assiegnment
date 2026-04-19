<?php

namespace App\Payments\Gateways;

use App\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class StripeGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $metadata): array
    {
        $success = rand(1, 100) > 10;

        if (!$success) {
            return [
                'status' => 'failed',
                'reference' => null,
                'data' => ['error' => 'Stripe card declined']
            ];
        }

        return [
            'status' => 'success',
            'reference' => 'ch_' . Str::random(24),
            'data' => ['message' => 'Stripe charge successful']
        ];
    }

    public function getIdentifier(): string
    {
        return 'stripe';
    }
}
