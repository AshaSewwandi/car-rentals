<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetMonth extends Model
{
    protected $fillable = ['month','touched_at'];

    protected $casts = ['touched_at' => 'datetime'];

    public function incomes(): HasMany { return $this->hasMany(BudgetIncome::class)->orderBy('sort')->orderBy('id'); }
    public function groups(): HasMany { return $this->hasMany(BudgetGroup::class)->orderBy('sort')->orderBy('id'); }
    public function entries(): HasMany { return $this->hasMany(BudgetEntry::class); }
}
