<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Support\ProfitLoss;
use Barryvdh\DomPDF\Facade\Pdf;
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
        // Renewals: for the current month, anything expired or due in the next 30 days from today;
        // for any other selected month, only renewals due in that month.
        $renewalsForCurrent = $month === now()->format('Y-m');
        // Days left: from today for the current month, from the 1st of the month for any other month.
        $renewalCountFrom = $renewalsForCurrent ? $today->copy() : $monthStart->copy()->startOfDay();
        $renewalWindowStart = $today->copy();
        $renewalWindowEnd = $today->copy()->addDays(30)->endOfDay();

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

        // Extra figures for the summary cards.
        $kpi = [
            'paymentCount' => Payment::query()->where('month', $month)->count(),
            'paidCount' => Payment::query()->where('month', $month)->where('status', 'paid')->count(),
            'expenseCount' => Expense::query()->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])->count(),
            'previousExpenses' => (float) Expense::query()
                ->whereBetween('date', [$monthStart->copy()->subMonth()->startOfMonth()->toDateString(), $monthStart->copy()->subMonth()->endOfMonth()->toDateString()])
                ->sum('amount'),
            'overdueCount' => Payment::query()->where('month', $month)->where('status', 'pending')
                ->whereDate('due_date', '<', $today->toDateString())->count(),
        ];

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
            ->where(function ($query) use ($renewalsForCurrent, $renewalWindowEnd, $monthStart, $monthEnd) {
                foreach (['tracker_insurance_expires', 'tracker_license_expires'] as $column) {
                    $renewalsForCurrent
                        ? $query->orWhereDate($column, '<=', $renewalWindowEnd->toDateString())
                        : $query->orWhereBetween($column, [$monthStart->toDateString(), $monthEnd->toDateString()]);
                }
            })
            ->orderByRaw("
                CASE
                    WHEN tracker_insurance_expires IS NULL AND tracker_license_expires IS NULL THEN 1
                    ELSE 0
                END
            ")
            ->orderByRaw('LEAST(COALESCE(tracker_insurance_expires, "9999-12-31"), COALESCE(tracker_license_expires, "9999-12-31"))')
            ->get()
            ->map(function (Vehicle $vehicle) use ($renewalCountFrom, $renewalsForCurrent, $renewalWindowEnd, $monthStart, $monthEnd) {
                $items = collect();

                // Days from the count start: negative = already expired.
                foreach (['Insurance' => $vehicle->tracker_insurance_expires, 'License' => $vehicle->tracker_license_expires] as $type => $date) {
                    $day = $date?->copy()->startOfDay();
                    $inSelectedMonth = $day && $day->between($monthStart->copy()->startOfDay(), $monthEnd);
                    if ($day && ($renewalsForCurrent ? $day->lte($renewalWindowEnd) : $inSelectedMonth)) {
                        $items->push([
                            'type' => $type,
                            'date' => $date,
                            'days_left' => (int) round($renewalCountFrom->diffInDays($date->copy()->startOfDay(), false)),
                        ]);
                    }
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
            ->take(25);

        $profit = $this->profitAndLoss($monthStart);
        $previousProfit = $this->profitAndLoss($monthStart->copy()->subMonth());

        return view('dashboard', compact(
            'kpi',
            'profit',
            'previousProfit',
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
            'renewalsForCurrent',
            'renewalCountFrom',
            'renewalWindowEnd'
        ));
    }

    // Monthly business report (PDF): income, expenses and profit or loss with analysis.
    public function report(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        abort_unless(is_string($month) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month), 404);
        $monthStart = Carbon::createFromFormat('Y-m-d', $month . '-01')->startOfDay();

        $pl = ProfitLoss::forMonth($monthStart, true);
        $previous = ProfitLoss::forMonth($monthStart->copy()->subMonth());
        $trend = collect(range(5, 0))->map(fn ($i) => ProfitLoss::forMonth($monthStart->copy()->subMonths($i)));

        $expected = (float) Payment::query()->where('month', $month)->sum('amount');
        $received = (float) Payment::query()->where('month', $month)->where('status', 'paid')->sum('paid_amount');
        $pending = Payment::query()->with(['rental.vehicle', 'rental.customer'])
            ->where('month', $month)->where('status', 'pending')->orderBy('due_date')->get();

        $data = compact('pl', 'previous', 'trend', 'expected', 'received', 'pending') + [
            'generatedBy' => $request->user()->name,
            'state' => $monthStart->isSameMonth(now()) ? 'current' : ($monthStart->isFuture() ? 'future' : 'past'),
        ];
        $data['insights'] = $this->reportInsights($data);

        return Pdf::loadView('dashboard-report-pdf', $data)
            ->setPaper('a4')
            ->download("business-report-{$month}.pdf");
    }

    private function reportInsights(array $d): array
    {
        $rs = fn ($n) => 'Rs ' . number_format(round(abs($n)));
        $pl = $d['pl'];
        $prev = $d['previous'];
        $out = [];

        if ($d['state'] === 'current') {
            $out[] = ['info', $pl['label'] . ' is still in progress, so these figures will change as more payments and costs are recorded.'];
        }
        if (!$pl['hasActivity']) {
            $out[] = ['info', 'No payments, trips, expenses or maintenance were recorded for ' . $pl['label'] . '.'];
            return $out;
        }

        $out[] = $pl['net'] >= 0
            ? ['good', 'The business made a profit of ' . $rs($pl['net']) . ($pl['margin'] !== null ? ' (' . round($pl['margin']) . '% of income).' : '.')]
            : ['bad', 'The business made a loss of ' . $rs($pl['net']) . ': costs were ' . $rs($pl['costs']) . ' against income of ' . $rs($pl['income']) . '.'];

        if ($prev['hasActivity']) {
            $change = $pl['net'] - $prev['net'];
            if ($change != 0) {
                $out[] = [$change > 0 ? 'good' : 'warn', 'Profit is ' . ($change > 0 ? 'up ' : 'down ') . $rs($change) . ' compared with ' . $prev['label']
                    . ' (' . ($prev['net'] < 0 ? 'a loss of ' : 'a profit of ') . $rs($prev['net']) . ').'];
            }
        }

        $topIncome = collect($pl['lines']['income'])->sortByDesc('amount')->first();
        if ($topIncome['amount'] > 0 && $pl['income'] > 0) {
            $out[] = ['info', $topIncome['label'] . ' brought in the most: ' . $rs($topIncome['amount']) . ' (' . round($topIncome['amount'] / $pl['income'] * 100) . '% of income).'];
        }
        $topCost = collect($pl['lines']['costs'])->sortByDesc('amount')->first();
        if ($topCost['amount'] > 0 && $pl['costs'] > 0) {
            $out[] = ['info', 'The biggest cost was ' . strtolower($topCost['label']) . ': ' . $rs($topCost['amount']) . ' (' . round($topCost['amount'] / $pl['costs'] * 100) . '% of costs).'];
        }

        $active = $pl['vehicles']->where('active', true);
        $best = $active->where('net', '>', 0)->sortByDesc('net')->first();
        $losing = $active->where('net', '<', 0)->sortBy('net');
        if ($best) {
            $out[] = ['good', 'Best earner: ' . $best['name'] . ' with a profit of ' . $rs($best['net']) . '.'];
        }
        if ($losing->isNotEmpty()) {
            $out[] = ['bad', $losing->count() . ' ' . ($losing->count() === 1 ? 'vehicle' : 'vehicles') . ' lost money: '
                . $losing->map(fn ($v) => $v['name'] . ' (−' . $rs($v['net']) . ')')->implode(', ') . '.'];
        }
        $idle = $pl['vehicles']->where('active', false)->count();
        if ($idle) {
            $out[] = ['warn', $idle . ' ' . ($idle === 1 ? 'vehicle had' : 'vehicles had') . ' no income or costs this month.'];
        }

        if ($d['expected'] > 0) {
            $pct = $d['received'] / $d['expected'] * 100;
            $out[] = [$pct >= 100 ? 'good' : ($pct >= 50 ? 'warn' : 'bad'), round($pct) . '% of expected long-term payments were collected (' . $rs($d['received']) . ' of ' . $rs($d['expected']) . ').'];
        }
        $overdue = $d['pending']->filter(fn ($p) => $p->due_date && $p->due_date->lt(now()->startOfDay()));
        if ($overdue->isNotEmpty()) {
            $out[] = ['bad', $overdue->count() . ' ' . ($overdue->count() === 1 ? 'payment is' : 'payments are') . ' overdue, totalling ' . $rs($overdue->sum('amount')) . '.'];
        }

        return $out;
    }

    private function profitAndLoss(Carbon $monthStart): array
    {
        return ProfitLoss::forMonth($monthStart);
    }
}
