<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model {
    protected $fillable = ['name'];

    public function gateways(): BelongsToMany {
        return $this->belongsToMany(Gateway::class);
    }
}
