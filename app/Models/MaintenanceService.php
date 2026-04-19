<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaintenanceService extends Model
{
    protected $fillable = ['name', 'price', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_service');
    }
}
