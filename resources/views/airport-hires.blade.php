<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Airport Hires | R&A Auto Rentals',
        'description' => 'Airport pickup and drop-off transfers with airport-ready vehicles, clear pricing, and fast booking support.',
        'keywords' => ['airport hires', 'airport transfer', 'airport pickup', 'airport drop off', 'Sri Lanka airport car hire'],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root { --bg:#f4f7fb; --surface:#fff; --surface-soft:#f8fbff; --line:#dbe6f3; --text:#0f172a; --muted:#64748b; --primary:#0a3f8f; --primary-2:#0f66c3; --primary-soft:#eaf2ff; --radius:18px; --shadow:0 18px 44px rgba(10,63,143,.12); }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--text); font-family:"Plus Jakarta Sans","Segoe UI",Tahoma,sans-serif; background:radial-gradient(68rem 30rem at 100% -20%, rgba(15,102,195,.14), transparent 70%), var(--bg); }
        .container { width:min(1180px, calc(100% - 2rem)); margin:0 auto; }
        .topbar { position:sticky; top:0; z-index:20; backdrop-filter:blur(10px); background:rgba(255,255,255,.92); border-bottom:1px solid var(--line); }
        .topbar-inner { min-height:74px; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .brand { display:inline-flex; align-items:center; gap:.72rem; text-decoration:none; color:inherit; }
        .brand img { width:48px; height:48px; object-fit:contain; border-radius:12px; border:1px solid var(--line); background:#f8fbff; padding:5px; }
        .brand-name { font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:1.18rem; font-weight:700; letter-spacing:-.02em; color:#0b1f3a; }
        .nav { display:inline-flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
        .nav a, .nav button { text-decoration:none; color:#475569; font-weight:600; font-size:.92rem; padding:.55rem .8rem; border-radius:10px; border:0; background:transparent; cursor:pointer; font-family:inherit; }
        .nav a:hover, .nav button:hover { background:var(--surface-soft); color:var(--text); }
        .nav .active-link { color:var(--primary-2); background:var(--primary-soft); }
        .nav .cta { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 22px rgba(10,63,143,.22); }
        main { padding:1.35rem 0 2.6rem; }
        .hero { display:grid; grid-template-columns:1.05fr .95fr; gap:1.2rem; align-items:stretch; margin-bottom:2rem; }
        .eyebrow { display:inline-flex; padding:.38rem .72rem; border-radius:999px; background:var(--primary-soft); border:1px solid #c8dcfb; color:var(--primary-2); font-size:.74rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        h1 { margin:.8rem 0 .9rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(2.15rem, 5vw, 4rem); line-height:.94; letter-spacing:-.04em; max-width:10ch; }
        h1 .accent { color:var(--primary-2); }
        .hero-copy p { color:var(--muted); line-height:1.75; max-width:56ch; margin:0 0 1rem; }
        .hero-points { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:.75rem; margin-top:1rem; }
        .point-card, .package-card, .fleet-card, .search-card, .contact-card { background:var(--surface); border:1px solid var(--line); box-shadow:var(--shadow); }
        .point-card { border-radius:14px; padding:.9rem; }
        .point-title { margin:0 0 .25rem; font-size:.76rem; color:var(--primary); text-transform:uppercase; letter-spacing:.08em; font-weight:800; }
        .point-note { margin:0; color:var(--muted); font-size:.88rem; line-height:1.5; }
        .hero-visual { min-height:360px; border-radius:22px; overflow:hidden; background:linear-gradient(180deg, rgba(13,102,195,.10), rgba(15,23,42,.03)); border:1px solid var(--line); box-shadow:var(--shadow); }
        .hero-visual img { width:100%; height:100%; object-fit:cover; }
        .search-card { position:relative; z-index:2; margin:0 auto; width:min(940px, calc(100% - 2rem)); border-radius:16px; padding:1rem 1rem 1.1rem; transform:translateY(-1.2rem); }
        .search-title { margin:0 0 .9rem; font-size:.96rem; font-weight:800; color:#0b1f3a; }
        .search-grid { display:grid; grid-template-columns:1.2fr 1fr 1fr auto; gap:.8rem; align-items:end; }
        .field { min-width:0; overflow:hidden; }
        .field label, .form-field label { display:block; margin-bottom:.35rem; font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.07em; font-weight:800; }
        .field-control { --control-h:46px; width:100%; height:var(--control-h); border:1px solid #c8d7ea; background:#f8fbff; border-radius:10px; overflow:hidden; }
        .field-control input, .field-control select { width:100%; min-width:0; max-width:100%; height:100%; min-height:100%; display:block; border:0; background:transparent; padding:0 .8rem; font:inherit; color:#0f172a; box-sizing:border-box; border-radius:10px; }
        .field-control.date-control, .field-control.time-control { position:relative; }
        .field-control.date-control::after { content:""; position:absolute; right:.7rem; top:50%; transform:translateY(-50%); width:18px; height:18px; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%236b7f9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-size:18px 18px; pointer-events:none; opacity:.9; }
        .field-control.time-control::after { content:"\23F0"; position:absolute; right:.7rem; top:50%; transform:translateY(-50%); font-size:1rem; color:#6b7f9a; pointer-events:none; opacity:.9; }
        .field-control input[type="date"], .field-control input[type="time"] { width:100%; min-width:0; max-width:100%; height:100% !important; min-height:100% !important; padding:0 2.1rem 0 .8rem; line-height:1.2; text-align:left; }
        .field-control input[type="date"] { -webkit-appearance:none; appearance:none; }
        .field-control input[type="time"] { -webkit-appearance:none; appearance:none; }
        .field-control input[type="date"]::-webkit-datetime-edit, .field-control input[type="time"]::-webkit-datetime-edit { height:100%; display:flex; align-items:center; }
        .field-control input[type="date"]::-webkit-date-and-time-value, .field-control input[type="time"]::-webkit-date-and-time-value { text-align:left; height:100%; display:flex; align-items:center; }
        .field-control input[type="date"]::-webkit-calendar-picker-indicator,
        .field-control input[type="time"]::-webkit-calendar-picker-indicator {
            position:absolute;
            inset:0;
            width:100%;
            height:100%;
            margin:0;
            padding:0;
            opacity:0;
            color:transparent;
            background:transparent;
            display:block;
            cursor:pointer;
        }
        .field-control input[type="time"]::-webkit-clear-button,
        .field-control input[type="time"]::-webkit-inner-spin-button {
            display:none;
            -webkit-appearance:none;
        }
        .field input.input-error, .field select.input-error { background:#fff7f7; }
        .field-control.input-error { border-color:#dc2626; background:#fff7f7; }
        .field-error { display:block; min-height:1.05rem; margin-top:.3rem; color:#b91c1c; font-size:.8rem; font-weight:600; }
        .form-alert { margin:0 0 .8rem; border:1px solid #fecaca; background:#fff1f2; color:#9f1239; border-radius:10px; padding:.65rem .75rem; font-size:.85rem; font-weight:600; }
        .search-btn, .submit-btn { border:0; border-radius:10px; font:inherit; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); cursor:pointer; }
        .search-btn { width:100%; height:46px; padding:0 1rem; display:inline-flex; align-items:center; justify-content:center; gap:.45rem; }
        .search-btn .btn-spinner { display:none; width:16px; height:16px; border:2px solid rgba(255,255,255,.45); border-top-color:#fff; border-radius:999px; animation:btn-spin .7s linear infinite; }
        .search-btn.is-loading .btn-spinner { display:inline-block; }
        .search-btn.is-loading { pointer-events:none; opacity:.95; }
        @keyframes btn-spin { to { transform:rotate(360deg); } }
        .submit-btn { margin-top:.9rem; width:100%; padding:.8rem .95rem; }
        .section { padding:1.4rem 0 0; }
        .section h2 { margin:0 0 .3rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(1.65rem,3vw,2.2rem); letter-spacing:-.03em; }
        .section-sub { margin:0 0 1.4rem; color:var(--muted); }
        .package-grid, .fleet-grid, .benefits-grid { display:grid; gap:1rem; }
        .package-grid, .fleet-grid, .benefits-grid { grid-template-columns:repeat(3, minmax(0,1fr)); }
        .package-card { position:relative; border-radius:18px; padding:1.1rem; }
        .package-card.featured { border-color:#8bb3f0; box-shadow:0 20px 42px rgba(10,63,143,.16); }
        .package-badge { position:absolute; top:-10px; right:16px; padding:.28rem .58rem; border-radius:999px; background:linear-gradient(135deg, var(--primary), var(--primary-2)); color:#fff; font-size:.64rem; text-transform:uppercase; letter-spacing:.07em; font-weight:800; }
        .package-icon, .benefit-icon { display:inline-flex; align-items:center; justify-content:center; font-weight:800; }
        .package-icon { width:42px; height:42px; border-radius:12px; background:var(--primary-soft); color:var(--primary); font-size:1.1rem; }
        .package-card h3, .benefit-card h3 { margin:.9rem 0 .45rem; }
        .package-card p, .benefit-card p { margin:0; color:var(--muted); line-height:1.65; }
        .package-card p { margin-bottom:.8rem; }
        .package-list { margin:0; padding-left:1rem; color:#476280; line-height:1.8; }
        .cta-row { display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:1.4rem; }
        .text-link { color:var(--primary); font-weight:700; text-decoration:none; }
        .fleet-card { overflow:hidden; border-radius:16px; }
        .fleet-photo { position:relative; height:210px; background:#eef4fb; }
        .fleet-photo img { width:100%; height:100%; object-fit:cover; }
        .fleet-tag { position:absolute; top:12px; left:12px; padding:.3rem .55rem; border-radius:999px; background:#22c55e; color:#fff; font-size:.64rem; letter-spacing:.06em; text-transform:uppercase; font-weight:800; }
        .fleet-body { padding:.9rem; }
        .fleet-head { display:flex; justify-content:space-between; gap:.8rem; align-items:flex-start; }
        .fleet-title { margin:0; font-size:1.02rem; font-weight:800; }
        .fleet-rate { color:var(--primary-2); font-weight:800; white-space:nowrap; }
        .fleet-rate small { color:#64748b; font-weight:700; }
        .fleet-sub { margin:.35rem 0 .55rem; color:var(--muted); font-size:.9rem; line-height:1.55; }
        .fleet-policy { margin:0 0 .75rem; color:#35537a; font-size:.88rem; line-height:1.55; }
        .fleet-meta { display:flex; flex-wrap:wrap; gap:.38rem; }
        .fleet-meta span { padding:.28rem .48rem; border-radius:999px; background:#f3f7fd; border:1px solid #dbe6f3; color:#5b728e; font-size:.72rem; }
        .benefits-band { margin-top:2.7rem; padding:3rem 0; background:linear-gradient(135deg, #0a3f8f, #0f66c3); color:#fff; }
        .benefits-band h2, .benefits-band .section-sub { color:#fff; text-align:center; }
        .benefit-card { text-align:center; padding:1rem; }
        .benefit-icon { width:54px; height:54px; border-radius:999px; background:rgba(255,255,255,.13); font-size:1.2rem; }
        .benefit-card p { color:rgba(255,255,255,.88); }
        .contact-card { display:grid; grid-template-columns:.8fr 1.2fr; gap:0; border-radius:18px; overflow:hidden; }
        .contact-info, .contact-form { padding:1.3rem 1.4rem; }
        .contact-info { background:#fbfdff; border-right:1px solid var(--line); }
        .contact-kicker { margin:0 0 1.1rem; font-size:.74rem; color:#64748b; text-transform:uppercase; letter-spacing:.08em; font-weight:800; }
        .contact-line { margin-bottom:1.2rem; color:#476280; line-height:1.7; }
        .contact-line strong { display:block; color:#0f172a; margin-bottom:.2rem; }
        .contact-line a { color:#35537a; text-decoration:none; font-weight:600; word-break:break-word; }
        .contact-line a:hover { color:#0a3f8f; text-decoration:underline; text-underline-offset:.15em; }
        .contact-form h3 { margin:0 0 1rem; font-size:1.15rem; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:.8rem; }
        .contact-form .form-field input,
        .contact-form .form-field textarea {
            width:100%;
            display:block;
            border:1px solid #c8d7ea;
            background:#f8fbff;
            border-radius:10px;
            padding:.72rem .8rem;
            font:inherit;
            color:#0f172a;
            box-sizing:border-box;
        }
        .form-field.full { grid-column:1 / -1; }
        .form-field textarea { min-height:120px; resize:vertical; }
        footer { margin-top:2.6rem; border-top:1px solid #215fb2; background:linear-gradient(135deg, #0a3f8f, #0f66c3); }
        .footer-inner { padding:1.35rem 0 1.1rem; color:#d9e8ff; font-size:.9rem; }
        .footer-grid { display:grid; grid-template-columns:1.2fr 1fr 1fr 1.2fr; gap:1.2rem; padding-bottom:1rem; border-bottom:1px solid rgba(219,232,255,.28); }
        .footer-brand { display:flex; align-items:flex-start; gap:.6rem; }
        .footer-logo { width:36px; height:24px; object-fit:contain; margin-top:.15rem; }
        .footer-title { margin:0 0 .45rem; font-size:.75rem; letter-spacing:.05em; text-transform:uppercase; color:#bfdbff; font-weight:800; }
        .footer-brand-name { margin:0 0 .35rem; font-size:.97rem; font-weight:700; color:#f8fbff; }
        .footer-copy { margin:0; color:#d9e8ff; font-size:.83rem; line-height:1.5; max-width:28ch; }
        .footer-links { list-style:none; margin:0; padding:0; display:grid; gap:.35rem; }
        .footer-links a { color:#e7f0ff; text-decoration:none; font-size:.85rem; }
        .footer-links a:hover { color:#fff; }
        .newsletter-note { margin:0 0 .55rem; font-size:.82rem; color:#d9e8ff; }
        .newsletter-form { display:flex; gap:.4rem; }
        .newsletter-form input { flex:1 1 auto; min-width:0; border:1px solid #fff; background:#fff; border-radius:8px; padding:.52rem .62rem; font:inherit; color:#0f172a; }
        .newsletter-form input::placeholder { color:#64748b; }
        .newsletter-btn { border:0; border-radius:8px; width:36px; height:36px; color:#fff; background:linear-gradient(135deg, #0a3f8f, #0f66c3); font-size:1rem; line-height:1; cursor:pointer; }
        .footer-bottom { padding-top:.8rem; display:flex; justify-content:space-between; align-items:center; gap:.6rem; flex-wrap:wrap; font-size:.82rem; }
        .footer-social { display:inline-flex; gap:.75rem; }
        .footer-social a { color:#d9e8ff; text-decoration:none; font-size:.76rem; letter-spacing:.04em; text-transform:uppercase; }
        @media (max-width:980px) { .hero,.contact-card,.footer-grid,.package-grid,.fleet-grid,.benefits-grid,.hero-points,.search-grid,.form-grid { grid-template-columns:1fr; } .hero { margin-bottom:1rem; } .search-card { transform:translateY(-.5rem); } .section { padding-top:1rem; } .footer-bottom { flex-direction:column; align-items:flex-start; } .contact-info { border-right:0; border-bottom:1px solid var(--line); } .contact-form .form-field input,.contact-form .form-field textarea { font-size:16px; } }
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
            <nav class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('fleet.index') }}">Fleet</a>
                <a class="active-link" href="{{ route('airport-hires.index') }}">Airport Hires</a>
                <a href="#contact-section">Contact</a>
                @auth
                    @if(auth()->user()->canAccess('dashboard'))
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    @elseif(auth()->user()->isCustomer())
                        <a href="{{ route('customer.dashboard') }}">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a class="cta" href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            @if(session('success'))
                <div style="margin-bottom:1rem;border:1px solid #bde5cc;background:#ecfdf3;color:#166534;padding:.75rem .9rem;border-radius:12px;font-weight:600;">{{ session('success') }}</div>
            @endif
            <section class="hero">
                <div class="hero-copy">
                    <span class="eyebrow">Airport Transfer Specialist</span>
                    <h1>Stress-Free Airport <span class="accent">Transfers</span> for Your Journey.</h1>
                    <p>Reliable airport pickup and drop-off services at your fingertips. From solo travelers to large groups, we ensure a smooth ride to and from the terminal.</p>
                    <div class="hero-points">
                        <article class="point-card"><p class="point-title">Punctual</p><p class="point-note">Zero waiting time policy with careful schedule planning.</p></article>
                        <article class="point-card"><p class="point-title">Fixed Rates</p><p class="point-note">Transparent pricing with no hidden airport transfer charges.</p></article>
                        <article class="point-card"><p class="point-title">Meet &amp; Greet</p><p class="point-note">Professional terminal pickup support for smooth arrivals.</p></article>
                    </div>
                </div>
                <div class="hero-visual">
                    <img src="{{ asset('images/airport.png') }}" alt="Airport transfer service">
                </div>
            </section>
            <section class="search-card">
                <p class="search-title">Book Your Airport Ride</p>
                <form id="airportSearchForm" class="search-grid" action="{{ route('fleet.index') }}" method="get" novalidate>
                    <div id="airport_search_alert" class="form-alert" style="display:none;grid-column:1 / -1;"></div>
                    <div class="field">
                        <label for="start_location">Pickup Airport</label>
                        <div class="field-control">
                            <select id="start_location" name="start_location" required>
                                @foreach($airports as $airport)
                                    <option value="{{ $airport }}">{{ $airport }}</option>
                                @endforeach
                            </select>
                        </div>
                        <small id="start_location_error" class="field-error"></small>
                    </div>
                    <div class="field">
                        <label for="start_date">Pickup Date</label>
                        <div class="field-control date-control">
                            <input id="start_date" name="start_date" type="date" required>
                        </div>
                        <small id="start_date_error" class="field-error"></small>
                    </div>
                    <div class="field">
                        <label for="pickup_time">Pickup Time</label>
                        <div class="field-control time-control">
                            <input id="pickup_time" name="pickup_time" type="time" required>
                        </div>
                        <small id="pickup_time_error" class="field-error"></small>
                    </div>
                    <input id="end_date" name="end_date" type="hidden">
                    <div class="field">
                        <label aria-hidden="true" style="visibility:hidden;">Search</label>
                        <button class="search-btn" type="submit" id="airportSubmitBtn" data-loading-text="Checking...">
                            <span class="btn-spinner" aria-hidden="true"></span>
                            <span class="btn-label">Find Available Cars</span>
                        </button>
                        <small class="field-error">&nbsp;</small>
                    </div>
                </form>
            </section>
            <section class="section">
                <h2>Airport Transfer Packages</h2>
                <p class="section-sub">Tailored solutions for every arrival and departure.</p>
                <div class="package-grid">
                    <article class="package-card"><div class="package-icon">A</div><h3>Standard Drop-off</h3><p>Punctual and efficient ride to the airport. Perfect for regular flyers who value timing.</p><ul class="package-list"><li>Door-to-terminal service</li><li>15 minute buffer included</li></ul></article>
                    <article class="package-card featured"><span class="package-badge">Most Popular</span><div class="package-icon">V</div><h3>VIP Meet &amp; Greet</h3><p>Driver waits at arrivals with your nameplate. Luggage assistance included for a premium experience.</p><ul class="package-list"><li>Flight tracking included</li><li>Personalized greeting</li></ul></article>
                    <article class="package-card"><div class="package-icon">G</div><h3>Group Shuttle</h3><p>Spacious vans for families or corporate groups. Plenty of room for all your luggage.</p><ul class="package-list"><li>Up to 12 passengers</li><li>Extra luggage space</li></ul></article>
                </div>
            </section>
            <section class="section">
                <div class="cta-row">
                    <div><h2>Featured Airport Fleet</h2><p class="section-sub">Vehicles optimized for comfort and luggage capacity.</p></div>
                    <a class="text-link" href="{{ route('fleet.index') }}">View Full Fleet</a>
                </div>
                <div class="fleet-grid">
                    @foreach($featuredCars as $car)
                        <article class="fleet-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] }}">
                            <div class="fleet-photo">
                                <span class="fleet-tag">{{ $car['airport_tag'] }}</span>
                                <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}">
                            </div>
                            <div class="fleet-body">
                                <div class="fleet-head">
                                    <h3 class="fleet-title">{{ $car['name'] }}</h3>
                                    <div class="fleet-rate">Rs {{ number_format($car['daily_rate'], 0) }} <small>/ day</small></div>
                                </div>
                                <p class="fleet-sub">{{ $car['driver_mode_label'] }}. {{ number_format($car['per_day_km']) }} km/day included.</p>
                                <p class="fleet-policy">Extra distance is charged at Rs {{ number_format($car['extra_km_rate'], 2) }} per km.</p>
                                <div class="fleet-meta">
                                    @foreach(array_filter([$car['year'], $car['transmission'], $car['fuel_type'], $car['color']]) as $meta)
                                        <span>{{ $meta }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
        <section class="benefits-band">
            <div class="container">
                <h2>Why Choose Our Airport Service?</h2>
                <p class="section-sub">The preferred choice for international travelers and local residents.</p>
                <div class="benefits-grid">
                    <article class="benefit-card"><div class="benefit-icon">F</div><h3>24/7 Flight Tracking</h3><p>We monitor your flight in real time. If your plane is delayed, we adjust your pickup automatically.</p></article>
                    <article class="benefit-card"><div class="benefit-icon">T</div><h3>No Waiting Time</h3><p>Your time is valuable. Our drivers are on-site 15 minutes before your scheduled pickup.</p></article>
                    <article class="benefit-card"><div class="benefit-icon">D</div><h3>Professional Drivers</h3><p>All our drivers are background-checked, polite, and well-acquainted with airport terminal logistics.</p></article>
                </div>
            </div>
        </section>
        <div class="container">
            <section id="contact-section" class="section">
                <div class="contact-card">
                    <div class="contact-info">
                        <p class="contact-kicker">Find Us</p>
                        <div class="contact-line"><strong>Main Office</strong><a href="https://maps.google.com/?q=Galle,Sri Lanka" target="_blank" rel="noopener">Galle, Sri Lanka</a></div>
                        <div class="contact-line"><strong>Phone</strong><a href="tel:+94777173264">+94 77 717 3264</a></div>
                        <div class="contact-line" style="margin-bottom:0;"><strong>Email</strong><a href="mailto:info@rnaautorentals.com.lk">info@rnaautorentals.com.lk</a></div>
                    </div>
                    <div class="contact-form">
                        <h3>Contact Us</h3>
                        <form method="post" action="{{ route('airport-hires.support.store') }}">
                            @csrf
                            <div class="form-grid">
                                <div class="form-field"><label for="contactName">Name</label><input id="contactName" type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required></div>
                                <div class="form-field"><label for="contactPhone">Phone</label><input id="contactPhone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+94 ..."></div>
                                <div class="form-field full"><label for="contactEmail">Email</label><input id="contactEmail" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com"></div>
                                <div class="form-field full"><label for="contactMessage">Message</label><textarea id="contactMessage" name="message" placeholder="Tell us your airport transfer plan." required>{{ old('message') }}</textarea></div>
                            </div>
                            <button type="submit" class="submit-btn">Send Message</button>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
    @include('partials.public-footer')
    <script>
        (function () {
            const form = document.getElementById('airportSearchForm');
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const startLocation = document.getElementById('start_location');
            const pickupTime = document.getElementById('pickup_time');
            const alertBox = document.getElementById('airport_search_alert');
            const submitBtn = document.getElementById('airportSubmitBtn');
            if (!form || !startDate || !endDate || !startLocation || !pickupTime) return;

            const fieldErrors = {
                start_location: document.getElementById('start_location_error'),
                start_date: document.getElementById('start_date_error'),
                pickup_time: document.getElementById('pickup_time_error'),
            };

            const clearError = (field, key) => {
                if (field) {
                    field.classList.remove('input-error');
                    const wrapper = field.closest('.field-control');
                    if (wrapper) wrapper.classList.remove('input-error');
                }
                if (fieldErrors[key]) fieldErrors[key].textContent = '';
            };

            const setError = (field, key, message) => {
                if (field) {
                    field.classList.add('input-error');
                    const wrapper = field.closest('.field-control');
                    if (wrapper) wrapper.classList.add('input-error');
                }
                if (fieldErrors[key]) fieldErrors[key].textContent = message;
            };

            const hideAlertIfNoErrors = () => {
                if (!alertBox) return;
                const hasErrors = Object.values(fieldErrors).some((el) => el && el.textContent.trim() !== '');
                if (!hasErrors) {
                    alertBox.style.display = 'none';
                }
            };

            const setLoadingState = () => {
                if (!submitBtn) return;
                const label = submitBtn.querySelector('.btn-label');
                if (label) {
                    label.dataset.originalText = label.textContent;
                    label.textContent = submitBtn.dataset.loadingText || 'Checking...';
                }
                submitBtn.classList.add('is-loading');
                submitBtn.disabled = true;
            };

            const clearLoadingState = () => {
                if (!submitBtn) return;
                const label = submitBtn.querySelector('.btn-label');
                if (label && label.dataset.originalText) {
                    label.textContent = label.dataset.originalText;
                }
                submitBtn.classList.remove('is-loading');
                submitBtn.disabled = false;
            };

            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            startDate.min = `${yyyy}-${mm}-${dd}`;

            const syncEndDate = () => {
                endDate.value = startDate.value;
            };
            startDate.addEventListener('change', syncEndDate);
            startLocation.addEventListener('change', () => {
                clearError(startLocation, 'start_location');
                hideAlertIfNoErrors();
            });
            startDate.addEventListener('change', () => {
                if (startDate.value && (!startDate.min || startDate.value >= startDate.min)) {
                    clearError(startDate, 'start_date');
                    hideAlertIfNoErrors();
                }
            });
            pickupTime.addEventListener('change', () => {
                if (pickupTime.value) {
                    clearError(pickupTime, 'pickup_time');
                    hideAlertIfNoErrors();
                }
            });

            form.addEventListener('submit', (event) => {
                let hasErrors = false;

                clearError(startLocation, 'start_location');
                clearError(startDate, 'start_date');
                clearError(pickupTime, 'pickup_time');
                if (alertBox) alertBox.style.display = 'none';

                if (!startLocation.value) {
                    setError(startLocation, 'start_location', 'Please select pickup airport.');
                    hasErrors = true;
                }

                if (!startDate.value) {
                    setError(startDate, 'start_date', 'Please select pickup date.');
                    hasErrors = true;
                } else if (startDate.min && startDate.value < startDate.min) {
                    setError(startDate, 'start_date', 'Pickup date cannot be in the past.');
                    hasErrors = true;
                }

                if (!pickupTime.value) {
                    setError(pickupTime, 'pickup_time', 'Please select pickup time.');
                    hasErrors = true;
                }

                if (hasErrors) {
                    event.preventDefault();
                    clearLoadingState();
                    return;
                }

                syncEndDate();
                setLoadingState();
                setTimeout(() => {
                    if (document.visibilityState === 'visible') {
                        clearLoadingState();
                    }
                }, 5000);
            });

            syncEndDate();
            window.addEventListener('pageshow', clearLoadingState);
            window.addEventListener('focus', clearLoadingState);
        })();
    </script>
</body>
</html>
