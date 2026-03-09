<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Group Packages | R&A Auto Rentals',
        'description' => 'Group travel packages with spacious fleet options, simple booking, and support for family trips, events, and tours.',
        'keywords' => ['group packages', 'group van rental', 'family trip rental', '12 seater van', 'group transport'],
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
        .nav .active-link { color:var(--primary-2); background:var(--primary-soft); }
        .nav .cta { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 22px rgba(10,63,143,.22); }
        main { padding-bottom:2.8rem; }
        .hero { position:relative; overflow:hidden; background:#dbe7f8; width:min(1180px, calc(100% - 2rem)); margin:1rem auto 0; border-radius:24px; box-shadow:var(--shadow); }
        .hero::after { content:""; position:absolute; inset:0; background:linear-gradient(90deg, rgba(8,23,52,.74), rgba(8,23,52,.18)); }
        .hero-image { width:100%; height:420px; object-fit:cover; display:block; }
        .hero-content { position:absolute; inset:0; z-index:3; display:flex; flex-direction:column; justify-content:center; align-items:flex-start; text-align:left; padding:2.85rem 2.25rem 5.75rem 2.25rem; color:#fff; max-width:48rem; }
        .hero-content h1 { margin:0 0 .8rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(2.2rem, 5vw, 4rem); line-height:.92; letter-spacing:-.04em; max-width:10ch; }
        .hero-content p { margin:0 0 1.15rem; font-size:1.02rem; line-height:1.7; color:rgba(255,255,255,.92); max-width:42ch; }
        .hero-actions { display:flex; gap:.75rem; flex-wrap:wrap; }
        .btn { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; border-radius:12px; padding:.85rem 1.2rem; font-weight:800; border:1px solid transparent; }
        .btn-primary { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 12px 24px rgba(10,63,143,.24); }
        .search-card { position:relative; z-index:2; width:min(1120px, calc(100% - 2rem)); margin:-1.2rem auto 0; background:var(--surface); border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow); padding:1rem; }
        .search-grid { display:grid; grid-template-columns:1.2fr 1fr 1fr 1fr auto; gap:.8rem; align-items:end; }
        .field label { display:block; margin-bottom:.35rem; font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.07em; font-weight:800; }
        .field input, .field select { width:100%; border:1px solid #c8d7ea; background:#f8fbff; border-radius:10px; padding:.72rem .8rem; font:inherit; color:#0f172a; }
        .field input.input-error, .field select.input-error { border-color:#dc2626; background:#fff7f7; }
        .field-error { display:block; min-height:1.05rem; margin-top:.3rem; color:#b91c1c; font-size:.8rem; font-weight:600; }
        .form-alert { margin:0 0 .8rem; border:1px solid #fecaca; background:#fff1f2; color:#9f1239; border-radius:10px; padding:.65rem .75rem; font-size:.85rem; font-weight:600; }
        .search-btn { width:100%; height:46px; border:0; border-radius:10px; padding:0 1rem; font:inherit; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:.45rem; }
        .search-btn .btn-spinner { display:none; width:16px; height:16px; border:2px solid rgba(255,255,255,.45); border-top-color:#fff; border-radius:999px; animation:btn-spin .7s linear infinite; }
        .search-btn.is-loading .btn-spinner { display:inline-block; }
        .search-btn.is-loading { pointer-events:none; opacity:.95; }
        @keyframes btn-spin { to { transform:rotate(360deg); } }
        .section { padding:3rem 0 0; }
        .section h2 { margin:0 0 .35rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(1.8rem, 3vw, 2.4rem); letter-spacing:-.03em; text-align:center; }
        .section-sub { margin:0 0 1.5rem; color:var(--muted); text-align:center; }
        .benefits-grid, .fleet-grid, .footer-grid { display:grid; gap:1rem; }
        .benefits-grid { grid-template-columns:repeat(4, minmax(0,1fr)); }
        .fleet-grid { grid-template-columns:repeat(2, minmax(0,1fr)); }
        .benefit-card, .fleet-card, .package-band { background:var(--surface); border:1px solid var(--line); border-radius:16px; box-shadow:var(--shadow); }
        .benefit-card { padding:1.2rem; }
        .benefit-icon { width:52px; height:52px; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; background:var(--primary-soft); color:var(--primary); font-weight:800; font-size:1.15rem; }
        .benefit-card h3 { margin:1rem 0 .45rem; font-size:1rem; }
        .benefit-card p { margin:0; color:var(--muted); line-height:1.7; font-size:.92rem; }
        .section-row { display:flex; justify-content:space-between; align-items:end; gap:1rem; margin-bottom:1.3rem; }
        .text-link { color:var(--primary); text-decoration:none; font-weight:800; }
        .fleet-card { overflow:hidden; display:grid; grid-template-columns:1fr 1fr; }
        .fleet-photo { min-height:220px; background:#eef4fb; }
        .fleet-photo img { width:100%; height:100%; object-fit:cover; display:block; }
        .fleet-body { padding:1rem; display:flex; flex-direction:column; }
        .fleet-head { display:flex; justify-content:space-between; gap:.8rem; align-items:flex-start; margin-bottom:.55rem; }
        .fleet-title { margin:0; font-size:1.08rem; font-weight:800; }
        .fleet-tag { display:inline-flex; padding:.2rem .45rem; border-radius:999px; background:var(--primary-soft); color:var(--primary); font-size:.64rem; text-transform:uppercase; letter-spacing:.06em; font-weight:800; }
        .fleet-sub { margin:0 0 .7rem; color:var(--muted); font-size:.92rem; }
        .fleet-meta { display:flex; flex-wrap:wrap; gap:.38rem; margin-bottom:1rem; }
        .fleet-meta span { padding:.28rem .48rem; border-radius:999px; background:#f3f7fd; border:1px solid #dbe6f3; color:#5b728e; font-size:.72rem; }
        .fleet-footer { margin-top:auto; display:flex; justify-content:space-between; align-items:end; gap:.75rem; }
        .fleet-rate { color:var(--primary); font-weight:800; font-size:1.65rem; line-height:1; }
        .fleet-rate small { display:block; color:#64748b; font-size:.8rem; font-weight:700; margin-top:.15rem; text-align:right; }
        .book-btn { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; border-radius:10px; padding:.7rem .95rem; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); }
        .package-band { margin-top:3rem; background:linear-gradient(135deg, #0a3f8f, #0f66c3); color:#fff; padding:2.4rem; display:flex; justify-content:space-between; align-items:center; gap:1.5rem; overflow:hidden; border-radius:20px; position:relative; }
        .package-band::after { content:""; position:absolute; width:240px; height:240px; border-radius:999px; right:-80px; top:-80px; background:rgba(255,255,255,.09); pointer-events:none; }
        .package-copy { position:relative; z-index:1; max-width:44rem; }
        .package-band h3 { margin:0 0 .65rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:2.1rem; line-height:1.05; letter-spacing:-.02em; }
        .package-band p { margin:0; color:rgba(255,255,255,.94); line-height:1.75; font-size:1.03rem; max-width:42rem; }
        .package-actions { display:flex; gap:.75rem; flex-wrap:wrap; margin-top:1.15rem; }
        .btn-light { background:#fff; color:var(--primary); border-color:#fff; box-shadow:0 12px 24px rgba(2,16,43,.22); }
        .btn-light:hover { background:#f2f7ff; color:#07306d; }
        .btn-outline { color:#fff; border-color:rgba(255,255,255,.55); background:transparent; }
        .btn-outline:hover { background:rgba(255,255,255,.12); border-color:rgba(255,255,255,.82); }
        .package-mark { width:96px; height:96px; border-radius:999px; border:1px solid rgba(255,255,255,.35); display:flex; align-items:center; justify-content:center; font-size:1.9rem; font-weight:800; color:rgba(255,255,255,.92); position:relative; z-index:1; }
        .quote-block { text-align:center; padding:3rem 0 0; }
        .quote-mark { color:var(--primary); font-size:3.4rem; line-height:1; }
        .quote-text { margin:.5rem auto 1rem; max-width:38rem; font-style:italic; font-size:1.6rem; line-height:1.45; color:#0f172a; }
        .quote-person { display:flex; justify-content:center; align-items:center; gap:.75rem; color:var(--muted); }
        .quote-avatar { width:42px; height:42px; border-radius:999px; background:#d9b48c; }
        footer { margin-top:2.6rem; background:#081734; color:#d9e8ff; }
        .footer-inner { padding:1.5rem 0 1rem; }
        .footer-grid { grid-template-columns:1.4fr 1fr 1fr 1fr; padding-bottom:1rem; border-bottom:1px solid rgba(219,232,255,.18); }
        .footer-brand { display:flex; align-items:flex-start; gap:.6rem; }
        .footer-logo { width:36px; height:24px; object-fit:contain; margin-top:.15rem; }
        .footer-title { margin:0 0 .55rem; font-size:.76rem; letter-spacing:.05em; text-transform:uppercase; color:#bfdbff; font-weight:800; }
        .footer-brand-name { margin:0 0 .35rem; font-size:.97rem; font-weight:700; color:#f8fbff; }
        .footer-copy { margin:0; color:#d9e8ff; font-size:.83rem; line-height:1.6; max-width:28ch; }
        .footer-links { list-style:none; margin:0; padding:0; display:grid; gap:.42rem; }
        .footer-links a { color:#e7f0ff; text-decoration:none; font-size:.85rem; }
        .footer-bottom { padding-top:.9rem; display:flex; justify-content:space-between; gap:1rem; flex-wrap:wrap; font-size:.82rem; color:#bcd0ef; }
        @media (max-width:980px) { .search-grid, .benefits-grid, .fleet-grid, .footer-grid { grid-template-columns:1fr; } .hero { width:min(1180px, calc(100% - 1rem)); margin-top:.75rem; } .hero-image { height:500px; } .hero-content { padding:2rem 1.25rem 6.5rem; } .fleet-card { grid-template-columns:1fr; } .package-band, .section-row, .footer-bottom { flex-direction:column; align-items:flex-start; } }
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
                <a class="active-link" href="{{ route('group-packages.index') }}">Group Packages</a>
                <a href="{{ route('airport-hires.index') }}">Specialists</a>
                <a href="{{ route('home') }}#contact-section">Support</a>
                <a href="{{ route('login') }}">My Booking</a>
                <a class="cta" href="{{ route('login') }}">Sign In</a>
            </nav>
        </div>
    </header>
    <main>
        <section class="hero">
            <img class="hero-image" src="{{ asset('images/friends-family.png') }}" alt="Group travel packages">
            <div class="hero-content">
                <h1>Unforgettable Journeys with Family &amp; Friends</h1>
                <p>Spacious vehicles and customized plans for your next group adventure. Comfort meets capability.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="{{ route('fleet.index') }}">Explore Fleet</a>
                </div>
            </div>
        </section>

        <section class="search-card">
            <form class="search-grid" id="groupSearchForm" action="{{ route('fleet.index') }}" method="get" novalidate>
                <div id="group_search_alert" class="form-alert" style="display:none; grid-column:1 / -1;"></div>
                <div class="field">
                    <label for="start_location">Location</label>
                    <input id="start_location" name="start_location" type="text" placeholder="Pickup & Drop-off">
                    <small id="start_location_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label for="start_date">Start Date</label>
                    <input id="start_date" name="start_date" type="date">
                    <small id="start_date_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label for="end_date">End Date</label>
                    <input id="end_date" name="end_date" type="date">
                    <small id="end_date_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label for="passengers">Passengers</label>
                    <select id="passengers" name="passengers">
                        <option>5-7 People</option>
                        <option>8-12 People</option>
                        <option>12+ People</option>
                    </select>
                    <small class="field-error">&nbsp;</small>
                </div>
                <div class="field">
                    <label aria-hidden="true" style="visibility:hidden;">Search</label>
                    <button class="search-btn" type="submit" id="groupSubmitBtn" data-loading-text="Checking...">
                        <span class="btn-spinner" aria-hidden="true"></span>
                        <span class="btn-label">Search Deals</span>
                    </button>
                    <small class="field-error">&nbsp;</small>
                </div>
            </form>
        </section>

        <div class="container">
            <section class="section">
                <h2>Why Choose Us for Groups</h2>
                <p class="section-sub">Flexible transport designed for family trips, events, and shared journeys.</p>
                <div class="benefits-grid">
                    <article class="benefit-card">
                        <div class="benefit-icon">V</div>
                        <h3>Spacious Fleet</h3>
                        <p>High-capacity interiors with extra room for luggage and legroom for every passenger.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="benefit-icon">D</div>
                        <h3>Group Discounts</h3>
                        <p>Unlock significant savings with multi-car bookings and long-term group rentals.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="benefit-icon">S</div>
                        <h3>Safety First</h3>
                        <p>Rigorous multi-point safety inspections before every single trip for your peace of mind.</p>
                    </article>
                    <article class="benefit-card">
                        <div class="benefit-icon">H</div>
                        <h3>24/7 Support</h3>
                        <p>Dedicated concierge support for groups, available around the clock wherever your journey leads.</p>
                    </article>
                </div>
            </section>

            <section class="section">
                <div class="section-row">
                    <div>
                        <h2 style="text-align:left;">Recommended Fleet</h2>
                        <p class="section-sub" style="text-align:left;">Perfectly sized for your group size and needs</p>
                    </div>
                    <a class="text-link" href="{{ route('fleet.index') }}">View Full Fleet</a>
                </div>
                <div class="fleet-grid">
                    @foreach($featuredCars as $car)
                        <article class="fleet-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] }}">
                            <div class="fleet-photo">
                                <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}">
                            </div>
                            <div class="fleet-body">
                                <div class="fleet-head">
                                    <div>
                                        <h3 class="fleet-title">{{ $car['name'] }}</h3>
                                        <p class="fleet-sub">{{ $car['model'] }} or similar</p>
                                    </div>
                                    <span class="fleet-tag">{{ $car['tag'] }}</span>
                                </div>
                                <div class="fleet-meta">
                                    <span>{{ $car['seats'] }} Seats</span>
                                    <span>{{ $car['bags'] }} Bags</span>
                                    <span>{{ $car['transmission'] }}</span>
                                </div>
                                <div class="fleet-footer">
                                    <div class="fleet-rate">Rs {{ number_format($car['daily_rate'], 0) }}<small>/ day</small></div>
                                    <a class="book-btn" href="{{ route('booking.confirm', ['car' => $car['id']]) }}">Book Now</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="package-band">
                <div class="package-copy">
                    <h3>Special Group Packages</h3>
                    <p>Planning a wedding, corporate retreat, or a multi-generational family vacation? Let our specialists design a custom itinerary and fleet package for your event.</p>
                    <div class="package-actions">
                        <a class="btn btn-light" href="{{ route('home') }}#contact-section">Contact Specialist</a>
                        <a class="btn btn-outline" href="{{ route('pricing.index') }}">View Packages</a>
                    </div>
                </div>
            </section>

            <section class="quote-block">
                <div class="quote-mark">"</div>
                <div class="quote-text">Our family trip from Galle to Kandy was smooth from start to finish. We booked a 12-seater van, it arrived on time, and the vehicle was clean and comfortable for everyone.</div>
                <div class="quote-person">
                    <div class="quote-avatar"></div>
                    <div>
                        <div style="font-weight:800;color:#0f172a;">The Wijesinghe Family</div>
                        <div>12-Seater Van Rental</div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    @include('partials.public-footer')
    <script>
        (function () {
            const form = document.getElementById('groupSearchForm');
            const startLocation = document.getElementById('start_location');
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');
            const alertBox = document.getElementById('group_search_alert');
            const submitBtn = document.getElementById('groupSubmitBtn');
            if (!form || !startDate || !endDate) return;

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

            if (startLocation) {
                startLocation.addEventListener('input', () => {
                    if (startLocation.value.trim() !== '') {
                        clearError(startLocation, 'start_location');
                        hideAlertIfNoErrors();
                    }
                });
            }

            startDate.addEventListener('change', () => {
                if (!startDate.value) return;
                clearError(startDate, 'start_date');
                endDate.min = startDate.value;
                if (endDate.value && endDate.value < startDate.value) {
                    setError(endDate, 'end_date', 'End date must be same day or after start date.');
                }
                hideAlertIfNoErrors();
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

                if (!startLocation?.value?.trim()) {
                    setError(startLocation, 'start_location', 'Please enter pickup location.');
                    hasErrors = true;
                }

                if (!startDate.value) {
                    setError(startDate, 'start_date', 'Please select start date.');
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



