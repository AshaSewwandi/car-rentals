@extends('layouts.app')
@section('title', 'Rental Trips')

@section('content')
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
      <table class="table table-striped mb-0 align-middle">
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
              <td>
                <strong>#{{ $booking->id }}</strong><br>
                <span class="text-muted">{{ $booking->customer_name }}</span>
              </td>
              <td>
                {{ $booking->car?->name }}<br>
                <span class="text-muted">{{ $booking->car?->plate_no }}</span>
              </td>
              <td>
                {{ $booking->start_date?->format('Y-m-d') }} to {{ $booking->end_date?->format('Y-m-d') }}<br>
                <span class="text-muted">{{ $booking->rental_days }} day(s)</span>
              </td>
              <td>
                <div>LKR {{ number_format((float)$booking->daily_rate, 2) }}/day</div>
                <strong>LKR {{ number_format((float)$booking->total_amount, 2) }}</strong>
              </td>
              <td>
                <div>Start: <strong>{{ $booking->start_mileage !== null ? number_format((float)$booking->start_mileage, 2) : '-' }}</strong></div>
                <div>End: <strong>{{ $booking->end_mileage !== null ? number_format((float)$booking->end_mileage, 2) : '-' }}</strong></div>
              </td>
              <td>
                <div>Used: <strong>{{ $booking->used_km !== null ? number_format((float)$booking->used_km, 2) : '-' }}</strong> km</div>
                <div>Included: <strong>{{ number_format((float)($booking->included_km ?? ($booking->rental_days * 150)), 2) }}</strong> km</div>
                <div>Extra: <strong>{{ $booking->extra_km !== null ? number_format((float)$booking->extra_km, 2) : '-' }}</strong> km</div>
                <div>Extra Charge: <strong>LKR {{ number_format((float)($booking->extra_km_charge ?? 0), 2) }}</strong></div>
                <div class="mt-1">Final: <strong>LKR {{ number_format((float)($booking->final_total ?? $booking->total_amount), 2) }}</strong></div>
              </td>
              <td>
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
              <td>
                <div>{{ ucfirst((string) $booking->payment_status) }}</div>
                <div class="text-muted small">LKR {{ number_format((float) $booking->total_amount, 2) }}</div>
              </td>
              <td>
                <div>{{ $booking->additional_payment_status === 'not_required' ? 'Not Yet' : ucfirst((string) $booking->additional_payment_status) }}</div>
                <div class="text-muted small">LKR {{ number_format((float) ($booking->additional_payment_amount ?? $booking->extra_km_charge ?? 0), 2) }}</div>
              </td>
              <td>
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
              <td style="min-width: 240px;">
                @if(auth()->user()->isAdmin())
                  @if($booking->status === 'cancelled')
                    <span class="text-muted small">Trip cancelled</span>
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

                <a href="{{ route('rental-trips.invoice-pdf', $booking) }}" class="btn btn-sm btn-outline-dark w-100 mt-2">
                  Invoice PDF
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center p-4 text-muted">No bookings found yet.</td>
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
