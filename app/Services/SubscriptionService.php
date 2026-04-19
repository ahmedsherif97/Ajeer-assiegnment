<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    public function startTrial(User $user, int $days = 14): Subscription
    {
        return Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'status' => 'active',
                'trial_ends_at' => Carbon::now()->addDays($days),
            ]
        );
    }

    public function hasValidSubscription(User $user): bool
    {
        $subscription = Subscription::where('user_id', $user->id)->first();
        return $subscription ? $subscription->isValid() : false;
    }
}
