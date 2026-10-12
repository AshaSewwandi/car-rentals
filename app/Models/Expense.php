<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Expense extends Model
{
    protected $fillable = ['vehicle_id','date','type','amount','note'];

    protected $casts = ['date' => 'date'];

    public const TYPE_LABELS = ['service' => 'Service', 'repair' => 'Repair', 'insurance' => 'Insurance', 'license' => 'License', 'tyre' => 'Tyre', 'other' => 'Other'];

    // True while an expense and its maintenance record are being synced, so the two don't update each other in a loop.
    public static bool $syncing = false;

    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }
    public function maintenance(): HasOne { return $this->hasOne(VehicleMaintenance::class); }

    // Expenses and vehicle maintenance are kept in step both ways (see VehicleMaintenance::booted).
    protected static function booted(): void
    {
        static::saved(fn (Expense $expense) => static::syncOnce(fn () => $expense->syncMaintenance()));
        static::deleting(fn (Expense $expense) => static::syncOnce(fn () => $expense->maintenance()->delete()));
    }

    public static function syncOnce(callable $callback): void
    {
        if (static::$syncing || !static::syncAvailable()) {
            return;
        }
        static::$syncing = true;
        try {
            $callback();
        } finally {
            static::$syncing = false;
        }
    }

    // The sync needs the vehicle_maintenances.expense_id column. Until that migration has run,
    // skip syncing so saving an expense or maintenance record still works.
    private static ?bool $syncAvailable = null;

    public static function syncAvailable(): bool
    {
        return static::$syncAvailable ??= Schema::hasColumn('vehicle_maintenances', 'expense_id');
    }

    public function syncMaintenance(): void
    {
        $label = self::TYPE_LABELS[$this->type] ?? 'Other';
        $note = trim((string) $this->note);

        $this->maintenance()->updateOrCreate([], [
            'vehicle_id' => $this->vehicle_id,
            'service_date' => $this->date,
            'part_name' => Str::limit($note !== '' ? "{$label}: {$note}" : $label, 250, ''),
            'amount' => $this->amount,
            'note' => 'Added from Expenses',
        ]);
    }
}
