<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Short-Term Rentals | R&A Auto Rentals',
        'description' => 'Flexible short-term car rentals with daily and weekly rates, live fleet selection, and simple booking flow.',
        'keywords' => ['short-term car rental', 'daily car rental', 'weekly rental', 'Sri Lanka short-term rental'],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root { --bg:#f4f7fb; --surface:#fff; --surface-soft:#f8fbff; --line:#dbe6f3; --text:#0f172a; --muted:#64748b; --primary:#0a3f8f; --primary-2:#0f66c3; --primary-soft:#eaf2ff; --dark:#081734; --radius:18px; --shadow:0 18px 44px rgba(10,63,143,.12); }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--text); font-family:"Plus Jakarta Sans","Segoe UI",Tahoma,sans-serif; background:radial-gradient(68rem 30rem at 100% -20%, rgba(15,102,195,.14), transparent 70%), var(--bg); }
        .container { width:min(1180px, calc(100% - 2rem)); margin:0 auto; }
        .topbar { position:sticky; top:0; z-index:20; backdrop-filter:blur(10px); background:rgba(255,255,255,.94); border-bottom:1px solid var(--line); }
        .topbar-inner { min-height:74px; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .brand { display:inline-flex; align-items:center; gap:.72rem; text-decoration:none; color:inherit; }
        .brand img { width:48px; height:48px; object-fit:contain; border-radius:12px; border:1px solid var(--line); background:#f8fbff; padding:5px; }
        .brand-name { font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:1.18rem; font-weight:700; letter-spacing:-.02em; color:#0b1f3a; }
        .nav { display:inline-flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
        .nav a { text-decoration:none; color:#475569; font-weight:600; font-size:.92rem; padding:.55rem .8rem; border-radius:10px; }
        .nav a:hover { background:var(--surface-soft); color:var(--text); }
        .nav .cta { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 22px rgba(10,63,143,.22); }
        main { padding-bottom:2.8rem; }
        .hero-shell { position:relative; overflow:hidden; background:#dbe7f8; }
        .hero-shell::after { content:""; position:absolute; inset:0; background:linear-gradient(180deg, rgba(8,23,52,.22), rgba(8,23,52,.44)); }
        .hero-image { width:100%; height:420px; object-fit:cover; display:block; }
        .hero-content { position:absolute; inset:0; z-index:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:1.5rem; color:#fff; }
        .hero-content h1 { margin:0 0 .8rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(2.2rem, 5vw, 4rem); line-height:.92; letter-spacing:-.04em; max-width:10ch; }
        .hero-content p { margin:0 0 1.2rem; max-width:58ch; font-size:1.05rem; line-height:1.7; color:rgba(255,255,255,.92); }
        .hero-actions { display:flex; gap:.75rem; flex-wrap:wrap; justify-content:center; }
        .btn { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; border-radius:12px; padding:.85rem 1.2rem; font-weight:800; border:1px solid transparent; }
        .btn-primary { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 12px 24px rgba(10,63,143,.24); }
        .btn-light { color:var(--text); background:#fff; border-color:#dbe6f3; }
        .search-card { position:relative; z-index:2; width:min(1080px, calc(100% - 2rem)); margin:-3.1rem auto 0; background:var(--surface); border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow); padding:1rem 1rem 1.1rem; }
        .search-card h2 { margin:0 0 1rem; font-size:1.35rem; }
        .search-grid { display:grid; grid-template-columns:1.15fr 1fr 1fr auto; gap:.8rem; align-items:start; }
        .field label { display:block; margin-bottom:.35rem; font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.07em; font-weight:800; }
        .field input, .field select { width:100%; border:1px solid #c8d7ea; background:#f8fbff; border-radius:10px; padding:.72rem .8rem; font:inherit; color:#0f172a; }
        .field input.input-error, .field select.input-error { border-color:#dc2626; background:#fff7f7; }
        .field-error { display:block; height:2.25rem; margin-top:.3rem; color:#b91c1c; font-size:.8rem; font-weight:600; line-height:1.35; overflow:hidden; }
        .form-alert { margin:0 0 .8rem; border:1px solid #fecaca; background:#fff1f2; color:#9f1239; border-radius:10px; padding:.65rem .75rem; font-size:.85rem; font-weight:600; grid-column:1 / -1; }
        .search-btn { width:100%; height:46px; border:0; border-radius:10px; padding:0 1rem; font:inherit; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:.45rem; }
        .search-btn .btn-spinner { display:none; width:16px; height:16px; border:2px solid rgba(255,255,255,.45); border-top-color:#fff; border-radius:999px; animation:btn-spin .7s linear infinite; }
        .search-btn.is-loading .btn-spinner { display:inline-block; }
        .search-btn.is-loading { pointer-events:none; opacity:.95; }
        @keyframes btn-spin { to { transform:rotate(360deg); } }
        .section { padding:3rem 0 0; }
        .section h2 { margin:0 0 .35rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(1.8rem, 3vw, 2.5rem); letter-spacing:-.03em; }
        .section-sub { margin:0 0 1.5rem; color:var(--muted); }
        .benefits-grid, .fleet-grid, .steps-grid, .footer-grid { display:grid; gap:1rem; }
        .benefits-grid, .fleet-grid, .steps-grid { grid-template-columns:repeat(3, minmax(0,1fr)); }
        .benefit-card, .fleet-card, .step-card { background:var(--surface); border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow); }
        .benefit-card { padding:1.3rem; text-align:center; }
        .benefit-icon { width:58px; height:58px; margin:0 auto; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; background:var(--primary-soft); color:var(--primary); font-weight:800; font-size:1.25rem; }
        .benefit-card h3 { margin:1rem 0 .55rem; font-size:1.08rem; }
        .benefit-card p { margin:0; color:var(--muted); line-height:1.7; }
        .section-row { display:flex; justify-content:space-between; align-items:end; gap:1rem; margin-bottom:1.4rem; }
        .text-link { color:var(--primary); text-decoration:none; font-weight:800; }
        .fleet-card { overflow:hidden; }
        .fleet-photo { position:relative; height:220px; background:#eef4fb; }
        .fleet-photo img { width:100%; height:100%; object-fit:cover; }
        .fleet-tag { position:absolute; top:12px; right:12px; padding:.28rem .55rem; border-radius:999px; background:#0f66c3; color:#fff; font-size:.66rem; text-transform:uppercase; letter-spacing:.06em; font-weight:800; }
        .fleet-body { padding:1rem; }
        .fleet-title-row { display:flex; justify-content:space-between; gap:.8rem; align-items:flex-start; margin-bottom:.45rem; }
        .fleet-title { margin:0; font-size:1.08rem; font-weight:800; }
        .fleet-rate { color:var(--primary); font-weight:800; font-size:1.65rem; line-height:1; }
        .fleet-rate small { display:block; color:#64748b; font-size:.8rem; font-weight:700; margin-top:.1rem; text-align:right; }
        .fleet-sub { margin:0 0 .7rem; color:var(--muted); font-size:.92rem; }
        .fleet-meta { display:flex; flex-wrap:wrap; gap:.38rem; margin-bottom:.95rem; }
        .fleet-meta span { padding:.28rem .48rem; border-radius:999px; background:#f3f7fd; border:1px solid #dbe6f3; color:#5b728e; font-size:.72rem; }
        .select-btn { display:block; width:100%; text-align:center; text-decoration:none; border-radius:10px; padding:.85rem 1rem; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); }
        .steps-wrap { background:transparent; padding-top:3.5rem; }
        .steps-grid { position:relative; }
        .step-card { padding:1.2rem; text-align:center; box-shadow:none; background:transparent; border:0; }
        .step-badge { width:56px; height:56px; margin:0 auto 1rem; border-radius:999px; background:linear-gradient(135deg, var(--primary), var(--primary-2)); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:1.35rem; box-shadow:0 12px 24px rgba(10,63,143,.24); }
        .step-card h3 { margin:0 0 .45rem; font-size:1.05rem; }
        .step-card p { margin:0; color:var(--muted); line-height:1.7; }
        footer { margin-top:2.6rem; border-top:1px solid #215fb2; background:linear-gradient(135deg, #0a3f8f, #0f66c3); color:#d9e8ff; }
        .footer-inner { padding:1.35rem 0 1.1rem; }
        .footer-grid { grid-template-columns:1.4fr 1fr 1fr 1fr; padding-bottom:1rem; border-bottom:1px solid rgba(219,232,255,.28); }
        .footer-brand { display:flex; align-items:flex-start; gap:.6rem; }
        .footer-logo { width:36px; height:24px; object-fit:contain; margin-top:.15rem; }
        .footer-title { margin:0 0 .55rem; font-size:.76rem; letter-spacing:.05em; text-transform:uppercase; color:#bfdbff; font-weight:800; }
        .footer-brand-name { margin:0 0 .35rem; font-size:.97rem; font-weight:700; color:#f8fbff; }
        .footer-copy { margin:0; color:#d9e8ff; font-size:.83rem; line-height:1.6; max-width:28ch; }
        .footer-links { list-style:none; margin:0; padding:0; display:grid; gap:.42rem; }
        .footer-links a { color:#e7f0ff; text-decoration:none; font-size:.85rem; }
        .footer-bottom { padding-top:.9rem; text-align:center; font-size:.82rem; color:#bcd0ef; }
        @media (max-width:980px) { .search-grid,.benefits-grid,.fleet-grid,.steps-grid,.footer-grid { grid-template-columns:1fr; } .hero-image { height:500px; } .search-card { margin-top:-2rem; } .section-row { flex-direction:column; align-items:flex-start; } }
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
                <a href="{{ route('fleet.index') }}">Fleet</a>
                <a href="{{ route('pricing.index') }}">Rates</a>
                <a href="#benefits">Benefits</a>
                <a href="#how-it-works">How It Works</a>
                <a class="cta" href="#search">Book Now</a>
            </nav>
        </div>
    </header>
    <main>
        <section class="hero-shell">
            <img class="hero-image" src="{{ asset('images/short-term.png') }}" alt="Short-term car rental">
            <div class="hero-content">
                <h1>Flexible Short-Term Car Rentals</h1>
                <p>Affordable daily and weekly rates for your next journey. No strings attached.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="{{ route('fleet.index') }}">Browse Fleet</a>
                    <a class="btn btn-light" href="{{ route('pricing.index') }}">View Pricing</a>
                </div>
            </div>
        </section>

        <section id="search" class="search-card">
            <h2>Search Daily Rentals</h2>
            <form id="shortTermSearchForm" class="search-grid" action="{{ route('fleet.index') }}" method="get" novalidate>
                <div id="short_search_alert" class="form-alert" style="display:none;"></div>
                <div class="field">
                    <label for="start_location">Pickup Location</label>
                    <select id="start_location" name="start_location" required>
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </select>
                    <small id="start_location_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label for="start_date">Start Date</label>
                    <input id="start_date" name="start_date" type="date" required>
                    <small id="start_date_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label for="end_date">End Date</label>
                    <input id="end_date" name="end_date" type="date" required>
                    <small id="end_date_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label aria-hidden="true" style="visibility:hidden;">Search</label>
                    <button class="search-btn" type="submit" id="shortTermSubmitBtn" data-loading-text="Checking...">
                        <span class="btn-spinner" aria-hidden="true"></span>
                        <span class="btn-label">Search Available Cars</span>
                    </button>
                    <small class="field-error">&nbsp;</small>
                </div>
            </form>
        </section>

        <div class="container">
            <section id="benefits" class="section">
                <h2 style="text-align:center;">Short-Term Rental Benefits</h2>
                <p class="section-sub" style="text-align:center;">Everything you need for a hassle-free journey</p>
                <div class="benefits-grid">
                    <article class="benefit-card">
                        <div class="benefit-icon">1</div>
                        <h3>No Long-Term Commitment</h3>
                        <p>Rent for a day, a weekend, or a week. You are in control of the duration.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="benefit-icon">2</div>
                        <h3>Insurance Included</h3>
                        <p>Drive with peace of mind. Comprehensive insurance coverage is standard on every hire.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="benefit-icon">3</div>
                        <h3>24/7 Roadside Assistance</h3>
                        <p>We are always here to help. Emergency support is just a phone call away, anytime.</p>
                    </article>
                </div>
            </section>

            <section class="section">
                <div class="section-row">
                    <div>
                        <h2>Daily Rental Fleet</h2>
                        <p class="section-sub">Premium quality cars for your daily needs</p>
                    </div>
                    <a class="text-link" href="{{ route('fleet.index') }}">View All Cars</a>
                </div>
                <div class="fleet-grid">
                    @foreach($featuredCars as $car)
                        <article class="fleet-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] }}">
                            <div class="fleet-photo">
                                <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}">
                                <span class="fleet-tag">{{ $car['tag'] }}</span>
                            </div>
                            <div class="fleet-body">
                                <div class="fleet-title-row">
                                    <h3 class="fleet-title">{{ $car['name'] }}</h3>
                                    <div class="fleet-rate">Rs {{ number_format($car['daily_rate'], 0) }}<small>/ per day</small></div>
                                </div>
                                <p class="fleet-sub">{{ $car['model'] ?: 'Compact daily rental' }} &bull; {{ $car['transmission'] ?: 'Automatic' }} &bull; {{ $car['seats'] }}</p>
                                <div class="fleet-meta">
                                    @foreach(array_filter([$car['fuel_type'], $car['transmission'], $car['seats']]) as $meta)
                                        <span>{{ $meta }}</span>
                                    @endforeach
                                </div>
                                <a class="select-btn" href="{{ route('booking.confirm', ['car' => $car['id']]) }}">Select Vehicle</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="how-it-works" class="section steps-wrap">
                <h2 style="text-align:center;">How It Works</h2>
                <div class="steps-grid">
                    <article class="step-card">
                        <div class="step-badge">1</div>
                        <h3>Choose your car</h3>
                        <p>Browse our diverse fleet and find the perfect ride for your trip.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-badge">2</div>
                        <h3>Select your dates</h3>
                        <p>Pick your start and end dates. Pay securely online or on pickup.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-badge">3</div>
                        <h3>Drive away</h3>
                        <p>Pick up your keys and enjoy the journey with 24/7 support.</p>
                    </article>
                </div>
            </section>
        </div>
    </main>
    @include('partials.public-footer')
    <script>
        (function () {
            const form = document.getElementById('shortTermSearchForm');
            const startLocation = document.getElementById('start_location');
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const alertBox = document.getElementById('short_search_alert');
            const submitBtn = document.getElementById('shortTermSubmitBtn');
            if (!form || !startLocation || !startDate || !endDate) return;

            const fieldErrors = {
                start_location: document.getElementById('start_location_error'),
                start_date: document.getElementById('start_date_error'),
                end_date: document.getElementById('end_date_error'),
            };

            const clearError = (field, key) => {
                if (field) field.classList.remove('input-error');
                if (fieldErrors[key]) fieldErrors[key].textContent = '';
            };

            const setError = (field, key, message) => {
                if (field) field.classList.add('input-error');
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
            endDate.min = `${yyyy}-${mm}-${dd}`;

            startLocation.addEventListener('change', () => {
                clearError(startLocation, 'start_location');
                hideAlertIfNoErrors();
            });

            startDate.addEventListener('change', () => {
                if (startDate.value) {
                    if (startDate.min && startDate.value < startDate.min) {
                        setError(startDate, 'start_date', 'Start date cannot be in the past.');
                        return;
                    }
                    clearError(startDate, 'start_date');
                    endDate.min = startDate.value;
                    if (endDate.value && endDate.value < startDate.value) {
                        setError(endDate, 'end_date', 'End date must be same day or after start date.');
                    }
                    hideAlertIfNoErrors();
                }
            });

            endDate.addEventListener('change', () => {
                if (!endDate.value) return;
                if (startDate.value && endDate.value < startDate.value) {
                    setError(endDate, 'end_date', 'End date must be same day or after start date.');
                    return;
                }
                clearError(endDate, 'end_date');
                hideAlertIfNoErrors();
            });

            form.addEventListener('submit', (event) => {
                let hasErrors = false;
                clearError(startLocation, 'start_location');
                clearError(startDate, 'start_date');
                clearError(endDate, 'end_date');
                if (alertBox) alertBox.style.display = 'none';

                if (!startLocation.value) {
                    setError(startLocation, 'start_location', 'Please select pickup location.');
                    hasErrors = true;
                }

                if (!startDate.value) {
                    setError(startDate, 'start_date', 'Please select start date.');
                    hasErrors = true;
                } else if (startDate.min && startDate.value < startDate.min) {
                    setError(startDate, 'start_date', 'Start date cannot be in the past.');
                    hasErrors = true;
                }

                if (!endDate.value) {
                    setError(endDate, 'end_date', 'Please select end date.');
                    hasErrors = true;
                } else if (startDate.value && endDate.value < startDate.value) {
                    setError(endDate, 'end_date', 'End date must be same day or after start date.');
                    hasErrors = true;
                }

                if (hasErrors) {
                    event.preventDefault();
                    clearLoadingState();
                    return;
                }

                setLoadingState();
                setTimeout(() => {
                    if (document.visibilityState === 'visible') {
                        clearLoadingState();
                    }
                }, 5000);
            });

            window.addEventListener('pageshow', clearLoadingState);
            window.addEventListener('focus', clearLoadingState);
        })();
    </script>
</body>
</html>

