<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Car Rentals | Move Smarter</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4efe4;
            --bg-accent: #f6d6a8;
            --panel: rgba(255, 255, 255, 0.82);
            --text: #172332;
            --muted: #4f6174;
            --primary: #e84a24;
            --primary-deep: #b93413;
            --teal: #136f7a;
            --line: rgba(23, 35, 50, 0.12);
            --shadow: 0 18px 48px rgba(23, 35, 50, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--text);
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            background:
                radial-gradient(55rem 35rem at 8% -10%, rgba(232, 74, 36, 0.2), transparent 70%),
                radial-gradient(40rem 20rem at 88% 4%, rgba(19, 111, 122, 0.24), transparent 70%),
                linear-gradient(180deg, #f8f3e9 0%, #efe6d6 100%);
            min-height: 100vh;
        }

        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image: radial-gradient(rgba(23, 35, 50, 0.05) 0.6px, transparent 0.6px);
            background-size: 5px 5px;
            opacity: 0.25;
        }

        .container {
            width: min(1150px, calc(100% - 2.8rem));
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(10px);
            background: rgba(248, 243, 233, 0.78);
            border-bottom: 1px solid var(--line);
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            padding: 1rem 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
            color: inherit;
            font-weight: 700;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            letter-spacing: 0.03em;
        }

        .brand-mark {
            width: 1.85rem;
            height: 1.85rem;
            border-radius: 0.55rem;
            background: linear-gradient(135deg, var(--primary) 0%, #f08f25 100%);
            box-shadow: 0 6px 14px rgba(232, 74, 36, 0.34);
        }

        .top-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .top-links a {
            text-decoration: none;
            font-weight: 600;
            color: var(--muted);
            padding: 0.55rem 0.85rem;
            border-radius: 0.65rem;
            transition: 150ms ease;
        }

        .top-links a:hover {
            color: var(--text);
            background: rgba(255, 255, 255, 0.75);
        }

        .top-links a.primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #f08f25);
        }

        .hero {
            padding: 4rem 0 2.5rem;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 2rem;
            align-items: center;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: var(--teal);
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            border: 1px solid rgba(19, 111, 122, 0.35);
            background: rgba(19, 111, 122, 0.08);
        }

        h1 {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(2.2rem, 5vw, 4.3rem);
            line-height: 1.05;
            letter-spacing: -0.02em;
            margin: 1rem 0 1rem;
            max-width: 16ch;
        }

        .hero p {
            font-size: clamp(1rem, 2.1vw, 1.18rem);
            line-height: 1.72;
            color: var(--muted);
            max-width: 52ch;
            margin: 0 0 1.6rem;
        }

        .cta-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            text-decoration: none;
            font-weight: 700;
            padding: 0.85rem 1.2rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 140ms ease, box-shadow 140ms ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #f08f25);
            box-shadow: 0 10px 20px rgba(232, 74, 36, 0.28);
        }

        .btn-secondary {
            color: var(--text);
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--line);
        }

        .hero-panel {
            background: var(--panel);
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 1.25rem;
            box-shadow: var(--shadow);
            padding: 1.35rem;
            animation: rise 700ms ease both;
        }

        .trip-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .trip-form label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--muted);
            display: block;
            margin: 0 0 0.4rem;
        }

        .trip-form input, .trip-form select {
            width: 100%;
            border: 1px solid rgba(23, 35, 50, 0.16);
            background: rgba(255, 255, 255, 0.86);
            border-radius: 0.7rem;
            padding: 0.74rem 0.8rem;
            font: inherit;
            color: var(--text);
        }

        .address-field {
            position: relative;
        }

        .address-suggestions {
            position: absolute;
            top: calc(100% + 0.25rem);
            left: 0;
            right: 0;
            z-index: 20;
            max-height: 220px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid rgba(23, 35, 50, 0.16);
            border-radius: 0.7rem;
            box-shadow: 0 10px 24px rgba(23, 35, 50, 0.12);
            display: none;
        }

        .address-suggestion-item {
            width: 100%;
            text-align: left;
            border: 0;
            background: transparent;
            font: inherit;
            color: var(--text);
            padding: 0.55rem 0.7rem;
            cursor: pointer;
        }

        .address-suggestion-item:hover {
            background: rgba(232, 74, 36, 0.08);
        }

        .trip-form .full {
            grid-column: 1 / -1;
        }

        .trip-form button {
            width: 100%;
            cursor: pointer;
            margin-top: 0.25rem;
        }

        .stats {
            padding: 1rem 0 3.3rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .stat {
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--line);
            border-radius: 1rem;
            padding: 1rem;
            backdrop-filter: blur(6px);
        }

        .stat strong {
            display: block;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .section-title {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            margin: 0 0 1rem;
            letter-spacing: -0.02em;
        }

        .section-note {
            max-width: 60ch;
            color: var(--muted);
            margin: 0 0 1.5rem;
            line-height: 1.7;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            padding-bottom: 4rem;
        }

        .feature {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--line);
            border-radius: 1rem;
            padding: 1.1rem 1rem;
            box-shadow: 0 10px 24px rgba(23, 35, 50, 0.08);
            animation: rise 800ms ease both;
        }

        .feature h3 {
            margin: 0 0 0.35rem;
            font-size: 1.05rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
        }

        .feature p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.94rem;
        }

        .footer {
            padding: 2rem 0 2.7rem;
            border-top: 1px solid var(--line);
            color: var(--muted);
            font-size: 0.92rem;
        }

        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .topbar-inner {
                padding: 0.8rem 0;
            }

            .hero {
                padding-top: 2.2rem;
            }

            .trip-form, .stats-grid, .feature-grid {
                grid-template-columns: 1fr;
            }

            .container {
                width: min(1150px, calc(100% - 1.4rem));
            }
        }

        @keyframes rise {
            from {
                transform: translateY(10px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="grain" aria-hidden="true"></div>

    <header class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark"></span>
                <span>Car Rentals</span>
            </a>
            <nav class="top-links">
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
                    <a class="primary" href="{{ route('dashboard') }}">Open Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a class="primary" href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-grid">
                <div>
                    <span class="badge">Fleet Operations Platform</span>
                    <h1>Run a premium rental business without operational chaos.</h1>
                    <p>
                        Manage cars, customers, agreements, expenses, and kilometer logs from one place.
                        Built for teams that want cleaner workflows, faster collections, and better visibility.
                    </p>
                    <div class="cta-row">
                        @auth
                            <a class="btn btn-primary" href="{{ route('dashboard') }}">Go To Dashboard</a>
                            <a class="btn btn-secondary" href="{{ route('gps-logs.index') }}">Open KM Logs</a>
                        @else
                            <a class="btn btn-primary" href="{{ route('register') }}">Create Account</a>
                            <a class="btn btn-secondary" href="{{ route('login') }}">Login</a>
                        @endauth
                    </div>
                </div>

                <aside class="hero-panel">
                    <form class="trip-form">
                        <div class="address-field">
                            <label for="pickup">Pickup Location</label>
                            <input id="pickup" type="text" placeholder="Search pickup address in Sri Lanka" autocomplete="off">
                            <input id="pickup-lat" type="hidden">
                            <input id="pickup-lng" type="hidden">
                            <div id="pickup-suggestions" class="address-suggestions"></div>
                        </div>
                        <div class="address-field">
                            <label for="dropoff">Drop-off Location</label>
                            <input id="dropoff" type="text" placeholder="Search drop-off address in Sri Lanka" autocomplete="off">
                            <input id="dropoff-lat" type="hidden">
                            <input id="dropoff-lng" type="hidden">
                            <div id="dropoff-suggestions" class="address-suggestions"></div>
                        </div>
                        <div>
                            <label for="date-start">Pickup Date</label>
                            <input id="date-start" type="date" value="{{ now()->toDateString() }}">
                        </div>
                        <div>
                            <label for="date-end">Drop-off Date</label>
                            <input id="date-end" type="date" value="{{ now()->addDays(3)->toDateString() }}">
                        </div>
                        <div class="full">
                            <button class="btn btn-primary" type="button">Start Reservation</button>
                        </div>
                        <div class="full" style="font-size:0.85rem;color:var(--muted);">
                            Sri Lanka address search powered by OpenStreetMap (free).
                        </div>
                    </form>
                </aside>
            </div>
        </section>

        <section class="stats">
            <div class="container stats-grid">
                <article class="stat">
                    <strong>99.2%</strong>
                    <span>Fleet availability control</span>
                </article>
                <article class="stat">
                    <strong>Daily</strong>
                    <span>KM activity logging</span>
                </article>
                <article class="stat">
                    <strong>3x</strong>
                    <span>Faster payment follow-up</span>
                </article>
                <article class="stat">
                    <strong>1 dashboard</strong>
                    <span>Cars, customers, contracts, and finance</span>
                </article>
            </div>
        </section>

        <section class="container">
            <h2 class="section-title">Everything your operations team needs</h2>
            <p class="section-note">
                This homepage is optimized to guide users into key modules quickly while keeping the visual style clean,
                high-contrast, and conversion focused.
            </p>

            <div class="feature-grid">
                <article class="feature">
                    <h3>Fleet Management</h3>
                    <p>Create, update, and monitor every vehicle profile with one-click access to status and details.</p>
                </article>
                <article class="feature">
                    <h3>Customer Registry</h3>
                    <p>Track customer records, rental history, and activity with fast search-ready structure.</p>
                </article>
                <article class="feature">
                    <h3>Payment Control</h3>
                    <p>View due payments, mark paid entries, and keep collection workflows visible for your team.</p>
                </article>
                <article class="feature">
                    <h3>Smart Agreements</h3>
                    <p>Manage rental contracts in one system and reduce manual errors across operations.</p>
                </article>
                <article class="feature">
                    <h3>Expense Tracking</h3>
                    <p>Log recurring and one-time expenses to keep profitability clear at all times.</p>
                </article>
                <article class="feature">
                    <h3>DAGPS KM Logs</h3>
                    <p>Record daily mileage and generate monthly per-car reports for operational control.</p>
                </article>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            Car Rentals Platform | Laravel {{ Illuminate\Foundation\Application::VERSION }} | PHP {{ PHP_VERSION }}
        </div>
    </footer>
    <script>
        (function () {
            const debounce = (fn, delay = 350) => {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn(...args), delay);
                };
            };

            const bindAddressSearch = (inputId, suggestionsId, latId, lngId) => {
                const input = document.getElementById(inputId);
                const suggestions = document.getElementById(suggestionsId);
                const latInput = document.getElementById(latId);
                const lngInput = document.getElementById(lngId);
                if (!input || !suggestions) return;

                const hideSuggestions = () => {
                    suggestions.innerHTML = '';
                    suggestions.style.display = 'none';
                };

                const renderSuggestions = (items) => {
                    suggestions.innerHTML = '';
                    if (!items.length) {
                        hideSuggestions();
                        return;
                    }

                    items.forEach((item) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'address-suggestion-item';
                        btn.textContent = item.display_name;
                        btn.addEventListener('click', () => {
                            input.value = item.display_name;
                            if (latInput) latInput.value = item.lat;
                            if (lngInput) lngInput.value = item.lon;
                            hideSuggestions();
                        });
                        suggestions.appendChild(btn);
                    });

                    suggestions.style.display = 'block';
                };

                const search = debounce(async () => {
                    const q = input.value.trim();
                    if (q.length < 3) {
                        hideSuggestions();
                        return;
                    }

                    try {
                        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&addressdetails=1&countrycodes=lk&limit=5&q=${encodeURIComponent(q)}`;
                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                            },
                        });
                        if (!response.ok) {
                            hideSuggestions();
                            return;
                        }
                        const data = await response.json();
                        renderSuggestions(Array.isArray(data) ? data : []);
                    } catch (e) {
                        hideSuggestions();
                    }
                }, 400);

                input.addEventListener('input', () => {
                    if (latInput) latInput.value = '';
                    if (lngInput) lngInput.value = '';
                    search();
                });

                document.addEventListener('click', (event) => {
                    if (!suggestions.contains(event.target) && event.target !== input) {
                        hideSuggestions();
                    }
                });
            };

            bindAddressSearch('pickup', 'pickup-suggestions', 'pickup-lat', 'pickup-lng');
            bindAddressSearch('dropoff', 'dropoff-suggestions', 'dropoff-lat', 'dropoff-lng');
        })();
    </script>
</body>
</html>
