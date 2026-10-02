<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetEntry extends Model
{
    protected $fillable = ['budget_month_id','budget_category_id','date','amount','note','created_by'];

    protected $casts = ['date' => 'date', 'amount' => 'float'];

    public function month(): BelongsTo { return $this->belongsTo(BudgetMonth::class, 'budget_month_id'); }
    public function category(): BelongsTo { return $this->belongsTo(BudgetCategory::class, 'budget_category_id'); }
}
