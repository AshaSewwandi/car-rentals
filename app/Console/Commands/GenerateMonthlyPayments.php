<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyPayments extends Command
{
    protected $signature = 'rentals:generate-monthly-payments {--month=}';
    protected $description = 'Generate monthly payment records for active rentals';

    public function handle(): int
    {
        $month = $this->option('month')
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : now()->startOfMonth();

        $monthKey = $month->format('Y-m');

        $rentals = Rental::query()
            ->where('status', 'active')
            ->with('car')
            ->get();

        $created = 0;

        foreach ($rentals as $rental) {
            // keep due_day safe (recommend 1..28)
            $dueDay = min(max((int)$rental->due_day, 1), 28);
            $dueDate = $month->copy()->day($dueDay);

            // if rental starts after this month's due date, still allow (you can change this rule if you want)
            Payment::firstOrCreate(
                ['rental_id' => $rental->id, 'month' => $monthKey],
                [
                    'due_date' => $dueDate->toDateString(),
                    'amount'   => $rental->monthly_rent,
                    'status'   => 'pending',
                ]
            );

            $created++;
        }

        $this->info("Processed rentals for {$monthKey}. (Payments ensured for active rentals)");
        return self::SUCCESS;
    }
}
