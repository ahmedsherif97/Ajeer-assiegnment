<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProcessPaymentRequest;
use App\Payments\Exceptions\GatewayNotAvailableException;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Throwable;

class PaymentController extends Controller {
    public function __construct(private PaymentService $paymentService) {}

    public function process(ProcessPaymentRequest $request): JsonResponse {
        try {
            $transaction = $this->paymentService->processPayment($request->validated());

            return response()->json([
                'success' => $transaction->status === 'success',
                'message' => $transaction->status === 'success' ? 'Payment successful' : 'Payment failed',
                'data' => [
                    'transaction_uuid' => $transaction->uuid,
                    'status' => $transaction->status,
                    'reference' => $transaction->gateway_reference,
                ]
            ], $transaction->status === 'success' ? 200 : 422);

        } catch (GatewayNotAvailableException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while processing the payment.'
            ], 500);
        }
    }
}
