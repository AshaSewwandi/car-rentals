@extends('layouts.app')
@section('title', 'R&A Auto Rentals')

@section('content')
<style>
  .dashboard-page {
    --dash-border: #dbe4ef;
    --dash-muted: #64748b;
    --dash-text: #0f172a;
    --dash-head-bg: #f8fbff;
    --dash-row-alt: #f8fbff;
    --dash-green: #059669;
    --dash-green-bg: #dcfce7;
    --dash-red: #dc2626;
    --dash-red-bg: #fee2e2;
  }

  .dashboard-page h4,
  .dashboard-page h5 {
    font-weight: 700;
    color: var(--dash-text);
  }

  .dashboard-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: 1rem;
  }

  .dashboard-toolbar-left {
    display: flex;
    align-items: center;
    gap: .9rem;
    flex-wrap: wrap;
  }

  .dash-search {
    min-width: 280px;
    border: 1px solid var(--dash-border);
    border-radius: .65rem;
    background: #f1f5f9;
    color: #475569;
    padding: .58rem .78rem;
  }

  .dashboard-toolbar-right {
    display: flex;
    align-items: center;
    gap: .55rem;
    flex-wrap: wrap;
  }

  .dashboard-page .kpi-card {
    background: #fff;
    border: 1px solid var(--dash-border);
    border-radius: .9rem;
    box-shadow: 0 5px 14px rgba(15, 23, 42, 0.04) !important;
  }

  .dashboard-page .kpi-label {
    color: var(--dash-muted) !important;
    font-weight: 600;
    margin-bottom: .4rem;
  }

  .dashboard-page .kpi-value {
    color: var(--dash-text);
    font-weight: 800;
    font-size: 2rem;
    line-height: 1;
  }

  .dashboard-page .delta {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 54px;
    padding: .22rem .52rem;
    border-radius: .45rem;
    font-size: .85rem;
    font-weight: 700;
  }

  .dashboard-page .delta.up {
    color: var(--dash-green);
    background: var(--dash-green-bg);
  }

  .dashboard-page .delta.down {
    color: var(--dash-red);
    background: var(--dash-red-bg);
  }

  .dashboard-page .kpi-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .65rem;
  }

  .dashboard-page .panel-card {
    background: #fff;
    border: 1px solid var(--dash-border);
    border-radius: .9rem;
    box-shadow: 0 5px 14px rgba(15, 23, 42, 0.04) !important;
  }

  .dashboard-page .panel-card .card-header {
    background: #fff;
    border-bottom: 1px solid var(--dash-border);
    color: var(--dash-text);
    font-weight: 700;
    padding: 1rem 1.1rem;
  }

  .dashboard-page .panel-link {
    color: #0f66c3;
    text-decoration: none;
    font-weight: 600;
    font-size: .95rem;
  }

  .dashboard-page .panel-card .table thead th {
    background: var(--dash-head-bg);
    color: #64748b;
    font-weight: 700;
    font-size: .82rem;
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .dashboard-page .panel-card .table td {
    background: #fff;
    border-color: var(--dash-border);
    color: #334155;
    font-weight: 500;
    padding-top: .9rem;
    padding-bottom: .9rem;
  }

  .dashboard-page .panel-card .table td.text-end {
    color: var(--dash-text);
    font-weight: 600;
  }

  .dashboard-page .panel-card .table-striped > tbody > tr:nth-of-type(odd) > * {
    background: var(--dash-row-alt);
  }

  @media (max-width: 991.98px) {
    .dashboard-toolbar {
      align-items: flex-start;
      flex-direction: column;
    }

    .dashboard-toolbar-right {
      width: 100%;
    }

    .dash-search {
      min-width: 100%;
    }
  }
</style>
<div class="dashboard-page">
@php
  $collectionDelta = $expected > 0 ? (($received - $expected) / $expected) * 100 : 0;
  $expenseDelta = $monthExpenses > 0 ? (($dueThisWeekExpenses - $monthExpenses) / $monthExpenses) * 100 : 0;
  $pendingDelta = $pendingPaymentsCount > 0 ? min(99, ($pendingPaymentsCount * 7.5)) : 0;
@endphp

<div class="dashboard-toolbar">
  <div class="dashboard-toolbar-left">
    <h4 class="mb-0">Overview</h4>
    <input class="dash-search" type="text" placeholder="Search cars, users..." aria-label="Search">
  </div>
  <div class="dashboard-toolbar-right">
    <form class="d-flex gap-2" method="get" action="{{ route('dashboard') }}">
      <input type="month" class="form-control" name="month" value="{{ $month }}">
      <button class="btn btn-dark">Filter</button>
    </form>
    @if(auth()->user()->canAccess('payments'))
      <a class="btn btn-dark" href="{{ route('payments.index') }}">+ Add Payment</a>
    @endif
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-label">Expected Payments</div>
        <div class="kpi-row">
          <div class="kpi-value">Rs {{ number_format($expected, 0) }}</div>
          <span class="delta {{ $collectionDelta >= 0 ? 'up' : 'down' }}">{{ $collectionDelta >= 0 ? '+' : '' }}{{ number_format($collectionDelta, 0) }}%</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-label">Received Payments</div>
        <div class="kpi-row">
          <div class="kpi-value">Rs {{ number_format($received, 0) }}</div>
          <span class="delta {{ $collectionDelta >= 0 ? 'up' : 'down' }}">{{ $collectionDelta >= 0 ? '+' : '' }}{{ number_format($collectionDelta, 0) }}%</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-label">Expenses</div>
        <div class="kpi-row">
          <div class="kpi-value">Rs {{ number_format($monthExpenses, 0) }}</div>
          <span class="delta {{ $expenseDelta <= 0 ? 'up' : 'down' }}">{{ $expenseDelta > 0 ? '+' : '' }}{{ number_format($expenseDelta, 0) }}%</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card h-100">
      <div class="card-body">
        <div class="kpi-label">Pending Tasks</div>
        <div class="kpi-row">
          <div class="kpi-value">{{ number_format($pendingPaymentsCount) }}</div>
          <span class="delta {{ $pendingPaymentsCount > 0 ? 'up' : 'down' }}">+{{ number_format($pendingDelta, 0) }}%</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-12 col-xl-7">
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Upcoming Payments</span>
        @if(auth()->user()->canAccess('payments'))
          <a class="panel-link" href="{{ route('payments.index') }}">View All</a>
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
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Upcoming Expenses</span>
        @if(auth()->user()->canAccess('expenses'))
          <a class="panel-link" href="{{ route('expenses.index') }}">View All</a>
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
