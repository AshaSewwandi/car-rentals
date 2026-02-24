@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <h4 class="mb-0">Dashboard Summary</h4>
  <form class="d-flex gap-2" method="get" action="{{ route('dashboard') }}">
    <input type="month" class="form-control" name="month" value="{{ $month }}">
    <button class="btn btn-dark">Filter</button>
  </form>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Expected Payments ({{ $month }})</div>
        <div class="fs-4 fw-bold">Rs {{ number_format($expected, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Received Payments ({{ $month }})</div>
        <div class="fs-4 fw-bold">Rs {{ number_format($received, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Expenses ({{ $month }})</div>
        <div class="fs-4 fw-bold">Rs {{ number_format($monthExpenses, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Pending Payments</div>
        <div class="fs-4 fw-bold">{{ number_format($pendingPaymentsCount) }}</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Upcoming Payments</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($upcomingPaymentsTotal, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Upcoming Expenses</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($upcomingExpensesTotal, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Due Payments (Next 7 Days)</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($dueThisWeekPayments, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted">Planned Expenses (Next 7 Days)</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($dueThisWeekExpenses, 2) }}</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-xl-7">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Upcoming Payments</span>
        @if(auth()->user()->canAccess('payments'))
          <a class="btn btn-sm btn-outline-dark" href="{{ route('payments.index') }}">Open Payments</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle">
            <thead>
              <tr>
                <th>Due Date</th>
                <th>Car</th>
                <th>Customer</th>
                <th>Month</th>
                <th class="text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($upcomingPayments as $payment)
                <tr>
                  <td>{{ $payment->due_date->format('Y-m-d') }}</td>
                  <td>{{ $payment->rental->car->name ?? '-' }}</td>
                  <td>{{ $payment->rental->customer->name ?? '-' }}</td>
                  <td>{{ $payment->month }}</td>
                  <td class="text-end">Rs {{ number_format($payment->amount, 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center p-4 text-muted">No upcoming pending payments.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-5">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Upcoming Expenses</span>
        @if(auth()->user()->canAccess('expenses'))
          <a class="btn btn-sm btn-outline-dark" href="{{ route('expenses.index') }}">Open Expenses</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle">
            <thead>
              <tr>
                <th>Date</th>
                <th>Car</th>
                <th>Type</th>
                <th class="text-end">Amount</th>
              </tr>
            </thead>
            <tbody>
              @forelse($upcomingExpenses as $expense)
                <tr>
                  <td>{{ $expense->date->format('Y-m-d') }}</td>
                  <td>{{ $expense->car?->name ?? '-' }}</td>
                  <td>{{ ucfirst($expense->type) }}</td>
                  <td class="text-end">Rs {{ number_format($expense->amount, 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center p-4 text-muted">No upcoming expenses.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
