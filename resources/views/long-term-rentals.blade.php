<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Long-Term Rentals | R&A Auto Rentals',
        'description' => 'Long-term monthly rental plans with discounted rates, flexible terms, and support for family and business travel.',
        'keywords' => ['long-term rentals', 'monthly car rental', 'Sri Lanka monthly rent a car', 'corporate rental plans'],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root { --bg:#f4f7fb; --surface:#fff; --line:#dbe6f3; --text:#0f172a; --muted:#64748b; --primary:#0a3f8f; --primary2:#0f66c3; --soft:#eaf2ff; --radius:16px; --shadow:0 18px 44px rgba(10,63,143,.12); }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--text); font-family:"Plus Jakarta Sans","Segoe UI",Tahoma,sans-serif; background:radial-gradient(70rem 28rem at 100% -20%, rgba(15,102,195,.14), transparent 70%), var(--bg); }
        .container { width:min(1140px, calc(100% - 2rem)); margin:0 auto; }
        .topbar { position:sticky; top:0; z-index:30; backdrop-filter:blur(10px); background:rgba(255,255,255,.94); border-bottom:1px solid var(--line); }
        .topbar-inner { min-height:72px; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .brand { display:inline-flex; align-items:center; gap:.65rem; text-decoration:none; color:inherit; }
        .brand img { width:44px; height:44px; object-fit:contain; border-radius:10px; border:1px solid var(--line); background:#f8fbff; padding:4px; }
        .brand-name { font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:1.12rem; font-weight:700; color:#0b1f3a; letter-spacing:-.02em; }
        .nav { display:inline-flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
        .nav a { text-decoration:none; color:#475569; font-weight:600; font-size:.9rem; padding:.52rem .75rem; border-radius:10px; }
        .nav a:hover { background:#f8fbff; color:#0f172a; }
        .nav .active { color:var(--primary2); background:var(--soft); }
        .nav .cta { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary2)); box-shadow:0 10px 20px rgba(10,63,143,.22); }
        main { padding-bottom:2.5rem; }
        .hero { margin:1rem auto 0; border-radius:20px; overflow:hidden; position:relative; box-shadow:var(--shadow); border:1px solid #c6daf3; }
        .hero img { width:100%; height:320px; object-fit:cover; display:block; }
        .hero::after { content:""; position:absolute; inset:0; background:linear-gradient(90deg, rgba(8,23,52,.76), rgba(8,23,52,.32)); }
        .hero-content { position:absolute; inset:0; z-index:1; padding:1.45rem 1.65rem; color:#fff; display:flex; flex-direction:column; justify-content:center; max-width:38rem; }
        .kicker { display:inline-flex; align-items:center; padding:.25rem .6rem; border-radius:999px; background:#0f66c3; font-size:.66rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; margin-bottom:.6rem; }
        .hero h1 { margin:0 0 .6rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(2rem, 4vw, 3.4rem); line-height:.92; letter-spacing:-.03em; }
        .hero h1 span { color:#66a9ff; }
        .hero p { margin:0 0 .9rem; max-width:50ch; color:rgba(255,255,255,.94); line-height:1.6; }
        .hero-actions { display:flex; gap:.6rem; flex-wrap:wrap; }
        .btn { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; border-radius:10px; padding:.72rem 1rem; font-size:.9rem; font-weight:800; border:1px solid transparent; }
        .btn-primary { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary2)); box-shadow:0 10px 20px rgba(10,63,143,.24); }
        .btn-dark { color:#fff; background:rgba(8,23,52,.8); border-color:rgba(255,255,255,.28); }
        .quick-card { margin:-1.2rem auto 0; width:min(1020px, calc(100% - 2rem)); background:var(--surface); border:1px solid var(--line); border-radius:14px; padding:1rem; box-shadow:var(--shadow); position:relative; z-index:2; }
        .quick-title { margin:0 0 .8rem; font-size:1.1rem; font-weight:800; }
        .quick-grid { display:grid; grid-template-columns:1fr 1fr 1fr 1fr 1fr auto; gap:.7rem; align-items:end; }
        .field label { display:block; margin-bottom:.3rem; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#64748b; }
        .field input, .field select { width:100%; border:1px solid #c8d7ea; background:#f8fbff; border-radius:10px; padding:.7rem .75rem; font:inherit; color:#0f172a; }
        .field input.input-error, .field select.input-error { border-color:#dc2626; background:#fff7f7; }
        .field-error { display:block; height:2.25rem; margin-top:.3rem; color:#b91c1c; font-size:.8rem; font-weight:600; line-height:1.35; overflow:hidden; }
        .form-alert { margin:0 0 .8rem; border:1px solid #fecaca; background:#fff1f2; color:#9f1239; border-radius:10px; padding:.65rem .75rem; font-size:.85rem; font-weight:600; grid-column:1 / -1; }
        .search-btn { height:44px; border:0; border-radius:10px; padding:0 1rem; font:inherit; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary2)); box-shadow:0 10px 20px rgba(10,63,143,.22); cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:.45rem; }
        .search-btn .btn-spinner { display:none; width:16px; height:16px; border:2px solid rgba(255,255,255,.45); border-top-color:#fff; border-radius:999px; animation:btn-spin .7s linear infinite; }
        .search-btn.is-loading .btn-spinner { display:inline-block; }
        .search-btn.is-loading { pointer-events:none; opacity:.95; }
        @keyframes btn-spin { to { transform:rotate(360deg); } }
        .section { padding:2.7rem 0 0; }
        .section h2 { margin:0 0 .35rem; text-align:center; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(1.8rem, 3vw, 2.4rem); letter-spacing:-.03em; }
        .section-sub { margin:0 0 1.3rem; text-align:center; color:var(--muted); }
        .benefits { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:.9rem; }
        .benefit { background:var(--surface); border:1px solid var(--line); border-radius:14px; padding:1.1rem; box-shadow:var(--shadow); }
        .icon { width:48px; height:48px; border-radius:12px; background:var(--soft); color:var(--primary); display:inline-flex; align-items:center; justify-content:center; font-weight:800; margin-bottom:.7rem; }
        .benefit h3 { margin:0 0 .35rem; font-size:1rem; }
        .benefit p { margin:0; color:var(--muted); line-height:1.55; font-size:.88rem; }
        .section-row { display:flex; justify-content:space-between; align-items:end; gap:.8rem; margin-bottom:1.1rem; }
        .section-row h2, .section-row .section-sub { text-align:left; margin-bottom:.3rem; }
        .section-row .section-sub { margin:0; }
        .text-link { color:var(--primary); text-decoration:none; font-weight:800; }
        .fleet-grid { display:grid; grid-template-columns:repeat(3, minmax(0,1fr)); gap:.9rem; }
        .fleet-card { background:var(--surface); border:1px solid var(--line); border-radius:14px; overflow:hidden; box-shadow:var(--shadow); }
        .fleet-photo { height:180px; background:#edf3ff; }
        .fleet-photo img { width:100%; height:100%; object-fit:cover; display:block; }
        .fleet-body { padding:.85rem; }
        .fleet-tag { display:inline-flex; padding:.18rem .45rem; border-radius:999px; background:var(--soft); color:var(--primary); font-size:.64rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.5rem; }
        .fleet-title { margin:0 0 .25rem; font-size:1rem; font-weight:800; }
        .fleet-sub { margin:0 0 .55rem; color:var(--muted); font-size:.86rem; }
        .fleet-meta { display:flex; gap:.32rem; flex-wrap:wrap; margin-bottom:.65rem; }
        .fleet-meta span { padding:.24rem .44rem; border-radius:999px; border:1px solid #dbe6f3; background:#f3f7fd; color:#5b728e; font-size:.7rem; }
        .fleet-foot { display:flex; justify-content:space-between; align-items:end; gap:.6rem; }
        .fleet-price { color:var(--primary); font-weight:800; font-size:1.5rem; line-height:1; }
        .fleet-price small { display:block; color:#64748b; font-size:.75rem; font-weight:700; margin-top:.1rem; }
        .select-btn { border:0; border-radius:8px; padding:.5rem .7rem; font-size:.8rem; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary2)); text-decoration:none; }
        .steps { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:.8rem; }
        .step { text-align:center; padding:.8rem; }
        .step .num { width:42px; height:42px; border-radius:999px; margin:0 auto .65rem; background:linear-gradient(135deg, var(--primary), var(--primary2)); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-weight:800; box-shadow:0 10px 20px rgba(10,63,143,.2); }
        .step h3 { margin:0 0 .28rem; font-size:.95rem; }
        .step p { margin:0; font-size:.82rem; color:var(--muted); line-height:1.5; }
        .custom { margin-top:2.2rem; display:grid; grid-template-columns:1fr 1.2fr; border:1px solid var(--line); border-radius:16px; overflow:hidden; box-shadow:var(--shadow); background:#fff; }
        .custom-info { background:linear-gradient(135deg, #0a3f8f, #0f66c3); color:#fff; padding:1.25rem; }
        .custom-info h3 { margin:0 0 .5rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:1.75rem; line-height:.95; }
        .custom-info p { margin:0 0 .8rem; color:rgba(255,255,255,.92); line-height:1.6; }
        .contact-line { font-size:.88rem; margin:.32rem 0; }
        .custom-form { padding:1.1rem; }
        .custom-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:.6rem; }
        .custom-form-grid .full { grid-column:1 / -1; }
        .custom-form label { display:block; margin-bottom:.28rem; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#64748b; }
        .custom-form input, .custom-form textarea { width:100%; border:1px solid #c8d7ea; background:#f8fbff; border-radius:9px; padding:.62rem .7rem; font:inherit; }
        .custom-form textarea { min-height:96px; resize:vertical; }
        .custom-form button { margin-top:.65rem; width:100%; border:0; border-radius:9px; padding:.65rem .9rem; font:inherit; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary2)); box-shadow:0 10px 20px rgba(10,63,143,.2); cursor:pointer; }
        footer { margin-top:2.4rem; background:#081734; color:#d9e8ff; }
        .footer-inner { padding:1.25rem 0 1rem; }
        .footer-grid { display:grid; grid-template-columns:1.2fr 1fr 1fr 1fr; gap:1rem; padding-bottom:.9rem; border-bottom:1px solid rgba(219,232,255,.18); }
        .footer-title { margin:0 0 .5rem; color:#bfdbff; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.07em; }
        .footer-brand { margin:0 0 .35rem; color:#f8fbff; font-weight:700; }
        .footer-copy { margin:0; font-size:.82rem; line-height:1.55; color:#d9e8ff; max-width:30ch; }
        .footer-links { list-style:none; margin:0; padding:0; display:grid; gap:.35rem; }
        .footer-links a { color:#e7f0ff; text-decoration:none; font-size:.84rem; }
        .footer-bottom { padding-top:.8rem; font-size:.78rem; color:#bcd0ef; text-align:center; }
        @media (max-width:980px) {
            .quick-grid, .benefits, .fleet-grid, .steps, .custom, .custom-form-grid, .footer-grid { grid-template-columns:1fr; }
            .hero img { height:420px; }
            .quick-card { margin-top:-1rem; }
            .section-row { flex-direction:column; align-items:flex-start; }
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
            <nav class="nav">
                <a href="{{ route('fleet.index') }}">Cars</a>
                <a class="active" href="{{ route('long-term-rentals.index') }}">Long-Term</a>
                <a href="{{ route('pricing.index') }}">Insurance</a>
                <a href="{{ route('airport-hires.index') }}">Locations</a>
                <a href="{{ route('home') }}#contact-section">Support</a>
                <a class="cta" href="{{ route('login') }}">Sign In</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="container hero">
            <img src="{{ asset('images/long-term.png') }}" alt="Long-term rental car">
            <div class="hero-content">
                <span class="kicker">Limited Offer</span>
                <h1>Long-Term Rentals: <span>Save up to 40%</span></h1>
                <p>Enjoy the freedom of a personal vehicle without ownership. Monthly plans with lower daily rates and included maintenance support.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#monthly-fleet">View Monthly Fleet</a>
                    <a class="btn btn-dark" href="#how-it-works">How It Works</a>
                </div>
            </div>
        </section>

        <section class="quick-card">
            <h2 class="quick-title">Quick Inquiry</h2>
            <form id="longTermQuickForm" class="quick-grid" action="{{ route('fleet.index') }}" method="get" novalidate>
                <div id="quick_form_alert" class="form-alert" style="display:none;"></div>
                <div class="field">
                    <label for="start_date">Start Date</label>
                    <input id="start_date" name="start_date" type="date" required>
                    <small id="start_date_error" class="field-error"></small>
                </div>
                <div class="field">
                    <label for="duration">Duration (Months)</label>
                    <select id="duration" name="duration">
                        @foreach($durationOptions as $duration)
                            <option value="{{ $duration }}">{{ $duration }}</option>
                        @endforeach
                    </select>
                    <small class="field-error">&nbsp;</small>
                </div>
                <div class="field">
                    <label for="quick_name">Name</label>
                    <input id="quick_name" name="quick_name" type="text">
                    <small class="field-error">&nbsp;</small>
                </div>
                <div class="field">
                    <label for="quick_phone">Phone</label>
                    <input id="quick_phone" name="quick_phone" type="text" placeholder="+94 ...">
                    <small class="field-error">&nbsp;</small>
                </div>
                <input type="hidden" id="end_date" name="end_date" value="">
                <div class="field">
                    <label aria-hidden="true" style="visibility:hidden;">Submit</label>
                    <button class="search-btn" type="submit" id="longTermSubmitBtn" data-loading-text="Checking...">
                        <span class="btn-spinner" aria-hidden="true"></span>
                        <span class="btn-label">Check Availability</span>
                    </button>
                    <small class="field-error">&nbsp;</small>
                </div>
            </form>
        </section>

        <div class="container">
            <section class="section">
                <h2>Why Choose Long-Term?</h2>
                <p class="section-sub">Get the best value and flexibility with monthly rental programs.</p>
                <div class="benefits">
                    <article class="benefit"><span class="icon">1</span><h3>Lower Daily Rates</h3><p>Up to 40% savings compared to standard daily rentals.</p></article>
                    <article class="benefit"><span class="icon">2</span><h3>Maintenance Included</h3><p>Oil changes, routine checks, and support are handled by us.</p></article>
                    <article class="benefit"><span class="icon">3</span><h3>24/7 Assistance</h3><p>Roadside support whenever you need help.</p></article>
                    <article class="benefit"><span class="icon">4</span><h3>Flexible Terms</h3><p>Switch vehicles or adjust terms as your requirements change.</p></article>
                </div>
            </section>

            <section id="monthly-fleet" class="section">
                <div class="section-row">
                    <div>
                        <h2>Our Monthly Fleet</h2>
                        <p class="section-sub">Choose from well-maintained vehicles ready for long journeys.</p>
                    </div>
                    <a class="text-link" href="{{ route('fleet.index') }}">View all cars</a>
                </div>
                <div class="fleet-grid">
                    @foreach($featuredCars as $car)
                        <article class="fleet-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] }}">
                            <div class="fleet-photo"><img src="{{ $car['image'] }}" alt="{{ $car['name'] }}"></div>
                            <div class="fleet-body">
                                <span class="fleet-tag">{{ $car['tag'] }}</span>
                                <h3 class="fleet-title">{{ $car['name'] }}</h3>
                                <p class="fleet-sub">{{ $car['model'] }} or similar</p>
                                <div class="fleet-meta">
                                    <span>{{ $car['transmission'] }}</span>
                                    <span>LKR {{ number_format($car['daily_rate'], 0) }}/day</span>
                                    <span>{{ number_format((int) $car['per_month_km']) }} km/month included</span>
                                    <span>Without driver</span>
                                </div>
                                <div class="fleet-foot">
                                    <div class="fleet-price">
                                        Rs {{ number_format($car['monthly_rate'], 0) }}
                                        <small>/ month (without driver)</small>
                                    </div>
                                    <a class="select-btn" href="{{ route('booking.confirm', ['car' => $car['id']]) }}">Select</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="how-it-works" class="section">
                <h2>How it Works</h2>
                <p class="section-sub">Simple process to get your monthly rental running fast.</p>
                <div class="steps">
                    <article class="step"><div class="num">1</div><h3>Choose Car</h3><p>Select the vehicle that matches your monthly need.</p></article>
                    <article class="step"><div class="num">2</div><h3>Request Quote</h3><p>Tell us your duration and preferred start date.</p></article>
                    <article class="step"><div class="num">3</div><h3>Verify ID</h3><p>Quick documentation and agreement check.</p></article>
                    <article class="step"><div class="num">4</div><h3>Drive Away</h3><p>Pick up and start your long-term journey.</p></article>
                </div>
            </section>

            <section class="custom">
                <div class="custom-info">
                    <h3>Need a Custom Plan?</h3>
                    <p>For corporate fleets or 12+ month rentals, we can design a tailored package for your usage.</p>
                    <p class="contact-line">+94 77 717 3264</p>
                    <p class="contact-line">business@rna-rentals.com</p>
                </div>
                <div class="custom-form">
                    <form method="post" action="{{ route('long-term-rentals.inquiry.store') }}">
                        @csrf
                        <input type="hidden" name="inquiry_type" value="custom">
                        <div class="custom-form-grid">
                            <div><label for="custom_name">Name</label><input id="custom_name" name="name" type="text" required></div>
                            <div><label for="custom_email">Email</label><input id="custom_email" name="email" type="email"></div>
                            <div class="full"><label for="custom_phone">Phone</label><input id="custom_phone" name="phone" type="text"></div>
                            <div class="full"><label for="custom_company">Company (Optional)</label><input id="custom_company" name="company" type="text"></div>
                            <div class="full"><label for="custom_message">Message</label><textarea id="custom_message" name="message" placeholder="Tell us monthly duration, pickup location, and vehicle type." required></textarea></div>
                        </div>
                        <button type="submit">Request Custom Quote</button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    @include('partials.public-footer')
    <script>
        (function () {
            const form = document.getElementById('longTermQuickForm');
            const startDate = document.getElementById('start_date');
            const duration = document.getElementById('duration');
            const endDate = document.getElementById('end_date');
            const alertBox = document.getElementById('quick_form_alert');
            const submitBtn = document.getElementById('longTermSubmitBtn');
            if (!form || !startDate || !duration || !endDate) return;

            const fieldErrors = {
                start_date: document.getElementById('start_date_error'),
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

            startDate.addEventListener('change', () => {
                if (!startDate.value) return;
                if (startDate.min && startDate.value < startDate.min) {
                    setError(startDate, 'start_date', 'Start date cannot be in the past.');
                    return;
                }
                clearError(startDate, 'start_date');
                hideAlertIfNoErrors();
            });

            const computeEndDate = () => {
                if (!startDate.value) {
                    endDate.value = '';
                    return;
                }

                const months = Math.max(1, parseInt(duration.value, 10) || 1);
                const start = new Date(startDate.value + 'T00:00:00');
                const end = new Date(start);
                end.setMonth(end.getMonth() + months);
                end.setDate(end.getDate() - 1);

                const yyyy = end.getFullYear();
                const mm = String(end.getMonth() + 1).padStart(2, '0');
                const dd = String(end.getDate()).padStart(2, '0');
                endDate.value = `${yyyy}-${mm}-${dd}`;
            };

            duration.addEventListener('change', computeEndDate);
            startDate.addEventListener('change', computeEndDate);

            form.addEventListener('submit', (event) => {
                let hasErrors = false;

                clearError(startDate, 'start_date');
                if (alertBox) alertBox.style.display = 'none';

                if (!startDate.value) {
                    setError(startDate, 'start_date', 'Please select start date.');
                    hasErrors = true;
                } else if (startDate.min && startDate.value < startDate.min) {
                    setError(startDate, 'start_date', 'Start date cannot be in the past.');
                    hasErrors = true;
                }

                if (hasErrors) {
                    event.preventDefault();
                    clearLoadingState();
                    return;
                }

                computeEndDate();
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
