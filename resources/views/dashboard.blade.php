@extends('layouts.app')
@section('title', 'Car Rentals')

@section('content')
<style>
  .dashboard-page {
    --dash-panel: rgba(246, 241, 232, 0.94);
    --dash-panel-soft: rgba(242, 235, 224, 0.9);
    --dash-line: rgba(23, 35, 50, 0.1);
    --dash-head: #46617b;
    --dash-row-text: #234d73;
  }

  .dashboard-page h4 {
    font-weight: 700;
    color: #25415c;
  }

  .dashboard-page .metric-card {
    background: linear-gradient(180deg, rgba(248, 243, 235, 0.96), rgba(240, 232, 218, 0.92));
    border: 1px solid rgba(23, 35, 50, 0.08);
    border-top: 2px solid rgba(232, 74, 36, 0.18);
    box-shadow: 0 6px 14px rgba(23, 35, 50, 0.04) !important;
  }

  .dashboard-page .metric-card .text-muted {
    color: #65819b !important;
    font-weight: 600;
  }

  .dashboard-page .metric-card .fs-4,
  .dashboard-page .metric-card .fs-5 {
    color: #2d4a67;
    font-weight: 700 !important;
    letter-spacing: 0.01em;
  }

  .dashboard-page .section-card {
    background: linear-gradient(180deg, var(--dash-panel), var(--dash-panel-soft));
    border: 1px solid var(--dash-line);
    box-shadow: 0 10px 24px rgba(23, 35, 50, 0.07) !important;
  }

  .dashboard-page .section-card .card-header {
    background: rgba(241, 233, 219, 0.9);
    border-bottom: 1px solid var(--dash-line);
    color: #203c56;
    font-weight: 700;
  }

  .dashboard-page .section-card .table thead th {
    background: rgba(238, 230, 214, 0.94);
    color: var(--dash-head);
    font-weight: 700;
  }

  .dashboard-page .section-card .table td {
    background: rgba(250, 247, 241, 0.72);
    border-color: rgba(23, 35, 50, 0.1);
    color: var(--dash-row-text);
    font-weight: 500;
  }

  .dashboard-page .section-card .table td.text-end {
    color: var(--dash-row-text);
    font-weight: 600;
  }

  .dashboard-page .section-card .table-striped > tbody > tr:nth-of-type(odd) > * {
    background: rgba(244, 238, 228, 0.78);
  }

  .dashboard-page .btn-outline-dark {
    background: rgba(236, 226, 207, 0.9);
    border-color: rgba(23, 35, 50, 0.24);
  }

  .dashboard-page .btn-outline-dark:hover {
    background: rgba(244, 236, 222, 0.98) !important;
    border-color: rgba(23, 35, 50, 0.24) !important;
    color: #1f3953 !important;
  }

  @media (max-width: 991.98px) {
    .dashboard-page .metric-card {
      border-top-width: 2px;
    }
  }
</style>
<div class="dashboard-page">
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <h4 class="mb-0">Dashboard Summary</h4>
  <form class="d-flex gap-2" method="get" action="{{ route('dashboard') }}">
    <input type="month" class="form-control" name="month" value="{{ $month }}">
    <button class="btn btn-dark">Filter</button>
  </form>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Expected Payments ({{ $month }})</div>
        <div class="fs-4 fw-bold">Rs {{ number_format($expected, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Received Payments ({{ $month }})</div>
        <div class="fs-4 fw-bold">Rs {{ number_format($received, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Expenses ({{ $month }})</div>
        <div class="fs-4 fw-bold">Rs {{ number_format($monthExpenses, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Pending Payments</div>
        <div class="fs-4 fw-bold">{{ number_format($pendingPaymentsCount) }}</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Upcoming Payments</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($upcomingPaymentsTotal, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Upcoming Expenses</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($upcomingExpensesTotal, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Due Payments (Next 7 Days)</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($dueThisWeekPayments, 2) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card metric-card h-100">
      <div class="card-body">
        <div class="text-muted">Planned Expenses (Next 7 Days)</div>
        <div class="fs-5 fw-bold">Rs {{ number_format($dueThisWeekExpenses, 2) }}</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-xl-7">
    <div class="card section-card">
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
    <div class="card section-card">
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
</div>
@endsection
