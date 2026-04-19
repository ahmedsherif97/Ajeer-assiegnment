<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gateway extends Model {
    protected $fillable = ['name', 'identifier', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function cities(): BelongsToMany {
        return $this->belongsToMany(City::class);
    }

    public function systemModules(): BelongsToMany {
        return $this->belongsToMany(SystemModule::class);
    }
}
