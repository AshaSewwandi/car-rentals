<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthStart = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $monthEnd = (clone $monthStart)->endOfMonth();
        $today = now()->startOfDay();
        $next7Days = now()->copy()->addDays(7)->endOfDay();
        $renewalWindowStart = $monthStart->copy()->startOfDay();
        $renewalWindowEnd = $monthStart->copy()->addDays(30)->endOfDay();

        $expected = Payment::query()
            ->where('month', $month)
            ->sum('amount');

        $received = Payment::query()
            ->where('month', $month)
            ->where('status', 'paid')
            ->sum('paid_amount');

        $monthExpenses = Expense::query()
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $pendingPaymentsCount = Payment::query()
            ->where('month', $month)
            ->where('status', 'pending')
            ->count();

        $upcomingPaymentsTotal = Payment::query()
            ->where('month', $month)
            ->where('status', 'pending')
            ->sum('amount');

        $upcomingExpensesTotal = Expense::query()
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $dueThisWeekPayments = Payment::query()
            ->where('month', $month)
            ->where('status', 'pending')
            ->whereBetween('due_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $dueThisWeekExpenses = Expense::query()
            ->whereBetween('date', [$today->toDateString(), $next7Days->toDateString()])
            ->sum('amount');

        $upcomingPayments = Payment::query()
            ->with(['rental.vehicle', 'rental.customer'])
            ->where('month', $month)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        $upcomingExpenses = Expense::query()
            ->with('vehicle')
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('date')
            ->limit(8)
            ->get();

        $renewalAlerts = Vehicle::query()
            ->where(function ($query) use ($renewalWindowStart, $renewalWindowEnd) {
                $query
                    ->whereBetween('tracker_insurance_expires', [$renewalWindowStart->toDateString(), $renewalWindowEnd->toDateString()])
                    ->orWhereBetween('tracker_license_expires', [$renewalWindowStart->toDateString(), $renewalWindowEnd->toDateString()]);
            })
            ->orderByRaw("
                CASE
                    WHEN tracker_insurance_expires IS NULL AND tracker_license_expires IS NULL THEN 1
                    ELSE 0
                END
            ")
            ->orderByRaw('LEAST(COALESCE(tracker_insurance_expires, "9999-12-31"), COALESCE(tracker_license_expires, "9999-12-31"))')
            ->get()
            ->map(function (Vehicle $vehicle) use ($renewalWindowStart, $renewalWindowEnd) {
                $items = collect();

                if ($vehicle->tracker_insurance_expires && $vehicle->tracker_insurance_expires->between($renewalWindowStart, $renewalWindowEnd)) {
                    $items->push([
                        'type' => 'Insurance',
                        'date' => $vehicle->tracker_insurance_expires,
                        'days_left' => $renewalWindowStart->diffInDays($vehicle->tracker_insurance_expires, false),
                    ]);
                }

                if ($vehicle->tracker_license_expires && $vehicle->tracker_license_expires->between($renewalWindowStart, $renewalWindowEnd)) {
                    $items->push([
                        'type' => 'License',
                        'date' => $vehicle->tracker_license_expires,
                        'days_left' => $renewalWindowStart->diffInDays($vehicle->tracker_license_expires, false),
                    ]);
                }

                return $items->map(fn (array $item) => [
                    'vehicle' => $vehicle,
                    'type' => $item['type'],
                    'date' => $item['date'],
                    'days_left' => $item['days_left'],
                ]);
            })
            ->flatten(1)
            ->sortBy('date')
            ->values()
            ->take(10);

        return view('dashboard', compact(
            'month',
            'expected',
            'received',
            'monthExpenses',
            'pendingPaymentsCount',
            'upcomingPaymentsTotal',
            'upcomingExpensesTotal',
            'dueThisWeekPayments',
            'dueThisWeekExpenses',
            'upcomingPayments',
            'upcomingExpenses',
            'renewalAlerts',
            'renewalWindowStart',
            'renewalWindowEnd'
        ));
    }
}
