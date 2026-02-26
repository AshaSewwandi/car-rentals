<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy | R&A Auto Rentals</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe6f3;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --radius: 14px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: var(--bg);
        }

        .container {
            width: min(980px, calc(100% - 2rem));
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: rgba(255, 255, 255, 0.94);
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(6px);
        }

        .topbar-inner {
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: nowrap;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            text-decoration: none;
            color: inherit;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: -.02em;
            min-width: 0;
            flex: 1 1 auto;
        }

        .brand img {
            width: 52px;
            height: 32px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .brand span {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 700;
            border-radius: 10px;
            padding: .62rem 1rem;
            border: 1px solid #c9d9ef;
            color: #1e3a8a;
            background: #f8fbff;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn:hover { background: #edf4ff; }

        .page {
            padding: 1.4rem 0 2rem;
        }

        .hero {
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            color: #fff;
            border-radius: var(--radius);
            padding: 1.3rem 1.2rem;
            margin-bottom: 1rem;
        }

        .hero h1 {
            margin: 0 0 .3rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(1.6rem, 3vw, 2.1rem);
            letter-spacing: -.02em;
        }

        .hero p {
            margin: 0;
            opacity: .95;
            line-height: 1.55;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: .9rem;
        }

        .card h2 {
            margin: 0 0 .6rem;
            font-size: 1.1rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            letter-spacing: -.01em;
        }

        .card p, .card li {
            color: #334155;
            line-height: 1.6;
        }

        .card ul {
            margin: .3rem 0 0 1.15rem;
            padding: 0;
        }

        .note {
            font-size: .9rem;
            color: var(--muted);
        }

        @media (max-width: 640px) {
            .container { width: calc(100% - 1rem); }
            .topbar-inner { min-height: 62px; gap: .45rem; }
            .brand { font-size: 1rem; gap: .5rem; }
            .brand img { width: 44px; height: 28px; }
            .btn { font-size: .9rem; padding: .52rem .76rem; border-radius: 9px; }
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
            <a class="btn" href="{{ route('home') }}">Back to Home</a>
        </div>
    </header>

    <main class="page">
        <div class="container">
            <section class="hero">
                <h1>Privacy Policy</h1>
                <p>This policy explains what customer data we collect, how we use it, and how we protect your information.</p>
            </section>

            <section class="card">
                <h2>1. Information We Collect</h2>
                <ul>
                    <li>Contact details such as name, phone number, and email.</li>
                    <li>Booking details such as vehicle selection, dates, and pickup/drop-off locations.</li>
                    <li>Support messages submitted through our website forms.</li>
                </ul>
            </section>

            <section class="card">
                <h2>2. How We Use Your Information</h2>
                <ul>
                    <li>To process bookings and rent requests.</li>
                    <li>To contact you for confirmations, updates, and support.</li>
                    <li>To manage agreements, payments, and service quality.</li>
                </ul>
            </section>

            <section class="card">
                <h2>3. Data Sharing</h2>
                <ul>
                    <li>We do not sell your personal information.</li>
                    <li>Information is shared only when required for operational, legal, or safety reasons.</li>
                </ul>
            </section>

            <section class="card">
                <h2>4. Data Security</h2>
                <ul>
                    <li>We apply reasonable security controls to protect stored customer data.</li>
                    <li>Access to admin systems is limited to authorized users.</li>
                </ul>
            </section>

            <section class="card">
                <h2>5. Your Rights</h2>
                <ul>
                    <li>You can request correction of incorrect details shared with us.</li>
                    <li>You may contact us to ask questions about your stored information.</li>
                </ul>
                <p class="note">Last updated: {{ now()->format('Y-m-d') }}</p>
            </section>
        </div>
    </main>
</body>
</html>
