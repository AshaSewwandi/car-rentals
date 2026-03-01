<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'car_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'pickup_location',
        'start_date',
        'end_date',
        'rental_days',
        'daily_rate',
        'total_amount',
        'currency',
        'payment_method',
        'payment_provider',
        'gateway_reference',
        'payment_status',
        'additional_payment_status',
        'additional_payment_amount',
        'additional_paid_at',
        'status',
        'handover_at',
        'returned_at',
        'returned_by',
        'start_mileage',
        'end_mileage',
        'used_km',
        'included_km',
        'extra_km',
        'extra_km_rate',
        'extra_km_charge',
        'final_total',
        'note',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'additional_paid_at' => 'datetime',
        'handover_at' => 'datetime',
        'returned_at' => 'datetime',
        'daily_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'additional_payment_amount' => 'decimal:2',
        'start_mileage' => 'decimal:2',
        'end_mileage' => 'decimal:2',
        'used_km' => 'decimal:2',
        'included_km' => 'decimal:2',
        'extra_km' => 'decimal:2',
        'extra_km_rate' => 'decimal:2',
        'extra_km_charge' => 'decimal:2',
        'final_total' => 'decimal:2',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }
}
