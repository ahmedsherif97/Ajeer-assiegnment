<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Transaction extends Model {
    protected $fillable = [
        'uuid', 'gateway_id', 'city_id', 'system_module_id', 'amount',
        'currency', 'status', 'gateway_reference', 'payload', 'response'
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function gateway(): BelongsTo {
        return $this->belongsTo(Gateway::class);
    }
}
