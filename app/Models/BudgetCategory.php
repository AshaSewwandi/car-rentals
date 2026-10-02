<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetCategory extends Model
{
    protected $fillable = ['budget_group_id','plan_key','name','icon','emoji','budget','sort'];

    protected $casts = ['budget' => 'float'];

    public function group(): BelongsTo { return $this->belongsTo(BudgetGroup::class, 'budget_group_id'); }
}
