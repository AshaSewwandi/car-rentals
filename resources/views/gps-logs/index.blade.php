@extends('layouts.app')
@section('title', 'DAGPS KM Tracking')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <h4 class="mb-0">DAGPS KM Tracking</h4>
  <form class="d-flex gap-2" method="get" action="{{ route('gps-logs.index') }}">
    <input type="month" class="form-control" name="month" value="{{ $month }}">
    <select name="car_id" class="form-select">
      <option value="">All Cars</option>
      @foreach($cars as $car)
        <option value="{{ $car->id }}" @selected((string) $carId === (string) $car->id)>
          {{ $car->name }} ({{ $car->plate_no }})
        </option>
      @endforeach
    </select>
    <button class="btn btn-dark">Filter</button>
  </form>
</div>

<div class="row g-3">
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">Add KM Log</div>
      <div class="card-body">
        <form method="post" action="{{ route('gps-logs.store') }}">
          @csrf
          <div class="mb-2">
            <label class="form-label">Car</label>
            <select name="car_id" class="form-select" required>
              <option value="">Select Car</option>
              @foreach($cars as $car)
                <option value="{{ $car->id }}" @selected(old('car_id', $carId) == $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Log Date</label>
            <input type="date" name="log_date" class="form-control" value="{{ old('log_date', now()->format('Y-m-d')) }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Opening KM</label>
            <input type="number" min="0" name="opening_km" class="form-control" value="{{ old('opening_km') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Closing KM</label>
            <input type="number" min="0" name="closing_km" class="form-control" value="{{ old('closing_km') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Source</label>
            <select name="source" class="form-select" required>
              <option value="manual" @selected(old('source') === 'manual')>Manual</option>
              <option value="dagps" @selected(old('source') === 'dagps')>DAGPS</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">DAGPS Ref</label>
            <input type="text" name="dagps_ref" class="form-control" value="{{ old('dagps_ref') }}" placeholder="Trip ID / Device ID">
          </div>
          <div class="mb-3">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control" value="{{ old('note') }}">
          </div>
          <button class="btn btn-dark w-100">Save KM Log</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between">
        <span>KM Logs ({{ $month }})</span>
        <strong>Total Distance: {{ number_format($totalDistance) }} km</strong>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped mb-0 align-middle">
            <thead>
              <tr>
                <th>Date</th>
                <th>Car</th>
                <th>Device</th>
                <th>Opening</th>
                <th>Closing</th>
                <th>Distance</th>
                <th>Source</th>
                <th>DAGPS Ref</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($logs as $log)
                <tr>
                  <form method="post" action="{{ route('gps-logs.update', $log) }}">
                    @csrf
                    @method('PUT')
                    <td><input type="date" name="log_date" class="form-control form-control-sm" value="{{ $log->log_date->format('Y-m-d') }}" required></td>
                    <td>
                      <select name="car_id" class="form-select form-select-sm" required>
                        @foreach($cars as $car)
                          <option value="{{ $car->id }}" @selected($log->car_id === $car->id)>{{ $car->name }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>{{ $log->car->tracker_device_name ?: ($log->car->dagps_device_id ?: '-') }}</td>
                    <td><input type="number" min="0" name="opening_km" class="form-control form-control-sm" value="{{ $log->opening_km }}" required></td>
                    <td><input type="number" min="0" name="closing_km" class="form-control form-control-sm" value="{{ $log->closing_km }}" required></td>
                    <td><span class="badge bg-secondary">{{ $log->distance_km }} km</span></td>
                    <td>
                      <select name="source" class="form-select form-select-sm" required>
                        <option value="manual" @selected($log->source === 'manual')>Manual</option>
                        <option value="dagps" @selected($log->source === 'dagps')>DAGPS</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" name="dagps_ref" class="form-control form-control-sm mb-1" value="{{ $log->dagps_ref }}">
                      <input type="text" name="note" class="form-control form-control-sm" value="{{ $log->note }}" placeholder="Note">
                    </td>
                    <td class="text-nowrap">
                      <button class="btn btn-sm btn-outline-dark">Update</button>
                  </form>
                  <form method="post" action="{{ route('gps-logs.destroy', $log) }}" class="d-inline" onsubmit="return confirm('Delete this log?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                    </td>
                </tr>
              @empty
                <tr><td colspan="9" class="text-center p-4 text-muted">No KM logs for {{ $month }}.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
