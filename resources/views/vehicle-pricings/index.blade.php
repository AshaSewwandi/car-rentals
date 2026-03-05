@extends('layouts.app')
@section('title', 'Vehicle Pricing')

@section('content')
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Vehicle Pricing</h4>
    <div class="text-muted">Manage pricing by vehicle make and model, including per day KM and extra KM charge.</div>
  </div>
  <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addVehiclePricingModal">Add Pricing</button>
</div>

<div class="card list-card">
  <div class="card-header">
    <span class="header-title">Pricing Chart</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th>Make</th>
            <th>Model</th>
            <th>Per Day KM</th>
            <th>Per Day Amount</th>
            <th>Per Month Amount</th>
            <th>KM / Month</th>
            <th>Driver Cost / Day</th>
            <th>Driver Cost / Month</th>
            <th>Extra 1 KM Amount</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($vehiclePricings as $vehiclePricing)
            <tr>
              <td>{{ $vehiclePricing->make ?: '-' }}</td>
              <td>{{ $vehiclePricing->model }}</td>
              <td>{{ number_format((int) $vehiclePricing->per_day_km) }} km</td>
              <td>LKR {{ number_format((float) $vehiclePricing->per_day_amount, 2) }}</td>
              <td>LKR {{ number_format((float) ($vehiclePricing->per_month_amount ?? 0), 2) }}</td>
              <td>{{ number_format((int) ($vehiclePricing->per_month_km ?? ((int) $vehiclePricing->per_day_km * 30))) }} km</td>
              <td>LKR {{ number_format((float) $vehiclePricing->driver_cost_per_day, 2) }}</td>
              <td>LKR {{ number_format((float) ($vehiclePricing->driver_cost_per_month ?? 0), 2) }}</td>
              <td>LKR {{ number_format((float) $vehiclePricing->extra_km_rate, 2) }}</td>
              <td>{{ $vehiclePricing->note ?: '-' }}</td>
              <td class="text-nowrap">
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editVehiclePricingModal{{ $vehiclePricing->id }}">Edit</button>
                <form method="post" action="{{ route('vehicle-pricings.destroy', $vehiclePricing) }}" class="d-inline" onsubmit="return confirm('Delete this pricing row?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11" class="text-center p-4 text-muted">No pricing rows added yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="addVehiclePricingModal" tabindex="-1" aria-labelledby="addVehiclePricingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addVehiclePricingModalLabel">Add Vehicle Pricing</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('vehicle-pricings.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Make</label>
            <input type="text" name="make" class="form-control" value="{{ old('make') }}" placeholder="Optional">
          </div>
          <div class="mb-2">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control" value="{{ old('model') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Per Day KM Count</label>
            <input type="number" name="per_day_km" min="1" class="form-control" value="{{ old('per_day_km', 150) }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Per Day Amount</label>
            <input type="number" name="per_day_amount" min="0" step="0.01" class="form-control" value="{{ old('per_day_amount') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Per Month Amount</label>
            <input type="number" name="per_month_amount" min="0" step="0.01" class="form-control" value="{{ old('per_month_amount') }}" placeholder="Optional monthly package">
          </div>
          <div class="mb-2">
            <label class="form-label">KM Per Month</label>
            <input type="number" name="per_month_km" min="1" class="form-control" value="{{ old('per_month_km', 4500) }}">
          </div>
          <div class="mb-2">
            <label class="form-label">Driver Cost Per Day</label>
            <input type="number" name="driver_cost_per_day" min="0" step="0.01" class="form-control" value="{{ old('driver_cost_per_day', 0) }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Driver Cost Per Month</label>
            <input type="number" name="driver_cost_per_month" min="0" step="0.01" class="form-control" value="{{ old('driver_cost_per_month', 0) }}">
          </div>
          <div class="mb-2">
            <label class="form-label">Exceed 1 KM Charge</label>
            <input type="number" name="extra_km_rate" min="0" step="0.01" class="form-control" value="{{ old('extra_km_rate', 25) }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control" value="{{ old('note') }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Pricing</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($vehiclePricings as $vehiclePricing)
  <div class="modal fade" id="editVehiclePricingModal{{ $vehiclePricing->id }}" tabindex="-1" aria-labelledby="editVehiclePricingModalLabel{{ $vehiclePricing->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editVehiclePricingModalLabel{{ $vehiclePricing->id }}">Edit Vehicle Pricing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('vehicle-pricings.update', $vehiclePricing) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label">Make</label>
              <input type="text" name="make" class="form-control" value="{{ $vehiclePricing->make }}">
            </div>
            <div class="mb-2">
              <label class="form-label">Model</label>
              <input type="text" name="model" class="form-control" value="{{ $vehiclePricing->model }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Per Day KM Count</label>
              <input type="number" name="per_day_km" min="1" class="form-control" value="{{ $vehiclePricing->per_day_km }}" required>
            </div>
              <div class="mb-2">
                <label class="form-label">Per Day Amount</label>
                <input type="number" name="per_day_amount" min="0" step="0.01" class="form-control" value="{{ $vehiclePricing->per_day_amount }}" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Per Month Amount</label>
                <input type="number" name="per_month_amount" min="0" step="0.01" class="form-control" value="{{ $vehiclePricing->per_month_amount }}">
              </div>
              <div class="mb-2">
                <label class="form-label">KM Per Month</label>
                <input type="number" name="per_month_km" min="1" class="form-control" value="{{ $vehiclePricing->per_month_km ?? ((int) $vehiclePricing->per_day_km * 30) }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Driver Cost Per Day</label>
                <input type="number" name="driver_cost_per_day" min="0" step="0.01" class="form-control" value="{{ $vehiclePricing->driver_cost_per_day }}" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Driver Cost Per Month</label>
                <input type="number" name="driver_cost_per_month" min="0" step="0.01" class="form-control" value="{{ $vehiclePricing->driver_cost_per_month }}">
              </div>
              <div class="mb-2">
                <label class="form-label">Exceed 1 KM Charge</label>
                <input type="number" name="extra_km_rate" min="0" step="0.01" class="form-control" value="{{ $vehiclePricing->extra_km_rate }}" required>
              </div>
            <div class="mb-1">
              <label class="form-label">Note</label>
              <input type="text" name="note" class="form-control" value="{{ $vehiclePricing->note }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-dark">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach
@endsection
