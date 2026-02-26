@extends('layouts.app')
@section('title', 'Rent Requests')

@section('content')
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Rent Requests</h4>
    <div class="text-muted">Review customer rent-on-request submissions and accept valid requests.</div>
  </div>
</div>

<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Request List</span>
    <span class="small text-muted">Total: {{ $rentRequests->total() }}</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th style="min-width:130px;">Received</th>
            <th style="min-width:160px;">Customer</th>
            <th style="min-width:180px;">Vehicle</th>
            <th style="min-width:140px;">Start Date</th>
            <th style="min-width:140px;">End Date</th>
            <th style="min-width:190px;">Start Location</th>
            <th style="min-width:190px;">End Location</th>
            <th style="min-width:190px;">Availability Check</th>
            <th style="min-width:120px;">Status</th>
            <th style="min-width:240px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rentRequests as $requestItem)
            <tr>
              <td>{{ $requestItem->created_at?->format('Y-m-d H:i') }}</td>
              <td>
                <strong>{{ $requestItem->name }}</strong><br>
                <span class="text-muted">{{ $requestItem->phone ?: '-' }}</span><br>
                <span class="text-muted">{{ $requestItem->email ?: '-' }}</span>
              </td>
              <td>
                {{ $requestItem->car_name ?: ($requestItem->car?->name ?? '-') }}<br>
                <span class="text-muted">{{ $requestItem->plate_no ?: ($requestItem->car?->plate_no ?? '-') }}</span>
              </td>
              <td>
                {{ $requestItem->start_date?->format('Y-m-d') ?: '-' }}
              </td>
              <td>
                {{ $requestItem->end_date?->format('Y-m-d') ?: '-' }}
              </td>
              <td>
                {{ $requestItem->start_location ?: 'N/A' }}
              </td>
              <td>
                {{ $requestItem->end_location ?: 'N/A' }}
              </td>
              <td>
                @if(!$requestItem->is_checkable)
                  <span class="text-muted">Set vehicle and dates to check</span>
                @elseif($requestItem->is_available_for_period)
                  <span class="badge text-bg-success">Available in selected dates</span>
                @else
                  <span class="badge text-bg-danger">Not available in selected dates</span>
                @endif
              </td>
              <td>
                @if($requestItem->status === 'accepted')
                  <span class="badge text-bg-success">Accepted</span>
                @else
                  <span class="badge text-bg-warning">Pending</span>
                @endif
              </td>
              <td>
                <button
                  class="btn btn-sm btn-outline-dark mb-2"
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#editRentRequestModal{{ $requestItem->id }}"
                >
                  Edit
                </button>
                <br>
                @if($requestItem->status !== 'accepted')
                  <form method="post" action="{{ route('rent-requests.accept', $requestItem) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-dark" {{ $requestItem->is_checkable && !$requestItem->is_available_for_period ? 'disabled' : '' }}>
                      Accept
                    </button>
                  </form>
                  @if($requestItem->is_checkable && !$requestItem->is_available_for_period)
                    <div class="small text-danger mt-1">Cannot accept until dates/vehicle are available.</div>
                  @endif
                @else
                  <span class="text-muted small">
                    Accepted by {{ $requestItem->acceptedBy?->name ?: 'Admin' }}<br>
                    {{ $requestItem->accepted_at?->format('Y-m-d H:i') }}
                  </span>
                @endif
                <div class="mt-2">
                  <form method="post" action="{{ route('rent-requests.destroy', $requestItem) }}" class="d-inline" onsubmit="return confirm('Cancel this rent request?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      Cancel Request
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @if($requestItem->message)
              <tr>
                <td colspan="10">
                  <strong>Message:</strong> {{ $requestItem->message }}
                </td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="10" class="text-center p-4 text-muted">No rent requests yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($rentRequests->hasPages())
    <div class="card-footer bg-white">
      {{ $rentRequests->appends(request()->query())->links() }}
    </div>
  @endif
</div>

@foreach($rentRequests as $requestItem)
  <div class="modal fade" id="editRentRequestModal{{ $requestItem->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form method="post" action="{{ route('rent-requests.update', $requestItem) }}">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title">Edit Dates & Locations</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info mb-3">
              Only start/end dates and start/end locations can be changed.
            </div>

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Vehicle (Read-only)</label>
                <input type="text" class="form-control" value="{{ $requestItem->car_name ?: ($requestItem->car?->name ?? '-') }} - {{ $requestItem->plate_no ?: ($requestItem->car?->plate_no ?? '-') }}" readonly>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Customer (Read-only)</label>
                <input type="text" class="form-control" value="{{ $requestItem->name }} / {{ $requestItem->phone ?: '-' }}" readonly>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date" value="{{ $requestItem->start_date?->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date" value="{{ $requestItem->end_date?->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Start Location</label>
                <input type="text" class="form-control" name="start_location" value="{{ $requestItem->start_location }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">End Location</label>
                <input type="text" class="form-control" name="end_location" value="{{ $requestItem->end_location }}">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-dark">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach
@endsection
