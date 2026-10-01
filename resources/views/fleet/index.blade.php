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
            -webkit-appearance: none;
            appearance: none;
            text-align: left;
            position: relative;
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

        .control-shell input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            opacity: 0;
            color: transparent;
            background: transparent;
            cursor: pointer;
            display: block;
        }

        .control-shell input[type="date"]::-webkit-clear-button,
        .control-shell input[type="date"]::-webkit-inner-spin-button {
            display: none;
            -webkit-appearance: none;
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
            gap: .72rem;
        }

        .fleet-card {
            border: 1px solid #d6e3f2;
            background: var(--surface);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.06);
            transition: transform .2s ease, box-shadow .2s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .fleet-photo {
            position: relative;
            height: 162px;
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

        .fleet-availability {
            position: absolute;
            top: .8rem;
            right: .8rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border-radius: 999px;
            padding: .38rem .75rem;
            background: rgba(255, 255, 255, 0.96);
            color: #0f172a;
            font-size: .77rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            border: 1px solid #d9e5f4;
        }

        .fleet-availability::before {
            content: "";
            width: .5rem;
            height: .5rem;
            border-radius: 999px;
            background: #16a34a;
        }

        .fleet-availability.rented::before {
            background: #f97316;
        }

        .fleet-body {
            padding: .68rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .fleet-kicker {
            margin: 0 0 .24rem;
            color: #0a3f8f;
            font-size: .64rem;
            font-weight: 800;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        .fleet-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .62rem;
            margin-bottom: .4rem;
        }

        .fleet-title-meta {
            min-width: 0;
        }

        .fleet-title-row h3 {
            margin: 0;
            font-size: 1.18rem;
            line-height: 1.06;
            letter-spacing: -.02em;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            word-break: break-word;
        }

        .fleet-price {
            text-align: right;
            white-space: normal;
            flex-shrink: 0;
            min-width: 100px;
        }

        .fleet-price-amount {
            display: block;
            color: #0f172a;
            font-weight: 800;
            font-size: 1.38rem;
            line-height: 1;
            letter-spacing: -.01em;
        }

        .fleet-rate-unit {
            color: #64748b;
            font-size: .8rem;
            font-weight: 600;
            margin-top: .22rem;
            display: block;
        }

        .fleet-policy {
            color: #1e3a8a;
            font-size: .7rem;
            line-height: 1.45;
            margin-bottom: .48rem;
            font-weight: 700;
            min-height: calc(1.45em * 2);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .fleet-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .36rem;
            margin-bottom: .7rem;
        }

        .fleet-meta span {
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d8e4f4;
            background: #f4f8ff;
            color: #334155;
            border-radius: 9px;
            font-size: .72rem;
            font-weight: 700;
            padding: .28rem .34rem;
            text-align: center;
        }

        .fleet-actions {
            margin-top: .1rem;
            margin-top: auto;
        }

        .fleet-primary-action {
            margin-bottom: .45rem;
        }

        .fleet-secondary-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .42rem;
        }

        .btn-request {
            width: 100%;
            border: 1px solid #d2dff0;
            background: #ffffff;
            color: #0f172a;
            font-weight: 700;
            border-radius: 9px;
            padding: .5rem .62rem;
            min-height: 38px;
            cursor: pointer;
            transition: all .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: .84rem;
        }

        .btn-request:hover {
            background: #f8fbff;
            border-color: #bfd1e9;
        }

        .btn-request.btn-primary-action {
            border-color: transparent;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #fff;
            box-shadow: 0 12px 22px rgba(15, 102, 195, 0.24);
            font-size: .88rem;
            font-weight: 800;
        }

        .btn-request.btn-primary-action:hover {
            filter: brightness(1.03);
        }

        .btn-request.btn-muted-action {
            background: #eef4ff;
            color: #64748b;
            border-color: #d4e1f2;
        }

        .fleet-tip {
            margin: 0 0 .36rem;
            color: #64748b;
            font-size: .68rem;
            font-weight: 600;
        }

        .fleet-status {
            margin: 0 0 .45rem;
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #0f172a;
        }

        .fleet-status::before {
            content: "";
            width: .45rem;
            height: .45rem;
            border-radius: 999px;
            background: #16a34a;
        }

        .fleet-status.rented::before {
            background: #f97316;
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
            .hero-card {
                padding: .85rem;
                border-radius: 12px;
            }
            .hero-sub { font-size: .9rem; }
            .hero-policy {
                font-size: .82rem;
                line-height: 1.35;
            }
            .fleet-grid { grid-template-columns: 1fr; }
            .filter-grid { grid-template-columns: 1fr; }
            .fleet-card {
                border-radius: 14px;
                box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
            }
            .fleet-photo { height: 170px; }
            .fleet-availability {
                top: .55rem;
                right: .55rem;
                font-size: .68rem;
                padding: .26rem .58rem;
            }
            .fleet-body { padding: .8rem; }
            .fleet-title-row { gap: .5rem; }
            .fleet-title-row h3 { font-size: 1.12rem; }
            .fleet-price {
                text-align: right;
                min-width: 0;
            }
            .fleet-price-amount { font-size: 1.3rem; }
            .fleet-rate-unit { font-size: .78rem; }
            .fleet-policy {
                font-size: .74rem;
                min-height: auto;
                -webkit-line-clamp: 3;
            }
            .fleet-meta {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: .4rem;
            }
            .fleet-meta span {
                min-height: 34px;
                font-size: .69rem;
                white-space: nowrap;
            }
            .fleet-status {
                font-size: .7rem;
                margin-bottom: .5rem;
            }
            .btn-request {
                min-height: 42px;
                font-size: .9rem;
            }
            .fleet-secondary-actions { grid-template-columns: 1fr; }
            .top-actions .btn-light { display: none; }
            .top-actions .btn-primary { min-height: 38px; }
            .top-actions .btn { padding: .5rem .72rem; font-size: .82rem; }
            .request-grid { grid-template-columns: 1fr; }
            .request-summary-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 420px) {
            .container { width: calc(100% - .9rem); }
            .fleet-photo { height: 158px; }
            .fleet-body { padding: .7rem; }
            .fleet-title-row h3 { font-size: 1.03rem; }
            .fleet-price-amount { font-size: 1.2rem; }
            .fleet-meta span { min-height: 32px; font-size: .66rem; }
            .btn-request { min-height: 40px; font-size: .86rem; }
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
                <p class="hero-policy">Each vehicle has different rental details. Check the card to see driver option, daily KM included, and extra KM cost.</p>
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
                    <div class="control">
                        <label for="order_type">Hire or Rent</label>
                        <div class="control-shell">
                            <select id="order_type" name="order_type">
                                <option value="" @selected(($filters['order_type'] ?? '') === '')>Either</option>
                                <option value="hire" @selected(($filters['order_type'] ?? '') === 'hire')>Hire</option>
                                <option value="rent" @selected(($filters['order_type'] ?? '') === 'rent')>Rent</option>
                            </select>
                        </div>
                    </div>
                    <div class="control filter-submit-wrap">
                        <span class="filter-submit-spacer">Action</span>
                        <button class="btn btn-primary filter-submit js-loading-submit" type="submit" data-loading-text="Checking Availability...">
                            <span class="btn-spinner" aria-hidden="true"></span>
                            <span class="btn-label">Find Available Vehicles</span>
                        </button>
                    </div>
                </form>

                <p class="fleet-tip">
                    Multi-stop trip or not sure which vehicle you need?
                    <a class="btn-request" href="{{ route('rent-requests.create') }}">Request a Custom Trip</a>
                </p>

                @if($filters['start_date'] && $filters['end_date'])
                    <div class="results-note">
                        Availability checked for {{ $filters['start_date'] }} to {{ $filters['end_date'] }}.
                        We are showing vehicles available for your selected dates.
                        @if($filters['start_location'])
                            Pickup location: {{ $filters['start_location'] }}.
                        @endif
                        {{ $cars->count() }} vehicle{{ $cars->count() === 1 ? '' : 's' }} found.
                    </div>
                @else
                    <div class="results-note">
                        Showing all vehicles. Select your start and end dates to check availability.
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
                @php
                    $hasDateFilter = !empty($filters['start_date']) && !empty($filters['end_date']);
                    $isAvailable = $hasDateFilter ? true : (($car['status'] ?? 'available') !== 'rented');
                    $availabilityLabel = $isAvailable ? 'Available' : 'Rented';
                @endphp
                <article class="fleet-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] ?: $car['plate_no'] }}">
                    <div class="fleet-photo">
                        <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='1.5rem';">
                        <span class="fleet-availability {{ $isAvailable ? '' : 'rented' }}">{{ $availabilityLabel }}</span>
                    </div>
                    <div class="fleet-body">
                        <p class="fleet-kicker">R&A Fleet</p>
                        <div class="fleet-title-row">
                            <div class="fleet-title-meta">
                                <h3>{{ $car['name'] ?: $car['plate_no'] }}</h3>
                            </div>
                            @if($car['rate'])
                                <div class="fleet-price">
                                    <span class="fleet-price-amount">Rs {{ $car['rate'] }}</span>
                                    <span class="fleet-rate-unit">per day</span>
                                </div>
                            @else
                                <div class="fleet-price">
                                    <span class="fleet-price-amount" style="font-size:1.2rem;">Rate on request</span>
                                </div>
                            @endif
                        </div>
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
                            @if($car['transmission']) <span>{{ $car['transmission'] }}</span> @else <span>Manual</span> @endif
                            @if($car['fuel_type']) <span>{{ $car['fuel_type'] }}</span> @else <span>Petrol</span> @endif
                            @if($car['color']) <span>{{ $car['color'] }}</span> @elseif($car['year']) <span>{{ $car['year'] }}</span> @else <span>R&A Ready</span> @endif
                        </div>
                        <div class="fleet-status {{ $isAvailable ? '' : 'rented' }}">{{ $availabilityLabel }}</div>
                        <div class="fleet-actions">
                            <div class="fleet-primary-action">
                                <a
                                    class="btn-request btn-primary-action"
                                    href="{{ route('rent-requests.create', ['vehicle' => ($car['name'] ?: $car['plate_no']) . ' (' . $car['plate_no'] . ')']) }}"
                                >
                                    Request This Vehicle
                                </a>
                            </div>
                            <div class="fleet-secondary-actions">
                                <a
                                    class="btn-request"
                                    href="{{ route('fleet.show', $car['id']) }}"
                                >
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="hero-card" style="grid-column: 1 / -1;">
                    @if($filters['start_date'] && $filters['end_date'])
                        No vehicles are available for the selected date range.
                    @else
                        No vehicles found in the fleet yet.
                    @endif
                </div>
            @endforelse
        </section>
    </main>
    @include('partials.public-footer')

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

            const resetAllLoadingButtons = () => {
                document.querySelectorAll('.is-loading').forEach((el) => {
                    clearButtonLoading(el);
                });
            };

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

            window.addEventListener('pageshow', () => {
                resetAllLoadingButtons();
            });
        })();
    </script>
</body>
</html>






