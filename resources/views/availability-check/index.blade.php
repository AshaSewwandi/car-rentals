@extends('layouts.app')
@section('title', 'Availability Check')

@section('content')
<style>
  .availability-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 16px;
  }

  .legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 4px;
    display: inline-block;
    margin-right: 6px;
  }

  .dot-available {
    background: #dcfce7;
    border: 1px solid #22c55e;
  }

  .dot-rented {
    background: #fee2e2;
    border: 1px solid #ef4444;
  }

  .timeline-wrap {
    overflow-x: auto;
  }

  .timeline-table {
    width: max-content;
    border-collapse: separate;
    border-spacing: 4px;
  }

  .timeline-table th,
  .timeline-table td {
    border: 0;
    text-align: center;
    vertical-align: middle;
    padding: 8px 6px;
    white-space: nowrap;
  }

  .timeline-table .vehicle-col {
    text-align: left;
    width: 220px;
    min-width: 220px;
    max-width: 220px;
    position: sticky;
    left: 0;
    background: #fff;
    z-index: 2;
    box-shadow: 8px 0 8px -8px rgba(15, 23, 42, 0.12);
  }

  .timeline-table th.date-col {
    min-width: 76px;
    background: #f8fafc;
    border-radius: 8px;
    font-size: .78rem;
    color: #64748b;
  }

  .timeline-cell {
    border-radius: 8px;
    font-weight: 700;
    font-size: .82rem;
  }

  .timeline-cell.available {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #86efac;
  }

  .timeline-cell.rented {
    background: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
  }

  .inventory-item {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    background: #f8fafc;
    margin-bottom: 10px;
  }

  .inventory-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 8px;
  }

  .inventory-stat {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    text-align: center;
    padding: 8px 4px;
  }

  .inventory-stat .value {
    font-size: 1.15rem;
    font-weight: 700;
    line-height: 1;
  }

  .inventory-stat .label {
    font-size: .7rem;
    color: #64748b;
    text-transform: uppercase;
  }

  @media (max-width: 1200px) {
    .availability-grid {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 920px) {
    .availability-grid > .card {
      padding: .75rem !important;
      overflow: hidden;
    }

    .timeline-wrap {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      padding-bottom: .2rem;
    }

    .timeline-table {
      border-spacing: 3px;
    }

    .timeline-table .vehicle-col {
      width: 132px;
      min-width: 132px;
      max-width: 132px;
      box-shadow: 6px 0 8px -8px rgba(15, 23, 42, 0.2);
    }

    .timeline-table th.date-col {
      min-width: 62px;
      font-size: .72rem;
    }

    .timeline-cell {
      font-size: .72rem;
      padding: 6px 4px !important;
    }
  }

  @media (max-width: 575.98px) {
    .timeline-table {
      border-spacing: 2px;
    }

    .timeline-table .vehicle-col {
      width: 118px;
      min-width: 118px;
      max-width: 118px;
      padding: 6px 4px !important;
    }

    .timeline-table th.date-col {
      min-width: 56px;
      font-size: .68rem;
      padding: 6px 4px !important;
    }

    .timeline-cell {
      font-size: .66rem;
      padding: 5px 3px !important;
      border-radius: 6px;
    }
  }
</style>

<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Availability Check Table</h4>
    <div class="text-muted">Check rented dates vs available dates for each vehicle.</div>
  </div>
</div>

<div class="card list-card">
  <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
    <span class="header-title">Availability Timeline (Gantt View)</span>
    <span class="small text-muted">Green = available, Red = rented/not available</span>
  </div>
  <div class="card-body">
    <form method="get" action="{{ route('availability-check.index') }}" class="row g-2 align-items-end mb-3">
      <div class="col-12 col-md-3">
        <label class="form-label mb-1">Start Date</label>
        <input type="date" class="form-control" id="availabilityStartDate" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label mb-1">End Date</label>
        <input type="date" class="form-control" id="availabilityEndDate" name="end_date" value="{{ $filters['end_date'] ?? '' }}" min="{{ $filters['start_date'] ?? '' }}">
      </div>
      <div class="col-12 col-md-4">
        <label class="form-label mb-1">Vehicle</label>
        <select class="form-select" name="car_id">
          <option value="">All Vehicles</option>
          @foreach($cars as $car)
            <option value="{{ $car->id }}" {{ (($filters['car_id'] ?? '') == $car->id) ? 'selected' : '' }}>
              {{ $car->name }} ({{ $car->plate_no }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-dark">Check</button>
      </div>
    </form>

    <div class="d-flex flex-wrap align-items-center gap-3 mb-3 small text-muted">
      <span><span class="legend-dot dot-available"></span>Available</span>
      <span><span class="legend-dot dot-rented"></span>Rented / Not Available</span>
      <span class="ms-auto">
        Selected: <strong>{{ \Carbon\Carbon::parse($filters['start_date'] ?? now()->toDateString())->format('Y-m-d') }}</strong>
        to
        <strong>{{ \Carbon\Carbon::parse($filters['end_date'] ?? now()->addDays(13)->toDateString())->format('Y-m-d') }}</strong>
      </span>
    </div>

    <div class="availability-grid">
      <div class="card p-3">
        <div class="fw-semibold mb-2">Inventory availability for selected date range</div>
        @forelse($rows as $row)
          <div class="inventory-item">
            <div class="fw-semibold">{{ $row['name'] }}</div>
            <div class="text-muted small mb-1">Plate: {{ $row['plate_no'] }}</div>
            <div class="inventory-stats">
              <div class="inventory-stat">
                <div class="value text-primary">{{ $row['total_stock'] }}</div>
                <div class="label">Total</div>
              </div>
              <div class="inventory-stat">
                <div class="value {{ $row['free_stock'] ? 'text-success' : 'text-danger' }}">{{ $row['free_stock'] }}</div>
                <div class="label">Free</div>
              </div>
            </div>
          </div>
        @empty
          <div class="text-muted small">No vehicles found.</div>
        @endforelse
      </div>

      <div class="card p-3">
        <div class="fw-semibold mb-2">Availability Timeline</div>
        <div class="timeline-wrap">
          <table class="timeline-table align-middle">
            <thead>
              <tr>
                <th class="vehicle-col">Vehicle</th>
                @foreach($timelineDates as $date)
                  <th class="date-col">
                    {{ $date->format('M d') }}
                  </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @forelse($rows as $row)
                <tr>
                  <td class="vehicle-col">
                    <div class="fw-semibold">{{ $row['name'] }}</div>
                    <div class="small text-muted">{{ $row['plate_no'] }}</div>
                  </td>
                  @foreach($row['cells'] as $cell)
                    <td class="timeline-cell {{ $cell['is_booked'] ? 'rented' : 'available' }}" title="{{ $cell['date'] }}">
                      {{ $cell['is_booked'] ? 'Rented' : 'Free' }}
                    </td>
                  @endforeach
                </tr>
              @empty
                <tr>
                  <td class="vehicle-col text-muted">No vehicles</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function () {
    const navEntry = performance.getEntriesByType('navigation')[0];
    const isReload = navEntry ? navEntry.type === 'reload' : performance.navigation.type === 1;
    if (isReload && window.location.search) {
      window.location.replace(window.location.pathname);
      return;
    }

    const startInput = document.getElementById('availabilityStartDate');
    const endInput = document.getElementById('availabilityEndDate');
    if (!startInput || !endInput) return;

    const syncEndDateMin = () => {
      endInput.min = startInput.value || '';
      if (startInput.value && endInput.value && endInput.value < startInput.value) {
        endInput.value = '';
      }
    };

    startInput.addEventListener('input', syncEndDateMin);
    syncEndDateMin();
  })();
</script>
@endsection
