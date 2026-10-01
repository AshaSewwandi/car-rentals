<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'service_date',
        'part_name',
        'amount',
        'mileage',
        'note',
    ];

    protected $casts = [
        'service_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
