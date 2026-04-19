<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = ['user_id', 'status', 'trial_ends_at', 'ends_at'];

    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'active' && (
                ($this->trial_ends_at && $this->trial_ends_at->isFuture()) ||
                ($this->ends_at && $this->ends_at->isFuture())
            );
    }
}
