<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetEntry extends Model
{
    protected $fillable = ['budget_month_id','budget_category_id','date','amount','note','created_by'];

    // Foreign keys cast to int: some MySQL drivers return them as strings, which breaks matching on the page.
    protected $casts = ['date' => 'date', 'amount' => 'float', 'budget_month_id' => 'integer', 'budget_category_id' => 'integer', 'created_by' => 'integer'];

    public function month(): BelongsTo { return $this->belongsTo(BudgetMonth::class, 'budget_month_id'); }
    public function category(): BelongsTo { return $this->belongsTo(BudgetCategory::class, 'budget_category_id'); }
}
