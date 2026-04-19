<?php

namespace App\Payments\Contracts;

interface PaymentGatewayInterface {
    public function charge(float $amount, string $currency, array $metadata): array;
    public function getIdentifier(): string;
}
