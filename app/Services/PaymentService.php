<?php

namespace App\Services;

use App\Models\Transaction;
use App\Payments\GatewayFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PaymentService
{
    public function __construct(private GatewayFactory $gatewayFactory)
    {
    }

    public function processPayment(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create([
                'gateway_id' => $data['gateway_id'],
                'city_id' => $data['city_id'],
                'system_module_id' => $data['system_module_id'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'USD',
                'status' => 'pending',
                'payload' => $data,
            ]);

            try {
                $gateway = $this->gatewayFactory->resolve(
                    $data['gateway_id'],
                    $data['city_id'],
                    $data['system_module_id']
                );

                $response = $gateway->charge($data['amount'], $data['currency'] ?? 'USD', $data);

                $transaction->update([
                    'status' => $response['status'],
                    'gateway_reference' => $response['reference'],
                    'response' => $response['data'],
                ]);

                Log::info("Payment processed successfully", ['transaction_id' => $transaction->id]);

            } catch (Throwable $e) {
                $transaction->update([
                    'status' => 'failed',
                    'response' => ['error_message' => $e->getMessage()],
                ]);

                Log::error("Payment processing failed", [
                    'transaction_id' => $transaction->id,
                    'error' => $e->getMessage()
                ]);

                throw $e;
            }

            return $transaction;
        });
    }
}
