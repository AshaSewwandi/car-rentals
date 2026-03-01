@extends('layouts.app')
@section('title', 'Vehicle Maintenance')

@section('content')
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Vehicle Maintenance</h4>
    <div class="text-muted">Track parts changed, service date, mileage, and maintenance expenses for each vehicle.</div>
  </div>
  <div class="d-flex gap-2">
    <form class="d-flex gap-2" method="get" action="{{ route('vehicle-maintenance.index') }}">
      <div>
        <label for="maintenance-month" class="visually-hidden">Select month</label>
        <input type="month" class="form-control" id="maintenance-month" name="month" value="{{ $month ?? '' }}" aria-label="Select month">
      </div>
      <select name="car_id" class="form-select">
        <option value="">All Vehicles</option>
        @foreach($cars as $car)
          <option value="{{ $car->id }}" @selected((string) $carId === (string) $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
        @endforeach
      </select>
      <button class="btn btn-dark">Filter</button>
    </form>
    <a class="btn btn-outline-dark" href="{{ route('vehicle-maintenance.export-pdf', ['month' => $month, 'car_id' => $carId]) }}">Export PDF</a>
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
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Maintenance Records</span>
    <div class="d-flex align-items-center gap-2">
      <strong>Rs {{ number_format($total, 2) }}</strong>
      <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">Add Record</button>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>Date</th>
            <th>Vehicle</th>
            <th>Part / Work</th>
            <th>Mileage</th>
            <th class="text-end">Amount</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($records as $record)
            <tr>
              <td>{{ $record->service_date->format('Y-m-d') }}</td>
              <td>{{ $record->car?->name }}{{ $record->car?->plate_no ? ' (' . $record->car->plate_no . ')' : '' }}</td>
              <td>{{ $record->part_name }}</td>
              <td>{{ $record->mileage !== null ? number_format($record->mileage) . ' km' : '-' }}</td>
              <td class="text-end">Rs {{ number_format((float) $record->amount, 2) }}</td>
              <td>{{ $record->note ?: '-' }}</td>
              <td class="text-nowrap">
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editMaintenanceModal{{ $record->id }}">Update</button>
                <button
                  type="button"
                  class="btn btn-sm btn-outline-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteMaintenanceModal"
                  data-delete-url="{{ route('vehicle-maintenance.destroy', $record) }}"
                  data-record-text="{{ $record->service_date->format('Y-m-d') }} | {{ $record->car?->name }} | {{ $record->part_name }}"
                >
                  Delete
                </button>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center p-4 text-muted">No maintenance records found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addMaintenanceModalLabel">Add Maintenance Record</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('vehicle-maintenance.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Vehicle</label>
            <select name="car_id" class="form-select" required>
              <option value="">Select Vehicle</option>
              @foreach($cars as $car)
                <option value="{{ $car->id }}" @selected(old('car_id', $carId) == $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
              @endforeach
            </select>
          </div>
          <div class="row g-2">
            <div class="col-12 col-md-6">
              <label class="form-label">Service Date</label>
              <input type="date" name="service_date" class="form-control" value="{{ old('service_date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">Mileage</label>
              <input type="number" min="0" name="mileage" class="form-control" value="{{ old('mileage') }}" placeholder="Optional">
            </div>
          </div>
          <div class="mb-2 mt-2">
            <label class="form-label">Part / Work</label>
            <input type="text" name="part_name" class="form-control" value="{{ old('part_name') }}" placeholder="Oil filter, brake pad, full service..." required>
          </div>
          <div class="mb-2">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount') }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label">Note</label>
            <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Record</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($records as $record)
  <div class="modal fade" id="editMaintenanceModal{{ $record->id }}" tabindex="-1" aria-labelledby="editMaintenanceModalLabel{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editMaintenanceModalLabel{{ $record->id }}">Edit Maintenance Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('vehicle-maintenance.update', $record) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label">Vehicle</label>
              <select name="car_id" class="form-select" required>
                @foreach($cars as $car)
                  <option value="{{ $car->id }}" @selected($record->car_id === $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
                @endforeach
              </select>
            </div>
            <div class="row g-2">
              <div class="col-12 col-md-6">
                <label class="form-label">Service Date</label>
                <input type="date" name="service_date" class="form-control" value="{{ $record->service_date->format('Y-m-d') }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Mileage</label>
                <input type="number" min="0" name="mileage" class="form-control" value="{{ $record->mileage }}">
              </div>
            </div>
            <div class="mb-2 mt-2">
              <label class="form-label">Part / Work</label>
              <input type="text" name="part_name" class="form-control" value="{{ $record->part_name }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Amount</label>
              <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ (float) $record->amount }}" required>
            </div>
            <div class="mb-1">
              <label class="form-label">Note</label>
              <textarea name="note" class="form-control" rows="3">{{ $record->note }}</textarea>
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

<div class="modal fade" id="deleteMaintenanceModal" tabindex="-1" aria-labelledby="deleteMaintenanceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteMaintenanceModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="deleteMaintenanceText">Are you sure you want to delete this maintenance record?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
        <form method="post" id="deleteMaintenanceForm" class="d-inline">
          @csrf
          @method('DELETE')
          <button class="btn btn-outline-danger">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteMaintenanceModal');
    const deleteForm = document.getElementById('deleteMaintenanceForm');
    const deleteText = document.getElementById('deleteMaintenanceText');

    if (!deleteModal || !deleteForm || !deleteText) {
      return;
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const deleteUrl = trigger?.getAttribute('data-delete-url');
      const recordText = trigger?.getAttribute('data-record-text') || 'this maintenance record';

      if (deleteUrl) {
        deleteForm.setAttribute('action', deleteUrl);
      }

      deleteText.textContent = `Are you sure you want to delete "${recordText}"?`;
    });
  });
</script>
@endsection
