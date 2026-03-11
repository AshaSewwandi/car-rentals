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
    .dashboard-page {
      max-width: 100%;
      overflow-x: hidden;
    }

    .app-main.app-main-col {
      overflow-x: hidden;
    }

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

    /* Override shared layout mobile rule that forces nowrap table scrolling */
    .dashboard-page .panel-card .table {
      display: table !important;
      width: 100% !important;
      min-width: 0 !important;
      white-space: normal !important;
      overflow: visible !important;
    }

    .dashboard-page .panel-card .table-responsive {
      overflow-x: hidden !important;
      -webkit-overflow-scrolling: auto;
    }
  }

  @media (max-width: 767.98px) {
    .dashboard-page {
      max-width: 100%;
      overflow-x: hidden;
    }

    .dashboard-page .row {
      margin-left: 0;
      margin-right: 0;
    }

    .dashboard-page .row > [class*="col-"] {
      padding-left: .35rem;
      padding-right: .35rem;
    }

    .dashboard-toolbar {
      gap: .6rem;
      margin-bottom: .8rem;
    }

    .dashboard-toolbar-left,
    .dashboard-toolbar-right {
      width: 100%;
    }

    .dashboard-toolbar-left h4 {
      width: 100%;
      margin-bottom: .1rem !important;
      font-size: 1.95rem;
    }

    .dashboard-toolbar-right {
      justify-content: flex-start;
      gap: .5rem;
    }

    .dashboard-toolbar-right form {
      width: 100%;
      display: grid !important;
      grid-template-columns: minmax(0, 1fr) auto;
      gap: .5rem !important;
    }

    .dashboard-toolbar-right form .form-control,
    .dashboard-toolbar-right form .btn,
    .dashboard-toolbar-right > .btn {
      min-height: 42px;
    }

    .dashboard-toolbar-right > .btn {
      width: auto;
      max-width: 100%;
    }

    .dashboard-page .kpi-value {
      font-size: 1.9rem;
    }

    .dashboard-page .panel-card .card-header {
      padding: .85rem .9rem;
      flex-wrap: wrap;
      gap: .35rem;
      align-items: flex-start !important;
    }

    .dashboard-page .panel-card {
      overflow: hidden;
    }

    .dashboard-page .panel-card .table-responsive {
      overflow: visible;
    }

    .dashboard-page .panel-card .table {
      white-space: normal !important;
      overflow: visible !important;
    }

    .dashboard-page .dash-table,
    .dashboard-page .dash-table thead,
    .dashboard-page .dash-table tbody,
    .dashboard-page .dash-table th,
    .dashboard-page .dash-table td,
    .dashboard-page .dash-table tr {
      display: block;
      width: 100%;
    }

    .dashboard-page .dash-table thead {
      display: none;
    }

    .dashboard-page .dash-table tbody tr {
      border-top: 1px solid var(--dash-border);
      padding: .25rem 0;
    }

    .dashboard-page .dash-table tbody td {
      position: relative;
      border: 0;
      border-top: 1px solid #edf3fb;
      padding: .58rem .8rem .58rem 42%;
      min-height: 42px;
      box-sizing: border-box;
      max-width: 100%;
      word-break: break-word;
      overflow-wrap: anywhere;
    }

    .dashboard-page .dash-table tbody td:first-child {
      border-top: 0;
    }

    .dashboard-page .dash-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .8rem;
      top: .58rem;
      width: calc(42% - 1rem);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .05em;
    }

    .dashboard-page .dash-table tbody td.text-end {
      text-align: left !important;
    }

    .dashboard-page .dash-table tbody td.no-data {
      padding: .95rem .85rem !important;
      text-align: center !important;
    }

    .dashboard-page .dash-table tbody td.no-data::before {
      display: none;
    }
  }

  /* iOS Safari-specific overflow guard */
  @supports (-webkit-touch-callout: none) {
    @media (max-width: 767.98px) {
      .dashboard-page {
        overflow-x: clip;
      }

      .dashboard-page .panel-card .table {
        white-space: normal !important;
      }

      .dashboard-page .dashboard-toolbar-right form {
        grid-template-columns: minmax(0, 1fr) !important;
      }
    }

    @media (max-width: 991.98px) {
      html, body {
        overflow-x: hidden !important;
      }
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
  <div class="col-12">
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Renewals From {{ $renewalWindowStart->format('Y-m-d') }} To {{ $renewalWindowEnd->format('Y-m-d') }}</span>
        @if(auth()->user()->canAccess('cars'))
          <a class="panel-link" href="{{ route('cars.index') }}">Manage Vehicles</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle dash-table">
            <thead>
              <tr>
                <th>Vehicle</th>
                <th>Renewal Type</th>
                <th>Renewal Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($renewalAlerts as $alert)
                <tr>
                  <td data-label="Vehicle">{{ $alert['car']->name }}{{ $alert['car']->plate_no ? ' (' . $alert['car']->plate_no . ')' : '' }}</td>
                  <td data-label="Renewal Type">{{ $alert['type'] }}</td>
                  <td data-label="Renewal Date">{{ $alert['date']->format('Y-m-d') }}</td>
                  <td data-label="Status">
                    @if($alert['days_left'] <= 7)
                      <span class="badge text-bg-danger">Due in {{ max($alert['days_left'], 0) }} day{{ max($alert['days_left'], 0) === 1 ? '' : 's' }}</span>
                    @else
                      <span class="badge text-bg-warning">Due in {{ $alert['days_left'] }} days</span>
                    @endif
                  </td>
                  <td data-label="Action">
                    @if(auth()->user()->canAccess('cars'))
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-dark"
                        data-bs-toggle="modal"
                        data-bs-target="#renewalUpdateModal"
                        data-renewal-url="{{ route('cars.renewal.update', $alert['car']) }}"
                        data-renewal-car="{{ $alert['car']->name }}{{ $alert['car']->plate_no ? ' (' . $alert['car']->plate_no . ')' : '' }}"
                        data-renewal-type="{{ strtolower($alert['type']) }}"
                        data-renewal-date="{{ $alert['date']->format('Y-m-d') }}"
                      >
                        Renew
                      </button>
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center p-4 text-muted no-data">No insurance or license renewals in the selected 30-day window.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-xl-7">
    <div class="card panel-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Payments For {{ $month }}</span>
        @if(auth()->user()->canAccess('payments'))
          <a class="panel-link" href="{{ route('payments.index') }}">View All</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle dash-table">
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
                  <td data-label="Due Date">{{ $payment->due_date->format('Y-m-d') }}</td>
                  <td data-label="Car">{{ $payment->rental->car->name ?? '-' }}</td>
                  <td data-label="Customer">{{ $payment->rental->customer->name ?? '-' }}</td>
                  <td data-label="Month">{{ $payment->month }}</td>
                  <td data-label="Amount" class="text-end">Rs {{ number_format($payment->amount, 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center p-4 text-muted no-data">No pending payments for {{ $month }}.</td></tr>
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
        <span>Expenses For {{ $month }}</span>
        @if(auth()->user()->canAccess('expenses'))
          <a class="panel-link" href="{{ route('expenses.index') }}">View All</a>
        @endif
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle dash-table">
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
                  <td data-label="Date">{{ $expense->date->format('Y-m-d') }}</td>
                  <td data-label="Car">{{ $expense->car?->name ?? '-' }}</td>
                  <td data-label="Type">{{ ucfirst($expense->type) }}</td>
                  <td data-label="Amount" class="text-end">Rs {{ number_format($expense->amount, 2) }}</td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center p-4 text-muted no-data">No expenses for {{ $month }}.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="renewalUpdateModal" tabindex="-1" aria-labelledby="renewalUpdateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renewalUpdateModalLabel">Update Renewal Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" id="renewalUpdateForm">
        @csrf
        @method('PATCH')
        <input type="hidden" name="renewal_type" id="renewal_type">
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Vehicle</label>
            <input type="text" class="form-control" id="renewal_car_name" readonly>
          </div>
          <div class="mb-2">
            <label class="form-label">Renewal Type</label>
            <input type="text" class="form-control" id="renewal_type_label" readonly>
          </div>
          <div class="mb-1">
            <label class="form-label">New Renewal Date</label>
            <input type="date" class="form-control" name="renewal_date" id="renewal_date" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Renewal Date</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const renewalModal = document.getElementById('renewalUpdateModal');
    const renewalForm = document.getElementById('renewalUpdateForm');
    const renewalCarName = document.getElementById('renewal_car_name');
    const renewalType = document.getElementById('renewal_type');
    const renewalTypeLabel = document.getElementById('renewal_type_label');
    const renewalDate = document.getElementById('renewal_date');

    if (!renewalModal || !renewalForm || !renewalCarName || !renewalType || !renewalTypeLabel || !renewalDate) {
      return;
    }

    renewalModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const formAction = trigger?.getAttribute('data-renewal-url') || '';
      const carName = trigger?.getAttribute('data-renewal-car') || '';
      const type = trigger?.getAttribute('data-renewal-type') || '';
      const date = trigger?.getAttribute('data-renewal-date') || '';

      renewalForm.setAttribute('action', formAction);
      renewalCarName.value = carName;
      renewalType.value = type;
      renewalTypeLabel.value = type ? type.charAt(0).toUpperCase() + type.slice(1) : '';
      renewalDate.value = date;
    });
  });
</script>

</div>
@endsection
