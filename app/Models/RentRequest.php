<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentRequest extends Model
{
    protected $fillable = [
        'car_id',
        'car_name',
        'plate_no',
        'name',
        'phone',
        'email',
        'start_location',
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
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }
}
