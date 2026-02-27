<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Terms of Service | R&A Auto Rentals',
        'description' => 'Review R&A Auto Rentals terms for bookings, rental usage, payments, mileage limits, cancellations, and customer responsibilities.',
        'keywords' => [
            'rental terms',
            'car rental conditions',
            'booking terms',
            'mileage policy',
            'cancellation policy',
            'rental agreement terms',
        ],
    ])
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

        footer {
            margin-top: 1.4rem;
            border-top: 1px solid #d8e5f5;
            background: linear-gradient(135deg, #184f9f 0%, #1f66c2 100%);
        }

        .footer-inner {
            padding: 1.35rem 0 1rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
            gap: 1rem;
            padding-bottom: .9rem;
            border-bottom: 1px solid rgba(219, 232, 255, .28);
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
        }

        .footer-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            border-radius: .6rem;
            border: 1px solid #dbe6f3;
            background: #f8fbff;
            padding: 4px;
            flex-shrink: 0;
        }

        .footer-brand-name {
            margin: 0 0 .3rem;
            font-weight: 800;
            color: #f8fbff;
            font-size: .96rem;
            letter-spacing: -.01em;
        }

        .footer-copy {
            margin: 0;
            color: #d9e8ff;
            font-size: .82rem;
            line-height: 1.55;
        }

        .footer-title {
            margin: 0 0 .55rem;
            color: #bfdbff;
            font-weight: 800;
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: .4rem;
        }

        .footer-links a {
            color: #e7f0ff;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 600;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        .newsletter-note {
            margin: 0 0 .45rem;
            color: #d9e8ff;
            font-size: .8rem;
        }

        .newsletter-form {
            display: flex;
            align-items: center;
            gap: .4rem;
        }

        .newsletter-form input {
            flex: 1;
            min-width: 0;
            border: 1px solid #ffffff;
            background: #ffffff;
            border-radius: .5rem;
            padding: .45rem .6rem;
            color: #0f172a;
            font-size: .84rem;
        }

        .newsletter-form button {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: .5rem;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-2), var(--primary));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(10, 63, 143, 0.2);
        }

        .footer-bottom {
            padding-top: .8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            color: #d9e8ff;
            font-size: .78rem;
            flex-wrap: wrap;
        }

        .footer-social {
            display: inline-flex;
            gap: .8rem;
        }

        .footer-social a {
            text-decoration: none;
            color: #d9e8ff;
            font-size: .74rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .footer-social a:hover {
            color: #ffffff;
        }

        @media (max-width: 920px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .container {
                width: calc(100% - 1rem);
            }

            .topbar-inner {
                min-height: 62px;
                gap: .45rem;
            }

            .brand {
                font-size: 1rem;
                gap: .5rem;
            }

            .brand img {
                width: 44px;
                height: 28px;
            }

            .btn {
                font-size: .9rem;
                padding: .52rem .76rem;
                border-radius: 9px;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }
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
                <h1>Terms of Service</h1>
                <p>Please review these terms before booking. They explain rental usage, payments, mileage, and customer responsibilities.</p>
            </section>

            <section class="card">
                <h2>1. Booking and Eligibility</h2>
                <ul>
                    <li>Bookings can be requested daily or monthly through our website.</li>
                    <li>A valid identity document and contact details are required.</li>
                    <li>We reserve the right to confirm or decline requests based on availability and verification.</li>
                </ul>
            </section>

            <section class="card">
                <h2>2. Rental Duration and Usage</h2>
                <ul>
                    <li>Vehicles may be rented with driver or without driver depending on package.</li>
                    <li>Daily package includes up to 150 km per day.</li>
                    <li>Extra mileage is charged at <strong>Rs. 25 per km</strong>.</li>
                </ul>
            </section>

            <section class="card">
                <h2>3. Payments and Charges</h2>
                <ul>
                    <li>Rental charges, deposit (if any), and due dates are shared during booking confirmation.</li>
                    <li>Late payments may delay service continuation or future bookings.</li>
                    <li>Traffic fines, damages, and violation penalties are customer responsibility.</li>
                </ul>
            </section>

            <section class="card">
                <h2>4. Cancellations and Changes</h2>
                <ul>
                    <li>Date/location change requests are subject to vehicle availability.</li>
                    <li>Cancellation requests should be sent as early as possible for better support.</li>
                    <li>Special packages or confirmed long rentals may have custom cancellation conditions.</li>
                </ul>
            </section>

            <section class="card">
                <h2>5. Support and Contact</h2>
                <p>For booking support, changes, or issues, please contact our team through the website contact form or direct support channels.</p>
                <p class="note">Last updated: {{ now()->format('Y-m-d') }}</p>
            </section>
        </div>
    </main>
    <footer>
        <div class="container footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <img class="footer-logo" src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals">
                        <div>
                            <p class="footer-brand-name">R&A Auto Rentals</p>
                            <p class="footer-copy">Reliable daily and monthly rentals with trusted support for business, family, and long-distance travel.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="footer-title">Quick Links</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('fleet.index') }}">Fleet</a></li>
                        <li><a href="{{ route('blogs') }}">Blogs</a></li>
                        <li><a href="{{ route('home') }}#contact-section">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Customer Care</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}#contact-section">Support Center</a></li>
                        <li><a href="{{ route('terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Newsletter</p>
                    <p class="newsletter-note">Get exclusive rental updates to your inbox.</p>
                    <form class="newsletter-form" action="#" method="post">
                        <input type="email" placeholder="Email">
                        <button type="button" aria-label="Subscribe">&#10148;</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div>&copy; {{ now()->year }} R&A Auto Rentals. All rights reserved.</div>
                <div class="footer-social">
                    <a href="#">Twitter</a>
                    <a href="#">Instagram</a>
                    <a href="#">Facebook</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
