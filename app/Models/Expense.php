<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = ['vehicle_id','date','type','amount','note'];

    protected $casts = ['date' => 'date'];

    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }
}

