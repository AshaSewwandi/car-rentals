<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
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
            ->where('status', 'pending')
            ->count();

        $upcomingPaymentsTotal = Payment::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', $today->toDateString())
            ->sum('amount');

        $upcomingExpensesTotal = Expense::query()
            ->whereDate('date', '>=', $today->toDateString())
            ->sum('amount');

        $dueThisWeekPayments = Payment::query()
            ->where('status', 'pending')
            ->whereBetween('due_date', [$today->toDateString(), $next7Days->toDateString()])
            ->sum('amount');

        $dueThisWeekExpenses = Expense::query()
            ->whereBetween('date', [$today->toDateString(), $next7Days->toDateString()])
            ->sum('amount');

        $upcomingPayments = Payment::query()
            ->with(['rental.car', 'rental.customer'])
            ->where('status', 'pending')
            ->whereDate('due_date', '>=', $today->toDateString())
            ->orderBy('due_date')
            ->limit(8)
            ->get();

        $upcomingExpenses = Expense::query()
            ->with('car')
            ->whereDate('date', '>=', $today->toDateString())
            ->orderBy('date')
            ->limit(8)
            ->get();

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
            'upcomingExpenses'
        ));
    }
}
