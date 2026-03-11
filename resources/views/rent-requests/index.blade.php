@extends('layouts.app')
@section('title', 'Rent Requests')

@section('content')
<style>
  .rr-table td,
  .rr-table th {
    vertical-align: top;
  }

  @media (max-width: 920px) {
    .list-card .card-header {
      flex-direction: column;
      align-items: flex-start !important;
      gap: .3rem;
    }

    .rr-table,
    .rr-table thead,
    .rr-table tbody,
    .rr-table th,
    .rr-table td,
    .rr-table tr {
      display: block;
      width: 100%;
    }

    .rr-table thead {
      display: none;
    }

    .rr-table tbody tr {
      border: 1px solid #dbe6f3;
      border-radius: 12px;
      margin: .65rem;
      width: calc(100% - 1.3rem);
      box-sizing: border-box;
      background: #fff;
      overflow: hidden;
    }

    .rr-table tbody td {
      position: relative;
      padding: .62rem .65rem .62rem 44%;
      min-height: 44px;
      border-top: 1px solid #edf3fb;
      box-sizing: border-box;
      max-width: 100%;
      word-break: break-word;
      overflow-wrap: anywhere;
    }

    .rr-table tbody td:first-child {
      border-top: 0;
    }

    .rr-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .65rem;
      top: .62rem;
      width: calc(44% - .95rem);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .04em;
      line-height: 1.2;
    }

    .rr-table tbody td.cell-actions .btn,
    .rr-table tbody td.cell-actions form,
    .rr-table tbody td.cell-actions .d-inline {
      width: 100%;
    }

    .rr-table tbody td.cell-actions .btn {
      width: 100%;
      margin-bottom: .42rem !important;
    }

    .rr-table tbody td.cell-actions br {
      display: none;
    }

    .rr-table tbody tr.rr-message-row td {
      padding: .72rem .75rem;
    }

    .rr-table tbody tr.rr-message-row td::before {
      display: none;
    }

    .rr-table tbody td.no-data {
      padding: .95rem .85rem !important;
      text-align: center !important;
    }

    .rr-table tbody td.no-data::before {
      display: none;
    }
  }
</style>
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
      <table class="table table-striped mb-0 align-middle rr-table">
        <thead>
          <tr>
            <th style="min-width:130px;">Received</th>
            <th style="min-width:160px;">Customer</th>
            <th style="min-width:180px;">Vehicle</th>
            <th style="min-width:140px;">Start Date</th>
            <th style="min-width:140px;">End Date</th>
            <th style="min-width:190px;">Pickup Location</th>
            <th style="min-width:190px;">Availability Check</th>
            <th style="min-width:120px;">Status</th>
            <th style="min-width:240px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rentRequests as $requestItem)
            <tr>
              <td data-label="Received">{{ $requestItem->created_at?->format('Y-m-d H:i') }}</td>
              <td data-label="Customer">
                <strong>{{ $requestItem->name }}</strong><br>
                <span class="text-muted">{{ $requestItem->phone ?: '-' }}</span><br>
                <span class="text-muted">{{ $requestItem->email ?: '-' }}</span>
              </td>
              <td data-label="Vehicle">
                {{ $requestItem->car_name ?: ($requestItem->car?->name ?? '-') }}<br>
                <span class="text-muted">{{ $requestItem->plate_no ?: ($requestItem->car?->plate_no ?? '-') }}</span>
              </td>
              <td data-label="Start Date">
                {{ $requestItem->start_date?->format('Y-m-d') ?: '-' }}
              </td>
              <td data-label="End Date">
                {{ $requestItem->end_date?->format('Y-m-d') ?: '-' }}
              </td>
              <td data-label="Pickup Location">
                {{ $requestItem->start_location ?: 'N/A' }}
              </td>
              <td data-label="Availability Check">
                @if(!$requestItem->is_checkable)
                  <span class="text-muted">Set vehicle and dates to check</span>
                @elseif($requestItem->is_available_for_period)
                  <span class="badge text-bg-success">Available in selected dates</span>
                @else
                  <span class="badge text-bg-danger">Not available in selected dates</span>
                @endif
              </td>
              <td data-label="Status">
                @if($requestItem->status === 'converted')
                  <span class="badge text-bg-primary">Converted</span>
                @elseif($requestItem->status === 'accepted')
                  <span class="badge text-bg-success">Accepted</span>
                @else
                  <span class="badge text-bg-warning">Pending</span>
                @endif
              </td>
              <td data-label="Action" class="cell-actions">
                <button
                  class="btn btn-sm btn-outline-dark mb-2"
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#editRentRequestModal{{ $requestItem->id }}"
                >
                  Edit
                </button>
                <br>
                @if(!in_array($requestItem->status, ['accepted', 'converted']))
                  <form method="post" action="{{ route('rent-requests.accept', $requestItem) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-dark" {{ $requestItem->is_checkable && !$requestItem->is_available_for_period ? 'disabled' : '' }}>
                      Accept & Convert
                    </button>
                  </form>
                  @if($requestItem->is_checkable && !$requestItem->is_available_for_period)
                    <div class="small text-danger mt-1">Cannot accept until dates/vehicle are available.</div>
                  @endif
                @else
                  <span class="text-muted small">
                    {{ $requestItem->status === 'converted' ? 'Converted' : 'Accepted' }} by {{ $requestItem->acceptedBy?->name ?: 'Admin' }}<br>
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
              <tr class="rr-message-row">
                <td colspan="9">
                  <strong>Message:</strong> {{ $requestItem->message }}
                </td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="9" class="text-center p-4 text-muted no-data">No rent requests yet.</td>
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
            <h5 class="modal-title">Edit Dates & Pickup Location</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="alert alert-info mb-3">
              Only start/end dates and pickup location can be changed.
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
                <label class="form-label">Pickup Location</label>
                <input type="text" class="form-control" name="start_location" value="{{ $requestItem->start_location }}">
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
