<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'Medical Transport | R&A Auto Rentals',
        'description' => 'Professional patient drop-off and non-emergency medical transport with safe, compassionate, and punctual service.',
        'keywords' => ['medical transport', 'patient drop off', 'dialysis transport', 'wheelchair transfer', 'non emergency transport'],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root { --bg:#f4f7fb; --surface:#fff; --surface-soft:#f8fbff; --line:#dbe6f3; --text:#0f172a; --muted:#64748b; --primary:#0a3f8f; --primary-2:#0f66c3; --primary-soft:#eaf2ff; --dark:#081734; --radius:18px; --shadow:0 18px 44px rgba(10,63,143,.12); }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--text); font-family:"Plus Jakarta Sans","Segoe UI",Tahoma,sans-serif; background:radial-gradient(68rem 30rem at 100% -20%, rgba(15,102,195,.14), transparent 70%), var(--bg); }
        .container { width:min(1180px, calc(100% - 2rem)); margin:0 auto; }
        .topbar { position:sticky; top:0; z-index:20; backdrop-filter:blur(10px); background:rgba(255,255,255,.94); border-bottom:1px solid var(--line); }
        .topbar-inner { min-height:74px; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .brand { display:inline-flex; align-items:center; gap:.72rem; text-decoration:none; color:inherit; }
        .brand img { width:48px; height:48px; object-fit:contain; border-radius:12px; border:1px solid var(--line); background:#f8fbff; padding:5px; }
        .brand-name { font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:1.18rem; font-weight:700; letter-spacing:-.02em; color:#0b1f3a; }
        .nav { display:inline-flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
        .nav a { text-decoration:none; color:#475569; font-weight:600; font-size:.92rem; padding:.55rem .8rem; border-radius:10px; }
        .nav a:hover { background:var(--surface-soft); color:var(--text); }
        .nav .active-link { color:var(--primary-2); background:var(--primary-soft); }
        .nav .cta { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 22px rgba(10,63,143,.22); }
        main { padding:1.4rem 0 2.8rem; }
        .hero { display:grid; grid-template-columns:1.05fr .95fr; gap:1.2rem; align-items:center; padding:1.8rem 0 1rem; }
        .eyebrow { display:inline-flex; padding:.38rem .72rem; border-radius:999px; background:var(--primary-soft); border:1px solid #c8dcfb; color:var(--primary-2); font-size:.74rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        h1 { margin:.8rem 0 .9rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(2.15rem, 5vw, 4rem); line-height:.94; letter-spacing:-.04em; max-width:9ch; }
        h1 .accent { color:var(--primary-2); }
        .hero-copy p { color:var(--muted); line-height:1.75; max-width:56ch; margin:0 0 1rem; }
        .hero-actions { display:flex; gap:.75rem; flex-wrap:wrap; }
        .btn { display:inline-flex; align-items:center; justify-content:center; text-decoration:none; border-radius:12px; padding:.85rem 1.2rem; font-weight:800; border:1px solid transparent; }
        .btn-primary { color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 12px 24px rgba(10,63,143,.24); }
        .btn-light { color:var(--text); background:#fff; border-color:#dbe6f3; }
        .quick-card, .feature-card, .fleet-card, .testimonial-card, .faq-item, .banner-card { background:var(--surface); border:1px solid var(--line); box-shadow:var(--shadow); border-radius:18px; }
        .quick-card { padding:1.2rem; }
        .quick-card h2 { margin:0 0 1rem; font-size:1.35rem; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:.8rem; }
        .form-field label { display:block; margin-bottom:.35rem; font-size:.72rem; color:#64748b; text-transform:uppercase; letter-spacing:.07em; font-weight:800; }
        .form-field input, .form-field select { width:100%; border:1px solid #c8d7ea; background:#f8fbff; border-radius:10px; padding:.72rem .8rem; font:inherit; color:#0f172a; }
        .form-field.full { grid-column:1 / -1; }
        .submit-btn { margin-top:.9rem; width:100%; border:0; border-radius:10px; padding:.85rem .95rem; font:inherit; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); cursor:pointer; }
        .section { padding:2.8rem 0 0; }
        .section h2 { margin:0 0 .35rem; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; font-size:clamp(1.8rem, 3vw, 2.4rem); letter-spacing:-.03em; text-align:center; }
        .section-sub { margin:0 0 1.5rem; color:var(--muted); text-align:center; }
        .features-grid, .fleet-grid, .testimonials-grid, .footer-grid { display:grid; gap:1rem; }
        .features-grid, .fleet-grid, .testimonials-grid { grid-template-columns:repeat(3, minmax(0,1fr)); }
        .feature-card { padding:1.2rem; text-align:center; }
        .feature-icon { width:56px; height:56px; margin:0 auto; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; background:var(--primary-soft); color:var(--primary); font-weight:800; font-size:1.2rem; }
        .feature-card h3 { margin:1rem 0 .5rem; font-size:1.04rem; }
        .feature-card p { margin:0; color:var(--muted); line-height:1.7; }
        .banner-card { overflow:hidden; position:relative; height:240px; }
        .banner-card img { width:100%; height:100%; object-fit:cover; display:block; }
        .banner-overlay { position:absolute; inset:0; background:linear-gradient(90deg, rgba(10,63,143,.82), rgba(10,63,143,.22)); color:#fff; padding:1.2rem 1.5rem; display:flex; flex-direction:column; justify-content:center; max-width:52%; }
        .banner-overlay h3 { margin:0 0 .45rem; font-size:1.55rem; line-height:1.02; font-family:"Space Grotesk","Segoe UI",Tahoma,sans-serif; }
        .banner-overlay p { margin:0 0 .7rem; line-height:1.6; color:rgba(255,255,255,.92); }
        .banner-link { color:#fff; text-decoration:none; font-weight:800; }
        .fleet-card { overflow:hidden; }
        .fleet-photo { height:220px; background:#eef4fb; }
        .fleet-photo img { width:100%; height:100%; object-fit:cover; }
        .fleet-body { padding:1rem; }
        .fleet-title-row { display:flex; justify-content:space-between; gap:.8rem; align-items:flex-start; margin-bottom:.45rem; }
        .fleet-title { margin:0; font-size:1.06rem; font-weight:800; }
        .fleet-rate { color:var(--primary); font-weight:800; font-size:1.5rem; line-height:1; }
        .fleet-rate small { display:block; color:#64748b; font-size:.8rem; font-weight:700; margin-top:.15rem; text-align:right; }
        .fleet-sub { margin:0 0 .75rem; color:var(--muted); font-size:.92rem; }
        .fleet-btn { display:block; width:100%; text-align:center; text-decoration:none; border-radius:10px; padding:.85rem 1rem; font-weight:800; color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-2)); box-shadow:0 10px 20px rgba(10,63,143,.22); }
        .testimonial-card { padding:1.1rem; }
        .stars { color:#facc15; letter-spacing:.08em; font-size:1rem; font-weight:800; }
        .testimonial-card blockquote { margin:.8rem 0 1rem; color:#475569; line-height:1.7; font-style:italic; }
        .testimonial-person { display:flex; align-items:center; gap:.7rem; }
        .testimonial-avatar { width:42px; height:42px; border-radius:999px; background:#e5e7eb; }
        .testimonial-name { font-weight:800; }
        .testimonial-role { color:var(--muted); font-size:.84rem; }
        .faq-wrap { max-width:860px; margin:0 auto; display:grid; gap:.8rem; }
        .faq-item { padding:0; overflow:hidden; }
        .faq-item summary { list-style:none; cursor:pointer; padding:1rem 1.1rem; font-weight:700; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .faq-item summary::-webkit-details-marker { display:none; }
        .faq-item summary::after { content:'+'; color:var(--primary); font-size:1.2rem; font-weight:800; line-height:1; flex-shrink:0; }
        .faq-item[open] summary::after { content:'-'; }
        .faq-item p { margin:0; padding:0 1.1rem 1rem; color:var(--muted); line-height:1.7; }
        footer { margin-top:2.6rem; background:#081734; color:#d9e8ff; }
        .footer-inner { padding:1.5rem 0 1rem; }
        .footer-grid { grid-template-columns:1.4fr 1fr 1fr 1fr; padding-bottom:1rem; border-bottom:1px solid rgba(219,232,255,.18); }
        .footer-brand { display:flex; align-items:flex-start; gap:.6rem; }
        .footer-logo { width:36px; height:24px; object-fit:contain; margin-top:.15rem; }
        .footer-title { margin:0 0 .55rem; font-size:.76rem; letter-spacing:.05em; text-transform:uppercase; color:#bfdbff; font-weight:800; }
        .footer-brand-name { margin:0 0 .35rem; font-size:.97rem; font-weight:700; color:#f8fbff; }
        .footer-copy { margin:0; color:#d9e8ff; font-size:.83rem; line-height:1.6; max-width:28ch; }
        .footer-links { list-style:none; margin:0; padding:0; display:grid; gap:.42rem; }
        .footer-links a { color:#e7f0ff; text-decoration:none; font-size:.85rem; }
        .footer-bottom { padding-top:.9rem; text-align:center; font-size:.82rem; color:#bcd0ef; }
        @media (max-width:980px) { .hero,.features-grid,.fleet-grid,.testimonials-grid,.footer-grid,.form-grid { grid-template-columns:1fr; } .banner-card { height:180px; } .banner-overlay { max-width:none; } }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="R&A Medical logo">
                <span class="brand-name">R&A Medical</span>
            </a>
            <nav class="nav">
                <a href="#services">Services</a>
                <a href="#safety">Safety</a>
                <a href="#testimonials">Testimonials</a>
                <a href="#faq">FAQ</a>
                <a class="cta" href="#booking">Book Now</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="hero">
                <div class="hero-copy">
                    <span class="eyebrow">Reliable Patient Transport</span>
                    <h1>Professional Patient <span class="accent">Drop-off</span> Services</h1>
                    <p>Safe, compassionate, and reliable medical transport for your loved ones. We ensure every journey is comfortable, dignified, and strictly on time.</p>
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="#booking">Schedule a Ride</a>
                        <a class="btn btn-light" href="#fleet">View Fleet</a>
                    </div>
                </div>
                <div id="booking" class="quick-card">
                    <h2>Quick Booking</h2>
                    <form method="post" action="{{ route('support-requests.store') }}">
                        @csrf
                        <div class="form-grid">
                            <div class="form-field">
                                <label for="pickup_location">Pickup Location</label>
                                <input id="pickup_location" type="text" name="name" placeholder="Hospital or Home Address" required>
                            </div>
                            <div class="form-field">
                                <label for="destination">Destination</label>
                                <input id="destination" type="text" name="phone" placeholder="Medical Facility">
                            </div>
                            <div class="form-field">
                                <label for="transport_type">Transport Type</label>
                                <select id="transport_type" name="email">
                                    @foreach($transportTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label for="date_time">Date & Time</label>
                                <input id="date_time" type="text" name="message" placeholder="mm/dd/yyyy, --:-- --" required>
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Calculate Estimate</button>
                    </form>
                </div>
            </section>

            <section id="services" class="section">
                <div class="features-grid">
                    <article class="feature-card">
                        <div class="feature-icon">M</div>
                        <h3>Trained Professionals</h3>
                        <p>Our drivers are certified in CPR and first aid, ensuring a safe environment throughout the journey.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">C</div>
                        <h3>Specialized Care</h3>
                        <p>Specially equipped vehicles for wheelchairs and stretchers, maintaining comfort and stability.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">T</div>
                        <h3>On-Time Guarantee</h3>
                        <p>We prioritize punctuality for medical appointments, ensuring you never miss a vital consultation.</p>
                    </article>
                </div>
            </section>

            <section id="safety" class="section">
                <div class="banner-card">
                    <img src="{{ asset('images/medical-page.png') }}" alt="Medical transport comfort">
                    <div class="banner-overlay">
                        <h3>Dedicated to Your Comfort</h3>
                        <p>Our fleet features state-of-the-art climate control, hydraulic lifts, and spacious seating to ensure every patient feels at home.</p>
                        <a class="banner-link" href="#fleet">Explore our fleet</a>
                    </div>
                </div>
            </section>

            <section id="testimonials" class="section">
                <h2>What Our Clients Say</h2>
                <p class="section-sub">Join over 1,000+ satisfied families who trust R&amp;A Medical Transport.</p>
                <div class="testimonials-grid">
                    <article class="testimonial-card">
                        <div class="stars">★★★★★</div>
                        <blockquote>"The drivers are incredibly respectful and patient with my elderly mother. They treat her like family every single time."</blockquote>
                        <div class="testimonial-person">
                            <div class="testimonial-avatar"></div>
                            <div><div class="testimonial-name">Sarah Jenkins</div><div class="testimonial-role">Daughter of Patient</div></div>
                        </div>
                    </article>
                    <article class="testimonial-card">
                        <div class="stars">★★★★★</div>
                        <blockquote>"Extremely reliable for post-surgery pickups. The stretcher service was seamless and very comfortable."</blockquote>
                        <div class="testimonial-person">
                            <div class="testimonial-avatar" style="background:#f1d0a2;"></div>
                            <div><div class="testimonial-name">Robert Miller</div><div class="testimonial-role">Dialysis Patient</div></div>
                        </div>
                    </article>
                    <article class="testimonial-card">
                        <div class="stars">★★★★★</div>
                        <blockquote>"I never have to worry about the time. They are always 5 minutes early and help me right to the door of my clinic."</blockquote>
                        <div class="testimonial-person">
                            <div class="testimonial-avatar" style="background:#d7b48a;"></div>
                            <div><div class="testimonial-name">Ellen Thompson</div><div class="testimonial-role">Recurring Client</div></div>
                        </div>
                    </article>
                </div>
            </section>

            <section id="fleet" class="section">
                <h2>Support Fleet</h2>
                <p class="section-sub">Comfort-focused vehicles available for patient and caregiver transport.</p>
                <div class="fleet-grid">
                    @foreach($featuredCars as $car)
                        <article class="fleet-card">
                            <div class="fleet-photo"><img src="{{ $car['image'] }}" alt="{{ $car['name'] }}"></div>
                            <div class="fleet-body">
                                <div class="fleet-title-row">
                                    <h3 class="fleet-title">{{ $car['name'] }}</h3>
                                    <div class="fleet-rate">Rs {{ number_format($car['daily_rate'], 0) }}<small>/ day</small></div>
                                </div>
                                <p class="fleet-sub">{{ $car['transmission'] }} &bull; {{ $car['fuel_type'] }}</p>
                                <a class="fleet-btn" href="{{ route('booking.confirm', ['car' => $car['id']]) }}">Select Vehicle</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="faq" class="section">
                <h2>Common Questions</h2>
                <div class="faq-wrap">
                    @foreach($faqItems as $item)
                        <details class="faq-item">
                            <summary>{{ $item['question'] }}</summary>
                            <p>{{ $item['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        </div>
    </main>
    <footer>
        <div class="container footer-inner">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">
                        <img class="footer-logo" src="{{ asset('images/logo.png') }}" alt="R&A Medical">
                        <div>
                            <p class="footer-brand-name">R&A Medical</p>
                            <p class="footer-copy">Providing high-quality, non-emergency medical transportation with a focus on safety, empathy, and punctuality.</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="footer-title">Quick Links</p>
                    <ul class="footer-links">
                        <li><a href="#services">Our Services</a></li>
                        <li><a href="#safety">Safety Standards</a></li>
                        <li><a href="#faq">Privacy Policy</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Services</p>
                    <ul class="footer-links">
                        <li><a href="#booking">Hospital Discharge</a></li>
                        <li><a href="#booking">Dialysis Transport</a></li>
                        <li><a href="#booking">Wheelchair Service</a></li>
                        <li><a href="#booking">Inter-facility Transfer</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Contact Us</p>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">123 Health Ave, Suite 400</a></li>
                        <li><a href="tel:+94777173264">1-800-CARE-NOW</a></li>
                        <li><a href="mailto:info@rnaautorentals.lk">info@ramedical.com</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">&copy; {{ now()->year }} R&amp;A Medical Transport. All rights reserved.</div>
        </div>
    </footer>
</body>
</html>
