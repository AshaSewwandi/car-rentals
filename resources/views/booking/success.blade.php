<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Status | R&A Auto Rentals</title>
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
            --ok: #166534;
            --ok-bg: #ecfdf3;
            --warn: #92400e;
            --warn-bg: #fff7ed;
            --radius: 14px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: var(--bg);
        }
        .container { width: min(760px, calc(100% - 2rem)); margin: 0 auto; }
        .page { padding: 1.4rem 0 2rem; }
        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1rem;
        }
        h1 {
            margin: 0 0 .5rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.55rem;
        }
        .status {
            border-radius: 10px;
            padding: .68rem .8rem;
            font-weight: 700;
            margin: .6rem 0 .8rem;
        }
        .status.ok { background: var(--ok-bg); color: var(--ok); border: 1px solid #bde5cc; }
        .status.warn { background: var(--warn-bg); color: var(--warn); border: 1px solid #fed7aa; }
        .row {
            display: flex;
            justify-content: space-between;
            gap: .6rem;
            border-bottom: 1px dashed var(--line);
            padding: .5rem 0;
            color: #334155;
        }
        .row:last-child { border-bottom: 0; }
        .actions {
            margin-top: .9rem;
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex; align-items: center; justify-content: center;
            text-decoration: none; font-weight: 700;
            border-radius: 10px; padding: .6rem .95rem;
            border: 1px solid transparent;
        }
        .btn-primary { background: linear-gradient(135deg, #0a3f8f, #0f66c3); color: #fff; }
        .btn-light { background: #f8fbff; color: #334155; border-color: #c9d9ef; }
    </style>
</head>
<body>
    <main class="page">
        <div class="container">
            <section class="card">
                <h1>Booking Confirmation</h1>
                <div class="status {{ $booking->payment_status === 'paid' ? 'ok' : 'warn' }}">
                    @if($booking->payment_status === 'paid')
                        Payment successful. Your booking is confirmed.
                    @else
                        Booking recorded. Payment is pending via selected method (online transfer or cash).
                    @endif
                </div>

                <div class="row"><span>Booking ID</span><strong>#{{ $booking->id }}</strong></div>
                <div class="row"><span>Vehicle</span><strong>{{ $booking->car?->name }} ({{ $booking->car?->plate_no }})</strong></div>
                <div class="row"><span>Customer</span><strong>{{ $booking->customer_name }}</strong></div>
                <div class="row"><span>Trip dates</span><strong>{{ $booking->start_date?->format('Y-m-d') }} to {{ $booking->end_date?->format('Y-m-d') }}</strong></div>
                <div class="row"><span>Pickup Location</span><strong>{{ $booking->pickup_location ?: '-' }}</strong></div>
                <div class="row">
                    <span>Payment method</span>
                    <strong>{{ $booking->payment_method === 'pay_later_bank' ? 'Online transfer' : 'Cash at pickup' }}</strong>
                </div>
                <div class="row"><span>Payment status</span><strong>{{ ucfirst($booking->payment_status) }}</strong></div>
                <div class="row"><span>Total</span><strong>{{ $booking->currency }} {{ number_format((float)$booking->total_amount, 2) }}</strong></div>

                <div class="actions">
                    <a class="btn btn-light" href="{{ route('fleet.index') }}">Back to Fleet</a>
                    <a class="btn btn-primary" href="{{ route('home') }}">Back Home</a>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
