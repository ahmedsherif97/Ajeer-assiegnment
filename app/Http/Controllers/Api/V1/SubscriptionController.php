<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
    }

    public function startTrial(Request $request): JsonResponse
    {
        $subscription = $this->subscriptionService->startTrial($request->user());

        return response()->json([
            'message' => 'Trial started successfully.',
            'data' => $subscription
        ]);
    }
}
