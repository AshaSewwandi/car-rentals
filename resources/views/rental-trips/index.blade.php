@extends('layouts.app')
@section('title', 'Rental Trips')

@section('content')
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Rental Trips</h4>
    <div class="text-muted">Record start and end mileage, then auto-calculate extra km and final amount.</div>
  </div>
</div>

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
            <th>Status</th>
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
                <span class="badge text-bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'confirmed' ? 'primary' : 'secondary') }}">
                  {{ ucfirst($booking->status) }}
                </span>
                <br>
                <span class="badge text-bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }} mt-1">
                  Payment: {{ ucfirst($booking->payment_status) }}
                </span>
              </td>
              <td style="min-width: 240px;">
                @if($booking->start_mileage === null)
                  <form method="post" action="{{ route('rental-trips.handover', $booking) }}" class="d-flex gap-2">
                    @csrf
                    <input type="number" step="0.01" min="0" name="start_mileage" class="form-control form-control-sm" placeholder="Start KM" required>
                    <button type="submit" class="btn btn-sm btn-dark">Start</button>
                  </form>
                @elseif($booking->status !== 'completed')
                  <form method="post" action="{{ route('rental-trips.return', $booking) }}" class="d-flex gap-2">
                    @csrf
                    <input type="number" step="0.01" min="{{ (float)$booking->start_mileage }}" name="end_mileage" class="form-control form-control-sm" placeholder="End KM" required>
                    <button type="submit" class="btn btn-sm btn-dark">Return</button>
                  </form>
                @else
                  <span class="text-muted small">Completed by {{ $booking->returnedBy?->name ?: 'Admin' }}<br>{{ $booking->returned_at?->format('Y-m-d H:i') }}</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center p-4 text-muted">No bookings found yet.</td>
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

