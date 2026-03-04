@extends('layouts.app')
@section('title', 'Car Details')

@section('content')
<style>
  .car-summary-meta {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
  }

  .car-summary-meta .badge {
    font-weight: 600;
  }
</style>
<div class="page-toolbar">
  <div class="mb-3">
    <h4 class="mb-1">Cars</h4>
    <div class="text-muted">{{ auth()->user()->isAdmin() ? 'Manage fleet information, tracker details, and current rental status.' : 'View only the vehicles assigned to your partner account.' }}</div>
  </div>
</div>
<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Car List</span>
    @if(auth()->user()->isAdmin())
      <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addCarModal">Add Car Details</button>
    @endif
  </div>
  <div class="card-body p-3">
    @forelse($cars as $car)
      <div class="card record-card mb-3">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
            <div>
              <h6 class="mb-1">{{ $car->name }}</h6>
              <div class="car-summary-meta">
                <span class="badge text-bg-light">{{ $car->plate_no }}</span>
                <span class="badge {{ $car->status === 'rented' ? 'text-bg-warning' : 'text-bg-success' }}">
                  {{ ucfirst($car->status) }}
                </span>
                @if($car->partner)
                  <span class="badge text-bg-primary">Partner: {{ $car->partner->name }}</span>
                @endif
                @if($car->make || $car->model)
                  <span class="badge text-bg-light">{{ trim(($car->make ?? '').' '.($car->model ?? '')) }}</span>
                @endif
                @if($car->year)
                  <span class="badge text-bg-light">{{ $car->year }}</span>
                @endif
                <span class="badge text-bg-light">
                  @if(($car->driver_mode ?? 'both') === 'with_driver_only')
                    With driver only
                  @elseif(($car->driver_mode ?? 'both') === 'without_driver_only')
                    Without driver only
                  @else
                    With / Without driver
                  @endif
                </span>
                @if($car->dagps_device_id)
                  <span class="badge text-bg-light">DAGPS: {{ $car->dagps_device_id }}</span>
                @endif
              </div>
              @if($car->note)
                <div class="small text-muted mt-2">Note: {{ $car->note }}</div>
              @endif
              @if($car->maintenance_last_service_date || $car->maintenance_next_service_date || $car->tracker_maintenance_mileage)
                <div class="small text-muted mt-2">
                  Maintenance:
                  @if($car->maintenance_last_service_date)
                    Last service {{ $car->maintenance_last_service_date->format('Y-m-d') }}
                  @endif
                  @if($car->maintenance_next_service_date)
                    | Next service {{ $car->maintenance_next_service_date->format('Y-m-d') }}
                  @endif
                  @if($car->tracker_maintenance_mileage)
                    | Interval {{ number_format($car->tracker_maintenance_mileage) }} km
                  @endif
                </div>
              @endif
            </div>

            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#viewCarModal{{ $car->id }}">
                See more details
              </button>
            @if(auth()->user()->isAdmin())
              <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#edit-car-{{ $car->id }}" aria-expanded="false" aria-controls="edit-car-{{ $car->id }}">
                  Edit details
                </button>
                <form method="post" action="{{ route('cars.destroy', $car) }}" onsubmit="return confirm('Delete this car?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </div>
            @endif
            </div>
          </div>

          @if(auth()->user()->isAdmin())
            <div class="collapse mt-3" id="edit-car-{{ $car->id }}">
              <form method="post" action="{{ route('cars.update', $car) }}">
                @csrf
                @method('PUT')

              <div class="row g-2">
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Display Name</label>
                  <input type="text" name="name" class="form-control form-control-sm" value="{{ $car->name }}" required>
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Plate Number</label>
                  <input type="text" name="plate_no" class="form-control form-control-sm" value="{{ $car->plate_no }}" required>
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Status</label>
                  <select name="status" class="form-select form-select-sm" required>
                    <option value="available" @selected($car->status === 'available')>Available</option>
                    <option value="rented" @selected($car->status === 'rented')>Rented</option>
                  </select>
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Partner</label>
                  <select name="partner_user_id" class="form-select form-select-sm">
                    <option value="">No partner</option>
                    @foreach($partners as $partner)
                      <option value="{{ $partner->id }}" @selected((int) $car->partner_user_id === (int) $partner->id)>{{ $partner->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row g-2 mt-1">
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Make</label>
                  <input type="text" name="make" class="form-control form-control-sm" value="{{ $car->make }}">
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Model</label>
                  <input type="text" name="model" class="form-control form-control-sm" value="{{ $car->model }}">
                </div>
                <div class="col-12 col-lg-2">
                  <label class="form-label small mb-1">Year</label>
                  <input type="number" name="year" min="1990" class="form-control form-control-sm" value="{{ $car->year }}">
                </div>
                <div class="col-12 col-lg-2">
                  <label class="form-label small mb-1">Color</label>
                  <input type="text" name="color" class="form-control form-control-sm" value="{{ $car->color }}">
                </div>
                <div class="col-12 col-lg-2">
                  <label class="form-label small mb-1">Fuel</label>
                  <input type="text" name="fuel_type" class="form-control form-control-sm" value="{{ $car->fuel_type }}">
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Transmission</label>
                  <input type="text" name="transmission" class="form-control form-control-sm" value="{{ $car->transmission }}">
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Driver Mode</label>
                  <select name="driver_mode" class="form-select form-select-sm" required>
                    <option value="both" @selected(($car->driver_mode ?? 'both') === 'both')>With or Without Driver</option>
                    <option value="with_driver_only" @selected(($car->driver_mode ?? 'both') === 'with_driver_only')>With Driver Only</option>
                    <option value="without_driver_only" @selected(($car->driver_mode ?? 'both') === 'without_driver_only')>Without Driver Only</option>
                  </select>
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">DAGPS Device ID</label>
                  <input type="text" name="dagps_device_id" class="form-control form-control-sm" value="{{ $car->dagps_device_id }}">
                </div>
                <div class="col-12 col-lg-6">
                  <label class="form-label small mb-1">Note</label>
                  <input type="text" name="note" class="form-control form-control-sm" value="{{ $car->note }}">
                </div>
              </div>

              <hr class="my-3">
              <div class="small text-muted mb-2">Tracker details</div>

              <div class="row g-2">
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Device Name</label>
                  <input type="text" name="tracker_device_name" class="form-control form-control-sm" value="{{ $car->tracker_device_name }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Device Type</label>
                  <input type="text" name="tracker_device_type" class="form-control form-control-sm" value="{{ $car->tracker_device_type }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">IMEI</label>
                  <input type="text" name="tracker_imei" class="form-control form-control-sm" value="{{ $car->tracker_imei }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">SIM</label>
                  <input type="text" name="tracker_sim" class="form-control form-control-sm" value="{{ $car->tracker_sim }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">ICCID</label>
                  <input type="text" name="tracker_iccid" class="form-control form-control-sm" value="{{ $car->tracker_iccid }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Contact Name</label>
                  <input type="text" name="tracker_contact_name" class="form-control form-control-sm" value="{{ $car->tracker_contact_name }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Contact Number</label>
                  <input type="text" name="tracker_contact_number" class="form-control form-control-sm" value="{{ $car->tracker_contact_number }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Activation Date</label>
                  <input type="datetime-local" name="tracker_activation_date" class="form-control form-control-sm" value="{{ optional($car->tracker_activation_date)->format('Y-m-d\\TH:i') }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Expiry Time</label>
                  <input type="text" name="tracker_expiry_time" class="form-control form-control-sm" value="{{ $car->tracker_expiry_time }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Insurance Expires</label>
                  <input type="date" name="tracker_insurance_expires" class="form-control form-control-sm" value="{{ optional($car->tracker_insurance_expires)->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">License Expires</label>
                  <input type="date" name="tracker_license_expires" class="form-control form-control-sm" value="{{ optional($car->tracker_license_expires)->format('Y-m-d') }}">
                </div>
              </div>

              <hr class="my-3">
              <div class="small text-muted mb-2">Maintenance details</div>

              <div class="row g-2">
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Service Interval (KM)</label>
                  <input type="number" min="0" name="tracker_maintenance_mileage" class="form-control form-control-sm" value="{{ $car->tracker_maintenance_mileage }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Last Service Date</label>
                  <input type="date" name="maintenance_last_service_date" class="form-control form-control-sm" value="{{ optional($car->maintenance_last_service_date)->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Last Service Mileage</label>
                  <input type="number" min="0" name="maintenance_last_service_mileage" class="form-control form-control-sm" value="{{ $car->maintenance_last_service_mileage }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Next Service Date</label>
                  <input type="date" name="maintenance_next_service_date" class="form-control form-control-sm" value="{{ optional($car->maintenance_next_service_date)->format('Y-m-d') }}">
                </div>
                <div class="col-12">
                  <label class="form-label small mb-1">Maintenance Notes</label>
                  <textarea name="maintenance_note" class="form-control form-control-sm" rows="2">{{ $car->maintenance_note }}</textarea>
                </div>
              </div>

                <div class="mt-3">
                  <button class="btn btn-sm btn-dark">Save Changes</button>
                </div>
              </form>
            </div>
          @endif
        </div>
      </div>

      <div class="modal fade" id="viewCarModal{{ $car->id }}" tabindex="-1" aria-labelledby="viewCarModalLabel{{ $car->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="viewCarModalLabel{{ $car->id }}">{{ $car->name }} Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Plate Number</div>
                  <div>{{ $car->plate_no }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Status</div>
                  <div>{{ ucfirst($car->status) }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Partner</div>
                  <div>{{ $car->partner?->name ?: 'No partner assigned' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">DAGPS Device ID</div>
                  <div>{{ $car->dagps_device_id ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Make</div>
                  <div>{{ $car->make ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Model</div>
                  <div>{{ $car->model ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Year</div>
                  <div>{{ $car->year ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Color</div>
                  <div>{{ $car->color ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Fuel Type</div>
                  <div>{{ $car->fuel_type ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Transmission</div>
                  <div>{{ $car->transmission ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Driver Mode</div>
                  <div>
                    @if(($car->driver_mode ?? 'both') === 'with_driver_only')
                      With driver only
                    @elseif(($car->driver_mode ?? 'both') === 'without_driver_only')
                      Without driver only
                    @else
                      With or without driver
                    @endif
                  </div>
                </div>
              </div>

              <hr class="my-3">
              <div class="fw-semibold mb-2">Tracker Details</div>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Device Name</div>
                  <div>{{ $car->tracker_device_name ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Device Type</div>
                  <div>{{ $car->tracker_device_type ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">IMEI</div>
                  <div>{{ $car->tracker_imei ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">SIM</div>
                  <div>{{ $car->tracker_sim ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">ICCID</div>
                  <div>{{ $car->tracker_iccid ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Contact Name</div>
                  <div>{{ $car->tracker_contact_name ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Contact Number</div>
                  <div>{{ $car->tracker_contact_number ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Activation Date</div>
                  <div>{{ optional($car->tracker_activation_date)->format('Y-m-d H:i') ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Expiry Time</div>
                  <div>{{ $car->tracker_expiry_time ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Insurance Expires</div>
                  <div>{{ optional($car->tracker_insurance_expires)->format('Y-m-d') ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">License Expires</div>
                  <div>{{ optional($car->tracker_license_expires)->format('Y-m-d') ?: '-' }}</div>
                </div>
              </div>

              <hr class="my-3">
              <div class="fw-semibold mb-2">Maintenance Details</div>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Service Interval (KM)</div>
                  <div>{{ $car->tracker_maintenance_mileage ? number_format($car->tracker_maintenance_mileage) . ' km' : '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Last Service Date</div>
                  <div>{{ optional($car->maintenance_last_service_date)->format('Y-m-d') ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Last Service Mileage</div>
                  <div>{{ $car->maintenance_last_service_mileage ? number_format($car->maintenance_last_service_mileage) . ' km' : '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Next Service Date</div>
                  <div>{{ optional($car->maintenance_next_service_date)->format('Y-m-d') ?: '-' }}</div>
                </div>
                <div class="col-12">
                  <div class="small text-muted">Maintenance Notes</div>
                  <div>{{ $car->maintenance_note ?: '-' }}</div>
                </div>
                <div class="col-12">
                  <div class="small text-muted">General Note</div>
                  <div>{{ $car->note ?: '-' }}</div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center p-4 text-muted">{{ auth()->user()->isAdmin() ? 'No cars yet. Add your first car.' : 'No vehicles are assigned to your partner account yet.' }}</div>
    @endforelse
  </div>
</div>

@if(auth()->user()->isAdmin())
  <div class="card list-card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="header-title">Vehicle Pricing Chart</span>
      <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addVehiclePricingModal">Add Pricing</button>
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
            <th>Driver Cost / Day</th>
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
                <td>LKR {{ number_format((float) $vehiclePricing->driver_cost_per_day, 2) }}</td>
                <td>LKR {{ number_format((float) $vehiclePricing->extra_km_rate, 2) }}</td>
                <td>{{ $vehiclePricing->note ?: '-' }}</td>
                <td class="text-nowrap">
                  <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editVehiclePricingModal{{ $vehiclePricing->id }}">Edit</button>
                  <form method="post" action="{{ route('vehicle-pricings.destroy', $vehiclePricing) }}" class="d-inline" onsubmit="return confirm('Delete this pricing chart?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center p-4 text-muted">No pricing chart rows yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endif

@if(auth()->user()->isAdmin())
  <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCarModalLabel">Add Car Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('cars.store') }}">
          @csrf
          <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Display Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Alto 1" required>
          </div>
          <div class="row g-2">
            <div class="col-12 col-md-6">
              <label class="form-label">Make</label>
              <input type="text" name="make" class="form-control" value="{{ old('make', 'Suzuki') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Model</label>
              <input type="text" name="model" class="form-control" value="{{ old('model', 'Alto') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Year</label>
              <input type="number" name="year" min="1990" class="form-control" value="{{ old('year') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Color</label>
              <input type="text" name="color" class="form-control" value="{{ old('color') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Fuel</label>
              <input type="text" name="fuel_type" class="form-control" value="{{ old('fuel_type', 'Petrol') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Transmission</label>
              <input type="text" name="transmission" class="form-control" value="{{ old('transmission', 'Manual') }}">
            </div>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Driver Mode</label>
            <select name="driver_mode" class="form-select" required>
              <option value="both" @selected(old('driver_mode', 'both') === 'both')>With or Without Driver</option>
              <option value="with_driver_only" @selected(old('driver_mode') === 'with_driver_only')>With Driver Only</option>
              <option value="without_driver_only" @selected(old('driver_mode') === 'without_driver_only')>Without Driver Only</option>
            </select>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Partner</label>
            <select name="partner_user_id" class="form-select">
              <option value="">No partner</option>
              @foreach($partners as $partner)
                <option value="{{ $partner->id }}" @selected((string) old('partner_user_id') === (string) $partner->id)>{{ $partner->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Plate Number</label>
            <input type="text" name="plate_no" class="form-control" value="{{ old('plate_no') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">DAGPS Device ID</label>
            <input type="text" name="dagps_device_id" class="form-control" value="{{ old('dagps_device_id') }}" placeholder="Optional">
          </div>
          <div class="row g-2">
            <div class="col-12 col-md-6">
              <label class="form-label">Device Name</label>
              <input type="text" name="tracker_device_name" class="form-control" value="{{ old('tracker_device_name') }}" placeholder="GT0630...">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Device Type</label>
              <input type="text" name="tracker_device_type" class="form-control" value="{{ old('tracker_device_type', 'GT06') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">IMEI</label>
              <input type="text" name="tracker_imei" class="form-control" value="{{ old('tracker_imei') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">SIM</label>
              <input type="text" name="tracker_sim" class="form-control" value="{{ old('tracker_sim') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">ICCID</label>
              <input type="text" name="tracker_iccid" class="form-control" value="{{ old('tracker_iccid') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Contact Name</label>
              <input type="text" name="tracker_contact_name" class="form-control" value="{{ old('tracker_contact_name') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Contact Number</label>
              <input type="text" name="tracker_contact_number" class="form-control" value="{{ old('tracker_contact_number') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Activation Date</label>
              <input type="datetime-local" name="tracker_activation_date" class="form-control" value="{{ old('tracker_activation_date') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Expiry Time</label>
              <input type="text" name="tracker_expiry_time" class="form-control" value="{{ old('tracker_expiry_time', 'Lifelong') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Insurance Expires</label>
              <input type="date" name="tracker_insurance_expires" class="form-control" value="{{ old('tracker_insurance_expires') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">License Expires</label>
              <input type="date" name="tracker_license_expires" class="form-control" value="{{ old('tracker_license_expires') }}">
            </div>
          </div>
          <hr class="my-3">
          <div class="small text-muted mb-2">Maintenance details</div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Service Interval (KM)</label>
              <input type="number" min="0" name="tracker_maintenance_mileage" class="form-control" value="{{ old('tracker_maintenance_mileage') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Last Service Date</label>
              <input type="date" name="maintenance_last_service_date" class="form-control" value="{{ old('maintenance_last_service_date') }}">
            </div>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Last Service Mileage</label>
              <input type="number" min="0" name="maintenance_last_service_mileage" class="form-control" value="{{ old('maintenance_last_service_mileage') }}">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Next Service Date</label>
              <input type="date" name="maintenance_next_service_date" class="form-control" value="{{ old('maintenance_next_service_date') }}">
            </div>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Maintenance Notes</label>
            <textarea name="maintenance_note" class="form-control" rows="3">{{ old('maintenance_note') }}</textarea>
          </div>
          <div class="mb-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="available" @selected(old('status') === 'available')>Available</option>
              <option value="rented" @selected(old('status') === 'rented')>Rented</option>
            </select>
          </div>
          <div class="mb-1">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control" value="{{ old('note') }}">
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-dark">Save Car</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif

@if(auth()->user()->isAdmin())
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
              <label class="form-label">Driver Cost Per Day</label>
              <input type="number" name="driver_cost_per_day" min="0" step="0.01" class="form-control" value="{{ old('driver_cost_per_day', 0) }}" required>
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
                <label class="form-label">Driver Cost Per Day</label>
                <input type="number" name="driver_cost_per_day" min="0" step="0.01" class="form-control" value="{{ $vehiclePricing->driver_cost_per_day }}" required>
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
@endif
@endsection
