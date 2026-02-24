<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'rental_id','month','due_date','amount','paid_date','paid_amount','method','status'
    ];

    protected $casts = [
        'due_date'  => 'date',
        'paid_date' => 'date',
    ];

    public function rental(): BelongsTo { return $this->belongsTo(Rental::class); }

    public function getIsLateAttribute(): bool
    {
        return $this->status === 'pending' && now()->startOfDay()->gt($this->due_date);
    }
}

