<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $cars = Car::query()->orderBy('name')->get();

        $expenses = Expense::query()
            ->with('car')
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$month])
            ->orderByDesc('date')
            ->get();

        $total = (float) $expenses->sum('amount');

        return view('expenses.index', compact('cars', 'expenses', 'month', 'total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:service,repair,insurance,license,tyre,other'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        Expense::create($data);

        return back()->with('success', 'Expense added successfully.');
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'in:service,repair,insurance,license,tyre,other'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $expense->update($data);

        return back()->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted successfully.');
    }
}
