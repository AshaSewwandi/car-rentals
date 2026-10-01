@extends('layouts.app')
@section('title', 'Place Order')

@section('content')
<div class="page-toolbar">
  <div class="mb-3">
    <h4 class="mb-1">Place Order</h4>
    <div class="text-muted">Create a booking on behalf of a customer and adjust the price for this order if needed.</div>
  </div>
</div>

<div class="card list-card mb-3">
  <div class="card-header">
    <span class="header-title">1. Select Vehicle &amp; Dates</span>
  </div>
  <div class="card-body">
    @if($error)
      <div class="alert alert-danger">{{ $error }}</div>
    @endif
    <form method="get" action="{{ route('orders.create') }}" class="row g-2 align-items-end">
      <div class="col-12 col-md-4">
        <label for="order_vehicle_id" class="form-label mb-1">Vehicle</label>
        <select id="order_vehicle_id" name="vehicle_id" class="form-select" required>
          <option value="">Select a vehicle</option>
          @foreach($vehicles as $vehicle)
            <option value="{{ $vehicle->id }}" @selected((string)($filters['vehicle_id'] ?? '') === (string)$vehicle->id)>
              {{ $vehicle->name }} ({{ $vehicle->plate_no }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label for="order_start_date" class="form-label mb-1">Start Date</label>
        <input id="order_start_date" type="date" name="start_date" class="form-control" value="{{ $filters['start_date'] ?? '' }}" required>
      </div>
      <div class="col-6 col-md-3">
        <label for="order_end_date" class="form-label mb-1">End Date</label>
        <input id="order_end_date" type="date" name="end_date" class="form-control" value="{{ $filters['end_date'] ?? '' }}" required>
      </div>
      <div class="col-12 col-md-2">
        <button type="submit" class="btn btn-primary w-100">Check Price</button>
      </div>
    </form>
  </div>
</div>

@if($quote)
  <form method="post" action="{{ route('orders.store') }}" id="orderForm">
    @csrf
    <input type="hidden" name="vehicle_id" value="{{ $quote['vehicle']->id }}">
    <input type="hidden" name="start_date" value="{{ $quote['start_date'] }}">
    <input type="hidden" name="end_date" value="{{ $quote['end_date'] }}">

    <div class="card list-card mb-3">
      <div class="card-header">
        <span class="header-title">2. Customer</span>
      </div>
      <div class="card-body">
        <div class="btn-group mb-3" role="group" aria-label="Customer type">
          <input type="radio" class="btn-check" name="customer_mode" id="customerModeExisting" value="existing" checked>
          <label class="btn btn-outline-primary btn-sm" for="customerModeExisting">Existing Customer</label>
          <input type="radio" class="btn-check" name="customer_mode" id="customerModeNew" value="new">
          <label class="btn btn-outline-primary btn-sm" for="customerModeNew">New Customer</label>
        </div>

        <div id="existingCustomerBlock">
          <label for="customerSearch" class="form-label mb-1">Search by name, email or phone</label>
          <input type="text" id="customerSearch" class="form-control" placeholder="Start typing to search customers...">
          <div id="customerSearchResults" class="list-group mt-1" style="max-height: 220px; overflow-y: auto;"></div>
          <div id="selectedCustomer" class="alert alert-secondary mt-2 d-none"></div>
          <input type="hidden" name="customer_user_id" id="customer_user_id" value="{{ old('customer_user_id') }}">
        </div>

        <div id="newCustomerBlock" class="d-none">
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <label for="customer_name" class="form-label mb-1">Full Name</label>
              <input type="text" id="customer_name" name="customer_name" class="form-control" value="{{ old('customer_name') }}">
            </div>
            <div class="col-12 col-md-4">
              <label for="customer_email" class="form-label mb-1">Email</label>
              <input type="email" id="customer_email" name="customer_email" class="form-control" value="{{ old('customer_email') }}">
            </div>
            <div class="col-12 col-md-4">
              <label for="customer_phone" class="form-label mb-1">Phone</label>
              <input type="text" id="customer_phone" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
            </div>
          </div>
          <div class="form-text">A customer account will be created automatically and a login email sent.</div>
        </div>
      </div>
    </div>

    <div class="card list-card mb-3">
      <div class="card-header">
        <span class="header-title">3. Trip Details &amp; Pricing</span>
      </div>
      <div class="card-body">
        <div class="text-muted mb-2">
          {{ $quote['vehicle']->name }} ({{ $quote['vehicle']->plate_no }}) &middot; {{ $quote['start_date'] }} to {{ $quote['end_date'] }} &middot; {{ $quote['rental_days'] }} day(s)
        </div>

        <div class="row g-2">
          <div class="col-12 col-md-4">
            <label for="pickup_location" class="form-label mb-1">Pickup Location</label>
            <input type="text" id="pickup_location" name="pickup_location" class="form-control" value="{{ old('pickup_location') }}">
          </div>

          <div class="col-12 col-md-4">
            <label for="final_destination" class="form-label mb-1">Destination <span class="text-muted">(optional)</span></label>
            <input type="text" id="final_destination" name="final_destination" class="form-control" value="{{ old('final_destination') }}" placeholder="Where does the trip end?">
          </div>

          <div class="col-12 col-md-4">
            <label for="order_type" class="form-label mb-1">Hire or Rent</label>
            <select id="order_type" name="order_type" class="form-select" required>
              @if($quote['available_for_hire'])
                <option value="hire" @selected(old('order_type', 'hire') === 'hire')>Hire</option>
              @endif
              @if($quote['available_for_rent'])
                <option value="rent" @selected(old('order_type') === 'rent')>Rent</option>
              @endif
            </select>
          </div>

          @if($quote['driver_mode'] === 'both')
            <div class="col-12 col-md-4">
              <label for="driver_option" class="form-label mb-1">Driver Option</label>
              <select id="driver_option" name="driver_option" class="form-select">
                <option value="without_driver" @selected(old('driver_option', $quote['default_driver_option']) === 'without_driver')>Self Drive</option>
                <option value="with_driver" @selected(old('driver_option', $quote['default_driver_option']) === 'with_driver')>With Driver</option>
              </select>
            </div>
          @else
            <input type="hidden" name="driver_option" value="{{ $quote['default_driver_option'] }}">
            <div class="col-12 col-md-4">
              <label class="form-label mb-1">Driver Option</label>
              <input type="text" class="form-control" value="{{ $quote['default_driver_option'] === 'with_driver' ? 'With Driver' : 'Self Drive' }}" disabled>
            </div>
          @endif

          <div class="col-12 col-md-4">
            <label for="payment_method" class="form-label mb-1">Payment Method</label>
            <select id="payment_method" name="payment_method" class="form-select">
              <option value="pay_later_bank" @selected(old('payment_method') === 'pay_later_bank')>Bank Transfer</option>
              <option value="pay_at_pickup_cash" @selected(old('payment_method', 'pay_at_pickup_cash') === 'pay_at_pickup_cash')>Cash at Pickup</option>
            </select>
          </div>
        </div>

        <div class="mb-2 mt-2">
          <label class="form-label mb-1">Stops Along the Way <span class="text-muted">(optional)</span></label>
          <div id="orderStopsList"></div>
          <button type="button" class="btn btn-outline-dark btn-sm" id="orderAddStopBtn">+ Add Stop</button>
        </div>

        <hr>
        <div class="text-muted mb-2">System-calculated prices are shown below. Adjust any amount to set a custom price for this order.</div>

        <div class="row g-2">
          <div class="col-6 col-md-3">
            <label for="daily_rate" class="form-label mb-1">Daily Rate (LKR)</label>
            <input type="number" step="0.01" min="0" id="daily_rate" name="daily_rate" class="form-control price-input" value="{{ old('daily_rate', $quote['daily_rate']) }}">
          </div>
          <div class="col-6 col-md-3">
            <label for="driver_rate" class="form-label mb-1">Driver Rate / Day (LKR)</label>
            <input type="number" step="0.01" min="0" id="driver_rate" name="driver_rate" class="form-control price-input" value="{{ old('driver_rate', $quote['driver_rate']) }}">
          </div>
          <div class="col-6 col-md-3">
            <label for="total_amount" class="form-label mb-1">Total Amount (LKR)</label>
            <input type="number" step="0.01" min="0" id="total_amount" name="total_amount" class="form-control" value="{{ old('total_amount', $quote['total_amount']) }}">
          </div>
          <div class="col-6 col-md-3">
            <label for="payment_status" class="form-label mb-1">Payment Status</label>
            <select id="payment_status" name="payment_status" class="form-select">
              <option value="pending" @selected(old('payment_status', 'pending') === 'pending')>Pending</option>
              <option value="paid" @selected(old('payment_status') === 'paid')>Already Paid</option>
            </select>
          </div>
        </div>

        <div class="row g-2 mt-1">
          <div class="col-6 col-md-3">
            <label for="partner_share_amount" class="form-label mb-1">Partner Share (LKR)</label>
            <input type="number" step="0.01" min="0" id="partner_share_amount" name="partner_share_amount" class="form-control" value="{{ old('partner_share_amount', $quote['partner_share_amount']) }}">
          </div>
          <div class="col-6 col-md-3">
            <label for="admin_share_amount" class="form-label mb-1">Admin Share (LKR)</label>
            <input type="number" step="0.01" min="0" id="admin_share_amount" name="admin_share_amount" class="form-control" value="{{ old('admin_share_amount', $quote['admin_share_amount']) }}">
          </div>
          <div class="col-12 col-md-6">
            <label for="note" class="form-label mb-1">Note</label>
            <input type="text" id="note" name="note" class="form-control" value="{{ old('note') }}">
          </div>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Place Order</button>
  </form>
@endif

<script>
(function () {
  const orderStopsList = document.getElementById('orderStopsList');
  const orderAddStopBtn = document.getElementById('orderAddStopBtn');

  function renumberOrderStops() {
    orderStopsList.querySelectorAll('.input-group-text').forEach((badge, index) => {
      badge.textContent = String(index + 1);
    });
  }

  function addOrderStopRow(value) {
    if (!orderStopsList) return;
    const row = document.createElement('div');
    row.className = 'input-group input-group-sm mb-2';

    const badge = document.createElement('span');
    badge.className = 'input-group-text';
    badge.textContent = '1';

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'stops[]';
    input.className = 'form-control';
    input.placeholder = 'e.g. Kandy';
    input.value = value || '';

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-outline-danger';
    removeBtn.textContent = '×';
    removeBtn.addEventListener('click', () => {
      row.remove();
      renumberOrderStops();
    });

    row.appendChild(badge);
    row.appendChild(input);
    row.appendChild(removeBtn);
    orderStopsList.appendChild(row);
    renumberOrderStops();
  }

  if (orderAddStopBtn) {
    orderAddStopBtn.addEventListener('click', () => addOrderStopRow());

    const oldStops = @json(collect(old('stops', []))->filter(fn ($s) => trim((string) $s) !== '')->values());
    oldStops.forEach((stop) => addOrderStopRow(stop));
  }

  const dailyRateInput = document.getElementById('daily_rate');
  const driverRateInput = document.getElementById('driver_rate');
  const driverOptionInput = document.getElementById('driver_option');
  const totalAmountInput = document.getElementById('total_amount');
  const rentalDays = {{ $quote['rental_days'] ?? 0 }};

  function recalculateTotal() {
    if (!dailyRateInput || !totalAmountInput) return;
    const dailyRate = parseFloat(dailyRateInput.value) || 0;
    const driverRate = parseFloat(driverRateInput?.value) || 0;
    const withDriver = driverOptionInput ? driverOptionInput.value === 'with_driver' : {{ ($quote['default_driver_option'] ?? '') === 'with_driver' ? 'true' : 'false' }};
    const total = (dailyRate * rentalDays) + (withDriver ? driverRate * rentalDays : 0);
    totalAmountInput.value = total.toFixed(2);
  }

  [dailyRateInput, driverRateInput, driverOptionInput].forEach((el) => {
    el?.addEventListener('input', recalculateTotal);
    el?.addEventListener('change', recalculateTotal);
  });

  const modeExisting = document.getElementById('customerModeExisting');
  const modeNew = document.getElementById('customerModeNew');
  const existingBlock = document.getElementById('existingCustomerBlock');
  const newBlock = document.getElementById('newCustomerBlock');
  const customerUserIdInput = document.getElementById('customer_user_id');

  function toggleCustomerMode() {
    const isNew = modeNew?.checked;
    existingBlock?.classList.toggle('d-none', !!isNew);
    newBlock?.classList.toggle('d-none', !isNew);
    if (isNew && customerUserIdInput) {
      customerUserIdInput.value = '';
    }
  }

  modeExisting?.addEventListener('change', toggleCustomerMode);
  modeNew?.addEventListener('change', toggleCustomerMode);

  const searchInput = document.getElementById('customerSearch');
  const resultsBox = document.getElementById('customerSearchResults');
  const selectedBox = document.getElementById('selectedCustomer');
  let searchTimer = null;

  function selectCustomer(customer) {
    customerUserIdInput.value = customer.id;
    selectedBox.textContent = `Selected: ${customer.name} (${customer.email || 'no email'} / ${customer.phone || 'no phone'})`;
    selectedBox.classList.remove('d-none');
    resultsBox.innerHTML = '';
    searchInput.value = '';
  }

  searchInput?.addEventListener('input', function () {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    customerUserIdInput.value = '';
    selectedBox.classList.add('d-none');
    if (q.length < 2) {
      resultsBox.innerHTML = '';
      return;
    }
    searchTimer = setTimeout(() => {
      fetch(`{{ route('orders.customers.search') }}?q=${encodeURIComponent(q)}`, {
        headers: { 'Accept': 'application/json' },
      })
        .then((res) => res.json())
        .then((customers) => {
          resultsBox.innerHTML = '';
          if (!customers.length) {
            resultsBox.innerHTML = '<div class="list-group-item text-muted">No matching customers found.</div>';
            return;
          }
          customers.forEach((customer) => {
            const item = document.createElement('button');
            item.type = 'button';
            item.className = 'list-group-item list-group-item-action';
            item.textContent = `${customer.name} — ${customer.email || 'no email'} / ${customer.phone || 'no phone'}`;
            item.addEventListener('click', () => selectCustomer(customer));
            resultsBox.appendChild(item);
          });
        })
        .catch(() => {
          resultsBox.innerHTML = '<div class="list-group-item text-danger">Search failed. Please try again.</div>';
        });
    }, 300);
  });
})();
</script>
@endsection
