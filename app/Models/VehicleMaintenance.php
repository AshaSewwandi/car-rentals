<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleMaintenance extends Model
{
    protected $fillable = [
        'vehicle_id',
        'expense_id',
        'service_date',
        'part_name',
        'amount',
        'mileage',
        'note',
    ];

    protected $casts = [
        'service_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    // The expense this record is paired with (created from it, or created for it).
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    // Maintenance and expenses are kept in step both ways (see Expense::booted).
    protected static function booted(): void
    {
        static::saved(fn (VehicleMaintenance $record) => Expense::syncOnce(fn () => $record->syncExpense()));
        static::deleting(fn (VehicleMaintenance $record) => Expense::syncOnce(fn () => $record->expense_id && Expense::whereKey($record->expense_id)->delete()));
    }

    public function syncExpense(): void
    {
        [$type, $note] = $this->expenseTypeAndNote();
        $data = [
            'vehicle_id' => $this->vehicle_id,
            'date' => $this->service_date,
            'type' => $type,
            'amount' => $this->amount,
            'note' => $note,
        ];

        // Look the link up fresh: a cached (empty) relation from when the record was created would make a duplicate.
        $expense = $this->expense_id ? Expense::find($this->expense_id) : null;
        if ($expense) {
            $expense->update($data);
            return;
        }

        $expense = Expense::create($data);
        $this->forceFill(['expense_id' => $expense->id])->saveQuietly();
        $this->setRelation('expense', $expense);
    }

    // "Repair: Brake pads" → ['repair', 'Brake pads']. Free text gets a type from keywords, with the text as the note.
    private function expenseTypeAndNote(): array
    {
        $text = trim((string) $this->part_name);
        $labels = array_flip(array_map('strtolower', Expense::TYPE_LABELS));

        if (preg_match('/^([a-z]+)(?:\s*:\s*(.*))?$/i', $text, $m) && isset($labels[strtolower($m[1])])) {
            return [$labels[strtolower($m[1])], ($m[2] ?? '') !== '' ? $m[2] : null];
        }

        $type = match (true) {
            (bool) preg_match('/tyre|tire|wheel/i', $text) => 'tyre',
            (bool) preg_match('/service|oil|filter/i', $text) => 'service',
            (bool) preg_match('/insurance/i', $text) => 'insurance',
            (bool) preg_match('/licen[cs]e|revenue/i', $text) => 'license',
            default => 'repair',
        };

        return [$type, $text !== '' ? $text : null];
    }
}
