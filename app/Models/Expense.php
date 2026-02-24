<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = ['car_id','date','type','amount','note'];

    protected $casts = ['date' => 'date'];

    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
}

