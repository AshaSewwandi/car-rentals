<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehiclePricing extends Model
{
    protected $fillable = [
        'make',
        'model',
        'per_day_km',
        'per_day_amount',
        'extra_km_rate',
        'driver_cost_per_day',
        'note',
    ];

    protected $casts = [
        'per_day_km' => 'integer',
        'per_day_amount' => 'decimal:2',
        'extra_km_rate' => 'decimal:2',
        'driver_cost_per_day' => 'decimal:2',
    ];
}
