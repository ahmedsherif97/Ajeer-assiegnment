<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        private CartService         $cartService,
        private SubscriptionService $subscriptionService
    )
    {
    }

    public function createFromCart(User $user, Carbon $scheduledAt): Booking
    {
        if (!$this->subscriptionService->hasValidSubscription($user)) {
            throw new Exception('Active subscription or trial is required to book services.');
        }

        $cart = $this->cartService->getCartForUser($user);

        if ($cart->items->isEmpty()) {
            throw new Exception('Cannot create a booking from an empty cart.');
        }

        return DB::transaction(function () use ($user, $cart, $scheduledAt) {
            $booking = Booking::create([
                'user_id' => $user->id,
                'status' => 'confirmed',
                'scheduled_at' => $scheduledAt,
                'total_amount' => $cart->getTotal(),
            ]);

            foreach ($cart->items as $item) {
                $booking->items()->create([
                    'bookable_type' => $item->purchasable_type,
                    'bookable_id' => $item->purchasable_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            }

            $this->cartService->clearCart($cart);

            return $booking->load('items.bookable');
        });
    }
}
