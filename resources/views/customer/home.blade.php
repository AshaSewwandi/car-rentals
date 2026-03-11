<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Rentals | R&A Auto Rentals</title>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-rounded-64.png') }}">
  <link rel="shortcut icon" href="{{ asset('images/logo-rounded-64.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('images/logo-rounded-64.png') }}">
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <style>
    :root {
      --ink: #0b2f57;
      --ink-2: #103b6a;
      --paper: #f5f7fb;
      --card: #ffffff;
      --line: #dbe6f3;
      --muted: #607087;
      --brand: #0f66c3;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      background: var(--paper);
      color: #0f172a;
    }
    .topbar {
      background: rgba(255, 255, 255, .92);
      color: #0f172a;
      border-bottom: 1px solid var(--line);
      padding: .9rem 0;
      backdrop-filter: blur(8px);
    }
    .container {
      width: min(1220px, calc(100% - 2rem));
      margin: 0 auto;
    }
    .topbar-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .8rem;
    }
    .brand {
      display: inline-flex;
      align-items: center;
      gap: .65rem;
      text-decoration: none;
      color: #0b1f3a;
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
      font-size: 1.65rem;
    }
    .brand img {
      width: 42px;
      height: 28px;
      object-fit: contain;
    }
    .actions {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
    }
    .btn {
      border: 1px solid transparent;
      border-radius: 10px;
      padding: .52rem .8rem;
      font-weight: 700;
      font-size: .86rem;
      text-decoration: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .btn-light {
      color: #334155;
      border-color: #c9d9ef;
      background: #f8fbff;
    }
    .btn-cta {
      color: #fff;
      background: linear-gradient(135deg, #0f66c3, #2c7cd2);
    }
    .btn-danger {
      color: #a61111;
      background: #fff;
      border-color: #f3b4b4;
    }
    .main {
      padding: 1.2rem 0 2rem;
      display: grid;
      grid-template-columns: 300px minmax(0, 1fr);
      gap: 1rem;
      align-items: start;
    }
    .card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
    }
    .side {
      padding: 1rem;
    }
    .avatar-row {
      display: flex;
      align-items: center;
      gap: .7rem;
      margin-bottom: .9rem;
    }
    .avatar-row img {
      width: 62px;
      height: 62px;
      border-radius: 999px;
      border: 1px solid #c7dbf5;
      background: #f0f6ff;
    }
    .avatar-name {
      margin: 0;
      font-size: 1.8rem;
      line-height: 1;
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      font-weight: 700;
      color: #12233c;
    }
    .side-links {
      list-style: none;
      margin: .6rem 0 0;
      padding: 0;
      display: grid;
      gap: .35rem;
    }
    .side-links a {
      text-decoration: none;
      color: #334155;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ebf1f8;
      background: #f8fbff;
      border-radius: 10px;
      padding: .58rem .66rem;
    }
    .side-links a.active {
      border-color: #c9dcf8;
      background: #edf4ff;
      color: #1e3a8a;
      font-weight: 700;
    }
    .content {
      padding: 1rem;
    }
    h1, h2 {
      margin: 0 0 .5rem;
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      color: #12233c;
    }
    .muted {
      margin: 0;
      color: var(--muted);
    }
    .orders {
      margin-top: .8rem;
      display: grid;
      gap: .7rem;
    }
    .order {
      border: 1px solid #e2ebf7;
      border-radius: 12px;
      padding: .75rem;
      background: #fbfdff;
    }
    .order-head {
      display: flex;
      justify-content: space-between;
      gap: .6rem;
      margin-bottom: .45rem;
      align-items: center;
    }
    .pill {
      border: 1px solid #d7e7fb;
      border-radius: 999px;
      padding: .18rem .55rem;
      font-size: .75rem;
      font-weight: 700;
      color: #1e3a8a;
      background: #edf4ff;
    }
    .grid {
      margin-top: 1.2rem;
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: .8rem;
    }
    .item {
      border: 1px solid var(--line);
      border-radius: 14px;
      overflow: hidden;
      background: #fff;
      text-decoration: none;
      color: inherit;
    }
    .item img {
      width: 100%;
      height: 170px;
      object-fit: cover;
      background: #eef4ff;
      display: block;
    }
    .item-body {
      padding: .62rem .72rem .72rem;
    }
    .item h3 {
      margin: 0 0 .2rem;
      font-size: 1.1rem;
    }
    .item p {
      margin: 0;
      color: #64748b;
      font-size: .9rem;
    }
    .logout-form { margin: 0; }
    @media (max-width: 1020px) {
      .main { grid-template-columns: 1fr; }
      .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
      .topbar-inner {
        flex-wrap: wrap;
      }
      .actions,
      .actions .btn,
      .actions .logout-form {
        width: 100%;
      }
      .grid { grid-template-columns: 1fr; }
      .brand { font-size: 1.2rem; }
      .avatar-name { font-size: 1.4rem; }
      .order-head {
        flex-wrap: wrap;
      }
    }
  </style>
</head>
<body>
  @php
    $avatarHash = md5(strtolower(trim((string) ($user->email ?? 'guest@example.com'))));
    $avatarUrl = 'https://www.gravatar.com/avatar/' . $avatarHash . '?d=identicon&s=120';
  @endphp

  <header class="topbar">
    <div class="container topbar-inner">
      <a class="brand" href="{{ route('home') }}">
        <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals">
        <span>R&A Auto Rentals</span>
      </a>
      <div class="actions">
        <form class="logout-form" method="post" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-cta" type="submit">Log out</button>
        </form>
      </div>
    </div>
  </header>

  <div class="container main">
    <aside class="card side">
      <div class="avatar-row">
        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}">
        <h2 class="avatar-name">{{ $user->name }}</h2>
      </div>
      <ul class="side-links">
        <li><a href="#" data-section-toggle="active" class="active">Active orders <span>&rsaquo;</span></a></li>
        <li><a href="#" data-section-toggle="completed">Completed orders <span>&rsaquo;</span></a></li>
        <li><a href="#" data-section-toggle="canceled">Canceled orders <span>&rsaquo;</span></a></li>
        <li><a href="{{ route('profile.edit') }}">Account settings <span>&rsaquo;</span></a></li>
      </ul>
    </aside>

    <section class="card content">
      <section id="active-orders-section" data-orders-section="active">
        <h1 id="active-orders">Active orders</h1>
        @if($activeTrips->isEmpty())
          <p class="muted">You have no active rental trips right now.</p>
        @else
          <div class="orders">
            @foreach($activeTrips as $trip)
              <article class="order">
                <div class="order-head">
                  <strong>#{{ $trip->id }} - {{ $trip->car?->name ?: 'Vehicle' }}</strong>
                  <span class="pill">{{ ucfirst($trip->status) }}</span>
                </div>
                <div class="muted">
                  {{ $trip->start_date?->format('Y-m-d') }} to {{ $trip->end_date?->format('Y-m-d') }}
                  | {{ $trip->car?->plate_no ?: '-' }}
                  | LKR {{ number_format((float)($trip->final_total ?? $trip->total_amount), 2) }}
                </div>
                <div style="margin-top:.6rem;display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                  <a class="btn btn-light" href="{{ route('profile.bookings.invoice-pdf', $trip) }}">Invoice</a>
                  @if(!$trip->handover_at && $trip->start_mileage === null)
                    <form style="margin:0;" method="post" action="{{ route('profile.bookings.cancel', $trip) }}" onsubmit="return confirm('Are you sure you want to cancel this trip? This action cannot be undone.');">
                      @csrf
                      <button class="btn btn-danger" type="submit">Cancel Trip</button>
                    </form>
                  @else
                    <span class="muted">Trip already started. Cancellation unavailable.</span>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </section>

      <section id="completed-orders-section" data-orders-section="completed" style="display:none;">
        <h2 id="completed-orders" style="margin-top:0;">Completed orders</h2>
        @if($completedTrips->isEmpty())
          <p class="muted">You have no completed orders yet.</p>
        @else
          <div class="orders">
            @foreach($completedTrips as $trip)
              <article class="order">
                <div class="order-head">
                  <strong>#{{ $trip->id }} - {{ $trip->car?->name ?: 'Vehicle' }}</strong>
                  <span class="pill" style="color:#166534;background:#ecfdf3;border-color:#bbf7d0;">Completed</span>
                </div>
                <div class="muted">
                  {{ $trip->start_date?->format('Y-m-d') }} to {{ $trip->end_date?->format('Y-m-d') }}
                  | {{ $trip->car?->plate_no ?: '-' }}
                  | LKR {{ number_format((float)($trip->final_total ?? $trip->total_amount), 2) }}
                </div>
                <div style="margin-top:.6rem;">
                  <a class="btn btn-light" href="{{ route('profile.bookings.invoice-pdf', $trip) }}">Invoice</a>
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </section>

      <section id="canceled-orders-section" data-orders-section="canceled" style="display:none;">
        <h2 id="canceled-orders" style="margin-top:0;">Canceled orders</h2>
        @if($canceledTrips->isEmpty())
          <p class="muted">You have no canceled orders yet.</p>
        @else
          <div class="orders">
            @foreach($canceledTrips as $trip)
              <article class="order">
                <div class="order-head">
                  <strong>#{{ $trip->id }} - {{ $trip->car?->name ?: 'Vehicle' }}</strong>
                  <span class="pill" style="color:#991b1b;background:#fef2f2;border-color:#fecaca;">Cancelled</span>
                </div>
                <div class="muted">
                  {{ $trip->start_date?->format('Y-m-d') }} to {{ $trip->end_date?->format('Y-m-d') }}
                  | {{ $trip->car?->plate_no ?: '-' }}
                  | LKR {{ number_format((float)($trip->final_total ?? $trip->total_amount), 2) }}
                </div>
                <div style="margin-top:.6rem;">
                  <a class="btn btn-light" href="{{ route('profile.bookings.invoice-pdf', $trip) }}">Invoice</a>
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </section>

      <div style="display:flex;align-items:center;justify-content:space-between;gap:.6rem;margin-top:1.1rem;">
        <h2>Start Borrowing</h2>
        <a class="btn btn-cta" href="{{ route('fleet.index') }}">Browse all</a>
      </div>
      <div class="grid">
        @foreach($recommendedCars as $car)
          <a class="item" href="{{ route('fleet.index') }}">
            <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}">
            <div class="item-body">
              <h3>{{ $car['name'] }}</h3>
              <p>{{ $car['plate_no'] }} | LKR {{ number_format($car['daily_rate'], 0) }}/day</p>
            </div>
          </a>
        @endforeach
      </div>
    </section>
  </div>
  <script>
    (function () {
      const toggleLinks = document.querySelectorAll('[data-section-toggle]');
      const sections = document.querySelectorAll('[data-orders-section]');

      const activate = (sectionKey) => {
        sections.forEach((section) => {
          section.style.display = section.dataset.ordersSection === sectionKey ? 'block' : 'none';
        });
        toggleLinks.forEach((link) => {
          link.classList.toggle('active', link.dataset.sectionToggle === sectionKey);
        });
      };

      toggleLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
          event.preventDefault();
          activate(link.dataset.sectionToggle);
        });
      });
    })();
  </script>
</body>
</html>
