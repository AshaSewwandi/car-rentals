@extends('layouts.app')
@section('title', 'Rental Trips')

@section('content')
<style>
  .trip-table td,
  .trip-table th {
    vertical-align: top;
  }

  .trip-table td.cell-actions.is-cancelled .cancelled-action-row {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: .35rem;
  }

  .trip-table td.cell-actions.is-cancelled .cancelled-action-row .trip-note {
    margin: 0;
  }

  .trip-table td.cell-actions.is-cancelled .cancelled-action-row .invoice-btn {
    width: auto;
    min-width: 118px;
  }

  @media (max-width: 920px) {
    .trip-table {
      border-collapse: separate;
      border-spacing: 0;
    }

    .list-card,
    .list-card .card-body,
    .list-card .table-responsive {
      max-width: 100%;
      overflow-x: hidden;
    }

    .page-toolbar {
      gap: .75rem !important;
    }

    .page-toolbar form {
      width: 100%;
      display: grid !important;
      grid-template-columns: 1fr !important;
      gap: .55rem !important;
    }

    .page-toolbar form > div,
    .page-toolbar form > button,
    .page-toolbar form > a {
      width: 100%;
    }

    .page-toolbar .form-select,
    .page-toolbar .form-control {
      min-width: 0 !important;
      width: 100%;
    }

    .list-card .card-header {
      flex-direction: column;
      align-items: flex-start !important;
      gap: .3rem;
    }

    .trip-table,
    .trip-table thead,
    .trip-table tbody,
    .trip-table th,
    .trip-table td,
    .trip-table tr {
      display: block;
      width: 100%;
    }

    .trip-table thead {
      display: none;
    }

    .trip-table tbody tr {
      border: 1.5px solid #c7d6ea;
      border-radius: 12px;
      margin: .65rem;
      width: calc(100% - 1.3rem);
      box-sizing: border-box;
      background: #fff;
      overflow: hidden;
      box-shadow: 0 1px 0 rgba(15, 23, 42, 0.04);
    }

    .trip-table tbody td {
      position: relative;
      padding: .62rem .65rem .62rem 43%;
      min-height: 44px;
      border-top: 1px solid #edf3fb;
      box-sizing: border-box;
      max-width: 100%;
      word-break: break-word;
      overflow-wrap: anywhere;
    }

    .trip-table tbody td:first-child {
      border-top: 0;
    }

    .trip-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .65rem;
      top: .62rem;
      width: calc(43% - .95rem);
      color: #64748b;
      font-size: .74rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .04em;
      white-space: normal;
      line-height: 1.2;
    }

    .trip-table tbody td.cell-actions .d-flex {
      flex-direction: column;
      gap: .45rem !important;
    }

    .trip-table tbody td.cell-actions .form-control,
    .trip-table tbody td.cell-actions .btn,
    .trip-table tbody td.cell-actions a {
      width: 100% !important;
      max-width: 100%;
    }

    .trip-table tbody td.cell-actions.is-cancelled .cancelled-action-row .trip-note {
      white-space: nowrap;
    }

    .trip-table tbody td.cell-actions.is-cancelled .cancelled-action-row .invoice-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: auto !important;
      min-width: 108px;
      min-height: 30px;
      padding: .24rem .52rem;
      font-size: .8rem;
      margin-top: 0 !important;
      margin-left: 0;
    }

    .trip-table tbody td.no-data {
      padding: 1rem .8rem !important;
      text-align: center;
    }

    .trip-table tbody td.no-data::before {
      display: none;
    }
  }

  /* iOS Safari specific overflow fix */
  @supports (-webkit-touch-callout: none) {
    @media (max-width: 920px) {
      .trip-table tbody td {
        padding-left: 44%;
      }

      .trip-table tbody td::before {
        width: calc(35% - .9rem);
        font-size: .7rem;
      }

      .trip-table tbody td.cell-actions.is-cancelled .cancelled-action-row {
        width: 100%;
      }

      .trip-table tbody td.cell-actions.is-cancelled .cancelled-action-row .trip-note {
        max-width: 100%;
      }
    }
  }
</style>
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Rental Trips</h4>
    <div class="text-muted">
      {{ auth()->user()->isAdmin() ? 'Record start and end mileage, then auto-calculate extra km and final amount.' : 'View only the rental trips linked to your partner account.' }}
    </div>
  </div>
  <form method="get" action="{{ route('rental-trips.index') }}" class="d-flex flex-wrap align-items-end gap-2">
    <div>
      <label for="trip_car_id" class="form-label mb-1">Vehicle</label>
      <select id="trip_car_id" name="car_id" class="form-select form-select-sm" style="min-width: 220px;">
        <option value="">All Vehicles</option>
        @foreach($cars as $car)
          <option value="{{ $car->id }}" @selected((string)($filters['car_id'] ?? '') === (string)$car->id)>
            {{ $car->name }} ({{ $car->plate_no }})
          </option>
        @endforeach
      </select>
    </div>
    <div>
      <label for="trip_date_from" class="form-label mb-1">From</label>
      <input id="trip_date_from" type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div>
      <label for="trip_date_to" class="form-label mb-1">To</label>
      <input id="trip_date_to" type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}" min="{{ $filters['date_from'] ?? '' }}">
    </div>
    <div>
      <label for="trip_status" class="form-label mb-1">Status</label>
      <select id="trip_status" name="status" class="form-select form-select-sm" style="min-width: 140px;">
        <option value="">All Statuses</option>
        <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
        <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>Confirmed</option>
        <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
        <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelled</option>
      </select>
    </div>
    <button type="submit" class="btn btn-sm btn-dark">Filter</button>
    <a href="{{ route('rental-trips.index') }}" class="btn btn-sm btn-outline-dark">Reset</a>
    <a href="{{ route('rental-trips.export-pdf', array_filter([
      'car_id' => $filters['car_id'] ?? null,
      'date_from' => $filters['date_from'] ?? null,
      'date_to' => $filters['date_to'] ?? null,
      'status' => $filters['status'] ?? null,
    ], fn($value) => $value !== null && $value !== '')) }}" class="btn btn-sm btn-outline-dark">
      Export PDF
    </a>
  </form>
</div>

<script>
  (function () {
    const navEntry = performance.getEntriesByType('navigation')[0];
    const isReload = navEntry ? navEntry.type === 'reload' : performance.navigation.type === 1;
    if (isReload && window.location.search) {
      window.location.replace(window.location.pathname);
      return;
    }

    const fromInput = document.getElementById('trip_date_from');
    const toInput = document.getElementById('trip_date_to');
    if (!fromInput || !toInput) return;

    const syncToDateMin = () => {
      toInput.min = fromInput.value || '';
      if (fromInput.value && toInput.value && toInput.value < fromInput.value) {
        toInput.value = '';
      }
    };

    fromInput.addEventListener('input', syncToDateMin);
    syncToDateMin();
  })();
</script>

<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Trip Billing List</span>
    <span class="small text-muted">Formula: Final = (Daily Rate × Days) + Extra KM Charge</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle trip-table">
        <thead>
          <tr>
            <th>Booking</th>
            <th>Vehicle</th>
            <th>Dates</th>
            <th>Base</th>
            <th>Mileage</th>
            <th>Usage Billing</th>
            <th>Trip Status</th>
            <th>Base Details</th>
            <th>Additional Details</th>
            <th>Revenue Split</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $booking)
            <tr>
              <td data-label="Booking">
                <strong>#{{ $booking->id }}</strong><br>
                <span class="text-muted">{{ $booking->customer_name }}</span>
              </td>
              <td data-label="Vehicle">
                {{ $booking->car?->name }}<br>
                <span class="text-muted">{{ $booking->car?->plate_no }}</span>
              </td>
              <td data-label="Dates">
                {{ $booking->start_date?->format('Y-m-d') }} to {{ $booking->end_date?->format('Y-m-d') }}<br>
                <span class="text-muted">{{ $booking->rental_days }} day(s)</span>
              </td>
              <td data-label="Base">
                <div>LKR {{ number_format((float)$booking->daily_rate, 2) }}/day</div>
                <strong>LKR {{ number_format((float)$booking->total_amount, 2) }}</strong>
              </td>
              <td data-label="Mileage">
                <div>Start: <strong>{{ $booking->start_mileage !== null ? number_format((float)$booking->start_mileage, 2) : '-' }}</strong></div>
                <div>End: <strong>{{ $booking->end_mileage !== null ? number_format((float)$booking->end_mileage, 2) : '-' }}</strong></div>
              </td>
              <td data-label="Usage Billing">
                <div>Used: <strong>{{ $booking->used_km !== null ? number_format((float)$booking->used_km, 2) : '-' }}</strong> km</div>
                <div>Included: <strong>{{ number_format((float)($booking->included_km ?? ($booking->rental_days * 150)), 2) }}</strong> km</div>
                <div>Extra: <strong>{{ $booking->extra_km !== null ? number_format((float)$booking->extra_km, 2) : '-' }}</strong> km</div>
                <div>Extra Charge: <strong>LKR {{ number_format((float)($booking->extra_km_charge ?? 0), 2) }}</strong></div>
                <div class="mt-1">Final: <strong>LKR {{ number_format((float)($booking->final_total ?? $booking->total_amount), 2) }}</strong></div>
              </td>
              <td data-label="Trip Status">
                @php
                  $statusClass = match ($booking->status) {
                    'completed' => 'success',
                    'confirmed' => 'warning',
                    'cancelled' => 'danger',
                    default => 'secondary',
                  };
                @endphp
                <span class="badge text-bg-{{ $statusClass }}">
                  {{ ucfirst($booking->status) }}
                </span>
              </td>
              <td data-label="Base Details">
                <div>{{ ucfirst((string) $booking->payment_status) }}</div>
                <div class="text-muted small">LKR {{ number_format((float) $booking->total_amount, 2) }}</div>
              </td>
              <td data-label="Additional Details">
                <div>{{ $booking->additional_payment_status === 'not_required' ? 'Not Yet' : ucfirst((string) $booking->additional_payment_status) }}</div>
                <div class="text-muted small">LKR {{ number_format((float) ($booking->additional_payment_amount ?? $booking->extra_km_charge ?? 0), 2) }}</div>
              </td>
              <td data-label="Revenue Split">
                @php
                  $partnerShareAmount = (float) ($booking->partner_share_amount ?? 0);
                  $adminShareAmount = (float) ($booking->admin_share_amount ?? ($booking->final_total ?? $booking->total_amount ?? 0));
                  $partnerSharePercentage = (float) ($booking->partner_share_percentage ?? 0);
                  $adminSharePercentage = (float) ($booking->admin_share_percentage ?? 100);
                @endphp
                @if(auth()->user()->isAdmin())
                  <div>Partner: <strong>{{ number_format($partnerSharePercentage, 2) }}%</strong></div>
                  <div class="text-muted small">LKR {{ number_format($partnerShareAmount, 2) }}</div>
                  <div class="mt-1">Admin: <strong>{{ number_format($adminSharePercentage, 2) }}%</strong></div>
                  <div class="text-muted small">LKR {{ number_format($adminShareAmount, 2) }}</div>
                @else
                  <div>Your Share: <strong>{{ number_format($partnerSharePercentage, 2) }}%</strong></div>
                  <div class="text-muted small">LKR {{ number_format($partnerShareAmount, 2) }}</div>
                @endif
              </td>
              <td data-label="Action" class="cell-actions {{ $booking->status === 'cancelled' ? 'is-cancelled' : '' }}" style="min-width: 240px;">
                @if(auth()->user()->isAdmin())
                  @if($booking->status === 'cancelled')
                    {{-- Handled below with invoice button in one row --}}
                  @elseif($booking->start_mileage === null)
                    <form method="post" action="{{ route('rental-trips.handover', $booking) }}" class="d-flex gap-2">
                      @csrf
                      <input type="text" inputmode="decimal" name="start_mileage" class="form-control form-control-sm" placeholder="Start KM" required>
                      <button type="submit" class="btn btn-sm btn-dark">Start</button>
                    </form>
                  @elseif($booking->status !== 'completed')
                    <form method="post" action="{{ route('rental-trips.return', $booking) }}" class="d-flex gap-2">
                      @csrf
                      <input type="text" inputmode="decimal" name="end_mileage" class="form-control form-control-sm" placeholder="End KM" required>
                      <button type="submit" class="btn btn-sm btn-dark">Return</button>
                    </form>
                  @else
                    <span class="text-muted small">Completed by {{ $booking->returnedBy?->name ?: 'Admin' }}<br>{{ $booking->returned_at?->format('Y-m-d H:i') }}</span>
                  @endif
                @else
                  <span class="text-muted small">Read only</span>
                @endif

                @if(auth()->user()->isAdmin() && $booking->status !== 'cancelled' && $booking->payment_status !== 'paid')
                  <form method="post" action="{{ route('rental-trips.payment.base.paid', $booking) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark w-100">Mark Base Paid</button>
                  </form>
                @endif

                @if(auth()->user()->isAdmin() && $booking->status !== 'cancelled' && $booking->additional_payment_status === 'pending')
                  <form method="post" action="{{ route('rental-trips.payment.additional.paid', $booking) }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-dark w-100">Mark Additional Paid</button>
                  </form>
                @endif

                @if(auth()->user()->isAdmin() && in_array($booking->status, ['pending', 'confirmed'], true) && !$booking->handover_at && $booking->start_mileage === null)
                  <form method="post" action="{{ route('rental-trips.cancel', $booking) }}" class="mt-2" onsubmit="return confirm('Cancel this rental trip?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">Cancel Trip</button>
                  </form>
                @endif

                @if($booking->status === 'cancelled')
                  <div class="cancelled-action-row mt-2">
                    <span class="text-muted small trip-note">Trip cancelled</span>
                    <a href="{{ route('rental-trips.invoice-pdf', $booking) }}" class="btn btn-sm btn-outline-dark invoice-btn">
                      Invoice PDF
                    </a>
                  </div>
                @else
                  <a href="{{ route('rental-trips.invoice-pdf', $booking) }}" class="btn btn-sm btn-outline-dark w-100 mt-2">
                    Invoice PDF
                  </a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center p-4 text-muted no-data">No bookings found yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($bookings->hasPages())
    <div class="card-footer bg-white">
      {{ $bookings->links() }}
    </div>
  @endif
</div>
@endsection
