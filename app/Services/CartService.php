<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CartService
{
    public function getCartForUser(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, Model $purchasable, int $quantity = 1): Cart
    {
        $cart = $this->getCartForUser($user);

        $existingItem = $cart->items()
            ->where('purchasable_type', get_class($purchasable))
            ->where('purchasable_id', $purchasable->id)
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'purchasable_type' => get_class($purchasable),
                'purchasable_id' => $purchasable->id,
                'quantity' => $quantity,
                'price' => $purchasable->price,
            ]);
        }

        return $cart->load('items.purchasable');
    }

    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
    }
}
