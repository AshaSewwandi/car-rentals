<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => $vehicle['name'] . ' | Vehicle Details | R&A Auto Rentals',
        'description' => 'View full vehicle details, pricing, mileage limits, and booking information using live database data.',
        'keywords' => ['vehicle details', 'car pricing', 'fleet details', 'rent a car sri lanka'],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f1f5fb;
            --surface: #ffffff;
            --line: #d8e4f3;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --soft: #edf4ff;
            --radius: 14px;
            --shadow: 0 14px 34px rgba(15, 35, 68, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            color: var(--text);
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            background: var(--bg);
        }

        .container {
            width: min(1220px, calc(100% - 2rem));
            margin: 0 auto;
        }

        main { padding: 1rem 0 2rem; }

        .breadcrumbs {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: #5f738d;
            font-size: .86rem;
            margin-bottom: .8rem;
        }

        .breadcrumbs a {
            color: #365984;
            text-decoration: none;
            font-weight: 600;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.35fr .65fr;
            gap: 1rem;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .vehicle-media {
            overflow: hidden;
        }

        .vehicle-badge {
            position: absolute;
            z-index: 2;
            top: .8rem;
            left: .8rem;
            display: inline-flex;
            padding: .28rem .56rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: .06em;
            font-weight: 800;
            font-size: .66rem;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
        }

        .media-wrap {
            position: relative;
            border-bottom: 1px solid var(--line);
        }

        .media-wrap img {
            width: 100%;
            height: 410px;
            object-fit: cover;
            display: block;
            background: #eef3fb;
        }

        .media-thumbs {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            padding: .7rem .8rem;
            border-top: 1px solid var(--line);
            background: #f8fbff;
        }

        .media-thumb {
            width: 74px;
            height: 52px;
            border-radius: 8px;
            border: 1px solid #c8d7ea;
            overflow: hidden;
            cursor: pointer;
            background: #fff;
            padding: 0;
        }

        .media-thumb.active {
            border-color: #0f66c3;
            box-shadow: 0 0 0 2px rgba(15, 102, 195, 0.18);
        }

        .media-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .vehicle-head {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            gap: .8rem;
            align-items: flex-start;
        }

        .vehicle-head h1 {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 2rem;
            line-height: .98;
            letter-spacing: -.02em;
        }

        .vehicle-plate {
            margin-top: .3rem;
            color: #64748b;
            font-weight: 600;
            font-size: .9rem;
        }

        .vehicle-rate {
            text-align: right;
            color: #0a4ca4;
            font-weight: 800;
            font-size: 2.1rem;
            line-height: 1;
            white-space: nowrap;
        }

        .vehicle-rate small {
            color: #64748b;
            font-weight: 700;
            font-size: .92rem;
            margin-left: .1rem;
        }

        .rate-note {
            margin-top: .22rem;
            color: #7b8da5;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .book-panel {
            overflow: hidden;
        }

        .book-head {
            padding: .9rem 1rem;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            color: #fff;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.32rem;
            font-weight: 700;
            letter-spacing: -.01em;
        }

        .book-body {
            padding: .95rem;
        }

        .field {
            margin-bottom: .62rem;
        }

        .field label {
            display: block;
            margin-bottom: .33rem;
            color: #64748b;
            font-size: .72rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            font-weight: 800;
        }

        .field input {
            width: 100%;
            border: 1px solid #c7d7ec;
            border-radius: 10px;
            background: #f8fbff;
            padding: .68rem .72rem;
            font: inherit;
            color: #0f172a;
        }

        .book-total {
            border-top: 1px solid #dbe6f3;
            margin-top: .7rem;
            padding-top: .7rem;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: .65rem;
        }

        .book-total small {
            color: #64748b;
            font-weight: 600;
        }

        .book-total strong {
            color: #0f172a;
            font-size: 1.45rem;
            font-weight: 800;
        }

        .book-btn {
            width: 100%;
            margin-top: .8rem;
            border: 0;
            border-radius: 10px;
            padding: .78rem 1rem;
            font: inherit;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            box-shadow: 0 10px 18px rgba(10, 63, 143, 0.24);
            cursor: pointer;
        }

        .book-help {
            margin-top: .7rem;
            padding: .7rem .8rem;
            border: 1px solid #d5e2f4;
            background: #f5f9ff;
            border-radius: 10px;
            color: #35547c;
            font-size: .84rem;
            font-weight: 600;
        }

        .meta-grid {
            margin-top: .9rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .7rem;
        }

        .meta-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: .8rem;
            box-shadow: var(--shadow);
        }

        .meta-title {
            color: #64748b;
            font-size: .7rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: .22rem;
        }

        .meta-value {
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
        }

        .section {
            margin-top: .9rem;
            padding: 1rem;
        }

        .section h2 {
            margin: 0 0 .65rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            letter-spacing: -.01em;
            font-size: 1.28rem;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .55rem;
        }

        .feature-item {
            border: 1px solid #dbe6f3;
            border-radius: 10px;
            background: #f8fbff;
            padding: .62rem .66rem;
            color: #294b77;
            font-weight: 600;
            font-size: .9rem;
        }

        .terms-list {
            display: grid;
            gap: .65rem;
        }

        .term-item {
            border: 1px solid #dbe6f3;
            border-radius: 10px;
            background: #f8fbff;
            padding: .7rem .75rem;
        }

        .term-item strong {
            display: block;
            margin-bottom: .2rem;
            color: #0f2f59;
        }

        .term-item span {
            color: #5f748f;
            font-size: .9rem;
        }

        .reviews {
            margin-top: .9rem;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .8rem;
        }

        .review-card {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            padding: .75rem;
            box-shadow: var(--shadow);
        }

        .review-head {
            font-weight: 700;
            color: #0f2f59;
            margin-bottom: .3rem;
        }

        .review-stars {
            color: #0f66c3;
            margin-bottom: .35rem;
            font-size: .92rem;
        }

        .review-text {
            color: #5f738d;
            font-size: .9rem;
            line-height: 1.55;
            margin: 0;
        }

        @media (max-width: 1024px) {
            .hero { grid-template-columns: 1fr; }
            .meta-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 700px) {
            .container { width: min(1220px, calc(100% - 1.2rem)); }
            .media-wrap img { height: 250px; }
            .vehicle-head {
                flex-direction: column;
            }
            .vehicle-rate {
                text-align: left;
            }
            .meta-grid,
            .feature-list,
            .reviews {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('partials.public-header')
    <main>
        <div class="container">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}">Home</a>
                <span>&rsaquo;</span>
                <a href="{{ route('fleet.index') }}">Our Fleet</a>
                <span>&rsaquo;</span>
                <span>{{ $vehicle['name'] }}</span>
            </div>

            <section class="hero">
                <article class="panel vehicle-media">
                    <div class="media-wrap">
                        <span class="vehicle-badge">{{ $vehicle['driver_mode_label'] }}</span>
                        <img id="primaryVehicleImage" src="{{ $vehicle['image'] }}" alt="{{ $vehicle['name'] }}" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='2rem';">
                    </div>
                    @if(!empty($vehicle['images']) && count($vehicle['images']) > 1)
                        <div class="media-thumbs" id="vehicleImageThumbs">
                            @foreach($vehicle['images'] as $imageUrl)
                                <button
                                    type="button"
                                    class="media-thumb {{ $loop->first ? 'active' : '' }}"
                                    data-image-url="{{ $imageUrl }}"
                                    aria-label="Show image {{ $loop->iteration }}"
                                >
                                    <img src="{{ $imageUrl }}" alt="{{ $vehicle['name'] }} thumbnail {{ $loop->iteration }}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                    <div class="vehicle-head">
                        <div>
                            <h1>{{ $vehicle['name'] }}</h1>
                            <div class="vehicle-plate">{{ $vehicle['plate_no'] }}</div>
                        </div>
                        <div>
                            <div class="vehicle-rate">Rs {{ number_format($vehicle['daily_rate'], 0) }}<small>/day</small></div>
                        </div>
                    </div>
                </article>

                <aside>
                    <article class="panel book-panel">
                        <div class="book-head">Book This Vehicle</div>
                        <div class="book-body">
                            <form action="{{ route('booking.confirm') }}" method="get">
                                <input type="hidden" name="car_id" value="{{ $vehicle['id'] }}">
                                <div class="field">
                                    <label for="start_location">Pick-up Location</label>
                                    <input id="start_location" name="start_location" type="text" placeholder="City, Airport, or Address">
                                </div>
                                <div class="field">
                                    <label for="start_date">Date</label>
                                    <input id="start_date" name="start_date" type="date">
                                </div>
                                <div class="field">
                                    <label for="end_date">Return Date</label>
                                    <input id="end_date" name="end_date" type="date">
                                </div>
                                <div class="book-total">
                                    <small>Daily base rate</small>
                                    <strong>Rs {{ number_format($vehicle['daily_rate'], 0) }}</strong>
                                </div>
                                <button type="submit" class="book-btn">Continue to Book</button>
                            </form>
                            <div class="book-help">
                                Need help booking? Call +94 77 717 3264
                            </div>
                        </div>
                    </article>
                </aside>
            </section>

            <section class="meta-grid">
                <article class="meta-card"><div class="meta-title">Seats</div><div class="meta-value">{{ $vehicle['seats'] }} Adults</div></article>
                <article class="meta-card"><div class="meta-title">Transmission</div><div class="meta-value">{{ $vehicle['transmission'] ?: 'Automatic' }}</div></article>
                <article class="meta-card"><div class="meta-title">Fuel</div><div class="meta-value">{{ $vehicle['fuel_type'] ?: 'Petrol' }}</div></article>
                <article class="meta-card"><div class="meta-title">Mileage</div><div class="meta-value">{{ number_format($vehicle['per_day_km']) }} km/day</div></article>
            </section>

            <section class="panel section">
                <h2>Features & Amenities</h2>
                <div class="feature-list">
                    <div class="feature-item">Daily package includes {{ number_format($vehicle['per_day_km']) }} km</div>
                    <div class="feature-item">Monthly package includes {{ number_format($vehicle['per_month_km']) }} km</div>
                    <div class="feature-item">Extra distance: Rs {{ number_format($vehicle['extra_km_rate'], 2) }} per km</div>
                    <div class="feature-item">{{ $vehicle['driver_mode_label'] }}</div>
                    @if($vehicle['driver_cost_per_day'] > 0)
                        <div class="feature-item">Driver cost: Rs {{ number_format($vehicle['driver_cost_per_day'], 2) }} / day</div>
                    @endif
                    <div class="feature-item">Long-term rentals: {{ $vehicle['allow_long_term'] ? 'Enabled' : 'Not enabled' }}</div>
                </div>
            </section>

            <section class="panel section">
                <h2>Rental Terms & Important Info</h2>
                <div class="terms-list">
                    <div class="term-item">
                        <strong>Insurance Policy</strong>
                        <span>Standard coverage applies. Contact support for additional coverage options.</span>
                    </div>
                    <div class="term-item">
                        <strong>Cancellation Policy</strong>
                        <span>Contact support as early as possible. Date changes depend on vehicle availability.</span>
                    </div>
                    @if(!empty($vehicle['note']))
                        <div class="term-item">
                            <strong>Vehicle Note</strong>
                            <span>{{ $vehicle['note'] }}</span>
                        </div>
                    @endif
                </div>
            </section>

            <section class="section" style="padding:0;">
                <h2 style="margin:0 0 .65rem;">Customer Reviews</h2>
                <div class="reviews">
                    <article class="review-card">
                        <div class="review-head">Customer Review</div>
                        <div class="review-stars">&#9733; &#9733; &#9733; &#9733; &#9733;</div>
                        <p class="review-text">Very comfortable vehicle and smooth booking process. Great experience with R&A Auto Rentals.</p>
                    </article>
                    <article class="review-card">
                        <div class="review-head">Customer Review</div>
                        <div class="review-stars">&#9733; &#9733; &#9733; &#9733; &#9734;</div>
                        <p class="review-text">Good condition vehicle and clear pricing. Support team was helpful throughout the booking.</p>
                    </article>
                </div>
            </section>
        </div>
    </main>
    @include('partials.public-footer')
    <script>
        (function () {
            const primaryImage = document.getElementById('primaryVehicleImage');
            const thumbs = document.querySelectorAll('#vehicleImageThumbs .media-thumb');
            if (!primaryImage || !thumbs.length) {
                return;
            }

            thumbs.forEach((thumb) => {
                thumb.addEventListener('click', () => {
                    const nextUrl = thumb.dataset.imageUrl;
                    if (!nextUrl) {
                        return;
                    }

                    primaryImage.src = nextUrl;
                    thumbs.forEach((item) => item.classList.remove('active'));
                    thumb.classList.add('active');
                });
            });
        })();
    </script>
</body>
</html>
