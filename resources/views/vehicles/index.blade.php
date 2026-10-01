@extends('layouts.app')
@section('title', 'Vehicle Details')

@section('content')
<style>
  .vehicle-summary-meta {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
  }

  .vehicle-summary-meta .badge {
    font-weight: 600;
  }

  .vehicle-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    align-items: flex-start;
  }

  .vehicle-actions-admin {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
  }

  .vehicle-pricing-wrap {
    overflow-x: auto;
  }

  @media (max-width: 920px) {
    .vehicle-actions,
    .vehicle-actions-admin {
      width: 100%;
    }

    .vehicle-actions {
      display: grid;
      grid-template-columns: 1fr;
      gap: .5rem;
    }

    .vehicle-actions-admin {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: .5rem;
    }

    .vehicle-actions .btn,
    .vehicle-actions-admin .btn,
    .vehicle-actions-admin form {
      width: 100%;
    }

    .vehicle-actions-admin form .btn {
      height: 100%;
    }

    .vehicle-pricing-wrap {
      overflow: visible;
    }

    .vehicle-pricing-table,
    .vehicle-pricing-table thead,
    .vehicle-pricing-table tbody,
    .vehicle-pricing-table th,
    .vehicle-pricing-table td,
    .vehicle-pricing-table tr {
      display: block;
      width: 100%;
    }

    .vehicle-pricing-table thead {
      display: none;
    }

    .vehicle-pricing-table tbody tr {
      border: 1px solid #dbe6f3;
      border-radius: 12px;
      margin: .65rem;
      background: #fff;
      overflow: hidden;
      box-sizing: border-box;
      width: calc(100% - 1.3rem);
    }

    .vehicle-pricing-table tbody td {
      position: relative;
      border-top: 1px solid #edf3fb;
      padding: .62rem .7rem .62rem 45%;
      min-height: 42px;
      word-break: break-word;
      overflow-wrap: anywhere;
      box-sizing: border-box;
    }

    .vehicle-pricing-table tbody td:first-child {
      border-top: 0;
    }

    .vehicle-pricing-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .7rem;
      top: .62rem;
      width: calc(45% - 1rem);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .04em;
      line-height: 1.2;
    }

    .vehicle-pricing-table tbody td.pricing-actions {
      padding-left: .7rem;
      display: flex;
      flex-direction: column;
      gap: .42rem;
      align-items: stretch;
    }

    .vehicle-pricing-table tbody td.pricing-actions::before {
      position: static;
      display: block;
      width: auto;
      margin-bottom: .4rem;
    }

    .vehicle-pricing-table tbody td.pricing-actions .btn,
    .vehicle-pricing-table tbody td.pricing-actions form,
    .vehicle-pricing-table tbody td.pricing-actions .d-inline {
      width: 100%;
    }

    .vehicle-pricing-table tbody td.pricing-actions form,
    .vehicle-pricing-table tbody td.pricing-actions .d-inline {
      display: block !important;
      margin: 0 !important;
    }

    .vehicle-pricing-table tbody td.pricing-actions .btn {
      margin: 0 !important;
    }

    .vehicle-pricing-table tbody td.no-data {
      padding: 1rem .8rem !important;
      text-align: center;
    }

    .vehicle-pricing-table tbody td.no-data::before {
      display: none;
    }

    .vehicle-pricing-table tbody td.no-data {
      border-top: 0;
    }
  }
</style>
<div class="page-toolbar">
  <div class="mb-3">
    <h4 class="mb-1">Vehicles</h4>
    <div class="text-muted">{{ auth()->user()->canManageData() ? 'Manage fleet information, tracker details, and current rental status.' : 'View only the vehicles assigned to your partner account.' }}</div>
  </div>
</div>
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
  <div class="alert alert-danger">
    <div class="fw-semibold mb-1">Please fix the following:</div>
    <ul class="mb-0 ps-3">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Vehicle List</span>
    @if(auth()->user()->canManageData())
      <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">Add Vehicle Details</button>
    @endif
  </div>
  <div class="card-body p-3">
    @forelse($vehicles as $vehicle)
      <div class="card record-card mb-3">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
            <div>
              <h6 class="mb-1">{{ $vehicle->name }}</h6>
              <div class="vehicle-summary-meta">
                <span class="badge text-bg-light">{{ $vehicle->plate_no }}</span>
                <span class="badge {{ $vehicle->status === 'rented' ? 'text-bg-warning' : 'text-bg-success' }}">
                  {{ ucfirst($vehicle->status) }}
                </span>
                @if($vehicle->partner)
                  <span class="badge text-bg-primary">Partner: {{ $vehicle->partner->name }}</span>
                @endif
                @if($vehicle->make || $vehicle->model)
                  <span class="badge text-bg-light">{{ trim(($vehicle->make ?? '').' '.($vehicle->model ?? '')) }}</span>
                @endif
                @if($vehicle->year)
                  <span class="badge text-bg-light">{{ $vehicle->year }}</span>
                @endif
                <span class="badge text-bg-light">
                  @if(($vehicle->driver_mode ?? 'both') === 'with_driver_only')
                    With driver only
                  @elseif(($vehicle->driver_mode ?? 'both') === 'without_driver_only')
                    Without driver only
                  @else
                    With / Without driver
                  @endif
                </span>
                <span class="badge {{ $vehicle->allow_long_term ? 'text-bg-info' : 'text-bg-secondary' }}">
                  {{ $vehicle->allow_long_term ? 'Long-term enabled' : 'No long-term' }}
                </span>
                <span class="badge {{ $vehicle->available_for_hire ? 'text-bg-info' : 'text-bg-secondary' }}">
                  {{ $vehicle->available_for_hire ? 'Hire enabled' : 'Not for hire' }}
                </span>
                <span class="badge {{ $vehicle->available_for_rent ? 'text-bg-info' : 'text-bg-secondary' }}">
                  {{ $vehicle->available_for_rent ? 'Rent enabled' : 'Not for rent' }}
                </span>
                @if($vehicle->dagps_device_id)
                  <span class="badge text-bg-light">DAGPS: {{ $vehicle->dagps_device_id }}</span>
                @endif
              </div>
              @if($vehicle->note)
                <div class="small text-muted mt-2">Note: {{ $vehicle->note }}</div>
              @endif
              @if($vehicle->images->isNotEmpty())
                <div class="small text-muted mt-1">{{ $vehicle->images->count() }} image(s) uploaded</div>
              @endif
              @if($vehicle->maintenance_last_service_date || $vehicle->maintenance_next_service_date || $vehicle->tracker_maintenance_mileage)
                <div class="small text-muted mt-2">
                  Maintenance:
                  @if($vehicle->maintenance_last_service_date)
                    Last service {{ $vehicle->maintenance_last_service_date->format('Y-m-d') }}
                  @endif
                  @if($vehicle->maintenance_next_service_date)
                    | Next service {{ $vehicle->maintenance_next_service_date->format('Y-m-d') }}
                  @endif
                  @if($vehicle->tracker_maintenance_mileage)
                    | Interval {{ number_format($vehicle->tracker_maintenance_mileage) }} km
                  @endif
                </div>
              @endif
            </div>

            <div class="vehicle-actions">
              <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#viewVehicleModal{{ $vehicle->id }}">
                See more details
              </button>
            @if(auth()->user()->canManageData())
              <div class="vehicle-actions-admin">
                <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="modal" data-bs-target="#editVehicleModal{{ $vehicle->id }}">
                  Edit details
                </button>
                <form method="post" action="{{ route('vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Delete this vehicle?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </div>
            @endif
            </div>
          </div>

          @if(auth()->user()->canManageData())
            <div class="modal fade" id="editVehicleModal{{ $vehicle->id }}" tabindex="-1" aria-labelledby="editVehicleModalLabel{{ $vehicle->id }}" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editVehicleModalLabel{{ $vehicle->id }}">Edit {{ $vehicle->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form method="post" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

              <div class="row g-2">
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Display Name</label>
                  <input type="text" name="name" class="form-control form-control-sm" value="{{ $vehicle->name }}" required>
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Plate Number</label>
                  <input type="text" name="plate_no" class="form-control form-control-sm" value="{{ $vehicle->plate_no }}" required>
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Status</label>
                  <select name="status" class="form-select form-select-sm" required>
                    <option value="available" @selected($vehicle->status === 'available')>Available</option>
                    <option value="rented" @selected($vehicle->status === 'rented')>Rented</option>
                  </select>
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Partner</label>
                  <select name="partner_user_id" class="form-select form-select-sm">
                    <option value="">No partner</option>
                    @foreach($partners as $partner)
                      <option value="{{ $partner->id }}" @selected((int) $vehicle->partner_user_id === (int) $partner->id)>{{ $partner->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row g-2 mt-1">
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Make</label>
                  <input type="text" name="make" class="form-control form-control-sm" value="{{ $vehicle->make }}">
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Model</label>
                  <input type="text" name="model" class="form-control form-control-sm" value="{{ $vehicle->model }}">
                </div>
                <div class="col-12 col-lg-2">
                  <label class="form-label small mb-1">Year</label>
                  <input type="number" name="year" min="1990" class="form-control form-control-sm" value="{{ $vehicle->year }}">
                </div>
                <div class="col-12 col-lg-2">
                  <label class="form-label small mb-1">Color</label>
                  <input type="text" name="color" class="form-control form-control-sm" value="{{ $vehicle->color }}">
                </div>
                <div class="col-12 col-lg-2">
                  <label class="form-label small mb-1">Fuel</label>
                  <input type="text" name="fuel_type" class="form-control form-control-sm" value="{{ $vehicle->fuel_type }}">
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Transmission</label>
                  <input type="text" name="transmission" class="form-control form-control-sm" value="{{ $vehicle->transmission }}">
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Driver Mode</label>
                  <select name="driver_mode" class="form-select form-select-sm" required>
                    <option value="both" @selected(($vehicle->driver_mode ?? 'both') === 'both')>With or Without Driver</option>
                    <option value="with_driver_only" @selected(($vehicle->driver_mode ?? 'both') === 'with_driver_only')>With Driver Only</option>
                    <option value="without_driver_only" @selected(($vehicle->driver_mode ?? 'both') === 'without_driver_only')>Without Driver Only</option>
                  </select>
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Long-Term Rental</label>
                  <select name="allow_long_term" class="form-select form-select-sm" required>
                    <option value="1" @selected($vehicle->allow_long_term)>Yes</option>
                    <option value="0" @selected(!$vehicle->allow_long_term)>No</option>
                  </select>
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Available for Hire</label>
                  <select name="available_for_hire" class="form-select form-select-sm" required>
                    <option value="1" @selected($vehicle->available_for_hire)>Yes</option>
                    <option value="0" @selected(!$vehicle->available_for_hire)>No</option>
                  </select>
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">Available for Rent</label>
                  <select name="available_for_rent" class="form-select form-select-sm" required>
                    <option value="1" @selected($vehicle->available_for_rent)>Yes</option>
                    <option value="0" @selected(!$vehicle->available_for_rent)>No</option>
                  </select>
                </div>
                <div class="col-12 col-lg-3">
                  <label class="form-label small mb-1">DAGPS Device ID</label>
                  <input type="text" name="dagps_device_id" class="form-control form-control-sm" value="{{ $vehicle->dagps_device_id }}">
                </div>
                <div class="col-12 col-lg-6">
                  <label class="form-label small mb-1">Note</label>
                  <input type="text" name="note" class="form-control form-control-sm" value="{{ $vehicle->note }}">
                </div>
                <div class="col-12">
                  <label class="form-label small mb-1">Add Vehicle Images</label>
                  <input type="file" name="images[]" class="form-control form-control-sm" multiple accept=".jpg,.jpeg,.png,.webp,image/*">
                  <div class="form-text">You can upload multiple images. Supported: JPG, PNG, WEBP (max 4MB each).</div>
                </div>
                @if($vehicle->images->isNotEmpty())
                  <div class="col-12">
                    <label class="form-label small mb-1">Remove Existing Images</label>
                    <div class="d-flex flex-wrap gap-3">
                      @foreach($vehicle->images as $image)
                        <label class="d-flex align-items-center gap-2 border rounded p-2">
                          <input type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}">
                          <img src="{{ asset($image->path) }}" alt="Vehicle image {{ $loop->iteration }}" style="width:72px;height:52px;object-fit:cover;border-radius:6px;border:1px solid #dbe6f3;">
                        </label>
                      @endforeach
                    </div>
                  </div>
                @endif
              </div>

              <hr class="my-3">
              <div class="small text-muted mb-2">Tracker details</div>

              <div class="row g-2">
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Device Name</label>
                  <input type="text" name="tracker_device_name" class="form-control form-control-sm" value="{{ $vehicle->tracker_device_name }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Device Type</label>
                  <input type="text" name="tracker_device_type" class="form-control form-control-sm" value="{{ $vehicle->tracker_device_type }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">IMEI</label>
                  <input type="text" name="tracker_imei" class="form-control form-control-sm" value="{{ $vehicle->tracker_imei }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">SIM</label>
                  <input type="text" name="tracker_sim" class="form-control form-control-sm" value="{{ $vehicle->tracker_sim }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">ICCID</label>
                  <input type="text" name="tracker_iccid" class="form-control form-control-sm" value="{{ $vehicle->tracker_iccid }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Contact Name</label>
                  <input type="text" name="tracker_contact_name" class="form-control form-control-sm" value="{{ $vehicle->tracker_contact_name }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Contact Number</label>
                  <input type="text" name="tracker_contact_number" class="form-control form-control-sm" value="{{ $vehicle->tracker_contact_number }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Activation Date</label>
                  <input type="datetime-local" name="tracker_activation_date" class="form-control form-control-sm" value="{{ optional($vehicle->tracker_activation_date)->format('Y-m-d\\TH:i') }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Expiry Time</label>
                  <input type="text" name="tracker_expiry_time" class="form-control form-control-sm" value="{{ $vehicle->tracker_expiry_time }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Insurance Expires</label>
                  <input type="date" name="tracker_insurance_expires" class="form-control form-control-sm" value="{{ optional($vehicle->tracker_insurance_expires)->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">License Expires</label>
                  <input type="date" name="tracker_license_expires" class="form-control form-control-sm" value="{{ optional($vehicle->tracker_license_expires)->format('Y-m-d') }}">
                </div>
              </div>

              <hr class="my-3">
              <div class="small text-muted mb-2">Maintenance details</div>

              <div class="row g-2">
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Service Interval (KM)</label>
                  <input type="number" min="0" name="tracker_maintenance_mileage" class="form-control form-control-sm" value="{{ $vehicle->tracker_maintenance_mileage }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Last Service Date</label>
                  <input type="date" name="maintenance_last_service_date" class="form-control form-control-sm" value="{{ optional($vehicle->maintenance_last_service_date)->format('Y-m-d') }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Last Service Mileage</label>
                  <input type="number" min="0" name="maintenance_last_service_mileage" class="form-control form-control-sm" value="{{ $vehicle->maintenance_last_service_mileage }}">
                </div>
                <div class="col-12 col-lg-4">
                  <label class="form-label small mb-1">Next Service Date</label>
                  <input type="date" name="maintenance_next_service_date" class="form-control form-control-sm" value="{{ optional($vehicle->maintenance_next_service_date)->format('Y-m-d') }}">
                </div>
                <div class="col-12">
                  <label class="form-label small mb-1">Maintenance Notes</label>
                  <textarea name="maintenance_note" class="form-control form-control-sm" rows="2">{{ $vehicle->maintenance_note }}</textarea>
                </div>
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
          @endif
        </div>
      </div>

      <div class="modal fade" id="viewVehicleModal{{ $vehicle->id }}" tabindex="-1" aria-labelledby="viewVehicleModalLabel{{ $vehicle->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="viewVehicleModalLabel{{ $vehicle->id }}">{{ $vehicle->name }} Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              @if($vehicle->images->isNotEmpty())
                <div class="mb-3">
                  <div class="small text-muted mb-2">Vehicle Images</div>
                  <div class="d-flex flex-wrap gap-2">
                    @foreach($vehicle->images as $image)
                      <img src="{{ asset($image->path) }}" alt="{{ $vehicle->name }} image {{ $loop->iteration }}" style="width:140px;height:96px;object-fit:cover;border-radius:8px;border:1px solid #dbe6f3;">
                    @endforeach
                  </div>
                </div>
              @endif
              <div class="row g-3">
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Plate Number</div>
                  <div>{{ $vehicle->plate_no }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Status</div>
                  <div>{{ ucfirst($vehicle->status) }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Partner</div>
                  <div>{{ $vehicle->partner?->name ?: 'No partner assigned' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">DAGPS Device ID</div>
                  <div>{{ $vehicle->dagps_device_id ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Make</div>
                  <div>{{ $vehicle->make ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Model</div>
                  <div>{{ $vehicle->model ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Year</div>
                  <div>{{ $vehicle->year ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-3">
                  <div class="small text-muted">Color</div>
                  <div>{{ $vehicle->color ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Fuel Type</div>
                  <div>{{ $vehicle->fuel_type ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Transmission</div>
                  <div>{{ $vehicle->transmission ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Driver Mode</div>
                  <div>
                    @if(($vehicle->driver_mode ?? 'both') === 'with_driver_only')
                      With driver only
                    @elseif(($vehicle->driver_mode ?? 'both') === 'without_driver_only')
                      Without driver only
                    @else
                      With or without driver
                    @endif
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Long-Term Rental</div>
                  <div>{{ $vehicle->allow_long_term ? 'Enabled' : 'Not allowed' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Available for Hire</div>
                  <div>{{ $vehicle->available_for_hire ? 'Yes' : 'No' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Available for Rent</div>
                  <div>{{ $vehicle->available_for_rent ? 'Yes' : 'No' }}</div>
                </div>
              </div>

              <hr class="my-3">
              <div class="fw-semibold mb-2">Tracker Details</div>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Device Name</div>
                  <div>{{ $vehicle->tracker_device_name ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Device Type</div>
                  <div>{{ $vehicle->tracker_device_type ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">IMEI</div>
                  <div>{{ $vehicle->tracker_imei ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">SIM</div>
                  <div>{{ $vehicle->tracker_sim ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">ICCID</div>
                  <div>{{ $vehicle->tracker_iccid ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Contact Name</div>
                  <div>{{ $vehicle->tracker_contact_name ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Contact Number</div>
                  <div>{{ $vehicle->tracker_contact_number ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Activation Date</div>
                  <div>{{ optional($vehicle->tracker_activation_date)->format('Y-m-d H:i') ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Expiry Time</div>
                  <div>{{ $vehicle->tracker_expiry_time ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Insurance Expires</div>
                  <div>{{ optional($vehicle->tracker_insurance_expires)->format('Y-m-d') ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">License Expires</div>
                  <div>{{ optional($vehicle->tracker_license_expires)->format('Y-m-d') ?: '-' }}</div>
                </div>
              </div>

              <hr class="my-3">
              <div class="fw-semibold mb-2">Maintenance Details</div>
              <div class="row g-3">
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Service Interval (KM)</div>
                  <div>{{ $vehicle->tracker_maintenance_mileage ? number_format($vehicle->tracker_maintenance_mileage) . ' km' : '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Last Service Date</div>
                  <div>{{ optional($vehicle->maintenance_last_service_date)->format('Y-m-d') ?: '-' }}</div>
                </div>
                <div class="col-12 col-md-4">
                  <div class="small text-muted">Last Service Mileage</div>
                  <div>{{ $vehicle->maintenance_last_service_mileage ? number_format($vehicle->maintenance_last_service_mileage) . ' km' : '-' }}</div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="small text-muted">Next Service Date</div>
                  <div>{{ optional($vehicle->maintenance_next_service_date)->format('Y-m-d') ?: '-' }}</div>
                </div>
                <div class="col-12">
                  <div class="small text-muted">Maintenance Notes</div>
                  <div>{{ $vehicle->maintenance_note ?: '-' }}</div>
                </div>
                <div class="col-12">
                  <div class="small text-muted">General Note</div>
                  <div>{{ $vehicle->note ?: '-' }}</div>
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
      <div class="text-center p-4 text-muted">{{ auth()->user()->canManageData() ? 'No vehicles yet. Add your first vehicle.' : 'No vehicles are assigned to your partner account yet.' }}</div>
    @endforelse
  </div>
</div>

@if(auth()->user()->canManageData())
  <div class="card list-card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span class="header-title">Vehicle Pricing Chart</span>
      <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addVehiclePricingModal">Add Pricing</button>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive vehicle-pricing-wrap">
        <table class="table table-striped mb-0 align-middle vehicle-pricing-table">
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
                <td data-label="Make">{{ $vehiclePricing->make ?: '-' }}</td>
                <td data-label="Model">{{ $vehiclePricing->model }}</td>
                <td data-label="Per Day KM">{{ number_format((int) $vehiclePricing->per_day_km) }} km</td>
                <td data-label="Per Day Amount">LKR {{ number_format((float) $vehiclePricing->per_day_amount, 2) }}</td>
                <td data-label="Driver Cost / Day">LKR {{ number_format((float) $vehiclePricing->driver_cost_per_day, 2) }}</td>
                <td data-label="Extra 1 KM Amount">LKR {{ number_format((float) $vehiclePricing->extra_km_rate, 2) }}</td>
                <td data-label="Note">{{ $vehiclePricing->note ?: '-' }}</td>
                <td data-label="Action" class="text-nowrap pricing-actions">
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
                <td colspan="8" class="text-center p-4 text-muted no-data">No pricing chart rows yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endif

@if(auth()->user()->canManageData())
  <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addVehicleModalLabel">Add Vehicle Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
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
            <label class="form-label">Long-Term Rental</label>
            <select name="allow_long_term" class="form-select" required>
              <option value="1" @selected(old('allow_long_term', '1') === '1')>Yes</option>
              <option value="0" @selected(old('allow_long_term') === '0')>No</option>
            </select>
          </div>
          <div class="row g-2 mt-0">
            <div class="col-12 col-md-6">
              <label class="form-label">Available for Hire</label>
              <select name="available_for_hire" class="form-select" required>
                <option value="1" @selected(old('available_for_hire', '1') === '1')>Yes</option>
                <option value="0" @selected(old('available_for_hire') === '0')>No</option>
              </select>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Available for Rent</label>
              <select name="available_for_rent" class="form-select" required>
                <option value="1" @selected(old('available_for_rent', '1') === '1')>Yes</option>
                <option value="0" @selected(old('available_for_rent') === '0')>No</option>
              </select>
            </div>
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
          <div class="mb-2 mt-2">
            <label class="form-label">Vehicle Images</label>
            <input type="file" name="images[]" class="form-control" multiple accept=".jpg,.jpeg,.png,.webp,image/*">
            <div class="form-text">Upload one or more images (JPG, PNG, WEBP. Max 4MB each).</div>
          </div>
        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-dark">Save Vehicle</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endif

@if(auth()->user()->canManageData())
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
