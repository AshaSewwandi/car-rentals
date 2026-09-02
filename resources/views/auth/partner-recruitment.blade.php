<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @include('partials.seo-meta', [
    'title' => 'Partner Recruitment | R&A Auto Rentals',
    'description' => 'Grow your earnings by joining R&A Auto Rentals as a vehicle partner.',
    'keywords' => [
      'partner recruitment',
      'vehicle partner',
      'earn with your car',
      'rental partner registration',
    ],
    'robots' => 'noindex,follow',
  ])
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --ink: #0f2648;
      --muted: #5b6f8d;
      --line: #d7e2f1;
      --brand: #0a4fb2;
      --brand-2: #0b62cf;
      --surface: #f3f7fc;
      --card: #ffffff;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
      color: var(--ink);
      background: var(--surface);
    }
    .partner-hero {
      position: relative;
      isolation: isolate;
      min-height: 430px;
      display: grid;
      align-items: center;
      overflow: hidden;
      padding: 1.1rem 0 2.2rem;
      background:
        linear-gradient(100deg, rgba(5, 35, 79, .94) 0%, rgba(8, 58, 127, .9) 53%, rgba(5, 35, 79, .86) 100%),
        radial-gradient(circle at 82% 46%, rgba(255, 255, 255, .22) 0%, rgba(255, 255, 255, 0) 52%);
    }
    .partner-hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background:
        radial-gradient(420px 270px at 83% 45%, rgba(255,255,255,.22), transparent 72%),
        linear-gradient(180deg, rgba(9, 35, 71, 0) 0%, rgba(9, 35, 71, .38) 100%);
      z-index: -1;
    }
    .hero-wrap {
      width: min(1180px, calc(100% - 2rem));
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1.1fr .9fr;
      gap: 1.2rem;
      align-items: center;
      padding: 2.2rem 0;
    }
    .hero-title {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      color: #fff;
      font-size: clamp(2rem, 4.2vw, 3.35rem);
      line-height: 1.08;
      letter-spacing: -.02em;
      margin: 0 0 .85rem;
      max-width: 14ch;
    }
    .hero-copy {
      color: #d7e5ff;
      max-width: 44ch;
      font-size: 1rem;
      margin-bottom: 1.2rem;
    }
    .hero-cta {
      border: 0;
      border-radius: 12px;
      padding: .8rem 1.3rem;
      font-weight: 700;
      color: #fff;
      background: linear-gradient(135deg, var(--brand), var(--brand-2));
      box-shadow: 0 12px 28px rgba(10, 79, 178, .34);
    }
    .hero-side {
      justify-self: end;
      width: min(430px, 100%);
      aspect-ratio: 4/3;
      border-radius: 24px;
      border: 1px solid rgba(204, 224, 255, .34);
      overflow: hidden;
      position: relative;
      background:
        radial-gradient(70% 110% at 78% 50%, rgba(255,255,255,.28), rgba(255,255,255,0) 70%),
        linear-gradient(140deg, rgba(215,230,255,.2), rgba(171,205,255,.06));
      backdrop-filter: blur(2px);
    }
    .hero-side img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      opacity: .82;
    }
    .hero-side::after {
      content: "";
      position: absolute;
      inset: 0;
      background:
        linear-gradient(135deg, rgba(13, 54, 116, .3), rgba(13, 54, 116, .06)),
        radial-gradient(circle at 65% 28%, rgba(255,255,255,.45), transparent 55%);
    }
    .section-wrap {
      width: min(1180px, calc(100% - 2rem));
      margin: 0 auto;
    }
    .benefits {
      margin-top: 1.15rem;
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 1.1rem;
      padding-bottom: 2.2rem;
    }
    .benefit-card {
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 18px;
      padding: 1.3rem;
      box-shadow: 0 12px 24px rgba(14, 31, 63, .06);
    }
    .benefit-card h3 {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      margin: 0 0 .55rem;
      font-size: 1.45rem;
      color: #102b52;
    }
    .benefit-card p { margin: 0; color: var(--muted); line-height: 1.7; }
    .partner-band {
      margin-top: .4rem;
      background: linear-gradient(135deg, #0a4eae, #0b61c9);
      color: #fff;
      border-radius: 22px;
      padding: 2rem 1rem;
      text-align: center;
    }
    .partner-band h2 {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      margin: 0 0 .6rem;
      font-size: clamp(1.8rem, 3vw, 2.3rem);
    }
    .partner-band p { margin: 0 auto 1.05rem; max-width: 50ch; color: #dce8ff; }
    .band-btn {
      border: 1px solid rgba(220, 232, 255, .38);
      border-radius: 12px;
      background: #fff;
      color: #0a4eae;
      font-weight: 700;
      padding: .76rem 1.3rem;
    }
    .partner-modal {
      position: fixed;
      inset: 0;
      z-index: 120;
      display: none;
    }
    .partner-modal.show { display: block; }
    .partner-modal .backdrop {
      position: absolute;
      inset: 0;
      background: rgba(10, 21, 44, .58);
      backdrop-filter: blur(2px);
    }
    .partner-modal .panel {
      position: relative;
      margin: clamp(1rem, 3vh, 2.5rem) auto;
      width: min(980px, calc(100% - 1.4rem));
      max-height: calc(100vh - 2rem);
      overflow: auto;
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 18px;
      box-shadow: 0 28px 60px rgba(13, 31, 62, .24);
      padding: 1.1rem;
    }
    .modal-top {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      align-items: center;
      padding: .35rem .2rem .85rem;
      border-bottom: 1px solid #e7edf7;
      margin-bottom: .9rem;
    }
    .modal-top h3 {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      margin: 0;
      font-size: 1.35rem;
    }
    .modal-close {
      border: 1px solid #cfddf2;
      background: #f5f9ff;
      border-radius: 10px;
      width: 38px;
      height: 38px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      color: #35537d;
      cursor: pointer;
    }
    .modal-close-icon {
      display: inline-block;
      font-size: 1.45rem;
      line-height: 1;
      font-weight: 700;
      transform: translateY(-1px);
      pointer-events: none;
    }
    .group-title {
      font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
      margin: 1.1rem 0 .75rem;
      font-size: 1.24rem;
      border-bottom: 1px solid #e7edf7;
      padding-bottom: .45rem;
    }
    .form-label {
      font-size: .75rem;
      text-transform: uppercase;
      letter-spacing: .07em;
      color: #213d63;
      font-weight: 700;
      margin-bottom: .4rem;
    }
    .form-control,
    .form-select {
      background: #f8fbff;
      border-color: #cfddf2;
      border-radius: 10px;
      min-height: 46px;
    }
    .form-control.is-invalid,
    .form-select.is-invalid {
      border-color: #dc3545;
      box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .12);
    }
    .invalid-feedback {
      display: block;
      font-size: .82rem;
    }
    .seg-wrap {
      display: grid;
      gap: .55rem;
    }
    .seg-wrap-three { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .seg-wrap-two { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .seg-wrap input { display: none; }
    .seg-wrap label {
      border: 1px solid #cfddf2;
      border-radius: 10px;
      background: #fff;
      min-height: 46px;
      display: grid;
      place-items: center;
      font-weight: 600;
      color: #304a6e;
      cursor: pointer;
      text-align: center;
      padding: .25rem;
    }
    .seg-wrap input:checked + label {
      border-color: #0a4fb2;
      color: #0a4fb2;
      background: #eff5ff;
      box-shadow: inset 0 0 0 1px #0a4fb2;
    }
    .pref-block {
      border: 1px solid #e3ebf7;
      border-radius: 12px;
      padding: .85rem;
      background: #fbfdff;
      height: 100%;
    }
    .sticky-submit {
      position: sticky;
      bottom: -.6rem;
      margin: 1rem -.35rem -.35rem;
      border-top: 1px solid #e7edf7;
      background: #fff;
      padding: .8rem .35rem .3rem;
      display: flex;
      gap: .6rem;
      justify-content: flex-end;
      flex-wrap: wrap;
    }
    .submit-btn {
      border: 0;
      border-radius: 11px;
      padding: .74rem 1.15rem;
      font-weight: 700;
      color: #fff;
      background: linear-gradient(135deg, var(--brand), var(--brand-2));
    }
    .cancel-btn {
      border: 1px solid #cfddf2;
      background: #fff;
      color: #264872;
      border-radius: 11px;
      padding: .72rem 1rem;
      font-weight: 600;
    }
    @media (max-width: 992px) {
      .hero-wrap { grid-template-columns: 1fr; }
      .hero-side { justify-self: start; height: 210px; }
      .benefits { grid-template-columns: 1fr; margin-top: 1rem; }
    }
    @media (max-width: 768px) {
      .seg-wrap-three { grid-template-columns: 1fr; }
      .seg-wrap-two { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  @include('partials.public-header')

  <section class="partner-hero">
    <div class="hero-wrap">
      <div>
        <h1 class="hero-title">Turn Your Car into a Daily Income Stream with R&amp;A Partners</h1>
        <p class="hero-copy">Start earning with zero hassle. List your vehicle on R&A and get consistent bookings from real customers—while we handle the heavy lifting.</p>
        <button type="button" class="hero-cta" id="openPartnerModal">Get Started</button>
      </div>
      <div class="hero-side">
        <img src="{{ asset('images/hero.png') }}" alt="Partner vehicle showcase">
      </div>
    </div>
  </section>

  <section class="section-wrap">
    <div class="benefits">
      <article class="benefit-card">
        <h3>Guaranteed Earning Potential</h3>
        <p>Maximize your income with high-demand bookings and transparent payouts—no hidden fees.</p>
      </article>
      <article class="benefit-card">
        <h3>Total Control, Your Rules</h3>
        <p>Set your availability, pricing, and preferences. You stay in charge—always.</p>
      </article>
      <article class="benefit-card">
        <h3>We Handle the Hard Work</h3>
        <p>From customer support to booking management, our team supports you every step of the way.</p>
      </article>
    </div>

    <div class="partner-band mb-4">
      <h2>Ready to earn with your car?</h2>
      <p>Join hundreds of partners already growing with R&amp;A Auto Rentals.</p>
      <button type="button" class="band-btn" id="openPartnerModalBottom">Register Now</button>
    </div>
  </section>

  <div class="partner-modal @if($errors->any()) show @endif" id="partnerModal" aria-hidden="{{ $errors->any() ? 'false' : 'true' }}">
    <div class="backdrop" id="partnerModalBackdrop"></div>
    <div class="panel" role="dialog" aria-modal="true" aria-labelledby="partnerModalTitle">
      <div class="modal-top">
        <div>
          <h3 id="partnerModalTitle">Become a Partner</h3>
          <div class="text-muted">Your trusted partner for turning vehicles into income—simple, secure, and profitable.</div>
        </div>
        <button type="button" class="modal-close" id="closePartnerModal" aria-label="Close popup">
          <span class="modal-close-icon">&times;</span>
        </button>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="post" action="{{ route('partner-recruitment.store') }}" id="partnerRecruitmentForm" novalidate>
        @csrf

        <div class="group-title">Partner Details</div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="partner_name" class="form-control @error('partner_name') is-invalid @enderror" value="{{ old('partner_name') }}" required>
            @error('partner_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" name="partner_phone" class="form-control @error('partner_phone') is-invalid @enderror" value="{{ old('partner_phone') }}" placeholder="+94 ..." required>
            @error('partner_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Email Address</label>
            <input type="email" name="partner_email" class="form-control @error('partner_email') is-invalid @enderror" value="{{ old('partner_email') }}" required>
            @error('partner_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" required>
          </div>
        </div>

        <div class="group-title">Vehicle Details</div>
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="vehicle_name" class="form-control @error('vehicle_name') is-invalid @enderror" value="{{ old('vehicle_name') }}" placeholder="e.g. 2023 Tesla Model 3" required>
            @error('vehicle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Plate Number</label>
            <input type="text" name="plate_no" class="form-control @error('plate_no') is-invalid @enderror" value="{{ old('plate_no') }}" placeholder="ABC-1234" required>
            @error('plate_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Make</label>
            <input type="text" name="make" class="form-control" value="{{ old('make') }}" placeholder="e.g. Toyota">
          </div>
          <div class="col-md-4">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control" value="{{ old('model') }}" placeholder="e.g. Camry">
          </div>
          <div class="col-md-4">
            <label class="form-label">Year</label>
            <select name="year" class="form-select">
              <option value="">Select year</option>
              @for($y = now()->year + 1; $y >= 1990; $y--)
                <option value="{{ $y }}" @selected((string) old('year') === (string) $y)>{{ $y }}</option>
              @endfor
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Color</label>
            <input type="text" name="color" class="form-control" value="{{ old('color') }}" placeholder="e.g. Midnight Blue">
          </div>
          <div class="col-md-4">
            <label class="form-label">Fuel Type</label>
            <select name="fuel_type" class="form-select">
              <option value="">Select</option>
              <option value="Petrol" @selected(old('fuel_type') === 'Petrol')>Petrol</option>
              <option value="Diesel" @selected(old('fuel_type') === 'Diesel')>Diesel</option>
              <option value="Hybrid" @selected(old('fuel_type') === 'Hybrid')>Hybrid</option>
              <option value="Electric" @selected(old('fuel_type') === 'Electric')>Electric</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Transmission</label>
            <select name="transmission" class="form-select">
              <option value="">Select</option>
              <option value="Automatic" @selected(old('transmission') === 'Automatic')>Automatic</option>
              <option value="Manual" @selected(old('transmission') === 'Manual')>Manual</option>
            </select>
          </div>
        </div>

        <div class="group-title">Rental Preferences</div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="pref-block">
              <label class="form-label">Driver Mode</label>
              <div class="seg-wrap seg-wrap-three">
                <input type="radio" name="driver_mode" id="driver_with" value="with_driver_only" @checked(old('driver_mode') === 'with_driver_only')>
                <label for="driver_with">With Driver</label>

                <input type="radio" name="driver_mode" id="driver_without" value="without_driver_only" @checked(old('driver_mode') === 'without_driver_only')>
                <label for="driver_without">Without Driver</label>

                <input type="radio" name="driver_mode" id="driver_both" value="both" @checked(old('driver_mode', 'both') === 'both')>
                <label for="driver_both">Both Options</label>
              </div>
              <div class="invalid-feedback js-radio-error" id="driverModeError">@error('driver_mode'){{ $message }}@enderror</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="pref-block">
              <label class="form-label">Long-Term Rental</label>
              <div class="seg-wrap seg-wrap-two">
                <input type="radio" name="allow_long_term" id="allow_lt_yes" value="1" @checked((string) old('allow_long_term', '1') === '1')>
                <label for="allow_lt_yes">Yes</label>

                <input type="radio" name="allow_long_term" id="allow_lt_no" value="0" @checked((string) old('allow_long_term') === '0')>
                <label for="allow_lt_no">No</label>
              </div>
              <div class="invalid-feedback js-radio-error" id="longTermError">@error('allow_long_term'){{ $message }}@enderror</div>
            </div>
          </div>
          <div class="col-12">
            <label class="form-label">Vehicle Note (Optional)</label>
            <textarea name="vehicle_note" class="form-control" rows="3" maxlength="255" placeholder="Any additional details about your vehicle...">{{ old('vehicle_note') }}</textarea>
          </div>
        </div>

        <div class="sticky-submit">
          <button type="button" class="cancel-btn" id="cancelPartnerModal">Cancel</button>
          <button type="submit" class="submit-btn">Register</button>
        </div>
      </form>
    </div>
  </div>

  @include('partials.public-footer')

  <script>
    (function () {
      const modal = document.getElementById('partnerModal');
      const openButtons = [
        document.getElementById('openPartnerModal'),
        document.getElementById('openPartnerModalBottom'),
      ].filter(Boolean);
      const closeButtons = [
        document.getElementById('closePartnerModal'),
        document.getElementById('cancelPartnerModal'),
        document.getElementById('partnerModalBackdrop'),
      ].filter(Boolean);

      const openModal = () => {
        if (!modal) return;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
      };

      const closeModal = () => {
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      };

      const form = document.getElementById('partnerRecruitmentForm');
      if (form) {
        form.addEventListener('submit', function (event) {
          const driverModeError = document.getElementById('driverModeError');
          const longTermError = document.getElementById('longTermError');
          if (driverModeError) driverModeError.textContent = '';
          if (longTermError) longTermError.textContent = '';

          const hasDriverMode = !!form.querySelector('input[name="driver_mode"]:checked');
          const hasLongTerm = !!form.querySelector('input[name="allow_long_term"]:checked');
          const hasNativeErrors = !form.checkValidity();

          if (!hasDriverMode || !hasLongTerm || hasNativeErrors) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
            openModal();

            if (!hasDriverMode && driverModeError) {
              driverModeError.textContent = 'Please select a driver mode.';
            }
            if (!hasLongTerm && longTermError) {
              longTermError.textContent = 'Please select long-term rental option.';
            }
          }
        });
      }

      openButtons.forEach((btn) => btn.addEventListener('click', openModal));
      closeButtons.forEach((btn) => btn.addEventListener('click', closeModal));

      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal && modal.classList.contains('show')) {
          closeModal();
        }
      });
    })();
  </script>
</body>
</html>
