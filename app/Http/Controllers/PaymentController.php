<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $this->syncRentalsFromAgreements();

        $payments = Payment::query()
            ->with(['rental.car','rental.customer'])
            ->where('month', $month)
            ->orderBy('due_date')
            ->get();

        $rentals = Rental::query()
            ->with(['car', 'customer'])
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->get();

        return view('payments.index', compact('payments', 'month', 'rentals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rental_id' => ['required', 'exists:rentals,id'],
            'month' => ['required', 'date_format:Y-m'],
            'due_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid'],
            'paid_date' => ['nullable', 'date'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'method' => ['nullable', 'in:cash,bank,online'],
        ], [
            'rental_id.required' => 'Please select a rental.',
        ]);

        $exists = Payment::query()
            ->where('rental_id', $data['rental_id'])
            ->where('month', $data['month'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['month' => 'This rental already has a payment for the selected month.'])
                ->withInput();
        }

        if ($data['status'] === 'pending') {
            $data['paid_date'] = null;
            $data['paid_amount'] = null;
            $data['method'] = null;
        } else {
            $data['paid_date'] = $data['paid_date'] ?? now()->toDateString();
            $data['paid_amount'] = $data['paid_amount'] ?? $data['amount'];
            $data['method'] = $data['method'] ?? 'cash';
        }

        Payment::create($data);

        return back()->with('success', 'Payment added successfully.');
    }

    public function markPaid(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'paid_date'   => ['required','date'],
            'paid_amount' => ['required','numeric','min:0'],
            'method'      => ['required','in:cash,bank,online'],
        ]);

        $payment->update([
            'paid_date'   => $data['paid_date'],
            'paid_amount' => $data['paid_amount'],
            'method'      => $data['method'],
            'status'      => 'paid',
        ]);

        return back()->with('success', 'Payment marked as paid.');
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'due_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,paid'],
            'paid_date' => ['nullable', 'date'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'method' => ['nullable', 'in:cash,bank,online'],
        ]);

        if ($data['status'] === 'pending') {
            $data['paid_date'] = null;
            $data['paid_amount'] = null;
            $data['method'] = null;
        }

        $payment->update($data);

        return back()->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back()->with('success', 'Payment deleted successfully.');
    }

    private function syncRentalsFromAgreements(): void
    {
        $agreements = Agreement::query()
            ->whereIn('status', ['active', 'ended'])
            ->get();

        foreach ($agreements as $agreement) {
            $startDate = $agreement->start_date?->toDateString() ?? now()->toDateString();
            $dueDay = (int) ($agreement->start_date?->format('d') ?? 5);
            $dueDay = max(1, min(28, $dueDay));

            Rental::updateOrCreate(
                [
                    'car_id' => $agreement->car_id,
                    'customer_id' => $agreement->customer_id,
                    'start_date' => $startDate,
                ],
                [
                    'end_date' => $agreement->end_date?->toDateString(),
                    'due_day' => $dueDay,
                    'monthly_rent' => $agreement->monthly_rent,
                    'deposit' => $agreement->deposit ?? 0,
                    'status' => $agreement->status === 'ended' ? 'ended' : 'active',
                    'note' => $agreement->note,
                ]
            );
        }
    }
}
