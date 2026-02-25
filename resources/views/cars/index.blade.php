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
    <div class="text-muted">Manage fleet information, tracker details, and current rental status.</div>
  </div>
</div>
<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Car List</span>
    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addCarModal">Add Car Details</button>
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
                @if($car->make || $car->model)
                  <span class="badge text-bg-light">{{ trim(($car->make ?? '').' '.($car->model ?? '')) }}</span>
                @endif
                @if($car->year)
                  <span class="badge text-bg-light">{{ $car->year }}</span>
                @endif
                @if($car->dagps_device_id)
                  <span class="badge text-bg-light">DAGPS: {{ $car->dagps_device_id }}</span>
                @endif
              </div>
              @if($car->note)
                <div class="small text-muted mt-2">Note: {{ $car->note }}</div>
              @endif
            </div>

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
          </div>

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
                  <label class="form-label small mb-1">Maintenance Mileage (KM)</label>
                  <input type="number" min="0" name="tracker_maintenance_mileage" class="form-control form-control-sm" value="{{ $car->tracker_maintenance_mileage }}">
                </div>
              </div>

              <div class="mt-3">
                <button class="btn btn-sm btn-dark">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="text-center p-4 text-muted">No cars yet. Add your first car.</div>
    @endforelse
  </div>
</div>

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
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Maintenance Mileage (KM)</label>
            <input type="number" min="0" name="tracker_maintenance_mileage" class="form-control" value="{{ old('tracker_maintenance_mileage') }}">
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
@endsection
