<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Booking Success | R&A Auto Rentals',
        'description' => 'Your rental booking has been recorded successfully. View booking details, payment status, and next steps.',
        'keywords' => [
            'booking success',
            'rental confirmation',
            'payment pending',
            'booking status',
            'trip details',
            'rental receipt',
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
            --success: #166534;
            --success-bg: #ecfdf3;
            --warning: #92400e;
            --warning-bg: #fff7ed;
            --radius: 14px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: var(--bg);
        }

        .container { width: min(900px, calc(100% - 2rem)); margin: 0 auto; }
        .page { padding: 1.25rem 0 2rem; }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1rem;
        }

        .header {
            display: flex;
            align-items: center;
            gap: .65rem;
            margin-bottom: .65rem;
        }

        .header-icon {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            flex-shrink: 0;
        }

        h1 {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.6rem;
        }

        .subtitle {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
        }

        .status {
            border-radius: 10px;
            padding: .75rem .85rem;
            font-weight: 700;
            margin: .8rem 0;
            border: 1px solid;
        }

        .status.ok {
            background: var(--success-bg);
            color: var(--success);
            border-color: #bde5cc;
        }

        .status.warn {
            background: var(--warning-bg);
            color: var(--warning);
            border-color: #fed7aa;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: .8rem;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
        }

        .panel-title {
            margin: 0;
            padding: .72rem .85rem;
            border-bottom: 1px solid var(--line);
            background: #f8fbff;
            font-size: .95rem;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .panel-body {
            padding: .15rem .85rem .2rem;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: .7rem;
            border-bottom: 1px dashed var(--line);
            padding: .56rem 0;
            color: #334155;
        }

        .row:last-child { border-bottom: 0; }
        .row strong { text-align: right; color: #0f172a; }

        .steps {
            margin: .35rem 0 .6rem;
            padding-left: 1rem;
            color: #334155;
            line-height: 1.7;
        }

        .bank-details {
            margin-top: .25rem;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
            border-radius: 10px;
            padding: .7rem .75rem;
        }

        .bank-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .45rem .8rem;
        }

        .bank-row {
            display: flex;
            justify-content: space-between;
            gap: .5rem;
            font-size: .9rem;
            border-bottom: 1px dashed #c7dbff;
            padding-bottom: .22rem;
        }

        .bank-row strong { text-align: right; }

        .actions {
            margin-top: .95rem;
            display: flex;
            gap: .55rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 700;
            border-radius: 10px;
            padding: .63rem .95rem;
            border: 1px solid transparent;
        }

        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-2)); color: #fff; }
        .btn-light { background: #f8fbff; color: #334155; border-color: #c9d9ef; }

        @media (max-width: 680px) {
            .container { width: calc(100% - 1rem); }
            .bank-grid { grid-template-columns: 1fr; }
            .row { flex-direction: column; gap: .25rem; }
            .row strong { text-align: left; }
        }
    </style>
</head>
<body>
    <main class="page">
        <div class="container">
            <section class="card">
                <div class="header">
                    <span class="header-icon">OK</span>
                    <h1>Booking Confirmed</h1>
                </div>
                <p class="subtitle">Your trip request is saved successfully. We have included all details below so you can review quickly.</p>

                <div class="status {{ $booking->payment_status === 'paid' ? 'ok' : 'warn' }}">
                    @if($booking->payment_status === 'paid')
                        Payment received. Your booking is fully confirmed.
                    @else
                        Payment is pending. Please complete payment using your selected method to finalize settlement.
                    @endif
                </div>

                <div class="grid">
                    <div class="panel">
                        <p class="panel-title">Booking Details</p>
                        <div class="panel-body">
                            <div class="row"><span>Booking ID</span><strong>#{{ $booking->id }}</strong></div>
                            <div class="row"><span>Vehicle</span><strong>{{ $booking->car?->name }} ({{ $booking->car?->plate_no }})</strong></div>
                            <div class="row"><span>Customer</span><strong>{{ $booking->customer_name }}</strong></div>
                            <div class="row"><span>Trip dates</span><strong>{{ $booking->start_date?->format('Y-m-d') }} to {{ $booking->end_date?->format('Y-m-d') }}</strong></div>
                            <div class="row"><span>Pickup location</span><strong>{{ $booking->pickup_location ?: '-' }}</strong></div>
                            <div class="row"><span>Payment method</span><strong>{{ $booking->payment_method === 'pay_later_bank' ? 'Online transfer' : 'Cash at pickup' }}</strong></div>
                            <div class="row"><span>Payment status</span><strong>{{ ucfirst($booking->payment_status) }}</strong></div>
                            <div class="row"><span>Total</span><strong>{{ $booking->currency }} {{ number_format((float)$booking->total_amount, 2) }}</strong></div>
                        </div>
                    </div>

                    <div class="panel">
                        <p class="panel-title">What Happens Next</p>
                        <div class="panel-body">
                            <ol class="steps">
                                <li>Keep your booking ID for reference.</li>
                                <li>Arrive on your pickup date with valid identification.</li>
                                <li>If payment is pending, complete it before pickup.</li>
                            </ol>

                            @if($booking->payment_method === 'pay_later_bank')
                                <div class="bank-details">
                                    <div class="bank-grid">
                                        <div class="bank-row"><span>Account Number</span><strong>1234567890</strong></div>
                                        <div class="bank-row"><span>Account Name</span><strong>R&A Auto Rentals</strong></div>
                                        <div class="bank-row"><span>Bank</span><strong>Commercial Bank</strong></div>
                                        <div class="bank-row"><span>Branch</span><strong>Galle Branch</strong></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="actions">
                    @auth
                        @if(!auth()->user()->isAdmin())
                            <a class="btn btn-primary" href="{{ route('customer.dashboard') }}">Go to My Dashboard</a>
                        @else
                            @if(auth()->user()->canAccess('dashboard'))
                                <a class="btn btn-primary" href="{{ route('dashboard') }}">Go to Dashboard</a>
                            @else
                                <a class="btn btn-primary" href="{{ route('home') }}">Go to Home</a>
                            @endif
                        @endif
                    @endauth
                    <a class="btn btn-light" href="{{ route('fleet.index') }}">Book Another Vehicle</a>
                    <a class="btn btn-light" href="{{ route('home') }}">Back Home</a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
