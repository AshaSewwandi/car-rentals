@extends('layouts.app')
@section('title', 'Agreement Management')

@section('content')
<style>
  .agreement-meta {
    display: flex;
    flex-wrap: wrap;
    gap: .4rem;
  }

  .agreement-meta .badge {
    font-weight: 600;
  }
</style>
<div class="row g-3">
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">Add Agreement</div>
      <div class="card-body">
        <form method="post" action="{{ route('agreements.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-2">
            <label class="form-label">Agreement No</label>
            <input type="text" name="agreement_no" class="form-control" value="{{ old('agreement_no') }}">
          </div>
          <div class="mb-2">
            <label class="form-label">Car</label>
            <select name="car_id" class="form-select" required>
              <option value="">Select Car</option>
              @foreach($cars as $car)
                <option value="{{ $car->id }}" @selected(old('car_id') == $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Customer</label>
            <select name="customer_id" class="form-select" required>
              <option value="">Select Customer</option>
              @foreach($customers as $customer)
                <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
          </div>
          <div class="mb-2">
            <label class="form-label">Monthly Rent</label>
            <input type="number" step="0.01" min="0" name="monthly_rent" class="form-control" value="{{ old('monthly_rent') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Deposit</label>
            <input type="number" step="0.01" min="0" name="deposit" class="form-control" value="{{ old('deposit', 0) }}">
          </div>
          <div class="mb-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="active" @selected(old('status') === 'active')>Active</option>
              <option value="ended" @selected(old('status') === 'ended')>Ended</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Agreement File (PDF/Image)</label>
            <input type="file" name="agreement_file" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control" value="{{ old('note') }}">
          </div>
          <button class="btn btn-dark w-100">Save Agreement</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">Agreement List</div>
      <div class="card-body p-3">
        @forelse($agreements as $agreement)
          <div class="card mb-3">
            <div class="card-body">
              <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div>
                  <h6 class="mb-1">{{ $agreement->agreement_no ?: 'Agreement #'.$agreement->id }}</h6>
                  <div class="agreement-meta">
                    <span class="badge text-bg-light">{{ $agreement->car?->name ?? 'No car' }}</span>
                    <span class="badge text-bg-light">{{ $agreement->customer?->name ?? 'No customer' }}</span>
                    <span class="badge {{ $agreement->status === 'ended' ? 'text-bg-secondary' : 'text-bg-success' }}">
                      {{ ucfirst($agreement->status) }}
                    </span>
                    <span class="badge text-bg-light">
                      {{ $agreement->start_date?->format('m/d/Y') ?? '-' }} - {{ $agreement->end_date?->format('m/d/Y') ?? 'Open' }}
                    </span>
                    <span class="badge text-bg-light">Rent: {{ number_format((float) $agreement->monthly_rent, 2) }}</span>
                    <span class="badge text-bg-light">Deposit: {{ number_format((float) $agreement->deposit, 2) }}</span>
                  </div>
                  @if($agreement->note)
                    <div class="small text-muted mt-2">Note: {{ $agreement->note }}</div>
                  @endif
                </div>

                <div class="d-flex flex-wrap gap-2">
                  @if($agreement->file_path)
                    <a href="{{ asset('storage/'.$agreement->file_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View File</a>
                  @endif
                  <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#edit-agreement-{{ $agreement->id }}" aria-expanded="false" aria-controls="edit-agreement-{{ $agreement->id }}">
                    Edit details
                  </button>
                  <form method="post" action="{{ route('agreements.destroy', $agreement) }}" onsubmit="return confirm('Delete this agreement?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </div>
              </div>

              <div class="collapse mt-3" id="edit-agreement-{{ $agreement->id }}">
                <form method="post" action="{{ route('agreements.update', $agreement) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                  <div class="row g-2">
                    <div class="col-12 col-lg-4">
                      <label class="form-label small mb-1">Agreement No</label>
                      <input type="text" name="agreement_no" class="form-control form-control-sm" value="{{ $agreement->agreement_no }}">
                    </div>
                    <div class="col-12 col-lg-4">
                      <label class="form-label small mb-1">Car</label>
                      <select name="car_id" class="form-select form-select-sm" required>
                        @foreach($cars as $car)
                          <option value="{{ $car->id }}" @selected($agreement->car_id === $car->id)>{{ $car->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-lg-4">
                      <label class="form-label small mb-1">Customer</label>
                      <select name="customer_id" class="form-select form-select-sm" required>
                        @foreach($customers as $customer)
                          <option value="{{ $customer->id }}" @selected($agreement->customer_id === $customer->id)>{{ $customer->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-12 col-lg-3">
                      <label class="form-label small mb-1">Start Date</label>
                      <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $agreement->start_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="col-12 col-lg-3">
                      <label class="form-label small mb-1">End Date</label>
                      <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $agreement->end_date?->format('Y-m-d') }}">
                    </div>
                    <div class="col-12 col-lg-3">
                      <label class="form-label small mb-1">Monthly Rent</label>
                      <input type="number" step="0.01" min="0" name="monthly_rent" class="form-control form-control-sm" value="{{ $agreement->monthly_rent }}" required>
                    </div>
                    <div class="col-12 col-lg-3">
                      <label class="form-label small mb-1">Deposit</label>
                      <input type="number" step="0.01" min="0" name="deposit" class="form-control form-control-sm" value="{{ $agreement->deposit }}">
                    </div>
                    <div class="col-12 col-lg-3">
                      <label class="form-label small mb-1">Status</label>
                      <select name="status" class="form-select form-select-sm" required>
                        <option value="active" @selected($agreement->status === 'active')>Active</option>
                        <option value="ended" @selected($agreement->status === 'ended')>Ended</option>
                      </select>
                    </div>
                    <div class="col-12 col-lg-5">
                      <label class="form-label small mb-1">Note</label>
                      <input type="text" name="note" class="form-control form-control-sm" value="{{ $agreement->note }}">
                    </div>
                    <div class="col-12 col-lg-4">
                      <label class="form-label small mb-1">Replace File</label>
                      <input type="file" name="agreement_file" class="form-control form-control-sm">
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
          <div class="text-center p-4 text-muted">No agreements yet.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
