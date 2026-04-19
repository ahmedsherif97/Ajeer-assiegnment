<?php

namespace App\Payments\Gateways;

use App\Payments\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Str;

class PaymobGateway implements PaymentGatewayInterface
{
    public function charge(float $amount, string $currency, array $metadata): array
    {
        $success = rand(1, 100) > 5;

        if (!$success) {
            return [
                'status' => 'failed',
                'reference' => null,
                'data' => ['error' => 'Paymob transaction failed']
            ];
        }

        return [
            'status' => 'success',
            'reference' => rand(10000000, 99999999),
            'data' => ['message' => 'Paymob charge successful']
        ];
    }

    public function getIdentifier(): string
    {
        return 'paymob';
    }
}
