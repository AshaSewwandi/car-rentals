<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = ['name','phone','nic','address'];

    public function rentals(): HasMany { return $this->hasMany(Rental::class); }
    public function agreements(): HasMany { return $this->hasMany(Agreement::class); }
}
