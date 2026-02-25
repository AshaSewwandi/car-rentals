<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>R&A Auto Rentals | Premium Fleet</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe6f3;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --primary-soft: #eaf2ff;
            --accent: #f1f6ff;
            --shadow: 0 18px 46px rgba(10, 63, 143, 0.12);
            --radius: 16px;
            --radius-sm: 12px;
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
            width: 56px;
            height: 34px;
            object-fit: contain;
        }

        .brand-name {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.25rem;
            line-height: .88;
            letter-spacing: -.02em;
            font-weight: 700;
            color: #0b1f3a;
        }

        .nav {
            display: flex;
            gap: .45rem;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav a {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: .95rem;
            padding: .55rem .8rem;
            border-radius: 10px;
        }

        .nav a:hover { background: var(--surface-soft); color: var(--text); }

        .nav .cta {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow: 0 10px 20px rgba(10, 63, 143, 0.24);
        }

        .hero {
            padding: 2rem 0 1rem;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.06fr .94fr;
            gap: 1.3rem;
            align-items: stretch;
        }

        .hero-copy {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .kicker {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .62rem;
            border: 1px solid #c7daf4;
            border-radius: 999px;
            font-size: .72rem;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: .08em;
            color: var(--primary-2);
            background: var(--primary-soft);
        }

        h1 {
            margin: .9rem 0 .8rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(2rem, 4.8vw, 3.55rem);
            line-height: .96;
            letter-spacing: -.03em;
            max-width: 12ch;
        }

        .brand-highlight {
            color: var(--primary);
            font-weight: 700;
        }

        .hero-copy p {
            color: var(--muted);
            line-height: 1.7;
            margin: 0 0 1.25rem;
            max-width: 54ch;
        }

        .hero-actions { display: flex; gap: .65rem; flex-wrap: wrap; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            border: 1px solid transparent;
            text-decoration: none;
            font-weight: 700;
            padding: .78rem 1.1rem;
            border-radius: 12px;
            font-size: .95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #fff;
            box-shadow: 0 8px 18px rgba(10, 63, 143, 0.26);
        }

        .btn-secondary {
            background: var(--surface-soft);
            color: #1e293b;
            border-color: var(--line);
        }

        .hero-art {
            position: relative;
            border-radius: var(--radius);
            border: 1px solid #bfd4ef;
            background:
                radial-gradient(32rem 13rem at 70% -5%, rgba(255, 255, 255, 0.76), transparent 65%),
                linear-gradient(140deg, #0a3f8f 0%, #0f66c3 48%, #8ac8ff 100%);
            box-shadow: var(--shadow);
            min-height: 430px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-art::before {
            content: "";
            position: absolute;
            inset: auto -30% -42% -30%;
            height: 220px;
            background: radial-gradient(closest-side, rgba(255, 255, 255, 0.42), transparent 75%);
            z-index: 1;
            pointer-events: none;
        }

        .hero-art::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg, rgba(10, 63, 143, 0.28) 4%, rgba(10, 63, 143, 0.08) 42%, rgba(15, 102, 195, 0.12) 100%);
            z-index: 2;
            pointer-events: none;
        }

        .hero-art img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: 64% center;
            transform: scale(1.03);
            filter: saturate(1.08) contrast(1.06);
        }

        .hero-art .chip {
            position: absolute;
            top: 1rem;
            right: 1rem;
            color: #fff;
            font-weight: 700;
            font-size: .8rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .32);
            background: rgba(7, 45, 107, 0.36);
            z-index: 3;
        }

        .availability {
            margin-top: 1rem;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: 0 12px 34px rgba(15, 23, 42, 0.08);
            padding: 1rem;
        }

        .availability-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 .75rem;
        }

        .availability-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr auto;
            gap: .65rem;
        }

        .control label {
            display: block;
            margin-bottom: .35rem;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
        }

        .control input {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 10px;
            padding: .72rem .8rem;
            font: inherit;
            color: var(--text);
        }

        .control .btn {
            width: 100%;
            margin-top: 1.42rem;
            white-space: nowrap;
        }

        .section {
            padding: 2.6rem 0 0;
        }

        .section h2 {
            margin: 0 0 .45rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            letter-spacing: -.02em;
            font-size: clamp(1.5rem, 3vw, 2.05rem);
        }

        .section p.head-note {
            margin: 0;
            color: var(--muted);
            max-width: 60ch;
            line-height: 1.7;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
            margin-top: 1rem;
        }

        .feature {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            padding: 1rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .feature .icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--primary-soft);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: .55rem;
        }

        .feature h3 {
            margin: 0 0 .35rem;
            font-size: 1.05rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
        }

        .feature p { margin: 0; color: var(--muted); line-height: 1.62; font-size: .93rem; }

        .fleet-grid {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .9rem;
        }

        .fleet-card {
            display: grid;
            grid-template-columns: .95fr 1.05fr;
            border-radius: var(--radius-sm);
            border: 1px solid var(--line);
            overflow: hidden;
            background: var(--surface);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
        }

        .fleet-photo {
            min-height: 170px;
            background:
                radial-gradient(7rem 7rem at 82% 22%, rgba(255, 255, 255, 0.9), transparent 70%),
                linear-gradient(140deg, #0c4d9f, #7bc4ff);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: .8rem;
        }

        .fleet-photo img {
            width: 92%;
            max-height: 120px;
            object-fit: contain;
            filter: drop-shadow(0 6px 16px rgba(0, 0, 0, .28));
        }

        .fleet-body { padding: .9rem; }
        .fleet-price { color: var(--primary-2); font-weight: 800; }
        .fleet-meta { color: var(--muted); font-size: .9rem; margin: .35rem 0 .7rem; }
        .fleet-body .btn { width: 100%; }

        .contact-card {
            margin-top: 1rem;
            background: linear-gradient(145deg, #eef6ff, #f7fbff);
            border: 1px solid #cfdef1;
            border-radius: var(--radius);
            padding: 1rem;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 1rem;
            align-items: center;
        }

        .contact-lines div { margin-bottom: .8rem; color: #334155; }
        .contact-lines strong { display: block; margin-bottom: .2rem; color: #0f172a; }

        .map-art {
            border-radius: 14px;
            border: 1px solid #d5e4f5;
            min-height: 180px;
            background:
                radial-gradient(7rem 7rem at 50% 35%, rgba(255, 255, 255, 0.8), transparent 65%),
                linear-gradient(145deg, #d7edff, #a8d5ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a3f8f;
            font-weight: 700;
            font-size: 1.12rem;
            text-align: center;
            padding: 1rem;
        }

        footer {
            margin-top: 2.6rem;
            border-top: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.72);
        }

        .footer-inner {
            padding: 1.2rem 0 1.5rem;
            color: #64748b;
            font-size: .92rem;
            display: flex;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; }
            .hero-art { min-height: 340px; }
            .availability-grid { grid-template-columns: 1fr 1fr; }
            .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fleet-grid { grid-template-columns: 1fr; }
            .contact-card { grid-template-columns: 1fr; }
        }

        @media (max-width: 700px) {
            .container { width: min(1180px, calc(100% - 1.2rem)); }
            .topbar-inner { min-height: 66px; }
            .brand-name { font-size: 1.6rem; }
            .availability-grid { grid-template-columns: 1fr; }
            .feature-grid { grid-template-columns: 1fr; }
            .fleet-card { grid-template-columns: 1fr; }
            .hero-copy { padding: 1.25rem; }
            .nav { gap: .2rem; }
            .nav a { padding: .48rem .62rem; }
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
            <nav class="nav">
                @auth
                    @if(auth()->user()->canAccess('cars'))
                        <a href="{{ route('cars.index') }}">Cars</a>
                    @endif
                    @if(auth()->user()->canAccess('customers'))
                        <a href="{{ route('customers.index') }}">Customers</a>
                    @endif
                    @if(auth()->user()->canAccess('payments'))
                        <a href="{{ route('payments.index') }}">Payments</a>
                    @endif
                    <a class="cta" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a class="cta" href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-copy">
                        <span class="kicker">Premium Rental Service</span>
                        <h1>Drive your dreams with <span class="brand-highlight">R&A Auto Rentals</span>.</h1>
                        <p>Manage cars, customers, payments, agreements, and mileage in one reliable platform built for <span class="brand-highlight">R&A Auto Rentals</span> daily work.</p>
                        <div class="hero-actions">
                            @auth
                                <a class="btn btn-primary" href="{{ route('dashboard') }}">Open Dashboard</a>
                                <a class="btn btn-secondary" href="{{ route('gps-logs.index') }}">Open KM Logs</a>
                            @else
                                <a class="btn btn-primary" href="{{ route('register') }}">Create Account</a>
                                <a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
                            @endauth
                        </div>
                    </div>

                    <div class="hero-art">
                        <!-- <span class="chip">R&A Fleet Ready</span> -->
                        <img src="{{ asset('images/hero car.jpg') }}" alt="R&A fleet visual" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='2rem';">
                    </div>
                </div>

                <div class="availability">
                    <h2 class="availability-title">Check Availability</h2>
                    <form class="availability-grid">
                        <div class="control">
                            <label for="location">Pickup Location</label>
                            <input id="location" type="text" placeholder="Colombo, Sri Lanka">
                        </div>
                        <div class="control">
                            <label for="pickup">Pickup Date</label>
                            <input id="pickup" type="date" value="{{ now()->toDateString() }}">
                        </div>
                        <div class="control">
                            <label for="return">Return Date</label>
                            <input id="return" type="date" value="{{ now()->addDays(3)->toDateString() }}">
                        </div>
                        <div class="control">
                            <button class="btn btn-primary" type="button">Search Cars</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2>Why R&A Auto Rentals?</h2>
                <p class="head-note">A practical, fast, and team-friendly system to run rental operations without spreadsheet overload.</p>
                <div class="feature-grid">
                    <article class="feature">
                        <span class="icon">🚗</span>
                        <h3>Fleet Control</h3>
                        <p>Track active cars, status, and vehicle info in a clear list with quick actions.</p>
                    </article>
                    <article class="feature">
                        <span class="icon">📄</span>
                        <h3>Smart Agreements</h3>
                        <p>Create and maintain rental agreements with date control and customer assignment.</p>
                    </article>
                    <article class="feature">
                        <span class="icon">💳</span>
                        <h3>Payment Control</h3>
                        <p>Monitor expected vs received payments and identify upcoming dues early.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2>Explore Fleet</h2>
                <p class="head-note">Quick highlight cards for your most rented vehicles.</p>
                <div class="fleet-grid">
                    <article class="fleet-card">
                        <div class="fleet-photo">
                            <img src="{{ asset('images/logo.png') }}" alt="R&A car">
                        </div>
                        <div class="fleet-body">
                            <h3>Tesla Model S</h3>
                            <div class="fleet-price">$120/day</div>
                            <div class="fleet-meta">Electric · 5 seats · Auto · AC</div>
                            <a class="btn btn-secondary" href="{{ route('cars.index') }}">View Cars</a>
                        </div>
                    </article>
                    <article class="fleet-card">
                        <div class="fleet-photo">
                            <img src="{{ asset('images/logo.png') }}" alt="R&A SUV">
                        </div>
                        <div class="fleet-body">
                            <h3>Range Rover Vogue</h3>
                            <div class="fleet-price">$250/day</div>
                            <div class="fleet-meta">Luxury SUV · 7 seats · Auto · Full option</div>
                            <a class="btn btn-secondary" href="{{ route('cars.index') }}">View Cars</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <h2>Find Us</h2>
                <div class="contact-card">
                    <div class="contact-lines">
                        <div>
                            <strong>Main Office</strong>
                            Galle, Sri Lanka
                        </div>
                        <div>
                            <strong>Phone</strong>
                            +94 77 717 3264
                        </div>
                        <div>
                            <strong>Email</strong>
                            info@rnautorentals.lk
                        </div>
                    </div>
                    <div class="map-art">R&A Service Coverage Map</div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container footer-inner">
            <div>© {{ now()->year }} R&A Auto Rentals. All rights reserved.</div>
            <div>Laravel {{ Illuminate\Foundation\Application::VERSION }} · PHP {{ PHP_VERSION }}</div>
        </div>
    </footer>
</body>
</html>
