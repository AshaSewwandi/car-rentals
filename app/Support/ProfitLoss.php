<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Cash-basis profit or loss for one month: money received minus money spent,
 * worked out per vehicle and then added up, so vehicle rows always match the total.
 * Used by the dashboard and the monthly business report.
 */
class ProfitLoss
{
    private const KEYS = ['rentals', 'trips', 'extras', 'expenses', 'partner'];

    public static function forMonth(Carbon $month, bool $withDetails = false): array
    {
        $from = $month->copy()->startOfMonth()->toDateString();
        $to = $month->copy()->endOfMonth()->toDateString();

        $byVehicle = [];
        $details = ['income' => collect(), 'costs' => collect()];
        $add = function ($vehicleId, string $key, float $amount) use (&$byVehicle) {
            $byVehicle[$vehicleId] ??= array_fill_keys(self::KEYS, 0.0);
            $byVehicle[$vehicleId][$key] += $amount;
        };
        $vehicleName = fn ($vehicle) => $vehicle ? trim($vehicle->name . ($vehicle->plate_no && $vehicle->plate_no !== $vehicle->name ? ' (' . $vehicle->plate_no . ')' : '')) : 'Removed vehicle';

        // Partner vehicles: the partner's cut is their percentage of what was actually received
        // (driver fees excluded, as in RevenueShareResolver). The stored partner_share_amount uses the
        // final total, which can include extra-km charges that haven't been paid yet.
        $partnerCut = fn (Booking $b, float $amount) => $amount * ((float) $b->partner_share_percentage) / 100;

        // Long-term rentals: monthly payments marked paid.
        $payments = Payment::query()
            ->with(['rental.vehicle', 'rental.customer'])
            ->where('month', $month->format('Y-m'))
            ->where('status', 'paid')
            ->get();
        foreach ($payments as $p) {
            $add($p->rental?->vehicle_id, 'rentals', (float) $p->paid_amount);
            $details['income']->push([
                'date' => $p->paid_date ?? $p->due_date, 'type' => 'Long-term rental', 'vehicle' => $vehicleName($p->rental?->vehicle),
                'who' => $p->rental?->customer?->name, 'note' => $p->method ? 'Paid by ' . $p->method : null, 'amount' => (float) $p->paid_amount,
            ]);
        }

        // Trips: base payment marked paid, counted in the month the trip starts.
        $trips = Booking::query()
            ->with('vehicle')
            ->where('status', '!=', 'cancelled')
            ->where('payment_status', 'paid')
            ->whereBetween('start_date', [$from, $to])
            ->get();
        foreach ($trips as $b) {
            $cut = $partnerCut($b, max((float) $b->total_amount - (float) $b->driver_total, 0));
            $add($b->vehicle_id, 'trips', (float) $b->total_amount);
            $add($b->vehicle_id, 'partner', $cut);
            $details['income']->push([
                'date' => $b->start_date, 'type' => 'Trip', 'vehicle' => $vehicleName($b->vehicle),
                'who' => $b->customer_name, 'note' => $b->start_date?->format('j M') . ' – ' . $b->end_date?->format('j M'), 'amount' => (float) $b->total_amount,
            ]);
            if ($cut > 0) {
                $details['costs']->push([
                    'date' => $b->start_date, 'type' => 'Partner share', 'vehicle' => $vehicleName($b->vehicle),
                    'who' => null, 'note' => round((float) $b->partner_share_percentage) . '% of trip for ' . $b->customer_name, 'amount' => $cut,
                ]);
            }
        }

        // Trips: extra-km charges, counted in the month they were paid.
        $extras = Booking::query()
            ->with('vehicle')
            ->where('status', '!=', 'cancelled')
            ->where('additional_payment_status', 'paid')
            ->whereBetween('additional_paid_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->get();
        foreach ($extras as $b) {
            $amount = (float) $b->additional_payment_amount;
            $cut = $partnerCut($b, $amount);
            $add($b->vehicle_id, 'extras', $amount);
            $add($b->vehicle_id, 'partner', $cut);
            $details['income']->push([
                'date' => $b->additional_paid_at, 'type' => 'Extra km', 'vehicle' => $vehicleName($b->vehicle),
                'who' => $b->customer_name, 'note' => $b->extra_km ? rtrim(rtrim(number_format((float) $b->extra_km, 2), '0'), '.') . ' extra km' : null, 'amount' => $amount,
            ]);
            if ($cut > 0) {
                $details['costs']->push([
                    'date' => $b->additional_paid_at, 'type' => 'Partner share', 'vehicle' => $vehicleName($b->vehicle),
                    'who' => null, 'note' => round((float) $b->partner_share_percentage) . '% of extra km for ' . $b->customer_name, 'amount' => $cut,
                ]);
            }
        }

        // Vehicle maintenance records are not counted: the same costs are already entered as expenses.
        foreach (Expense::query()->with('vehicle')->whereBetween('date', [$from, $to])->get() as $e) {
            $add($e->vehicle_id, 'expenses', (float) $e->amount);
            $details['costs']->push([
                'date' => $e->date, 'type' => 'Expense · ' . ucfirst((string) $e->type), 'vehicle' => $vehicleName($e->vehicle),
                'who' => null, 'note' => $e->note, 'amount' => (float) $e->amount,
            ]);
        }

        // One row per vehicle, including vehicles with no money movement this month.
        $vehicles = Vehicle::query()->orderBy('name')->get(['id', 'name', 'plate_no']);
        $rows = $vehicles->map(fn (Vehicle $v) => self::vehicleRow($v->name, $v->plate_no, $byVehicle[$v->id] ?? array_fill_keys(self::KEYS, 0.0)));
        // Money on a vehicle that has since been deleted still counts towards the total.
        foreach (array_diff(array_keys($byVehicle), $vehicles->pluck('id')->all()) as $orphan) {
            $rows->push(self::vehicleRow('Removed vehicle', '', $byVehicle[$orphan]));
        }

        $sum = fn (string $key) => (float) $rows->sum(fn ($r) => $r['parts'][$key]);
        $income = $sum('rentals') + $sum('trips') + $sum('extras');
        $costs = $sum('expenses') + $sum('partner');

        $result = [
            'month' => $month->format('Y-m'),
            'label' => $month->format('F Y'),
            'income' => $income,
            'costs' => $costs,
            'net' => $income - $costs,
            'margin' => $income > 0 ? ($income - $costs) / $income * 100 : null,
            'hasActivity' => $income > 0 || $costs > 0,
            'vehicles' => $rows->sortByDesc('net')->values(),
            'lines' => [
                'income' => [
                    ['label' => 'Long-term rental payments', 'amount' => $sum('rentals')],
                    ['label' => 'Trip payments', 'amount' => $sum('trips')],
                    ['label' => 'Extra km charges', 'amount' => $sum('extras')],
                ],
                'costs' => [
                    ['label' => 'Expenses', 'amount' => $sum('expenses')],
                    ['label' => 'Partner share of trips', 'amount' => $sum('partner')],
                ],
            ],
        ];

        if ($withDetails) {
            $sortByDate = fn (Collection $items) => $items->sortBy(fn ($i) => $i['date']?->format('Y-m-d H:i:s') ?? '')->values();
            $result['details'] = ['income' => $sortByDate($details['income']), 'costs' => $sortByDate($details['costs'])];
        }

        return $result;
    }

    private static function vehicleRow(string $name, ?string $plate, array $parts): array
    {
        $income = $parts['rentals'] + $parts['trips'] + $parts['extras'];
        $costs = $parts['expenses'] + $parts['partner'];

        return [
            'name' => $name,
            'plate' => $plate,
            'parts' => $parts,
            'income' => $income,
            'costs' => $costs,
            'net' => $income - $costs,
            'margin' => $income > 0 ? ($income - $costs) / $income * 100 : null,
            'active' => $income > 0 || $costs > 0,
        ];
    }
}
