@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<style>
  .profile-trips-wrap {
    overflow-x: auto;
  }

  @media (max-width: 920px) {
    .profile-trips-wrap {
      overflow: visible;
    }

    .profile-trips-table,
    .profile-trips-table thead,
    .profile-trips-table tbody,
    .profile-trips-table th,
    .profile-trips-table td,
    .profile-trips-table tr {
      display: block;
      width: 100%;
    }

    .profile-trips-table thead {
      display: none;
    }

    .profile-trips-table tbody tr {
      border: 1px solid #dbe6f3;
      border-radius: 12px;
      margin: .7rem;
      background: #fff;
      overflow: hidden;
      box-sizing: border-box;
      width: calc(100% - 1.4rem);
    }

    .profile-trips-table tbody td {
      position: relative;
      border-top: 1px solid #edf3fb;
      padding: .62rem .7rem .62rem 38%;
      min-height: 42px;
      word-break: break-word;
      overflow-wrap: anywhere;
      box-sizing: border-box;
      text-align: left !important;
    }

    .profile-trips-table tbody td:first-child {
      border-top: 0;
    }

    .profile-trips-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .7rem;
      top: .62rem;
      width: calc(38% - 1rem);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .04em;
      line-height: 1.2;
    }

    .profile-trips-table tbody td.trip-actions-cell {
      padding-left: .7rem;
    }

    .profile-trips-table tbody td.trip-actions-cell::before {
      position: static;
      display: block;
      width: auto;
      margin-bottom: .4rem;
    }

    .profile-trips-table tbody td.trip-actions-cell .trip-actions {
      display: flex;
      flex-direction: column;
      gap: .42rem;
      align-items: stretch;
    }

    .profile-trips-table tbody td.trip-actions-cell .trip-actions .btn,
    .profile-trips-table tbody td.trip-actions-cell .trip-actions form {
      width: 100%;
      margin: 0 !important;
    }

    .profile-trips-table tbody td.no-data {
      padding: 1rem .8rem !important;
      text-align: center !important;
      border-top: 0;
    }

    .profile-trips-table tbody td.no-data::before {
      display: none;
    }
  }
</style>
<div class="page-toolbar">
  <div>
    <h4 class="mb-1">Edit Profile</h4>
    <div class="text-muted">Update your account details and manage active rental trips.</div>
  </div>
</div>

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card list-card">
  <div class="card-header">
    <span class="header-title">Profile Details</span>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('profile.update') }}">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-12 col-lg-6">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="col-12 col-lg-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="col-12 col-lg-6">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+94 ...">
        </div>
      </div>

      <hr class="my-4">
      <div class="mb-2 fw-semibold">Change Password (Optional)</div>
      <div class="text-muted small mb-3">Only fill these fields if you want to set a new password.</div>

      <div class="row g-3">
        <div class="col-12 col-lg-4">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-control" autocomplete="current-password">
        </div>

        <div class="col-12 col-lg-4">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" autocomplete="new-password">
        </div>

        <div class="col-12 col-lg-4">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-dark">Save Profile</button>
      </div>
    </form>
  </div>
</div>

<div class="card list-card mt-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Active Rental Trips</span>
    <span class="small text-muted">{{ $activeTrips->count() }} active</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive profile-trips-wrap">
      <table class="table table-striped mb-0 align-middle profile-trips-table">
        <thead>
          <tr>
            <th style="min-width:110px;">Booking</th>
            <th style="min-width:180px;">Vehicle</th>
            <th style="min-width:170px;">Dates</th>
            <th style="min-width:110px;">Status</th>
            <th style="min-width:160px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($activeTrips as $trip)
            <tr>
              <td data-label="Booking">#{{ $trip->id }}</td>
              <td data-label="Vehicle">
                {{ $trip->car?->name ?: '-' }}<br>
                <span class="text-muted">{{ $trip->car?->plate_no ?: '-' }}</span>
              </td>
              <td data-label="Dates">
                {{ $trip->start_date?->format('Y-m-d') }} to {{ $trip->end_date?->format('Y-m-d') }}<br>
                <span class="text-muted">{{ $trip->rental_days }} day(s)</span>
              </td>
              <td data-label="Status">
                <span class="badge text-bg-{{ $trip->status === 'confirmed' ? 'primary' : 'secondary' }}">
                  {{ ucfirst($trip->status) }}
                </span>
              </td>
              <td data-label="Action" class="trip-actions-cell">
                @if(!$trip->handover_at && $trip->start_mileage === null)
                  <div class="trip-actions d-flex flex-wrap gap-2">
                    <a href="{{ route('profile.bookings.invoice-pdf', $trip) }}" class="btn btn-sm btn-outline-dark">Invoice</a>
                    <form method="post" action="{{ route('profile.bookings.cancel', $trip) }}" onsubmit="return confirm('Are you sure you want to cancel this trip? This action cannot be undone.');">
                      @csrf
                      <button class="btn btn-sm btn-outline-danger">Cancel Trip</button>
                    </form>
                  </div>
                @else
                  <div class="trip-actions d-flex flex-wrap gap-2 align-items-center">
                    <a href="{{ route('profile.bookings.invoice-pdf', $trip) }}" class="btn btn-sm btn-outline-dark">Invoice</a>
                    <span class="text-muted small">Trip already started</span>
                  </div>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center p-4 text-muted no-data">No active rental trips.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
