<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetIncome extends Model
{
    protected $fillable = ['budget_month_id','name','amount','sort'];

    protected $casts = ['amount' => 'float'];

    public function month(): BelongsTo { return $this->belongsTo(BudgetMonth::class, 'budget_month_id'); }
}
