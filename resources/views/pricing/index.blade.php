<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Pricing Guide | R&A Auto Rentals',
        'description' => 'Compare rental pricing by vehicle make and model, including daily package, included KM, extra KM rate, and optional driver cost.',
        'keywords' => [
            'car rental pricing',
            'rental rates',
            'vehicle price list',
            'daily car rental rates',
            'driver cost rental',
            'extra km charge',
        ],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --line: #dbe6f3;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --primary-soft: #eaf2ff;
            --accent: #e8f3ff;
            --success-soft: #eefbf3;
            --success-text: #0f7a3b;
            --warning-soft: #fff6e7;
            --warning-text: #a16207;
            --radius: 16px;
            --shadow: 0 18px 46px rgba(10, 63, 143, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--text);
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            background: radial-gradient(68rem 30rem at 100% -20%, rgba(15, 102, 195, 0.14), transparent 70%), var(--bg);
        }

        .container {
            width: min(1180px, calc(100% - 2rem));
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
            gap: 1rem;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .72rem;
            text-decoration: none;
            color: inherit;
        }

        .brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #f8fbff;
            padding: 5px;
        }

        .brand-name {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.2rem;
            letter-spacing: -.02em;
            font-weight: 700;
            color: #0b1f3a;
        }

        .top-actions {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 700;
            padding: .62rem 1rem;
            font-size: .92rem;
            border: 1px solid #c8d9ef;
            color: #35537a;
            background: #f8fbff;
        }

        .btn-primary {
            border-color: transparent;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow: 0 12px 22px rgba(10, 63, 143, 0.22);
        }

        main {
            padding: 1.5rem 0 2.4rem;
        }

        .hero {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .hero-inner {
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 1rem;
            padding: 1.5rem;
        }

        .hero-copy h1 {
            margin: .35rem 0 .7rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(2rem, 4.2vw, 3rem);
            line-height: .98;
            letter-spacing: -.03em;
        }

        .eyebrow {
            display: inline-flex;
            padding: .38rem .72rem;
            border-radius: 999px;
            background: var(--primary-soft);
            border: 1px solid #c8dcfb;
            color: var(--primary-2);
            font-size: .74rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .hero-copy p {
            color: var(--muted);
            line-height: 1.72;
            margin: 0;
            max-width: 58ch;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .75rem;
            align-content: start;
        }

        .stat-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface-soft);
            padding: 1rem;
        }

        .stat-label {
            color: var(--muted);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .stat-value {
            margin-top: .45rem;
            font-size: 1.45rem;
            font-weight: 800;
            color: #0b1f3a;
        }

        .stat-note {
            margin-top: .35rem;
            color: #476280;
            font-size: .86rem;
            line-height: 1.45;
        }

        .notice {
            margin-top: 1rem;
            border: 1px solid #cfe0f7;
            background: #f8fbff;
            color: #3a5778;
            border-radius: 14px;
            padding: .9rem 1rem;
            line-height: 1.65;
        }

        .section {
            margin-top: 1.25rem;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.2rem;
            border-bottom: 1px solid var(--line);
        }

        .section-title {
            margin: 0;
            font-size: 1.5rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
        }

        .section-sub {
            margin: .25rem 0 0;
            color: var(--muted);
        }

        .pricing-table-wrap {
            overflow-x: auto;
        }

        .pricing-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 860px;
        }

        .pricing-table th,
        .pricing-table td {
            padding: 1rem 1.15rem;
            border-bottom: 1px solid #e3edf8;
            text-align: left;
            vertical-align: top;
        }

        .pricing-table th {
            font-size: .8rem;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #617792;
            background: #fbfdff;
        }

        .pricing-table tbody tr:hover {
            background: #fbfdff;
        }

        .model-name {
            font-weight: 800;
            color: #0b1f3a;
        }

        .make-text {
            display: block;
            margin-top: .18rem;
            color: var(--muted);
            font-size: .9rem;
        }

        .rate {
            font-weight: 800;
            color: var(--primary);
            font-size: 1.05rem;
        }

        .rate-stack {
            display: grid;
            gap: .55rem;
            min-width: 180px;
        }

        .rate-card {
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            background: #fbfdff;
            padding: .72rem .8rem;
        }

        .rate-label {
            display: block;
            margin-bottom: .18rem;
            color: #617792;
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .package-stack {
            display: grid;
            gap: .5rem;
            min-width: 220px;
        }

        .package-line {
            color: #476280;
            font-size: .92rem;
            line-height: 1.45;
        }

        .muted {
            color: var(--muted);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .84rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .pill-success {
            background: var(--success-soft);
            color: var(--success-text);
        }

        .pill-warning {
            background: var(--warning-soft);
            color: var(--warning-text);
        }

        .pill-neutral {
            background: #eef4fb;
            color: #476280;
        }

        .note-text {
            max-width: 28ch;
            color: #4d627d;
            line-height: 1.55;
        }

        .empty-state {
            padding: 2.5rem 1.2rem;
            text-align: center;
            color: var(--muted);
        }

        .footer {
            margin-top: 2.6rem;
            border-top: 1px solid #215fb2;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
        }

        .footer-inner {
            padding: 1.35rem 0 1.1rem;
            color: #d9e8ff;
            font-size: .9rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
            gap: 1.2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(219, 232, 255, 0.28);
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
        }

        .footer-brand img {
            width: 36px;
            height: 24px;
            object-fit: contain;
            margin-top: .15rem;
        }

        .footer-title {
            margin: 0 0 .45rem;
            font-size: .75rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #bfdbff;
            font-weight: 800;
        }

        .footer-brand-name {
            margin: 0 0 .35rem;
            font-size: .97rem;
            font-weight: 700;
            color: #f8fbff;
        }

        .footer-copy {
            margin: 0;
            color: #d9e8ff;
            font-size: .83rem;
            line-height: 1.5;
            max-width: 28ch;
        }

        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: .35rem;
        }

        .footer-links a {
            color: #e7f0ff;
            text-decoration: none;
            font-size: .85rem;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        .newsletter-note {
            margin: 0 0 .55rem;
            font-size: .82rem;
            color: #d9e8ff;
        }

        .newsletter-form {
            display: flex;
            gap: .4rem;
        }

        .newsletter-form input {
            flex: 1 1 auto;
            min-width: 0;
            border: 1px solid #ffffff;
            background: #ffffff;
            border-radius: 8px;
            padding: .52rem .62rem;
            font: inherit;
            color: #0f172a;
        }

        .newsletter-form input::placeholder {
            color: #64748b;
        }

        .newsletter-btn {
            border: 0;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
        }

        .footer-bottom {
            padding-top: .8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
            font-size: .82rem;
        }

        .footer-social {
            display: inline-flex;
            gap: .75rem;
        }

        .footer-social a {
            color: #d9e8ff;
            text-decoration: none;
            font-size: .76rem;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        @media (max-width: 960px) {
            .hero-inner {
                grid-template-columns: 1fr;
            }

            .stat-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
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
                <a class="btn" href="{{ route('fleet.index') }}">View Fleet</a>
                <a class="btn btn-primary" href="{{ route('home') }}">Back Home</a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <section class="hero">
                <div class="hero-inner">
                    <div class="hero-copy">
                        <span class="eyebrow">Pricing Guide</span>
                        <h1>See daily rental pricing before you book.</h1>
                        <p>
                            This page shows the current pricing rows saved in our system for each vehicle make and model. Customers can compare daily package amounts, included KM, extra KM charges, and driver cost in one place before choosing a vehicle.
                        </p>
                        <div class="notice">
                            Driver availability may still differ by vehicle. Some cars are available with driver only, some without driver only, and some support both options. The booking page will show the exact rule for the selected vehicle.
                        </div>
                    </div>

                    <div class="stat-grid">
                        <article class="stat-card">
                            <div class="stat-label">Pricing Rows</div>
                            <div class="stat-value">{{ $pricingRows->count() }}</div>
                            <div class="stat-note">Live records managed from the admin pricing page.</div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-label">Starting Daily Rate</div>
                            <div class="stat-value">
                                @if($startingRate !== null)
                                    Rs {{ number_format((float) $startingRate, 2) }}
                                @else
                                    -
                                @endif
                            </div>
                            <div class="stat-note">Lowest current daily package available in the pricing list.</div>
                        </article>
                        <article class="stat-card">
                            <div class="stat-label">Highest Included KM</div>
                            <div class="stat-value">
                                @if($highestIncludedKm !== null)
                                    {{ number_format((int) $highestIncludedKm) }} km
                                @else
                                    -
                                @endif
                            </div>
                            <div class="stat-note">Maximum per-day included KM from the current pricing rows.</div>
                        </article>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">Vehicle Pricing List</h2>
                        <p class="section-sub">See the full daily amount for self-drive and with-driver packages, plus daily KM limit and extra KM rate.</p>
                    </div>
                </div>

                @if($pricingRows->isEmpty())
                    <div class="empty-state">
                        No pricing records are available right now. Please contact R&amp;A Auto Rentals for the latest rates.
                    </div>
                @else
                    <div class="pricing-table-wrap">
                        <table class="pricing-table">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Without Driver</th>
                                    <th>With Driver</th>
                                    <th>Package Terms</th>
                                    <th>Extra KM Charge</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pricingRows as $pricing)
                                    @php
                                        $baseDailyAmount = (float) $pricing->per_day_amount;
                                        $driverDailyAmount = (float) $pricing->driver_cost_per_day;
                                        $withDriverAmount = $baseDailyAmount + $driverDailyAmount;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="model-name">{{ $pricing->model }}</div>
                                            <span class="make-text">{{ $pricing->make ?: 'General pricing' }}</span>
                                        </td>
                                        <td>
                                            <div class="rate-stack">
                                                <div class="rate-card">
                                                    <span class="rate-label">Without Driver</span>
                                                    <div class="rate">Rs {{ number_format($baseDailyAmount, 2) }}</div>
                                                    <div class="muted">full amount per day</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="rate-stack">
                                                <div class="rate-card">
                                                    <span class="rate-label">With Driver</span>
                                                    <div class="rate">Rs {{ number_format($withDriverAmount, 2) }}</div>
                                                    @if($driverDailyAmount > 0)
                                                        <div class="muted">includes Rs {{ number_format($driverDailyAmount, 2) }} driver cost pre day</div>
                                                    @else
                                                        <div class="muted">same rate as without driver</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="package-stack">
                                                <span class="pill pill-neutral">{{ number_format((int) $pricing->per_day_km) }} km daily limit</span>
                                                @if($driverDailyAmount > 0)
                                                    <div class="package-line">Driver package add-on: Rs {{ number_format($driverDailyAmount, 2) }} per day</div>
                                                @else
                                                    <div class="package-line">No separate driver charge configured for this package.</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="pill pill-warning">Rs {{ number_format((float) $pricing->extra_km_rate, 2) }} / extra km</span>
                                        </td>
                                        <td class="note-text">
                                            {{ $pricing->note ?: '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals">
                        <div>
                            <p class="footer-brand-name">R&A Auto Rentals</p>
                            <p class="footer-copy">Browse pricing first, compare vehicle packages quickly, then continue to fleet or booking with a clearer idea of your expected rental cost.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="footer-title">Quick Links</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('fleet.index') }}">Fleet</a></li>
                        <li><a href="{{ route('pricing.index') }}">Rates</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Customer Care</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('login') }}">Customer Login</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Newsletter</p>
                    <p class="newsletter-note">Get exclusive rental updates to your inbox.</p>
                    <form class="newsletter-form" action="#" method="post">
                        <input type="email" placeholder="Email">
                        <button class="newsletter-btn" type="button" aria-label="Subscribe">&#10148;</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; {{ now()->year }} R&A Auto Rentals. All rights reserved.</div>
                <div class="footer-social">
                    <a href="#">Twitter</a>
                    <a href="#">Instagram</a>
                    <a href="#">Facebook</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
