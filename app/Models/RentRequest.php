<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentRequest extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vehicle_name',
        'plate_no',
        'name',
        'phone',
        'email',
        'passenger_count',
        'start_location',
        'final_destination',
        'stops',
        'start_date',
        'end_date',
        'message',
        'status',
        'accepted_by',
        'accepted_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'accepted_at' => 'datetime',
        'stops' => 'array',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }
}
