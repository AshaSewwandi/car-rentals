@extends('layouts.app')
@section('title', 'Trip Requests')

@section('content')
@php
  $canManageData = auth()->user()->canManageData();
@endphp
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
    <h4 class="mb-1">Trip Requests</h4>
    <div class="text-muted">Review custom trip requests, assign a vehicle, and quote the cost.</div>
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
            <th style="min-width:90px;">Passengers</th>
            <th style="min-width:140px;">Start Date</th>
            <th style="min-width:140px;">End Date</th>
            <th style="min-width:190px;">Pickup Location</th>
            <th style="min-width:180px;">Vehicle</th>
            <th style="min-width:120px;">Status</th>
            @if($canManageData)
              <th style="min-width:240px;">Action</th>
            @endif
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
              <td data-label="Passengers">{{ $requestItem->passenger_count ?: '-' }}</td>
              <td data-label="Start Date">
                {{ $requestItem->start_date?->format('Y-m-d') ?: '-' }}
              </td>
              <td data-label="End Date">
                {{ $requestItem->end_date?->format('Y-m-d') ?: '-' }}
              </td>
              <td data-label="Pickup Location">
                {{ $requestItem->start_location ?: 'N/A' }}
              </td>
              <td data-label="Vehicle">
                @if($requestItem->vehicle)
                  {{ $requestItem->vehicle->name }}<br>
                  <span class="text-muted">{{ $requestItem->vehicle->plate_no }}</span>
                @else
                  <span class="text-muted">Not assigned yet</span>
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
              @if($canManageData)
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
                    <button
                      class="btn btn-sm btn-dark"
                      type="button"
                      data-bs-toggle="modal"
                      data-bs-target="#reviewRentRequestModal{{ $requestItem->id }}"
                    >
                      Review &amp; Assign
                    </button>
                  @else
                    <span class="text-muted small">
                      {{ $requestItem->status === 'converted' ? 'Converted' : 'Accepted' }} by {{ $requestItem->acceptedBy?->name ?: 'Admin' }}<br>
                      {{ $requestItem->accepted_at?->format('Y-m-d H:i') }}
                    </span>
                  @endif
                  <div class="mt-2">
                    <form method="post" action="{{ route('rent-requests.destroy', $requestItem) }}" class="d-inline" onsubmit="return confirm('Cancel this trip request?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        Cancel Request
                      </button>
                    </form>
                  </div>
                </td>
              @endif
            </tr>
            @if($requestItem->message || $requestItem->final_destination || !empty($requestItem->stops))
              <tr class="rr-message-row">
                <td colspan="{{ $canManageData ? 9 : 8 }}">
                  @if($requestItem->final_destination)
                    <div><strong>Final destination:</strong> {{ $requestItem->final_destination }}</div>
                  @endif
                  @if(!empty($requestItem->stops))
                    <div><strong>Stops:</strong> {{ implode(', ', $requestItem->stops) }}</div>
                  @endif
                  @if($requestItem->message)
                    <div><strong>Message:</strong> {{ $requestItem->message }}</div>
                  @endif
                </td>
              </tr>
            @endif
          @empty
            <tr>
              <td colspan="{{ $canManageData ? 9 : 8 }}" class="text-center p-4 text-muted no-data">No trip requests yet.</td>
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

@if($canManageData)
@foreach($rentRequests as $requestItem)
  <div class="modal fade" id="editRentRequestModal{{ $requestItem->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <form method="post" action="{{ route('rent-requests.update', $requestItem) }}">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title">Edit Trip Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Customer (Read-only)</label>
                <input type="text" class="form-control" value="{{ $requestItem->name }} / {{ $requestItem->phone ?: '-' }}" readonly>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Passengers</label>
                <input type="number" min="1" class="form-control" name="passenger_count" value="{{ $requestItem->passenger_count }}">
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
              <div class="col-12 col-md-6">
                <label class="form-label">Final Destination</label>
                <input type="text" class="form-control" name="final_destination" value="{{ $requestItem->final_destination }}">
              </div>
              <div class="col-12">
                <label class="form-label">Stops (one per line)</label>
                <textarea class="form-control" name="stops" rows="3">{{ implode("\n", $requestItem->stops ?? []) }}</textarea>
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

  @if(!in_array($requestItem->status, ['accepted', 'converted']))
    @php
      $rrDays = ($requestItem->start_date && $requestItem->end_date)
        ? max(1, (int) $requestItem->start_date->diffInDays($requestItem->end_date) + 1)
        : 1;
    @endphp
    <div class="modal fade" id="reviewRentRequestModal{{ $requestItem->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <form method="post" action="{{ route('rent-requests.accept', $requestItem) }}" class="rr-accept-form" data-days="{{ $rrDays }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Review &amp; Assign Vehicle</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-2">
                <div class="small text-muted">Trip</div>
                <div>{{ $requestItem->start_location ?: 'N/A' }} &rarr; {{ $requestItem->final_destination ?: 'N/A' }}</div>
                @if(!empty($requestItem->stops))
                  <div class="small text-muted">Stops: {{ implode(', ', $requestItem->stops) }}</div>
                @endif
                <div class="small text-muted">
                  {{ $requestItem->start_date?->format('Y-m-d') }} to {{ $requestItem->end_date?->format('Y-m-d') }}
                  &middot; {{ $rrDays }} day(s) &middot; {{ $requestItem->passenger_count }} passenger(s)
                </div>
              </div>

              <div class="row g-2">
                <div class="col-12 col-md-6">
                  <label class="form-label mb-1">Assign Vehicle</label>
                  <select name="vehicle_id" class="form-select rr-vehicle-select" required>
                    <option value="">Select a vehicle</option>
                    @foreach($vehicles as $vehicle)
                      <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->plate_no }})</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label mb-1">Hire or Rent</label>
                  <select name="order_type" class="form-select rr-order-type" required>
                    <option value="hire">Hire</option>
                    <option value="rent">Rent</option>
                  </select>
                </div>
                <div class="col-12 col-md-6 rr-driver-wrap">
                  <label class="form-label mb-1">Driver Option</label>
                  <select name="driver_option" class="form-select rr-driver-option">
                    <option value="without_driver">Self Drive</option>
                    <option value="with_driver">With Driver</option>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <label class="form-label mb-1">Payment Method</label>
                  <select name="payment_method" class="form-select rr-payment-method">
                    <option value="pay_later_bank">Bank Transfer</option>
                    <option value="pay_at_pickup_cash" selected>Cash at Pickup</option>
                  </select>
                </div>
              </div>

              <hr>
              <div class="text-muted mb-2 small">Suggested rates fill in automatically once a vehicle is selected — adjust any amount to set a custom price for this trip.</div>

              <div class="row g-2">
                <div class="col-6 col-md-3">
                  <label class="form-label mb-1">Daily Rate (LKR)</label>
                  <input type="number" step="0.01" min="0" name="daily_rate" class="form-control rr-daily-rate" required>
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label mb-1">Driver Rate / Day (LKR)</label>
                  <input type="number" step="0.01" min="0" name="driver_rate" class="form-control rr-driver-rate" value="0">
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label mb-1">Total Cost (LKR)</label>
                  <input type="number" step="0.01" min="0" name="total_amount" class="form-control rr-total-amount" required>
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label mb-1">Payment Status</label>
                  <select name="payment_status" class="form-select rr-payment-status">
                    <option value="pending" selected>Pending</option>
                    <option value="paid">Already Paid</option>
                  </select>
                </div>
              </div>

              <div class="row g-2 mt-1">
                <div class="col-6 col-md-4">
                  <label class="form-label mb-1">Partner Share (LKR)</label>
                  <input type="number" step="0.01" min="0" name="partner_share_amount" class="form-control rr-partner-share" value="0">
                </div>
                <div class="col-6 col-md-4">
                  <label class="form-label mb-1">Admin Share (LKR)</label>
                  <input type="number" step="0.01" min="0" name="admin_share_amount" class="form-control rr-admin-share" value="0">
                </div>
                <div class="col-12 col-md-4">
                  <label class="form-label mb-1">Note <span class="text-muted">(optional)</span></label>
                  <input type="text" name="note" class="form-control">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-dark">Accept &amp; Convert</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endforeach
@endif

@if($canManageData)
<script>
(function () {
  const vehiclePricing = @json($vehiclePricing);

  function setOptions(select, options) {
    const previousValue = select.value;
    select.innerHTML = '';
    options.forEach(([value, label]) => select.add(new Option(label, value)));
    if (options.some(([value]) => value === previousValue)) {
      select.value = previousValue;
    }
  }

  function recalcTotal(form) {
    const dailyRateInput = form.querySelector('.rr-daily-rate');
    const driverRateInput = form.querySelector('.rr-driver-rate');
    const driverOptionSelect = form.querySelector('.rr-driver-option');
    const totalAmountInput = form.querySelector('.rr-total-amount');
    if (!dailyRateInput || !totalAmountInput) return;

    const days = parseInt(form.dataset.days || '1', 10) || 1;
    const dailyRate = parseFloat(dailyRateInput.value) || 0;
    const driverRate = parseFloat(driverRateInput?.value) || 0;
    const withDriver = driverOptionSelect ? driverOptionSelect.value === 'with_driver' : false;
    totalAmountInput.value = ((dailyRate * days) + (withDriver ? driverRate * days : 0)).toFixed(2);
  }

  function applyVehicle(form) {
    const vehicleSelect = form.querySelector('.rr-vehicle-select');
    const orderTypeSelect = form.querySelector('.rr-order-type');
    const driverWrap = form.querySelector('.rr-driver-wrap');
    const driverOptionSelect = form.querySelector('.rr-driver-option');
    const dailyRateInput = form.querySelector('.rr-daily-rate');
    const driverRateInput = form.querySelector('.rr-driver-rate');
    const partnerShareInput = form.querySelector('.rr-partner-share');
    const adminShareInput = form.querySelector('.rr-admin-share');

    const info = vehiclePricing[vehicleSelect.value];
    if (!info) return;

    const orderTypeOptions = [];
    if (info.available_for_hire) orderTypeOptions.push(['hire', 'Hire']);
    if (info.available_for_rent) orderTypeOptions.push(['rent', 'Rent']);
    if (orderTypeSelect && orderTypeOptions.length) setOptions(orderTypeSelect, orderTypeOptions);

    if (driverWrap && driverOptionSelect) {
      if (info.driver_mode === 'with_driver_only') {
        setOptions(driverOptionSelect, [['with_driver', 'With Driver']]);
      } else if (info.driver_mode === 'without_driver_only') {
        setOptions(driverOptionSelect, [['without_driver', 'Self Drive']]);
      } else {
        setOptions(driverOptionSelect, [['without_driver', 'Self Drive'], ['with_driver', 'With Driver']]);
      }
    }

    if (dailyRateInput) dailyRateInput.value = info.daily_rate.toFixed(2);
    if (driverRateInput) driverRateInput.value = info.driver_rate.toFixed(2);

    recalcTotal(form);

    const total = parseFloat(form.querySelector('.rr-total-amount')?.value) || 0;
    if (partnerShareInput) partnerShareInput.value = (total * (info.partner_share_percentage / 100)).toFixed(2);
    if (adminShareInput) adminShareInput.value = (total * (info.admin_share_percentage / 100)).toFixed(2);
  }

  document.addEventListener('change', function (e) {
    const form = e.target.closest('.rr-accept-form');
    if (!form) return;

    if (e.target.classList.contains('rr-vehicle-select')) {
      applyVehicle(form);
    } else if (e.target.classList.contains('rr-driver-option')) {
      recalcTotal(form);
    }
  });

  document.addEventListener('input', function (e) {
    if (e.target.classList.contains('rr-daily-rate') || e.target.classList.contains('rr-driver-rate')) {
      const form = e.target.closest('.rr-accept-form');
      if (form) recalcTotal(form);
    }
  });
})();
</script>
@endif
@endsection
