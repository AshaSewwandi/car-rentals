<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetGroup extends Model
{
    protected $fillable = ['budget_month_id','plan_key','name','icon','sort'];

    protected $casts = ['budget_month_id' => 'integer', 'sort' => 'integer'];

    public function month(): BelongsTo { return $this->belongsTo(BudgetMonth::class, 'budget_month_id'); }
    public function categories(): HasMany { return $this->hasMany(BudgetCategory::class)->orderBy('sort')->orderBy('id'); }
}
