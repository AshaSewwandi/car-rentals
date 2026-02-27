<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Full Fleet | R&A Auto Rentals',
        'description' => 'Browse the full R&A rental fleet, filter by pickup location and dates, and find available cars for your travel plan.',
        'keywords' => [
            'car fleet',
            'available cars',
            'fleet availability',
            'vehicle booking',
            'rental car list',
            'pickup location booking',
        ],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --line: #dbe6f3;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --radius: 14px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--text);
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            background: radial-gradient(68rem 30rem at 100% -20%, rgba(15, 102, 195, 0.14), transparent 70%), var(--bg);
        }

        .container {
            width: min(1220px, calc(100% - 2rem));
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid var(--line);
        }

        .topbar-inner {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
            color: inherit;
        }

        .brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 10px;
            border: 1px solid #dbe6f3;
            background: #f8fbff;
            padding: 4px;
        }

        .brand-name {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-weight: 700;
            letter-spacing: -.02em;
            font-size: 1.22rem;
            color: #0b1f3a;
        }

        .top-actions {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            text-decoration: none;
            font-weight: 700;
            padding: .58rem .95rem;
            border-radius: 10px;
            font-size: .9rem;
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
        }

        .btn-light {
            color: #334155;
            background: #f8fbff;
            border-color: #dbe6f3;
        }

        .hero {
            padding: 1.2rem 0 .4rem;
        }

        .hero-card {
            border: 1px solid var(--line);
            background: var(--surface);
            border-radius: var(--radius);
            padding: 1rem;
        }

        .hero-title {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.95rem;
            letter-spacing: -.02em;
        }

        .hero-sub {
            margin: .35rem 0 0;
            color: var(--muted);
        }

        .hero-policy {
            margin: .45rem 0 0;
            color: #1e3a8a;
            font-size: .9rem;
            font-weight: 600;
        }

        .filter-grid {
            --filter-control-height: 52px;
            margin-top: .9rem;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: .65rem;
            align-items: start;
        }

        .filter-submit {
            align-self: stretch;
            margin-top: 0;
            width: 100%;
            height: var(--filter-control-height);
            padding-top: 0;
            padding-bottom: 0;
        }

        .filter-submit-wrap {
            display: flex;
            flex-direction: column;
        }

        .filter-submit-spacer {
            display: block;
            margin-bottom: .35rem;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            visibility: hidden;
        }

        .control label {
            display: block;
            margin-bottom: .35rem;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #64748b;
        }

        .control input {
            width: 100%;
            height: var(--filter-control-height);
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 10px;
            padding: .65rem .7rem;
            color: #0f172a;
            font: inherit;
        }

        .control input.input-error {
            border-color: #dc2626;
            background: #fff7f7;
        }

        .field-error {
            display: block;
            min-height: 1.1rem;
            visibility: hidden;
            margin-top: .35rem;
            color: #b91c1c;
            font-size: .8rem;
            font-weight: 600;
        }

        .field-error.show {
            visibility: visible;
        }

        .results-note {
            margin-top: .8rem;
            padding: .7rem .85rem;
            border: 1px solid #dbe6f3;
            border-radius: 10px;
            background: #f8fbff;
            color: #334155;
            font-size: .9rem;
            font-weight: 600;
        }


        .fleet-grid {
            margin: 1rem 0 2rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .fleet-card {
            border: 1px solid var(--line);
            background: var(--surface);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .fleet-photo {
            height: 210px;
            background: #eef4ff;
            overflow: hidden;
        }

        .fleet-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .fleet-body {
            padding: .9rem;
        }

        .fleet-title-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: .6rem;
            margin-bottom: .25rem;
        }

        .fleet-title-row h3 {
            margin: 0;
            font-size: 1.05rem;
        }

        .fleet-price {
            color: var(--primary-2);
            font-weight: 800;
            white-space: nowrap;
        }

        .fleet-rate-unit {
            color: #64748b;
            font-size: .74rem;
            font-weight: 700;
            margin-left: .1rem;
        }

        .fleet-sub {
            color: #64748b;
            font-size: .9rem;
            margin-bottom: .35rem;
        }

        .fleet-policy {
            color: #1e3a8a;
            font-size: .78rem;
            line-height: 1.35;
            margin-bottom: .45rem;
            font-weight: 600;
        }

        .fleet-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .3rem;
        }

        .fleet-meta span {
            font-size: .77rem;
            color: #475569;
            border: 1px solid #dbe6f3;
            background: #f8fbff;
            border-radius: 999px;
            padding: .2rem .52rem;
        }

        .fleet-status {
            margin-top: .55rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .fleet-status::before {
            content: "";
            width: .48rem;
            height: .48rem;
            border-radius: 999px;
            background: #16a34a;
        }

        .fleet-status.rented::before {
            background: #f59e0b;
        }

        .fleet-actions {
            margin-top: .7rem;
        }

        .btn-request {
            width: 100%;
            border: 1px solid #c6d9f2;
            background: #eef5ff;
            color: #0a3f8f;
            font-weight: 700;
            border-radius: 10px;
            padding: .5rem .75rem;
            cursor: pointer;
        }

        .btn-request:hover {
            background: #e2eeff;
        }

        .alert {
            margin-top: .8rem;
            border-radius: 10px;
            padding: .65rem .8rem;
            font-weight: 600;
            border: 1px solid;
        }

        .alert-success {
            color: #166534;
            background: #ecfdf3;
            border-color: #bde5cc;
        }

        .alert-danger {
            color: #991b1b;
            background: #fef2f2;
            border-color: #fecaca;
        }

        .request-modal {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.56);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            z-index: 60;
        }

        .request-modal.open {
            display: flex;
        }

        .request-panel {
            width: min(620px, 100%);
            max-height: calc(100vh - 2rem);
            overflow: auto;
            background: #fff;
            border: 1px solid #dbe6f3;
            border-radius: 14px;
            padding: 1rem;
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.25);
        }

        .request-title {
            margin: 0 0 .25rem;
            font-size: 1.2rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
        }

        .request-sub {
            margin: 0 0 .8rem;
            color: #64748b;
            font-size: .9rem;
        }

        .request-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .65rem;
        }

        .request-field label {
            display: block;
            margin-bottom: .35rem;
            font-size: .74rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
            font-weight: 700;
        }

        .request-field input,
        .request-field textarea {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 10px;
            padding: .62rem .7rem;
            color: #0f172a;
            font: inherit;
        }

        .request-field textarea {
            min-height: 130px;
            resize: vertical;
        }

        .request-field.full {
            grid-column: 1 / -1;
        }

        .request-actions {
            margin-top: .85rem;
            display: flex;
            justify-content: flex-end;
            gap: .55rem;
        }

        .btn-cancel {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #334155;
        }

        @media (max-width: 1050px) {
            .fleet-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .filter-grid .filter-submit-wrap { grid-column: 1 / -1; }
        }

        @media (max-width: 700px) {
            .container { width: min(1220px, calc(100% - 1.2rem)); }
            .topbar-inner { min-height: 66px; }
            .brand-name { font-size: 1rem; }
            .hero-title { font-size: 1.5rem; }
            .fleet-grid { grid-template-columns: 1fr; }
            .filter-grid { grid-template-columns: 1fr; }
            .fleet-photo { height: 190px; }
            .top-actions .btn { padding: .5rem .72rem; font-size: .82rem; }
            .request-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals logo">
                <span class="brand-name">R&A Auto Rentals</span>
            </a>
            <div class="top-actions">
                <a class="btn btn-light" href="{{ route('home') }}">Back Home</a>
                @auth
                    <a class="btn btn-primary" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <div class="hero-card">
                <h1 class="hero-title">Full Fleet</h1>
                <p class="hero-sub">Check vehicle availability for your selected date period and pickup location.</p>
                <p class="hero-policy">Rental options: With driver or without driver. Daily package includes 150 km. Extra distance is charged at Rs 25 per km.</p>
                <form class="filter-grid" id="fleetFilterForm" method="get" action="{{ route('fleet.index') }}" novalidate>
                    <div class="control">
                        <label for="start_location">Pickup Location</label>
                        <input id="start_location" name="start_location" type="text" value="{{ $filters['start_location'] }}" placeholder="City, Airport, or Address" required aria-describedby="fleet_start_location_error">
                        <small class="field-error" id="fleet_start_location_error"></small>
                    </div>
                    <div class="control">
                        <label for="start_date">Start Date</label>
                        <input id="start_date" name="start_date" type="date" value="{{ $filters['start_date'] }}" required aria-describedby="fleet_start_date_error">
                        <small class="field-error" id="fleet_start_date_error"></small>
                    </div>
                    <div class="control">
                        <label for="end_date">End Date</label>
                        <input id="end_date" name="end_date" type="date" value="{{ $filters['end_date'] }}" required aria-describedby="fleet_end_date_error">
                        <small class="field-error" id="fleet_end_date_error"></small>
                    </div>
                    <div class="control filter-submit-wrap">
                        <span class="filter-submit-spacer">Action</span>
                        <button class="btn btn-primary filter-submit" type="submit">Find Available Cars</button>
                    </div>
                </form>

                @if($filters['start_date'] && $filters['end_date'])
                    <div class="results-note">
                        System check completed for {{ $filters['start_date'] }} to {{ $filters['end_date'] }}.
                        Showing only available vehicles.
                        @if($filters['start_location'])
                            | Pickup: {{ $filters['start_location'] }}
                        @endif
                        ({{ $cars->count() }} result{{ $cars->count() === 1 ? '' : 's' }})
                    </div>
                @else
                    <div class="results-note">
                        Showing all vehicles. Select start and end dates to filter by availability.
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="fleet-grid">
            @forelse($cars as $car)
                <article class="fleet-card">
                    <div class="fleet-photo">
                        <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='1.5rem';">
                    </div>
                    <div class="fleet-body">
                        <div class="fleet-title-row">
                            <h3>{{ $car['name'] ?: $car['plate_no'] }}</h3>
                            @if($car['rate'])
                                <div class="fleet-price">Rs {{ $car['rate'] }}<span class="fleet-rate-unit">/day</span></div>
                            @else
                                <div class="fleet-price">Rate on request</div>
                            @endif
                        </div>
                        <div class="fleet-sub">{{ $car['plate_no'] }}</div>
                        <div class="fleet-policy">With driver / Without driver · 150 km/day included · Rs 25 per extra km</div>
                        <div class="fleet-meta">
                            @if($car['year']) <span>{{ $car['year'] }}</span> @endif
                            @if($car['make']) <span>{{ $car['make'] }}</span> @endif
                            @if($car['model']) <span>{{ $car['model'] }}</span> @endif
                            @if($car['transmission']) <span>{{ $car['transmission'] }}</span> @endif
                            @if($car['fuel_type']) <span>{{ $car['fuel_type'] }}</span> @endif
                            @if($car['color']) <span>{{ $car['color'] }}</span> @endif
                        </div>
                        @if($filters['start_date'] && $filters['end_date'])
                            <div class="fleet-status">Available</div>
                        @else
                            <div class="fleet-status {{ $car['status'] === 'rented' ? 'rented' : '' }}">
                                {{ $car['status'] === 'rented' ? 'Rented' : 'Available' }}
                            </div>
                        @endif
                        <div class="fleet-actions">
                            @if($filters['start_date'] && $filters['end_date'])
                                <a
                                    class="btn-request"
                                    style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-bottom:.45rem;background:linear-gradient(135deg,#0a3f8f,#0f66c3);color:#fff;border-color:transparent;"
                                    href="{{ route('booking.confirm', [
                                        'car_id' => $car['id'],
                                        'start_date' => $filters['start_date'],
                                        'end_date' => $filters['end_date'],
                                        'start_location' => $filters['start_location'],
                                    ]) }}"
                                >
                                    Continue to Book
                                </a>
                            @else
                                <button
                                    type="button"
                                    class="btn-request"
                                    style="margin-bottom:.45rem;background:#dbe6f3;color:#475569;border-color:#c8d7ea;cursor:not-allowed;"
                                    title="Select start date and end date first"
                                    disabled
                                >
                                    Continue to Book
                                </button>
                                <div style="font-size:.78rem;color:#64748b;margin:-.2rem 0 .35rem;">Select start and end dates first.</div>
                            @endif
                            <button
                                type="button"
                                class="btn-request js-request-btn"
                                data-car-id="{{ $car['id'] }}"
                                data-car="{{ $car['name'] ?: $car['plate_no'] }}"
                                data-plate="{{ $car['plate_no'] }}"
                                data-start-date="{{ $filters['start_date'] }}"
                                data-end-date="{{ $filters['end_date'] }}"
                                data-start-location="{{ $filters['start_location'] }}"
                            >
                                Rent on Request
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="hero-card" style="grid-column: 1 / -1;">
                    @if($filters['start_date'] && $filters['end_date'])
                        No vehicles are available for the selected date range.
                    @else
                        No cars found in the fleet yet.
                    @endif
                </div>
            @endforelse
        </section>
    </main>

    <div class="request-modal" id="requestModal" aria-hidden="true">
        <div class="request-panel" role="dialog" aria-modal="true" aria-labelledby="requestTitle">
            <h2 class="request-title" id="requestTitle">Rent on Request</h2>
            <p class="request-sub" id="requestSub">Submit your request and our team will contact you.</p>

            <form method="post" action="{{ route('rent-requests.store') }}">
                @csrf
                <input type="hidden" name="car_id" id="requestCarId">
                <input type="hidden" name="car_name" id="requestCarName">
                <input type="hidden" name="plate_no" id="requestPlateNo">
                <input type="hidden" name="start_location" id="requestStartLocation">
                <input type="hidden" name="start_date" id="requestStartDate">
                <input type="hidden" name="end_date" id="requestEndDate">
                <div class="request-grid">
                    <div class="request-field">
                        <label for="requestName">Name</label>
                        <input id="requestName" name="name" type="text" required>
                    </div>
                    <div class="request-field">
                        <label for="requestPhone">Phone</label>
                        <input id="requestPhone" name="phone" type="text" placeholder="+94 ...">
                    </div>
                    <div class="request-field full">
                        <label for="requestEmail">Email</label>
                        <input id="requestEmail" name="email" type="email" placeholder="you@example.com">
                    </div>
                    <div class="request-field full">
                        <label for="requestMessage">Request Details</label>
                        <textarea id="requestMessage" name="message" required></textarea>
                    </div>
                </div>
                <div class="request-actions">
                    <button type="button" class="btn btn-cancel" id="requestCancel">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('requestModal');
            const cancelBtn = document.getElementById('requestCancel');
            const messageEl = document.getElementById('requestMessage');
            const subEl = document.getElementById('requestSub');
            const openButtons = document.querySelectorAll('.js-request-btn');
            const carIdEl = document.getElementById('requestCarId');
            const carNameEl = document.getElementById('requestCarName');
            const plateNoEl = document.getElementById('requestPlateNo');
            const startLocationEl = document.getElementById('requestStartLocation');
            const startDateEl = document.getElementById('requestStartDate');
            const endDateEl = document.getElementById('requestEndDate');

            const closeModal = () => {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const carId = button.dataset.carId || '';
                    const car = button.dataset.car || '-';
                    const plate = button.dataset.plate || '-';
                    const startDate = button.dataset.startDate || 'Not selected';
                    const endDate = button.dataset.endDate || 'Not selected';
                    const startLocation = button.dataset.startLocation || 'Not selected';

                    carIdEl.value = carId;
                    carNameEl.value = car;
                    plateNoEl.value = plate;
                    startLocationEl.value = startLocation === 'Not selected' ? '' : startLocation;
                    startDateEl.value = startDate === 'Not selected' ? '' : startDate;
                    endDateEl.value = endDate === 'Not selected' ? '' : endDate;

                    subEl.textContent = `Vehicle: ${car} (${plate})`;
                    messageEl.value =
`Rental request:
Vehicle: ${car} (${plate})
Pickup location: ${startLocation}
Start date: ${startDate}
End date: ${endDate}
Please contact me with availability and final rent details.`;

                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                });
            });

            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            const filterForm = document.getElementById('fleetFilterForm');
            const filterPickupInput = document.getElementById('start_location');
            const filterStartDateInput = document.getElementById('start_date');
            const filterEndDateInput = document.getElementById('end_date');
            const filterPickupError = document.getElementById('fleet_start_location_error');
            const filterStartDateError = document.getElementById('fleet_start_date_error');
            const filterEndDateError = document.getElementById('fleet_end_date_error');
            let hasTriedFilterSubmit = false;

            const showFilterError = (input, errorEl, message) => {
                input.classList.add('input-error');
                errorEl.textContent = message;
                errorEl.classList.add('show');
            };

            const clearFilterError = (input, errorEl) => {
                input.classList.remove('input-error');
                errorEl.textContent = '';
                errorEl.classList.remove('show');
            };

            const syncFilterEndDateMin = () => {
                if (!filterStartDateInput.value) {
                    filterEndDateInput.min = '';
                    return;
                }

                filterEndDateInput.min = filterStartDateInput.value;
                if (filterEndDateInput.value && filterEndDateInput.value < filterStartDateInput.value) {
                    filterEndDateInput.value = '';
                }
            };

            const validateFilterForm = (focusFirstInvalid = true) => {
                let isValid = true;
                let firstInvalidInput = null;

                clearFilterError(filterPickupInput, filterPickupError);
                clearFilterError(filterStartDateInput, filterStartDateError);
                clearFilterError(filterEndDateInput, filterEndDateError);

                if (!filterPickupInput.value.trim()) {
                    showFilterError(filterPickupInput, filterPickupError, 'Please enter pickup location.');
                    firstInvalidInput = firstInvalidInput || filterPickupInput;
                    isValid = false;
                }

                if (!filterStartDateInput.value) {
                    showFilterError(filterStartDateInput, filterStartDateError, 'Please select a start date.');
                    firstInvalidInput = firstInvalidInput || filterStartDateInput;
                    isValid = false;
                }

                if (!filterEndDateInput.value) {
                    showFilterError(filterEndDateInput, filterEndDateError, 'Please select an end date.');
                    firstInvalidInput = firstInvalidInput || filterEndDateInput;
                    isValid = false;
                }

                if (filterStartDateInput.value && filterEndDateInput.value && filterEndDateInput.value < filterStartDateInput.value) {
                    showFilterError(filterEndDateInput, filterEndDateError, 'End date must be on or after start date.');
                    firstInvalidInput = firstInvalidInput || filterEndDateInput;
                    isValid = false;
                }

                if (!isValid && firstInvalidInput && focusFirstInvalid) {
                    firstInvalidInput.focus();
                }

                return isValid;
            };

            filterForm.addEventListener('submit', (event) => {
                hasTriedFilterSubmit = true;
                if (!validateFilterForm()) {
                    event.preventDefault();
                }
            });

            [filterPickupInput, filterStartDateInput, filterEndDateInput].forEach((input) => {
                input.addEventListener('input', () => {
                    if (input === filterStartDateInput) {
                        syncFilterEndDateMin();
                    }
                    if (!hasTriedFilterSubmit) {
                        return;
                    }
                    validateFilterForm(false);
                });
            });

            syncFilterEndDateMin();
        })();
    </script>
</body>
</html>
