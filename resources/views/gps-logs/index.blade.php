@extends('layouts.app')
@section('title', 'DAGPS KM Tracking')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  .sheet-scroll-wrap {
    overflow-x: auto;
    padding-bottom: .25rem;
  }

  .sheet-grid {
    display: flex;
    flex-wrap: nowrap;
    gap: .65rem;
    min-width: 100%;
    width: max-content;
  }

  .sheet-block {
    flex: 0 0 calc((100% - 1.95rem) / 4);
    min-width: 270px;
    max-width: 320px;
  }

  .sheet-block table {
    margin-bottom: 0;
    font-size: .9rem;
    table-layout: fixed;
    width: 100%;
  }

  .sheet-block .sheet-header {
    font-weight: 700;
    text-align: center;
    padding: .55rem .65rem;
    border-bottom: 1px solid rgba(23, 35, 50, 0.12);
    color: var(--text);
    background: rgba(244, 239, 228, 0.96);
    box-shadow: inset 0 3px 0 rgba(232, 74, 36, 0.45);
  }

  .sheet-palette-0,
  .sheet-palette-1,
  .sheet-palette-2,
  .sheet-palette-3 {
    background: rgba(248, 243, 233, 0.9);
    border: 1px solid rgba(23, 35, 50, 0.12) !important;
    box-shadow: 0 8px 20px rgba(23, 35, 50, 0.05);
  }

  .sheet-block table thead th {
    background: rgba(239, 230, 214, 0.95);
    color: #3f5873;
    font-weight: 700;
  }

  .sheet-block table tbody td {
    background: rgba(244, 239, 228, 0.62);
  }

  .sheet-block table tbody tr:nth-child(even) td {
    background: rgba(239, 230, 214, 0.72);
  }

  .sheet-km-cell {
    width: 102px;
    min-width: 102px;
  }

  .sheet-km-input {
    width: 100%;
    max-width: none;
    text-align: right;
  }

  .sheet-service {
    background: rgba(23, 35, 50, 0.16) !important;
  }

  .sheet-service td {
    border-left: 0 !important;
    background: rgba(23, 35, 50, 0.12) !important;
  }

  .sheet-service td:first-child {
    border-left: 3px solid rgba(232, 74, 36, 0.7) !important;
  }

  .service-badge {
    display: inline-block;
    margin-top: 2px;
    padding: 1px 6px;
    border-radius: 999px;
    font-size: .68rem;
    font-weight: 700;
    color: #fff;
    background: #172332;
  }

  .service-action-btn {
    font-size: .72rem;
    padding: .16rem .42rem;
    border-radius: .45rem;
  }

  .sheet-date-cell {
    white-space: nowrap;
  }

  .service-icon {
    margin-left: 6px;
    color: #be3614;
    font-size: .85rem;
  }

  @media (max-width: 1199.98px) {
    .sheet-grid {
      gap: .8rem;
    }

    .sheet-block {
      flex: 0 0 270px;
      min-width: 270px;
      max-width: 270px;
    }
  }
</style>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <h4 class="mb-0">Mileage Report</h4>
  <div class="d-flex flex-wrap gap-2">
  <form class="d-flex gap-2" method="get" action="{{ route('gps-logs.index') }}">
    <input type="month" class="form-control" name="month" value="{{ $month }}">
    <input type="date" class="form-control" name="start_date" value="{{ $usingCustomRange ? $startDate : '' }}">
    <input type="date" class="form-control" name="end_date" value="{{ $usingCustomRange ? $endDate : '' }}">
    <select name="car_id" class="form-select">
      <option value="">Select Car</option>
      @foreach($cars as $car)
        <option value="{{ $car->id }}" @selected((string) $carId === (string) $car->id)>
          {{ $car->name }}
        </option>
      @endforeach
    </select>
    <button class="btn btn-dark">Filter</button>
  </form>
  <a class="btn btn-outline-dark" href="{{ route('gps-logs.report', ['month' => $month, 'start_date' => $usingCustomRange ? $startDate : null, 'end_date' => $usingCustomRange ? $endDate : null, 'car_id' => $carId]) }}">Export PDF</a>
  </div>
</div>

<div class="row g-3 mb-1">
  <div class="col-12 col-md-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Period</div>
        <div class="fs-5 fw-bold">{{ $periodLabel }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Total Mileage</div>
        <div class="fs-5 fw-bold">{{ number_format($totalDistance, 2) }} km</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-2">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Days Logged</div>
        <div class="fs-5 fw-bold">{{ number_format($daysLogged) }}</div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-3">
    <div class="card shadow-sm">
      <div class="card-body">
        <div class="text-muted small">Monthly Average ({{ $periodMonthsCount }} month{{ $periodMonthsCount > 1 ? 's' : '' }})</div>
        <div class="fs-5 fw-bold">{{ number_format($avgKmPerMonth, 2) }} km</div>
      </div>
    </div>
  </div>
</div>

@if(!empty($serviceStats))
  <div class="card shadow-sm mt-3">
    <div class="card-header">Service Tracker</div>
    <div class="card-body">
      <div class="row g-2">
        <div class="col-12 col-md-2">
          <div class="small text-muted">Last Service</div>
          <div class="fw-semibold">{{ $serviceStats['last_service_date'] ?? 'Not marked' }}</div>
        </div>
        <div class="col-12 col-md-2">
          <div class="small text-muted">KM Since Service</div>
          <div class="fw-semibold">{{ number_format($serviceStats['km_after_service'], 2) }} km</div>
        </div>
        <div class="col-12 col-md-2">
          <div class="small text-muted">Service Interval</div>
          <div class="fw-semibold">{{ number_format($serviceStats['interval_km']) }} km</div>
        </div>
        <div class="col-12 col-md-2">
          <div class="small text-muted">{{ $serviceStats['overdue_km'] > 0 ? 'Overdue By' : 'Remaining' }}</div>
          <div class="fw-semibold {{ $serviceStats['overdue_km'] > 0 ? 'text-danger' : '' }}">
            {{ number_format($serviceStats['overdue_km'] > 0 ? $serviceStats['overdue_km'] : $serviceStats['remaining_km'], 2) }} km
          </div>
        </div>
        <div class="col-12 col-md-2">
          <div class="small text-muted">Avg/Day (Post Service)</div>
          <div class="fw-semibold">{{ number_format($serviceStats['avg_per_day_after_service'], 2) }} km</div>
        </div>
        <div class="col-12 col-md-2">
          <div class="small text-muted">Estimated Next Service</div>
          <div class="fw-semibold">{{ $serviceStats['next_service_date'] ?? '-' }}</div>
        </div>
      </div>
    </div>
  </div>
@endif

<div class="card shadow-sm mt-3 mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Daily Mileage Sheet</span>
    <!-- <span class="small text-muted">Select one car to input day-by-day KM</span> -->
  </div>
  <div class="card-body">
    @if(empty($carId))
      <div class="text-muted">Choose a specific car from the filter above to load the sheet.</div>
    @else
      <form method="post" action="{{ route('gps-logs.sheet') }}">
        @csrf
        <input type="hidden" name="car_id" value="{{ $carId }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="start_date" value="{{ $usingCustomRange ? $startDate : '' }}">
        <input type="hidden" name="end_date" value="{{ $usingCustomRange ? $endDate : '' }}">
        <input type="hidden" name="cycle_day" value="{{ $cycleDay }}">

        <div class="sheet-scroll-wrap">
          <div class="sheet-grid">
            @foreach($sheetPeriods as $idx => $period)
              <div class="sheet-block border rounded overflow-hidden sheet-palette-{{ $idx % 4 }}">
                <div class="sheet-header">{{ $period['title'] }}</div>
                <div class="table-responsive">
                  <table class="table table-sm align-middle mb-0">
                    <thead>
                      <tr>
                        <th class="ps-2">Date</th>
                        <th class="text-end pe-2 sheet-km-cell">Km</th>
                        <th class="text-center pe-2" style="width:78px;">Service</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($period['rows'] as $row)
                        <tr class="{{ $row['is_service'] ? 'sheet-service' : '' }}">
                          <td class="ps-2 sheet-date-cell">
                            {{ $row['label'] }}
                            @if($row['is_service'])
                              <span class="fa fa-cogs service-icon" title="Service marked"></span>
                            @endif
                          </td>
                          <td class="text-end pe-2 sheet-km-cell">
                            <input
                              type="number"
                              min="0"
                              step="any"
                              class="form-control form-control-sm d-inline-block sheet-km-input"
                              name="distances[{{ $row['date'] }}]"
                              value="{{ $row['km'] }}"
                            >
                          </td>
                          <td class="text-center pe-2">
                            <button
                              type="button"
                              class="btn btn-outline-dark service-action-btn"
                              data-bs-toggle="modal"
                              data-bs-target="#serviceModal"
                              data-service-date="{{ $row['date'] }}"
                              data-service-type="{{ $row['service_type'] }}"
                              data-service-cost="{{ $row['service_cost'] }}"
                              data-service-note="{{ $row['service_note'] }}"
                            >
                              {{ $row['is_service'] ? 'Edit' : 'Mark' }}
                            </button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="mt-3">
          <button class="btn btn-dark">Save Daily KM Sheet</button>
        </div>
      </form>
    @endif
  </div>
</div>

<div class="modal fade" id="serviceModal" tabindex="-1" aria-labelledby="serviceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="serviceModalLabel">Mark Service Date</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('gps-logs.service') }}">
        @csrf
        <input type="hidden" name="car_id" value="{{ $carId }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="start_date" value="{{ $usingCustomRange ? $startDate : '' }}">
        <input type="hidden" name="end_date" value="{{ $usingCustomRange ? $endDate : '' }}">
        <input type="hidden" name="cycle_day" value="{{ $cycleDay }}">
        <input type="hidden" name="original_service_date" id="original_service_date">
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Service Date</label>
            <input type="date" class="form-control" name="service_date" id="service_date" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Service Type</label>
            <input type="text" class="form-control" name="service_type" id="service_type" placeholder="Oil Change / Full Service" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Service Cost</label>
            <input type="number" step="0.01" min="0" class="form-control" name="service_cost" id="service_cost" placeholder="Optional">
          </div>
          <div class="mb-2">
            <label class="form-label">Note</label>
            <input type="text" class="form-control" name="service_note" id="service_note" placeholder="Optional details">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Service</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('serviceModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const dateInput = modal.querySelector('#service_date');
      const originalDateInput = modal.querySelector('#original_service_date');
      const typeInput = modal.querySelector('#service_type');
      const costInput = modal.querySelector('#service_cost');
      const noteInput = modal.querySelector('#service_note');

      const selectedDate = button.getAttribute('data-service-date') || '';
      if (dateInput) dateInput.value = selectedDate;
      if (originalDateInput) originalDateInput.value = selectedDate;
      if (typeInput) typeInput.value = button.getAttribute('data-service-type') || '';
      if (costInput) costInput.value = button.getAttribute('data-service-cost') || '';
      if (noteInput) noteInput.value = button.getAttribute('data-service-note') || '';
    });
  });
</script>

@endsection
