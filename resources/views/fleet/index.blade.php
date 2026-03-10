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
            gap: .45rem;
            border: 1px solid transparent;
            text-decoration: none;
            font-weight: 700;
            padding: .58rem .95rem;
            border-radius: 10px;
            font-size: .9rem;
        }

        .btn .btn-spinner,
        .btn-request .btn-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 999px;
            animation: btn-spin .7s linear infinite;
            flex-shrink: 0;
        }

        .btn-light .btn-spinner,
        .btn-request .btn-spinner {
            border-color: rgba(15, 23, 42, 0.22);
            border-top-color: #0f66c3;
        }

        .btn.is-loading .btn-spinner,
        .btn-request.is-loading .btn-spinner {
            display: inline-block;
        }

        .btn.is-loading,
        .btn-request.is-loading {
            pointer-events: none;
        }

        @keyframes btn-spin {
            to { transform: rotate(360deg); }
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

        .control {
            min-width: 0;
            overflow: hidden;
        }

        .control-shell {
            width: 100%;
            height: var(--filter-control-height);
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 10px;
            overflow: hidden;
        }

        .control-shell input {
            width: 100%;
            min-width: 0;
            max-width: 100%;
            height: 100%;
            min-height: 100%;
            border: 0;
            background: transparent;
            border-radius: 10px;
            padding: 0 .7rem;
            color: #0f172a;
            font: inherit;
            box-sizing: border-box;
        }

        .control-shell.date-shell {
            position: relative;
        }

        .control-shell.date-shell::after {
            content: "";
            position: absolute;
            right: .7rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%236b7f9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: 18px 18px;
            pointer-events: none;
            opacity: .9;
        }

        .control-shell input[type="date"] {
            width: 100%;
            min-width: 0;
            max-width: 100%;
            height: 100% !important;
            min-height: 100% !important;
            padding: 0 2.2rem 0 .7rem;
            -webkit-appearance: auto;
            appearance: auto;
            text-align: left;
        }

        .control-shell input[type="date"]::-webkit-datetime-edit {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .control-shell input[type="date"]::-webkit-date-and-time-value {
            text-align: left;
            height: 100%;
            display: flex;
            align-items: center;
        }

        .control input.input-error {
            background: #fff7f7;
        }

        .control-shell.input-error {
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
            overflow: hidden;
            background: #fff;
            border: 1px solid #dbe6f3;
            border-radius: 14px;
            box-shadow: 0 24px 54px rgba(15, 23, 42, 0.25);
        }

        .request-panel-body {
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem;
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 #e2e8f0;
        }

        .request-panel-body::-webkit-scrollbar {
            width: 10px;
        }

        .request-panel-body::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 999px;
        }

        .request-panel-body::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 999px;
            border: 2px solid #e2e8f0;
            background-clip: padding-box;
        }

        .request-panel-body::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .request-panel-body::-webkit-scrollbar-button {
            width: 0;
            height: 0;
            display: none;
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

        .request-field input.input-error,
        .request-field textarea.input-error,
        .request-summary.input-error {
            border-color: #dc2626 !important;
            background: #fff7f7 !important;
        }

        .request-error {
            display: none;
            margin-top: .35rem;
            color: #b91c1c;
            font-size: .8rem;
            font-weight: 600;
            line-height: 1.3;
        }

        .request-error.show {
            display: block;
        }

        .request-summary {
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            background: #f8fbff;
            padding: .7rem .8rem;
        }

        .request-summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .55rem .8rem;
        }

        .request-summary-item {
            padding: .45rem .55rem;
            border-radius: 10px;
            border: 1px solid #dbe6f3;
            background: #fff;
        }

        .request-summary-item strong {
            display: block;
            font-size: .7rem;
            color: #64748b;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: .2rem;
        }

        .request-summary-item span {
            color: #0f172a;
            font-weight: 600;
            font-size: .9rem;
            line-height: 1.35;
        }

        .request-summary-item input {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 8px;
            padding: .46rem .5rem;
            color: #0f172a;
            font: inherit;
            font-size: .9rem;
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
            .request-summary-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('partials.public-header')
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
                <p class="hero-policy">Rental options vary by vehicle. Check each vehicle card for driver availability, included daily KM, and extra KM charge.</p>
                <form class="filter-grid" id="fleetFilterForm" method="get" action="{{ route('fleet.index') }}" novalidate>
                    <div class="control">
                        <label for="start_location">Pickup Location</label>
                        <div class="control-shell">
                            <input id="start_location" name="start_location" type="text" value="{{ $filters['start_location'] }}" placeholder="City, Airport, or Address" required aria-describedby="fleet_start_location_error">
                        </div>
                        <small class="field-error" id="fleet_start_location_error"></small>
                    </div>
                    <div class="control">
                        <label for="start_date">Start Date</label>
                        <div class="control-shell date-shell">
                            <input id="start_date" name="start_date" type="date" value="{{ $filters['start_date'] }}" required aria-describedby="fleet_start_date_error">
                        </div>
                        <small class="field-error" id="fleet_start_date_error"></small>
                    </div>
                    <div class="control">
                        <label for="end_date">End Date</label>
                        <div class="control-shell date-shell">
                            <input id="end_date" name="end_date" type="date" value="{{ $filters['end_date'] }}" required aria-describedby="fleet_end_date_error">
                        </div>
                        <small class="field-error" id="fleet_end_date_error"></small>
                    </div>
                    <div class="control filter-submit-wrap">
                        <span class="filter-submit-spacer">Action</span>
                        <button class="btn btn-primary filter-submit js-loading-submit" type="submit" data-loading-text="Checking Availability...">
                            <span class="btn-spinner" aria-hidden="true"></span>
                            <span class="btn-label">Find Available Cars</span>
                        </button>
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
                <article class="fleet-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] ?: $car['plate_no'] }}">
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
                        <div class="fleet-policy">
                            @if(($car['driver_mode'] ?? 'both') === 'with_driver_only')
                                With driver only
                            @elseif(($car['driver_mode'] ?? 'both') === 'without_driver_only')
                                Without driver only
                            @else
                                With driver / Without driver
                            @endif
                            · {{ number_format((float) ($car['per_day_km'] ?? 150), 0) }} km/day included
                            · Rs {{ number_format((float) ($car['extra_km_rate'] ?? 25), 0) }} per extra km
                        </div>
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
                            <a
                                class="btn-request"
                                style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-bottom:.45rem;background:#eef5ff;color:#0a3f8f;border-color:#c8d7ea;"
                                href="{{ route('fleet.show', $car['id']) }}"
                            >
                                Details
                            </a>
                            @if($filters['start_date'] && $filters['end_date'])
                                <a
                                    class="btn-request"
                                    data-loading-link="true"
                                    data-loading-text="Opening..."
                                    style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;margin-bottom:.45rem;background:linear-gradient(135deg,#0a3f8f,#0f66c3);color:#fff;border-color:transparent;"
                                    href="{{ route('booking.confirm', [
                                        'car_id' => $car['id'],
                                        'start_date' => $filters['start_date'],
                                        'end_date' => $filters['end_date'],
                                        'start_location' => $filters['start_location'],
                                    ]) }}"
                                >
                                    <span class="btn-spinner" aria-hidden="true"></span>
                                    <span class="btn-label">Continue to Book</span>
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
                                <span class="btn-spinner" aria-hidden="true"></span>
                                <span class="btn-label">Rent on Request</span>
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
    @include('partials.public-footer')

    <div class="request-modal" id="requestModal" aria-hidden="true">
        <div class="request-panel" role="dialog" aria-modal="true" aria-labelledby="requestTitle">
            <div class="request-panel-body">
                <h2 class="request-title" id="requestTitle">Rent on Request</h2>
                <p class="request-sub" id="requestSub">Submit your request and our team will contact you.</p>
                @php
                    $currentUser = auth()->user();
                @endphp

                <form id="requestForm" method="post" action="{{ route('rent-requests.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="car_id" id="requestCarId">
                    <input type="hidden" name="car_name" id="requestCarName">
                    <input type="hidden" name="plate_no" id="requestPlateNo">
                    <div class="request-grid">
                        <div class="request-field">
                            <label for="requestName">Name</label>
                            <input id="requestName" name="name" type="text" value="{{ old('name', $currentUser?->name) }}" required>
                            <small class="request-error" id="requestNameError"></small>
                        </div>
                        <div class="request-field">
                            <label for="requestPhone">Phone</label>
                            <input id="requestPhone" name="phone" type="text" value="{{ old('phone', $currentUser?->phone) }}" placeholder="+94 ...">
                            <small class="request-error" id="requestPhoneError"></small>
                        </div>
                        <div class="request-field full">
                            <label for="requestEmail">Email</label>
                            <input id="requestEmail" name="email" type="email" value="{{ old('email', $currentUser?->email) }}" placeholder="you@example.com">
                            <small class="request-error" id="requestEmailError"></small>
                        </div>
                        <div class="request-field full">
                            <label>Rental Details</label>
                            <div class="request-summary" id="requestSummaryBlock">
                                <div class="request-summary-grid">
                                    <div class="request-summary-item">
                                        <strong>Vehicle</strong>
                                        <span id="requestSummaryVehicle">-</span>
                                    </div>
                                    <div class="request-summary-item">
                                        <strong>Pickup</strong>
                                        <input type="text" id="requestStartLocation" name="start_location" placeholder="City, Airport, or Address">
                                    </div>
                                    <div class="request-summary-item">
                                        <strong>Start Date</strong>
                                        <input type="date" id="requestStartDate" name="start_date">
                                    </div>
                                    <div class="request-summary-item">
                                        <strong>End Date</strong>
                                        <input type="date" id="requestEndDate" name="end_date">
                                    </div>
                                </div>
                            </div>
                            <small class="request-error" id="requestDetailsError"></small>
                        </div>
                        <div class="request-field full">
                            <label for="requestMessage">Additional Note (Optional)</label>
                            <textarea id="requestMessage" name="message" placeholder="Any special request, pickup time, child seat, driver, etc."></textarea>
                        </div>
                    </div>
                    <div class="request-actions">
                        <button type="button" class="btn btn-cancel" id="requestCancel">Cancel</button>
                        <button type="submit" class="btn btn-primary js-loading-submit" data-loading-text="Sending Request...">
                            <span class="btn-spinner" aria-hidden="true"></span>
                            <span class="btn-label">Send Request</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const setButtonLoading = (button, loadingText) => {
                if (!button || button.classList.contains('is-loading')) return;
                const label = button.querySelector('.btn-label');
                if (label) {
                    label.dataset.originalText = label.textContent;
                    label.textContent = loadingText || button.dataset.loadingText || 'Loading...';
                }
                button.classList.add('is-loading');
                if (button.tagName === 'BUTTON') {
                    button.disabled = true;
                }
            };

            const clearButtonLoading = (button) => {
                if (!button) return;
                const label = button.querySelector('.btn-label');
                if (label && label.dataset.originalText) {
                    label.textContent = label.dataset.originalText;
                }
                button.classList.remove('is-loading');
                if (button.tagName === 'BUTTON') {
                    button.disabled = false;
                }
            };

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
            const requestNameEl = document.getElementById('requestName');
            const requestPhoneEl = document.getElementById('requestPhone');
            const requestEmailEl = document.getElementById('requestEmail');
            const requestForm = document.getElementById('requestForm');
            const requestSummaryBlockEl = document.getElementById('requestSummaryBlock');
            const requestSummaryVehicleEl = document.getElementById('requestSummaryVehicle');
            const requestNameErrorEl = document.getElementById('requestNameError');
            const requestPhoneErrorEl = document.getElementById('requestPhoneError');
            const requestEmailErrorEl = document.getElementById('requestEmailError');
            const requestDetailsErrorEl = document.getElementById('requestDetailsError');
            const requestSubmitBtn = requestForm ? requestForm.querySelector('.js-loading-submit') : null;
            const defaultRequestName = @json(old('name', $currentUser?->name ?? ''));
            const defaultRequestPhone = @json(old('phone', $currentUser?->phone ?? ''));
            const defaultRequestEmail = @json(old('email', $currentUser?->email ?? ''));

            const showRequestError = (inputEl, errorEl, message) => {
                if (inputEl) inputEl.classList.add('input-error');
                if (errorEl) {
                    errorEl.textContent = message;
                    errorEl.classList.add('show');
                }
            };

            const clearRequestError = (inputEl, errorEl) => {
                if (inputEl) inputEl.classList.remove('input-error');
                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.classList.remove('show');
                }
            };

            const validateRequestForm = () => {
                let valid = true;

                clearRequestError(requestNameEl, requestNameErrorEl);
                clearRequestError(requestPhoneEl, requestPhoneErrorEl);
                clearRequestError(requestEmailEl, requestEmailErrorEl);
                clearRequestError(requestSummaryBlockEl, requestDetailsErrorEl);

                if (!requestNameEl.value.trim()) {
                    showRequestError(requestNameEl, requestNameErrorEl, 'Please enter your name.');
                    valid = false;
                }

                const phoneValue = (requestPhoneEl.value || '').trim();
                const emailValue = (requestEmailEl.value || '').trim();
                const phoneDigits = phoneValue.replace(/\D/g, '');
                const hasPhone = phoneValue.length > 0;
                const hasEmail = emailValue.length > 0;

                if (!hasPhone) {
                    showRequestError(requestPhoneEl, requestPhoneErrorEl, 'Please enter phone number.');
                    valid = false;
                } else if (phoneDigits.length < 9 || phoneDigits.length > 15) {
                    showRequestError(requestPhoneEl, requestPhoneErrorEl, 'Please enter a valid phone number.');
                    valid = false;
                }

                if (!hasEmail) {
                    showRequestError(requestEmailEl, requestEmailErrorEl, 'Please enter email.');
                    valid = false;
                } else {
                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailPattern.test(emailValue)) {
                        showRequestError(requestEmailEl, requestEmailErrorEl, 'Please enter a valid email address.');
                        valid = false;
                    }
                }

                if (!startLocationEl.value.trim() || !startDateEl.value || !endDateEl.value) {
                    showRequestError(requestSummaryBlockEl, requestDetailsErrorEl, 'Please select pickup location, start date, and end date first.');
                    valid = false;
                }

                if (startDateEl.value && endDateEl.value && endDateEl.value < startDateEl.value) {
                    showRequestError(requestSummaryBlockEl, requestDetailsErrorEl, 'End date must be on or after start date.');
                    valid = false;
                }

                return valid;
            };

            const syncRequestEndDateMin = () => {
                endDateEl.min = startDateEl.value || '';
                if (startDateEl.value && endDateEl.value && endDateEl.value < startDateEl.value) {
                    endDateEl.value = '';
                }
            };

            const closeModal = () => {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
            };

            openButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    setButtonLoading(button, 'Opening...');
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
                    syncRequestEndDateMin();
                    if (requestNameEl) requestNameEl.value = defaultRequestName;
                    if (requestPhoneEl) requestPhoneEl.value = defaultRequestPhone;
                    if (requestEmailEl) requestEmailEl.value = defaultRequestEmail;
                    clearRequestError(requestNameEl, requestNameErrorEl);
                    clearRequestError(requestPhoneEl, requestPhoneErrorEl);
                    clearRequestError(requestEmailEl, requestEmailErrorEl);
                    clearRequestError(requestSummaryBlockEl, requestDetailsErrorEl);
                    if (requestSummaryVehicleEl) requestSummaryVehicleEl.textContent = `${car} (${plate})`;

                    subEl.textContent = `Vehicle: ${car} (${plate})`;
                    messageEl.value = 'Please contact me with availability and final rent details.';

                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                    setTimeout(() => clearButtonLoading(button), 250);
                });
            });

            cancelBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            if (requestForm) {
                requestForm.addEventListener('submit', (event) => {
                    if (!validateRequestForm()) {
                        event.preventDefault();
                        clearButtonLoading(requestSubmitBtn);
                        return;
                    }
                    setButtonLoading(requestSubmitBtn, 'Sending Request...');
                });
            }

            [requestNameEl, requestPhoneEl, requestEmailEl].forEach((el) => {
                if (!el) return;
                el.addEventListener('input', () => {
                    validateRequestForm();
                });
            });

            [startLocationEl, startDateEl, endDateEl].forEach((el) => {
                if (!el) return;
                el.addEventListener('input', () => {
                    if (el === startDateEl) {
                        syncRequestEndDateMin();
                    }
                    validateRequestForm();
                });
            });

            const filterForm = document.getElementById('fleetFilterForm');
            const filterPickupInput = document.getElementById('start_location');
            const filterStartDateInput = document.getElementById('start_date');
            const filterEndDateInput = document.getElementById('end_date');
            const filterPickupError = document.getElementById('fleet_start_location_error');
            const filterStartDateError = document.getElementById('fleet_start_date_error');
            const filterEndDateError = document.getElementById('fleet_end_date_error');
            const filterSubmitBtn = filterForm ? filterForm.querySelector('.js-loading-submit') : null;
            let hasTriedFilterSubmit = false;

            const showFilterError = (input, errorEl, message) => {
                input.classList.add('input-error');
                const wrapper = input.closest('.control-shell');
                if (wrapper) wrapper.classList.add('input-error');
                errorEl.textContent = message;
                errorEl.classList.add('show');
            };

            const clearFilterError = (input, errorEl) => {
                input.classList.remove('input-error');
                const wrapper = input.closest('.control-shell');
                if (wrapper) wrapper.classList.remove('input-error');
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
                    clearButtonLoading(filterSubmitBtn);
                    return;
                }
                setButtonLoading(filterSubmitBtn, 'Checking Availability...');
            });

            document.querySelectorAll('[data-loading-link="true"]').forEach((link) => {
                link.addEventListener('click', () => {
                    setButtonLoading(link, link.dataset.loadingText || 'Opening...');
                });
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






