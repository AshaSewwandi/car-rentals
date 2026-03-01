<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Booking Confirmation | R&A Auto Rentals',
        'description' => 'Confirm your rental booking details, review pricing, choose payment method, and finalize your trip with R&A Auto Rentals.',
        'keywords' => [
            'booking confirmation',
            'rental payment method',
            'online transfer rental',
            'cash at pickup',
            'trip confirmation',
            'rental checkout',
        ],
        'robots' => 'noindex,follow',
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --line: #dbe6f3;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --ok: #16a34a;
            --radius: 14px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: var(--bg);
        }
        .container { width: min(1160px, calc(100% - 2rem)); margin: 0 auto; }
        .topbar {
            background: rgba(255,255,255,.94);
            border-bottom: 1px solid var(--line);
            position: sticky; top: 0; z-index: 20;
            backdrop-filter: blur(8px);
        }
        .topbar-inner {
            min-height: 70px;
            display: flex; align-items: center; justify-content: space-between; gap: .8rem;
        }
        .brand {
            display: inline-flex; align-items: center; gap: .65rem;
            text-decoration: none; color: inherit;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-weight: 700; font-size: 1.16rem;
        }
        .brand img { width: 44px; height: 30px; object-fit: contain; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            gap: .45rem;
            text-decoration: none; border: 1px solid transparent;
            border-radius: 10px; font-weight: 700;
            padding: .62rem 1rem;
        }
        .btn .btn-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 999px;
            animation: btn-spin .7s linear infinite;
        }
        .btn-light .btn-spinner {
            border-color: rgba(15, 23, 42, 0.22);
            border-top-color: #0f66c3;
        }
        .btn.is-loading .btn-spinner {
            display: inline-block;
        }
        .btn.is-loading {
            pointer-events: none;
        }
        @keyframes btn-spin {
            to { transform: rotate(360deg); }
        }
        .btn-light { background: #f8fbff; color: #334155; border-color: #c9d9ef; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-2)); color: #fff; }
        .page { padding: 1.1rem 0 1.8rem; }
        .banner {
            background: #fff;
            border: 1px solid #cde2ff;
            border-radius: var(--radius);
            padding: .9rem 1rem;
            margin-bottom: .9rem;
            display: flex; flex-wrap: wrap; gap: .7rem 1.3rem; align-items: center;
        }
        .banner strong { display: block; font-size: .92rem; }
        .banner span { color: var(--muted); font-size: .85rem; }
        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 350px;
            gap: .9rem;
            align-items: start;
        }
        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .card-header {
            padding: .9rem 1rem;
            border-bottom: 1px solid var(--line);
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
        }
        .card-body { padding: 1rem; }
        .car-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: .8rem;
        }
        .car-row img {
            width: 100%; height: 100px; border-radius: 10px;
            border: 1px solid var(--line); object-fit: cover; background: #eef4ff;
        }
        .car-name { margin: 0 0 .2rem; font-size: 1.14rem; }
        .car-meta { color: var(--muted); margin: 0 0 .45rem; }
        .pill {
            display: inline-flex; align-items: center;
            border-radius: 999px; padding: .2rem .56rem;
            font-size: .76rem; font-weight: 700;
            background: #ecfdf3; color: var(--ok);
            border: 1px solid #bde5cc;
        }
        .feature-list {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .45rem .7rem; margin-top: .8rem;
        }
        .feature-list div {
            font-size: .86rem; color: #334155;
        }
        .feature-list div::before {
            content: "✓";
            color: var(--ok);
            font-weight: 700;
            margin-right: .35rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .7rem;
        }
        .field label {
            display: block; margin-bottom: .35rem;
            font-size: .75rem; font-weight: 800; letter-spacing: .05em;
            text-transform: uppercase; color: #64748b;
        }
        .field input, .field select, .field textarea {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 10px;
            padding: .62rem .72rem;
            color: #0f172a;
            font: inherit;
        }
        .field textarea { min-height: 92px; resize: vertical; }
        .field.full { grid-column: 1 / -1; }
        .help { font-size: .8rem; color: #64748b; margin-top: .25rem; }
        .bank-details {
            margin-top: .6rem;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            border-radius: 10px;
            padding: .72rem .8rem;
        }
        .bank-details-title {
            margin: 0 0 .45rem;
            font-size: .86rem;
            font-weight: 800;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .bank-details-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .45rem .8rem;
        }
        .bank-details-row {
            display: flex;
            justify-content: space-between;
            gap: .5rem;
            font-size: .88rem;
            color: #1e293b;
            border-bottom: 1px dashed #c7dbff;
            padding-bottom: .2rem;
        }
        .bank-details-row strong {
            color: #0f172a;
            font-weight: 700;
            text-align: right;
        }
        .summary-row {
            display: flex; justify-content: space-between; gap: .6rem;
            padding: .5rem 0; border-bottom: 1px dashed #dbe6f3;
            color: #334155;
        }
        .summary-row:last-child { border-bottom: 0; }
        .summary-row.total {
            font-weight: 800; color: #0a3f8f; font-size: 1.08rem;
            padding-top: .8rem;
        }
        .note {
            margin-top: .7rem;
            border: 1px solid #bde5cc;
            background: #ecfdf3;
            color: #166534;
            border-radius: 10px;
            font-size: .84rem;
            padding: .55rem .65rem;
            line-height: 1.45;
        }
        .actions {
            margin-top: .9rem;
            display: flex;
            justify-content: flex-end;
            gap: .55rem;
        }
        .alert {
            border-radius: 10px;
            padding: .65rem .8rem;
            font-weight: 600;
            border: 1px solid;
            margin-bottom: .7rem;
        }
        .alert-danger { color: #991b1b; background: #fef2f2; border-color: #fecaca; }
        @media (max-width: 980px) {
            .layout { grid-template-columns: 1fr; }
        }
        @media (max-width: 760px) {
            .container { width: calc(100% - 1rem); }
            .car-row { grid-template-columns: 1fr; }
            .grid, .feature-list { grid-template-columns: 1fr; }
            .bank-details-grid { grid-template-columns: 1fr; }
            .actions { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals">
                <span>R&A Auto Rentals</span>
            </a>
            <a class="btn btn-light" href="{{ route('fleet.index', $filters) }}">Back to Fleet</a>
        </div>
    </header>

    <main class="page">
        <div class="container">
            @php
                $currentUser = auth()->user();
                $avatarHash = md5(strtolower(trim((string) ($currentUser->email ?? 'guest@example.com'))));
                $avatarUrl = 'https://www.gravatar.com/avatar/' . $avatarHash . '?d=identicon&s=120';
            @endphp
            <div class="banner">
                <div><strong>Pickup</strong><span>{{ $filters['start_location'] ?: 'To be confirmed' }} - {{ $filters['start_date'] }}</span></div>
                <div><strong>Return Date</strong><span>{{ $filters['end_date'] }}</span></div>
                <div><span>Booking confirmation and payment</span></div>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="post" action="{{ route('booking.store') }}">
                @csrf
                <input type="hidden" name="car_id" value="{{ $car->id }}">
                <input type="hidden" name="start_date" value="{{ $filters['start_date'] }}">
                <input type="hidden" name="end_date" value="{{ $filters['end_date'] }}">
                <input type="hidden" name="pickup_location" value="{{ $filters['start_location'] }}">

                <section class="layout">
                    <div>
                        <div class="card">
                            <div class="card-header">Your deal</div>
                            <div class="card-body">
                                <div class="car-row">
                                    <img src="{{ asset('images/' . strtolower(str_replace([' ', '-'], '_', $car->plate_no)) . '.png') }}" alt="{{ $car->name }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='1rem';">
                                    <div>
                                        <h2 class="car-name">{{ trim($car->name . ' ' . ($car->year ?? '')) }}</h2>
                                        <p class="car-meta">{{ $car->plate_no }} · {{ $car->transmission ?: 'Transmission N/A' }} · {{ $car->fuel_type ?: 'Fuel N/A' }}</p>
                                        <span class="pill">Free cancellation up to 48 hours before pickup</span>
                                        <div class="feature-list">
                                            <div>With driver or without driver</div>
                                            <div>150 km/day included</div>
                                            <div>Rs 25 per extra km</div>
                                            <div>Customer support available</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-top:.8rem;">
                            <div class="card-header">Customer details</div>
                            <div class="card-body">
                                <div style="display:flex;align-items:center;gap:.65rem;padding:.55rem .65rem;border:1px solid #dbe6f3;border-radius:10px;background:#f8fbff;margin-bottom:.8rem;">
                                    <img src="{{ $avatarUrl }}" alt="{{ $currentUser?->name ?: 'Guest user' }}" style="width:42px;height:42px;border-radius:999px;object-fit:cover;border:1px solid #c8d7ea;">
                                    <div style="line-height:1.2;">
                                        <div style="font-weight:700;color:#0f172a;">{{ $currentUser?->name ?: 'Guest booking' }}</div>
                                        <div style="font-size:.82rem;color:#64748b;">{{ $currentUser?->phone ?: 'Phone not set' }}</div>
                                    </div>
                                </div>
                                @guest
                                    <div class="help" style="margin:-.2rem 0 .65rem;">
                                        Continue as guest: we will create your customer account automatically and send a temporary password to your email.
                                    </div>
                                @endguest
                                <div class="grid">
                                    <div class="field">
                                        <label>Name</label>
                                        <input type="text" name="customer_name" value="{{ old('customer_name', $currentUser?->name) }}" required>
                                    </div>
                                    <div class="field">
                                        <label>Phone</label>
                                        <input type="text" name="customer_phone" value="{{ old('customer_phone', $currentUser?->phone) }}" placeholder="+94 ..." required>
                                    </div>
                                    <div class="field full">
                                        <label>Email</label>
                                        <input type="email" name="customer_email" value="{{ old('customer_email', $currentUser?->email) }}" placeholder="you@example.com" required>
                                    </div>
                                    <div class="field full">
                                        <label>Payment method</label>
                                        <select name="payment_method" id="paymentMethod" required>
                                            <option value="pay_later_bank" {{ old('payment_method') === 'pay_later_bank' ? 'selected' : '' }}>Online transfer</option>
                                            <option value="pay_at_pickup_cash" {{ old('payment_method') === 'pay_at_pickup_cash' ? 'selected' : '' }}>Cash at pickup</option>
                                        </select>
                                        <div class="help">Only online transfer or cash is available at the moment. Payment remains pending until settlement.</div>
                                        <div class="bank-details" id="bankTransferDetails">
                                            <p class="bank-details-title">Online Transfer Payment Details</p>
                                            <div class="bank-details-grid">
                                                <div class="bank-details-row">
                                                    <span>Account Number</span>
                                                    <strong>1234567890</strong>
                                                </div>
                                                <div class="bank-details-row">
                                                    <span>Account Name</span>
                                                    <strong>R&A Auto Rentals</strong>
                                                </div>
                                                <div class="bank-details-row">
                                                    <span>Bank</span>
                                                    <strong>Commercial Bank</strong>
                                                </div>
                                                <div class="bank-details-row">
                                                    <span>Branch</span>
                                                    <strong>Galle Branch</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field full">
                                        <label>Special note</label>
                                        <textarea name="note" placeholder="Any request for pickup time, child seat, driver, etc.">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="card">
                        <div class="card-header">Price breakdown</div>
                        <div class="card-body">
                            <div class="summary-row"><span>Daily rent</span><strong>LKR {{ number_format($dailyRate, 2) }}</strong></div>
                            <div class="summary-row"><span>Rental days</span><strong>{{ $rentalDays }}</strong></div>
                            <div class="summary-row total"><span>Total</span><span>LKR {{ number_format($totalAmount, 2) }}</span></div>
                            <div class="note">
                                Included: 150 km/day. Extra usage is charged at Rs 25 per km.
                            </div>
                            <div class="actions">
                                <a class="btn btn-light" href="{{ route('fleet.index', $filters) }}">Edit selection</a>
                                <button class="btn btn-primary js-loading-submit" type="submit" data-loading-text="Confirming...">
                                    <span class="btn-spinner" aria-hidden="true"></span>
                                    <span class="btn-label">Confirm booking</span>
                                </button>
                            </div>
                        </div>
                    </aside>
                </section>
            </form>
        </div>
    </main>
    <script>
        (function () {
            const paymentMethodEl = document.getElementById('paymentMethod');
            const bankDetailsEl = document.getElementById('bankTransferDetails');
            const bookingForm = document.querySelector('form[action="{{ route('booking.store') }}"]');
            const bookingSubmitBtn = bookingForm ? bookingForm.querySelector('.js-loading-submit') : null;

            if (!paymentMethodEl || !bankDetailsEl) {
                return;
            }

            const syncBankDetails = () => {
                bankDetailsEl.style.display = paymentMethodEl.value === 'pay_later_bank' ? 'block' : 'none';
            };

            paymentMethodEl.addEventListener('change', syncBankDetails);
            syncBankDetails();

            if (bookingForm && bookingSubmitBtn) {
                bookingForm.addEventListener('submit', () => {
                    const label = bookingSubmitBtn.querySelector('.btn-label');
                    if (label) {
                        label.dataset.originalText = label.textContent;
                        label.textContent = bookingSubmitBtn.dataset.loadingText || 'Confirming...';
                    }
                    bookingSubmitBtn.classList.add('is-loading');
                    bookingSubmitBtn.disabled = true;
                });
            }
        })();
    </script>
</body>
</html>
