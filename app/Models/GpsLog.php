<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GpsLog extends Model
{
    protected $fillable = [
        'car_id',
        'log_date',
        'opening_km',
        'closing_km',
        'source',
        'dagps_ref',
        'note',
    ];

    protected $casts = [
        'log_date' => 'date',
        'opening_km' => 'float',
        'closing_km' => 'float',
    ];

    protected $appends = ['distance_km'];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getDistanceKmAttribute(): float
    {
        return max(0, (float) $this->closing_km - (float) $this->opening_km);
    }
}
