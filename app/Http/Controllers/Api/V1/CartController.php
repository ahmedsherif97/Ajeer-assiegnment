<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceService;
use App\Models\Package;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:service,package',
            'id' => 'required|integer',
            'quantity' => 'integer|min:1'
        ]);

        $modelClass = $validated['type'] === 'service' ? MaintenanceService::class : Package::class;
        $purchasable = $modelClass::findOrFail($validated['id']);

        $cart = $this->cartService->addItem(
            $request->user(),
            $purchasable,
            $validated['quantity'] ?? 1
        );

        return response()->json([
            'message' => 'Item added to cart.',
            'data' => [
                'cart' => $cart,
                'total' => $cart->getTotal()
            ]
        ]);
    }
}
