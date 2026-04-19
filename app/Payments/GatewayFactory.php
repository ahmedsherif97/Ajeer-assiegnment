<?php

namespace App\Payments;

use App\Models\Gateway;
use App\Payments\Contracts\PaymentGatewayInterface;
use App\Payments\Exceptions\GatewayNotAvailableException;
use Illuminate\Support\Facades\Cache;

class GatewayFactory
{
    protected array $gateways = [];

    public function registerGateway(PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$gateway->getIdentifier()] = $gateway;
    }

    public function resolve(int $gatewayId, int $cityId, int $moduleId): PaymentGatewayInterface
    {
        $cacheKey = "gateway_availability_{$gatewayId}_{$cityId}_{$moduleId}";

        $gatewayModel = Cache::remember($cacheKey, 3600, function () use ($gatewayId, $cityId, $moduleId) {
            return Gateway::where('id', $gatewayId)
                ->where('is_active', true)
                ->whereHas('cities', fn($q) => $q->where('cities.id', $cityId))
                ->whereHas('systemModules', fn($q) => $q->where('system_modules.id', $moduleId))
                ->first();
        });

        if (!$gatewayModel) {
            throw new GatewayNotAvailableException('The selected gateway is not available for this city, module, or is inactive.');
        }

        if (!isset($this->gateways[$gatewayModel->identifier])) {
            throw new GatewayNotAvailableException("Gateway implementation for [{$gatewayModel->identifier}] not found.");
        }

        return $this->gateways[$gatewayModel->identifier];
    }
}
