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
    ];

    protected $appends = ['distance_km'];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getDistanceKmAttribute(): int
    {
        return max(0, (int) $this->closing_km - (int) $this->opening_km);
    }
}
