<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-meta', [
        'title' => 'R&A Auto Rentals | Premium Fleet',
        'description' => 'Book daily and monthly rental vehicles in Sri Lanka. Check car availability, compare fleet options, and confirm your trip with R&A Auto Rentals.',
        'keywords' => [
            'premium car rental',
            'fleet booking',
            'daily vehicle rental',
            'monthly vehicle rental',
            'Sri Lanka rent a car',
            'Galle rent a car',
            'airport pickup rentals',
        ],
    ])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe6f3;
            --primary: #0a3f8f;
            --primary-2: #0f66c3;
            --primary-soft: #eaf2ff;
            --accent: #f1f6ff;
            --shadow: 0 18px 46px rgba(10, 63, 143, 0.12);
            --radius: 16px;
            --radius-sm: 12px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--text);
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            background: radial-gradient(68rem 30rem at 100% -20%, rgba(15, 102, 195, 0.14), transparent 70%), var(--bg);
        }

        html {
            scroll-behavior: smooth;
        }

        .container {
            width: min(1180px, calc(100% - 2rem));
            margin: 0 auto;
        }

        .home-hero {
            padding: 3rem 0 1rem;
        }

        .hero-plain {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 780px;
            margin: 0 auto;
        }

        .hero-plain h1 {
            margin: .9rem 0 .9rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            line-height: 1.08;
            letter-spacing: -.02em;
            color: var(--text);
        }

        .hero-plain h1 .accent {
            display: block;
            color: var(--primary-2);
            font-size: 3.08rem;
        }

        .hero-plain > p {
            margin: 0 0 1.6rem;
            color: var(--muted);
            font-size: 1.08rem;
            max-width: 56ch;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .75rem;
            border-radius: 999px;
            background: var(--primary-soft);
            border: 1px solid #c7daf4;
            color: var(--primary-2);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .03em;
        }

        .hero-search {
            background: #fff;
            border: 1px solid #d5e2f3;
            border-radius: 16px;
            padding: 1.1rem;
            width: min(1140px, 100%);
            margin: 0 auto;
            box-shadow: 0 16px 34px rgba(6, 23, 49, 0.1);
        }

        .hero-search-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .6rem;
        }

        .hero-field {
            display: flex;
            flex-direction: column;
            gap: .3rem;
            background: #f8fbff;
            border: 1px solid #dfe9f7;
            border-radius: 12px;
            padding: .6rem .7rem;
            min-width: 0;
        }

        .hero-field:focus-within {
            border-color: #7eb0ec;
            background: #fff;
        }

        .hero-field label {
            color: #607793;
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .hero-field input,
        .hero-field select {
            width: 100%;
            border: 0;
            background: transparent;
            padding: 0;
            font: inherit;
            font-weight: 700;
            color: #102948;
            min-width: 0;
        }

        .hero-field input:focus,
        .hero-field select:focus {
            outline: none;
        }

        .hero-stops {
            margin-top: .6rem;
        }

        .hero-stops-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
            margin-bottom: .4rem;
        }

        .hero-stops-head label {
            color: #607793;
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .hero-stops-head label .optional {
            text-transform: none;
            font-weight: 500;
            letter-spacing: 0;
            color: #94a3b8;
        }

        .hero-add-stop-btn {
            border: 1px solid #dfe9f7;
            background: #f8fbff;
            color: var(--primary-2);
            font-weight: 700;
            font-size: .78rem;
            padding: .32rem .7rem;
            border-radius: 8px;
            cursor: pointer;
        }

        .hero-add-stop-btn:hover {
            background: #eaf2ff;
        }

        .hero-stop-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .45rem;
        }

        .hero-stop-badge {
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background: var(--primary-soft);
            color: var(--primary-2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .74rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .hero-stop-row input {
            flex: 1;
            min-width: 0;
            border: 1px solid #dfe9f7;
            background: #f8fbff;
            border-radius: 10px;
            padding: .55rem .7rem;
            font: inherit;
            color: #102948;
        }

        .hero-stop-row input:focus {
            outline: none;
            border-color: #7eb0ec;
            background: #fff;
        }

        .hero-stop-remove {
            border: 0;
            background: #fef2f2;
            color: #b91c1c;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
        }

        .hero-search-actions {
            margin-top: .85rem;
            padding-top: .8rem;
            border-top: 1px solid #eef2f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .hero-search-note {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .4rem;
            color: #64748b;
            font-size: .8rem;
            font-weight: 600;
        }

        .hero-search-note strong {
            color: #15803d;
        }

        .hero-search-buttons {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .btn-whatsapp {
            background: #e9f9f0;
            color: #15803d;
            border-color: #bfe8cf;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .45rem;
            border: 1px solid transparent;
            text-decoration: none;
            font-weight: 700;
            padding: .78rem 1.1rem;
            border-radius: 12px;
            font-size: .95rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #fff;
            box-shadow: 0 8px 18px rgba(10, 63, 143, 0.26);
        }

        .section {
            padding: 2.6rem 0 0;
        }

        .section-anchor {
            scroll-margin-top: 92px;
        }

        .section-anchor:target {
            animation: sectionFocus 1.15s ease;
        }

        @keyframes sectionFocus {
            0% { background-color: rgba(255, 255, 255, 0); }
            35% { background-color: rgba(15, 102, 195, 0.09); }
            100% { background-color: rgba(255, 255, 255, 0); }
        }

        .section h2 {
            margin: 0 0 .45rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            letter-spacing: -.02em;
            font-size: clamp(1.5rem, 3vw, 2.05rem);
        }

        .section p.head-note {
            margin: 0;
            color: var(--muted);
            max-width: 80ch;
            line-height: 1.7;
        }

        .contact-card {
            margin-top: 1rem;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1.6rem;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 2rem;
            align-items: start;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-bottom: 1.1rem;
        }

        .contact-info-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: var(--primary-soft);
            color: var(--primary-2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .contact-info-text {
            display: flex;
            flex-direction: column;
            gap: .1rem;
        }

        .contact-info-label {
            font-size: .68rem;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .contact-info-value {
            font-weight: 700;
            color: #0f172a;
        }

        .contact-info-value a {
            color: inherit;
            text-decoration: none;
        }

        .contact-info-value a:hover {
            color: var(--primary-2);
        }

        .whatsapp-cta-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .7rem;
            text-decoration: none;
            background: #e9f9f0;
            border: 1px solid #bfe8cf;
            border-radius: 12px;
            padding: .85rem 1rem;
            margin-top: .4rem;
            transition: background .15s ease;
        }

        .whatsapp-cta-box:hover {
            background: #ddf3e6;
        }

        .whatsapp-cta-left {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .whatsapp-cta-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #16a34a;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            flex-shrink: 0;
        }

        .whatsapp-cta-title {
            font-weight: 800;
            color: #0f172a;
            font-size: .92rem;
        }

        .whatsapp-cta-sub {
            color: #64748b;
            font-size: .76rem;
        }

        .whatsapp-cta-arrow {
            color: #15803d;
            font-size: 1.1rem;
        }

        .contact-form {
            border-radius: 14px;
            border: 1px solid var(--line);
            background: var(--surface-soft);
            padding: 1.3rem;
        }

        .contact-form-title {
            margin: 0 0 .3rem;
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }

        .contact-form-sub {
            margin: 0 0 1rem;
            color: var(--muted);
            font-size: .84rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .65rem;
        }

        .contact-field label {
            display: block;
            margin-bottom: .3rem;
            font-size: .78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .contact-field label .required {
            color: #dc2626;
        }

        .contact-field input,
        .contact-field select,
        .contact-field textarea {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #ffffff;
            border-radius: 10px;
            padding: .68rem .75rem;
            font: inherit;
            color: #0f172a;
        }

        .contact-field textarea {
            min-height: 108px;
            resize: vertical;
        }

        .contact-field.full {
            grid-column: 1 / -1;
        }

        .contact-hint {
            grid-column: 1 / -1;
            margin: -.3rem 0 0;
            color: #94a3b8;
            font-size: .76rem;
        }

        .contact-submit-row {
            margin-top: .9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .contact-submit-note {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            color: #64748b;
            font-size: .78rem;
        }

        .contact-submit {
            border: 0;
            border-radius: 10px;
            padding: .7rem 1.3rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            box-shadow: 0 8px 16px rgba(10, 63, 143, 0.24);
            cursor: pointer;
            white-space: nowrap;
        }

        .trust-strip {
            margin-top: 1.5rem;
            padding: 0 .5rem;
        }

        .trust-grid {
            max-width: 780px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: .6rem;
        }

        .trust-item {
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            gap: .6rem;
            color: #0f2b52;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: .55rem .85rem;
            box-shadow: 0 4px 12px rgba(15, 23, 42, .04);
        }

        .trust-item .dot {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            border: 1px solid #cde0f7;
            background: linear-gradient(135deg, var(--primary-soft), #ffffff);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-2);
            font-size: .78rem;
            font-weight: 800;
            flex-shrink: 0;
        }

        .trust-item-text {
            display: flex;
            flex-direction: column;
            gap: .1rem;
            min-width: 0;
        }

        .trust-item-title {
            font-weight: 800;
            font-size: .92rem;
        }

        .trust-item-sub {
            color: #64748b;
            font-size: .76rem;
            font-weight: 500;
        }

        .home-services {
            background: #f5f8fd;
            padding-bottom: .6rem;
            padding-top: .35rem;
        }

        .home-services.section {
            padding-top: 2.6rem;
        }

        .service-grid-modern {
            margin-top: 1.2rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }

        .service-card-modern {
            background: #ffffff;
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            padding: 1rem;
            min-height: 172px;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .service-card-modern:hover {
            transform: translateY(-4px);
            border-color: #c3d8f2;
            box-shadow: 0 16px 30px rgba(10, 63, 143, 0.12);
        }

        .service-card-modern h3 {
            margin: .55rem 0 .4rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.05rem;
        }

        .service-card-modern p {
            margin: 0;
            color: #64748b;
            line-height: 1.55;
            font-size: .88rem;
        }

        .service-icon-modern {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #eaf2ff;
            border: 1px solid #d2e2f8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f66c3;
            font-size: 1rem;
        }

        .vehicle-type-grid {
            margin-top: 1.2rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
        }

        .vehicle-type-card {
            background: #ffffff;
            border: 1px solid #dbe6f3;
            border-radius: 14px;
            padding: 1.6rem 1.2rem;
            text-align: center;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .vehicle-type-card:hover {
            transform: translateY(-4px);
            border-color: #c3d8f2;
            box-shadow: 0 16px 30px rgba(10, 63, 143, 0.12);
        }

        .vehicle-type-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 999px;
            background: var(--primary-soft);
            font-size: 2rem;
            margin-bottom: .8rem;
        }

        .vehicle-type-card h3 {
            margin: 0 0 .4rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.15rem;
        }

        .vehicle-type-card p {
            margin: 0;
            color: #64748b;
            line-height: 1.55;
            font-size: .9rem;
        }

        .how-it-works {
            padding-bottom: .6rem;
        }

        .steps-grid {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.1rem;
        }

        .step-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #dbe6f3;
            border-radius: 14px;
            padding: 1.4rem 1.2rem 1.2rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(10, 63, 143, 0.12);
        }

        .step-card.is-featured {
            border-color: var(--primary-2);
            box-shadow: 0 12px 26px rgba(10, 63, 143, 0.14);
        }

        .step-card.is-featured .step-number {
            background: linear-gradient(135deg, var(--primary-2), #2f8ce0);
        }

        .step-number {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: .85rem;
            box-shadow: 0 8px 16px rgba(10, 63, 143, 0.22);
        }

        .step-card h3 {
            margin: 0 0 .4rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.12rem;
        }

        .step-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.58;
            font-size: .9rem;
        }

        .how-it-works-cta {
            margin-top: 1.6rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .how-it-works-cta-note {
            margin: 0;
            color: var(--muted);
            font-size: .88rem;
        }

        .how-it-works-cta-note a {
            color: var(--primary-2);
            font-weight: 700;
            text-decoration: none;
        }

        .how-it-works-cta-note a:hover {
            text-decoration: underline;
        }

        .journeys-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .journeys-head-link {
            text-decoration: none;
            color: var(--primary-2);
            font-weight: 800;
            font-size: .86rem;
            white-space: nowrap;
        }

        .journeys-head-link:hover {
            text-decoration: underline;
        }

        .journey-card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .05);
            display: flex;
            flex-direction: column;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .journey-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(10, 63, 143, 0.12);
        }

        .journey-media {
            position: relative;
            height: 176px;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            overflow: hidden;
        }

        .journey-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .35s ease;
        }

        .journey-card:hover .journey-media img {
            transform: scale(1.05);
        }

        .journey-media-badge {
            position: absolute;
            left: .7rem;
            top: .7rem;
            background: rgba(255, 255, 255, .95);
            color: var(--primary);
            font-size: .66rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: .22rem .55rem;
            border-radius: 999px;
        }

        .journey-body {
            padding: 1.1rem 1.1rem 1.2rem;
            display: flex;
            flex-direction: column;
            flex: 1;
            gap: .7rem;
        }

        .journey-title {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.1rem;
        }

        .journey-route {
            color: var(--primary-2);
            font-weight: 700;
            font-size: .8rem;
        }

        .journey-desc {
            margin: 0;
            color: #475569;
            font-size: .87rem;
            line-height: 1.55;
            flex: 1;
        }

        .journey-footer {
            margin-top: .3rem;
            padding-top: .7rem;
            border-top: 1px solid #edf3fb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .6rem;
        }

        .journey-price-note {
            display: flex;
            flex-direction: column;
            gap: .05rem;
        }

        .journey-price-label {
            font-size: .66rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .journey-price-value {
            font-size: .84rem;
            font-weight: 800;
            color: var(--primary);
        }

        .journey-cta {
            text-decoration: none;
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 800;
            font-size: .78rem;
            padding: .5rem .75rem;
            border-radius: 9px;
            white-space: nowrap;
        }

        .journey-cta:hover {
            background: #dbe9ff;
        }

        .our-fleet {
            padding-bottom: .6rem;
        }

        .carousel {
            position: relative;
            margin-top: 1.3rem;
        }

        .carousel-track {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: .2rem;
        }

        .carousel-track::-webkit-scrollbar {
            display: none;
        }

        .carousel-slide {
            scroll-snap-align: start;
            flex: 0 0 calc((100% - 2rem) / 3);
            min-width: 0;
        }

        .carousel-arrow {
            position: absolute;
            top: 88px;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid var(--line);
            box-shadow: 0 8px 20px rgba(15, 23, 42, .14);
            cursor: pointer;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary);
            transition: background .15s ease;
        }

        .carousel-arrow:hover {
            background: var(--primary-soft);
        }

        .carousel-arrow.prev {
            left: -18px;
        }

        .carousel-arrow.next {
            right: -18px;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: .4rem;
            margin-top: 1rem;
        }

        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: #cbd8ec;
            border: 0;
            padding: 0;
            cursor: pointer;
            transition: width .2s ease, background .2s ease;
        }

        .carousel-dot.active {
            background: var(--primary-2);
            width: 22px;
        }

        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        .trust-grid .reveal:nth-child(1) { transition-delay: 0ms; }
        .trust-grid .reveal:nth-child(2) { transition-delay: 90ms; }
        .trust-grid .reveal:nth-child(3) { transition-delay: 180ms; }

        .service-grid-modern .reveal:nth-child(1) { transition-delay: 0ms; }
        .service-grid-modern .reveal:nth-child(2) { transition-delay: 80ms; }
        .service-grid-modern .reveal:nth-child(3) { transition-delay: 160ms; }
        .service-grid-modern .reveal:nth-child(4) { transition-delay: 240ms; }

        .steps-grid .reveal:nth-child(1) { transition-delay: 0ms; }
        .steps-grid .reveal:nth-child(2) { transition-delay: 100ms; }
        .steps-grid .reveal:nth-child(3) { transition-delay: 200ms; }

        @media (max-width: 1024px) {
            .carousel-slide {
                flex: 0 0 calc((100% - 1rem) / 2);
            }
            .contact-card { grid-template-columns: 1fr; }
            .service-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 700px) {
            .container { width: min(1180px, calc(100% - 1.2rem)); }
            .contact-grid { grid-template-columns: 1fr; }
            .home-hero { padding-top: 1.5rem; }
            .hero-plain > p {
                font-size: 1rem;
            }
            .hero-search {
                padding: .8rem;
            }
            .hero-search-grid {
                grid-template-columns: 1fr;
            }
            .hero-search-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .hero-search-buttons {
                flex-direction: column;
            }
            .hero-search-buttons .btn {
                width: 100%;
            }
            .trust-grid {
                flex-direction: column;
                align-items: stretch;
            }
            .service-grid-modern { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .vehicle-type-grid { grid-template-columns: 1fr; }
            .carousel-slide {
                flex: 0 0 88%;
            }
            .carousel-arrow {
                width: 34px;
                height: 34px;
                font-size: 1rem;
            }
            .carousel-arrow.prev { left: 4px; }
            .carousel-arrow.next { right: 4px; }
        }
    </style>
</head>
<body>
    @include('partials.public-header')

    <main>
        @if(session('success'))
            <div class="container" style="margin-top:1rem;">
                <div style="border:1px solid #bde5cc;background:#ecfdf3;color:#166534;padding:.75rem .9rem;border-radius:12px;font-weight:600;">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="container" style="margin-top:1rem;">
                <div style="border:1px solid #fecaca;background:#fef2f2;color:#991b1b;padding:.75rem .9rem;border-radius:12px;">
                    <ul style="margin:0;padding-left:1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <section id="home-section" class="section-anchor home-hero">
            <div class="container">
                <div class="hero-plain">
                    <span class="hero-kicker">✓ Trusted Car &amp; Van Rentals in Sri Lanka</span>
                    <h1>Explore Sri Lanka <span class="accent">Your Journey, Our Responsibility.</span></h1>
                    <p>Tell us where you're going, how many are travelling, and when. We'll help you choose the right vehicle for your journey.</p>
                </div>
                <form class="hero-search" action="{{ route('rent-requests.create') }}" method="get">
                    <div class="hero-search-grid">
                        <div class="hero-field">
                            <label for="hero_pickup">📍 Pickup Point</label>
                            <input id="hero_pickup" name="start_location" type="text" placeholder="Where are you starting?">
                        </div>
                        <div class="hero-field">
                            <label for="hero_destination">🧭 Places to Visit</label>
                            <input id="hero_destination" name="destination" type="text" placeholder="Where do you want to go?">
                        </div>
                        <div class="hero-field">
                            <label for="hero_window">📅 How Many Days</label>
                            <select id="hero_window" name="window">
                                <option value="" selected disabled>Select your trip duration</option>
                                <option value="Airport transfer only">Just an Airport Transfer</option>
                                <option value="3-4 days (short trip)">3 - 4 Days (Short Trip)</option>
                                <option value="5-7 days (see more of the island)">5 - 7 Days (See More of the Island)</option>
                                <option value="8-10 days (longer trip)">8 - 10 Days (Longer Trip)</option>
                                <option value="11+ days (full island trip)">11+ Days (Full Island Trip)</option>
                            </select>
                        </div>
                    </div>
                    <div class="hero-stops">
                        <div class="hero-stops-head">
                            <label for="heroAddStopBtn">🧳 Add Stops on the Way <span class="optional">(optional)</span></label>
                            <button type="button" class="hero-add-stop-btn" id="heroAddStopBtn">+ Add Another Stop</button>
                        </div>
                        <div id="heroStopsList"></div>
                    </div>
                    <div class="hero-search-actions">
                        <div class="hero-search-note">
                            <span><strong>✓</strong> Flexible Trip Plans</span>
                            <span>&middot;</span>
                            <span><strong>✓</strong> Suitable Vehicle Options</span>
                            <span>&middot;</span>
                            <span><strong>✓</strong> Quick WhatsApp Support</span>
                        </div>
                        <div class="hero-search-buttons">
                            <a class="btn btn-whatsapp" href="https://wa.me/94775998951" target="_blank" rel="noopener noreferrer">💬 Chat on WhatsApp</a>
                            <button class="btn btn-primary" type="submit">🧭 Plan Your Trip</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="trust-strip" aria-label="Key advantages">
            <div class="trust-grid">
                <div class="trust-item reveal">
                    <span class="dot">🕐</span>
                    <span class="trust-item-text">
                        <span class="trust-item-title">24/7 Support</span>
                        <span class="trust-item-sub">We are here to help anytime</span>
                    </span>
                </div>
                <div class="trust-item reveal">
                    <span class="dot">Rs</span>
                    <span class="trust-item-text">
                        <span class="trust-item-title">No Hidden Fees</span>
                        <span class="trust-item-sub">Clear prices, no surprises</span>
                    </span>
                </div>
                <div class="trust-item reveal">
                    <span class="dot">✓</span>
                    <span class="trust-item-text">
                        <span class="trust-item-title">Free Cancellation</span>
                        <span class="trust-item-sub">Change your plans with no cost</span>
                    </span>
                </div>
            </div>
        </section>

        <section id="payments-section" class="section section-anchor home-services">
            <div class="container">
                <h2>Our Services</h2>
                <p class="head-note">Simple vehicle solutions for your trips, family journeys, airport transfers and special occasions across Sri Lanka.</p>
                <div class="service-grid-modern">
                    <article class="service-card-modern reveal">
                        <span class="service-icon-modern">🚗</span>
                        <h3>Trip Rentals</h3>
                        <p>Choose a vehicle that fits your trip, group size and budget.</p>
                    </article>
                    <article class="service-card-modern reveal">
                        <span class="service-icon-modern">✈</span>
                        <h3>Airport Transfers</h3>
                        <p>Convenient airport pickup and drop-off with a comfortable ride.</p>
                    </article>
                    <article class="service-card-modern reveal">
                        <span class="service-icon-modern">👨‍👩‍👧</span>
                        <h3>Family &amp; Group Travel</h3>
                        <p>Comfortable vehicle options for family trips and group journeys.</p>
                    </article>
                    <article class="service-card-modern reveal">
                        <span class="service-icon-modern">★</span>
                        <h3>Special Events</h3>
                        <p>Vehicle options for weddings, events and special occasions.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="routes-section" class="section section-anchor">
            <div class="container">
                <div class="journeys-head">
                    <div>
                        <p class="head-note" style="margin-bottom:.2rem;font-weight:800;color:var(--primary-2);text-transform:uppercase;font-size:.76rem;letter-spacing:.06em;">Trip Ideas</p>
                        <h2>Popular Sri Lanka Trips</h2>
                        <p class="head-note">Planning a trip? Choose a route or customise it your way.</p>
                    </div>
                    <a class="journeys-head-link" href="{{ route('rent-requests.create') }}">Plan Your Own Trip &rarr;</a>
                </div>
                <div class="carousel" id="journeysCarousel">
                <div class="carousel-track">
                    <article class="journey-card carousel-slide">
                        <div class="journey-media">
                            <img src="{{ asset('images/journeys/southern-coast.jpg') }}" alt="Aerial view of Sri Lanka's southern coast road along the beach">
                            <span class="journey-media-badge">South Coast</span>
                        </div>
                        <div class="journey-body">
                            <h3 class="journey-title">South Coast Escape</h3>
                            <div class="journey-route">Galle &rarr; Mirissa &rarr; Yala</div>
                            <p class="journey-desc">Beaches, wildlife and beautiful coastal roads.</p>
                            <div class="journey-footer">
                                <div class="journey-price-note">
                                    <span class="journey-price-label">Price</span>
                                    <span class="journey-price-value">Ask Us for a Price</span>
                                </div>
                                <a class="journey-cta" href="{{ route('rent-requests.create', ['start_location' => 'Galle', 'destination' => 'Yala', 'note' => 'South Coast Escape route: Galle, Mirissa, Yala']) }}">Plan This Trip</a>
                            </div>
                        </div>
                    </article>
                    <article class="journey-card carousel-slide">
                        <div class="journey-media">
                            <img src="{{ asset('images/journeys/tea-hills.jpg') }}" alt="Tea plantation hills in Nuwara Eliya, Sri Lanka">
                            <span class="journey-media-badge">Hill Country</span>
                        </div>
                        <div class="journey-body">
                            <h3 class="journey-title">Hill Country Journey</h3>
                            <div class="journey-route">Galle &rarr; Kandy &rarr; Nuwara Eliya &rarr; Ella</div>
                            <p class="journey-desc">Mountains, waterfalls, tea estates and scenic roads.</p>
                            <div class="journey-footer">
                                <div class="journey-price-note">
                                    <span class="journey-price-label">Price</span>
                                    <span class="journey-price-value">Ask Us for a Price</span>
                                </div>
                                <a class="journey-cta" href="{{ route('rent-requests.create', ['start_location' => 'Galle', 'destination' => 'Ella', 'note' => 'Hill Country Journey route: Galle, Kandy, Nuwara Eliya, Ella']) }}">Plan This Trip</a>
                            </div>
                        </div>
                    </article>
                    <article class="journey-card carousel-slide">
                        <div class="journey-media">
                            <img src="{{ asset('images/journeys/cultural-triangle.jpg') }}" alt="Golden Buddha statue at Dambulla Cave Temple, Sri Lanka">
                            <span class="journey-media-badge">Cultural Triangle</span>
                        </div>
                        <div class="journey-body">
                            <h3 class="journey-title">Cultural Triangle Discovery</h3>
                            <div class="journey-route">Galle &rarr; Dambulla &rarr; Sigiriya &rarr; Anuradhapura</div>
                            <p class="journey-desc">Ancient cities, cave temples and iconic rock fortresses steeped in history.</p>
                            <div class="journey-footer">
                                <div class="journey-price-note">
                                    <span class="journey-price-label">Price</span>
                                    <span class="journey-price-value">Ask Us for a Price</span>
                                </div>
                                <a class="journey-cta" href="{{ route('rent-requests.create', ['start_location' => 'Galle', 'destination' => 'Anuradhapura', 'note' => 'Cultural Triangle Discovery route: Galle, Dambulla, Sigiriya, Anuradhapura']) }}">Plan This Trip</a>
                            </div>
                        </div>
                    </article>
                    <article class="journey-card carousel-slide">
                        <div class="journey-media">
                            <img src="{{ asset('images/journeys/airport-transfer.jpg') }}" alt="Aerial view of an expressway interchange">
                            <span class="journey-media-badge">Direct &middot; Fast</span>
                        </div>
                        <div class="journey-body">
                            <h3 class="journey-title">Airport Transfer</h3>
                            <div class="journey-route">Galle &rarr; Colombo &rarr; Bandaranaike International Airport</div>
                            <p class="journey-desc">Easy pickup and drop-off with a comfortable ride.</p>
                            <div class="journey-footer">
                                <div class="journey-price-note">
                                    <span class="journey-price-label">Price</span>
                                    <span class="journey-price-value">Ask Us for a Price</span>
                                </div>
                                <a class="journey-cta" href="{{ route('rent-requests.create', ['start_location' => 'Galle', 'destination' => 'Bandaranaike International Airport', 'note' => 'Airport Transfer route: Galle, Colombo, Bandaranaike International Airport']) }}">Book Transfer</a>
                            </div>
                        </div>
                    </article>
                </div>
                <button type="button" class="carousel-arrow prev" aria-label="Previous journey">&lsaquo;</button>
                <button type="button" class="carousel-arrow next" aria-label="Next journey">&rsaquo;</button>
                <div class="carousel-dots"></div>
                </div>
            </div>
        </section>

        <section id="how-it-works-section" class="section section-anchor how-it-works">
            <div class="container">
                <p class="head-note" style="margin-bottom:.2rem;font-weight:800;color:var(--primary-2);text-transform:uppercase;font-size:.76rem;letter-spacing:.06em;">Simple Steps</p>
                <h2>How It Works</h2>
                <p class="head-note">Tell us about your trip. We will find the right vehicle and driver for you &mdash; no guesswork.</p>
                <div class="steps-grid">
                    <article class="step-card reveal">
                        <span class="step-number">1</span>
                        <h3>Tell Us Your Trip</h3>
                        <p>Share your pickup point, destinations, dates and number of passengers.</p>
                    </article>
                    <article class="step-card is-featured reveal">
                        <span class="step-number">2</span>
                        <h3>We Find the Right Vehicle</h3>
                        <p>We'll recommend a vehicle that suits your trip and group.</p>
                    </article>
                    <article class="step-card reveal">
                        <span class="step-number">3</span>
                        <h3>Confirm &amp; Go</h3>
                        <p>Confirm your booking and get ready for your journey.</p>
                    </article>
                </div>
                <div class="how-it-works-cta">
                    <a class="btn btn-primary" href="{{ route('rent-requests.create') }}">Plan My Trip</a>
                    <p class="how-it-works-cta-note">Want to look around first? <a href="{{ route('fleet.index') }}">See our vehicles</a>.</p>
                </div>
            </div>
        </section>

        <section id="fleet-section" class="section section-anchor our-fleet">
            <div class="container">
                <p class="head-note" style="margin-bottom:.2rem;font-weight:800;color:var(--primary-2);text-transform:uppercase;font-size:.76rem;letter-spacing:.06em;">Well Maintained</p>
                <h2>Find the Right Vehicle for Your Trip</h2>
                <p class="head-note">From small groups to family trips, we'll help you choose a vehicle that fits your journey.</p>
                <div class="vehicle-type-grid">
                    <article class="vehicle-type-card reveal">
                        <span class="vehicle-type-icon">🚗</span>
                        <h3>Cars</h3>
                        <p>Perfect for couples and small groups.</p>
                    </article>
                    <article class="vehicle-type-card reveal">
                        <span class="vehicle-type-icon">🚐</span>
                        <h3>Vans</h3>
                        <p>Comfortable for families and medium-sized groups.</p>
                    </article>
                    <article class="vehicle-type-card reveal">
                        <span class="vehicle-type-icon">🚌</span>
                        <h3>Large Vans</h3>
                        <p>More space for bigger groups and longer journeys.</p>
                    </article>
                </div>
                <div class="how-it-works-cta">
                    <a class="btn btn-primary" href="{{ route('fleet.index') }}">View Vehicles</a>
                </div>
            </div>
        </section>

        <section id="contact-section" class="section section-anchor">
            <div class="container">
                <div class="contact-card reveal">
                    <div class="contact-lines">
                        <p class="head-note" style="margin-bottom:.2rem;font-weight:800;color:var(--primary-2);text-transform:uppercase;font-size:.76rem;letter-spacing:.06em;">Contact Us</p>
                        <h2 style="margin-top:0;">Planning a Trip? Let's Talk.</h2>
                        <p class="head-note" style="margin-bottom:1.4rem;">Tell us your destination, travel dates and number of passengers. We'll help you find a suitable vehicle and plan your journey.</p>
                        <div class="contact-info-item">
                            <span class="contact-info-icon">📍</span>
                            <span class="contact-info-text">
                                <span class="contact-info-label">Main Office</span>
                                <span class="contact-info-value">Galle, Sri Lanka</span>
                            </span>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon">📞</span>
                            <span class="contact-info-text">
                                <span class="contact-info-label">Hotline / WhatsApp</span>
                                <span class="contact-info-value"><a href="tel:+94775998951">077 599 8951</a></span>
                            </span>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon">✉️</span>
                            <span class="contact-info-text">
                                <span class="contact-info-label">Email</span>
                                <span class="contact-info-value"><a href="mailto:info@rnaautorentals.com.lk">info@rnaautorentals.com.lk</a></span>
                            </span>
                        </div>
                        <div class="contact-info-item">
                            <span class="contact-info-icon">🕐</span>
                            <span class="contact-info-text">
                                <span class="contact-info-label">Working Hours</span>
                                <span class="contact-info-value">Monday - Sunday, 7.00 AM - 9.00 PM</span>
                            </span>
                        </div>
                        <a class="whatsapp-cta-box" href="https://wa.me/94775998951" target="_blank" rel="noopener noreferrer">
                            <span class="whatsapp-cta-left">
                                <span class="whatsapp-cta-icon">💬</span>
                                <span>
                                    <span class="whatsapp-cta-title">Chat with Us on WhatsApp</span><br>
                                    <span class="whatsapp-cta-sub">We reply fast</span>
                                </span>
                            </span>
                            <span class="whatsapp-cta-arrow">&rarr;</span>
                        </a>
                    </div>
                    <div class="contact-form">
                        <h3 class="contact-form-title">Request a Trip Quote</h3>
                        <p class="contact-form-sub">Tell us about your trip and we'll get back to you with suitable vehicle options.</p>
                        <form action="{{ route('support-requests.store') }}" method="post">
                            @csrf
                            <div class="contact-grid">
                                <div class="contact-field">
                                    <label for="contactName">Full Name <span class="required">*</span></label>
                                    <input id="contactName" type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Maya Miller" required>
                                </div>
                                <div class="contact-field">
                                    <label for="contactPhone">Phone / WhatsApp <span class="required">*</span></label>
                                    <input id="contactPhone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+94 77 123 4567" required>
                                </div>
                                <div class="contact-field">
                                    <label for="contactTravelDate">Travel Date</label>
                                    <input id="contactTravelDate" type="date" name="travel_date" value="{{ old('travel_date') }}">
                                </div>
                                <div class="contact-field">
                                    <label for="contactPassengers">Number of Passengers</label>
                                    <input id="contactPassengers" type="number" min="1" name="passenger_count" value="{{ old('passenger_count') }}" placeholder="e.g. 4">
                                </div>
                                <div class="contact-field">
                                    <label for="contactPickup">Pickup Location</label>
                                    <input id="contactPickup" type="text" name="pickup_location" value="{{ old('pickup_location') }}" placeholder="e.g. Colombo Airport">
                                </div>
                                <div class="contact-field">
                                    <label for="contactDestination">Destinations / Route</label>
                                    <input id="contactDestination" type="text" name="destination" value="{{ old('destination') }}" placeholder="e.g. Galle, Mirissa, Yala">
                                </div>
                                <div class="contact-field full">
                                    <label for="contactVehicle">Preferred Vehicle</label>
                                    <select id="contactVehicle" name="vehicle_type">
                                        <option value="">Not sure &mdash; recommend one</option>
                                        <option value="Car">Car</option>
                                        <option value="Van">Van</option>
                                        <option value="Large Van">Large Van</option>
                                    </select>
                                </div>
                            </div>
                            <div class="contact-submit-row">
                                <span class="contact-submit-note">🔒 Your details are kept private and are only used to contact you about your request.</span>
                                <button type="submit" class="contact-submit">Get My Quote</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.public-footer')

    <script>
        (function () {
            const stopsList = document.getElementById('heroStopsList');
            const addStopBtn = document.getElementById('heroAddStopBtn');
            if (!stopsList || !addStopBtn) {
                return;
            }

            const renumberStops = () => {
                stopsList.querySelectorAll('.hero-stop-badge').forEach((badge, index) => {
                    badge.textContent = String(index + 1);
                });
            };

            const addStopRow = (value) => {
                const row = document.createElement('div');
                row.className = 'hero-stop-row';

                const badge = document.createElement('span');
                badge.className = 'hero-stop-badge';
                badge.textContent = '1';

                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'stops[]';
                input.placeholder = 'e.g. Kandy';
                input.value = value || '';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'hero-stop-remove';
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', () => {
                    row.remove();
                    renumberStops();
                });

                row.appendChild(badge);
                row.appendChild(input);
                row.appendChild(removeBtn);
                stopsList.appendChild(row);
                renumberStops();
            };

            addStopBtn.addEventListener('click', () => addStopRow());
        })();

        (function () {
            const revealEls = document.querySelectorAll('.reveal');
            if (!revealEls.length) {
                return;
            }

            if (!('IntersectionObserver' in window)) {
                revealEls.forEach((el) => el.classList.add('is-visible'));
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            revealEls.forEach((el) => io.observe(el));
        })();

        (function () {
            const AUTOPLAY_DELAY = 7500;
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            const initCarousel = (root) => {
                const track = root.querySelector('.carousel-track');
                const slides = track ? Array.from(track.children) : [];
                if (!track || slides.length < 2) {
                    return;
                }

                const prevBtn = root.querySelector('.carousel-arrow.prev');
                const nextBtn = root.querySelector('.carousel-arrow.next');
                const dotsWrap = root.querySelector('.carousel-dots');

                if (dotsWrap) {
                    slides.forEach((_, index) => {
                        const dot = document.createElement('button');
                        dot.type = 'button';
                        dot.className = 'carousel-dot' + (index === 0 ? ' active' : '');
                        dot.setAttribute('aria-label', 'Go to slide ' + (index + 1));
                        dot.addEventListener('click', () => {
                            slides[index].scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
                            resetAutoplay();
                        });
                        dotsWrap.appendChild(dot);
                    });
                }

                const dots = dotsWrap ? Array.from(dotsWrap.children) : [];

                const updateActiveDot = () => {
                    if (!dots.length) return;
                    const trackLeft = track.getBoundingClientRect().left;
                    let closestIndex = 0;
                    let closestDistance = Infinity;
                    slides.forEach((slide, index) => {
                        const distance = Math.abs(slide.getBoundingClientRect().left - trackLeft);
                        if (distance < closestDistance) {
                            closestDistance = distance;
                            closestIndex = index;
                        }
                    });
                    dots.forEach((dot, index) => dot.classList.toggle('active', index === closestIndex));
                };

                let scrollTimer = null;
                track.addEventListener('scroll', () => {
                    clearTimeout(scrollTimer);
                    scrollTimer = setTimeout(updateActiveDot, 100);
                });

                const scrollNext = () => {
                    const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
                    if (atEnd) {
                        track.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        track.scrollBy({ left: track.clientWidth * 0.9, behavior: 'smooth' });
                    }
                };

                prevBtn?.addEventListener('click', () => {
                    track.scrollBy({ left: -track.clientWidth * 0.9, behavior: 'smooth' });
                    resetAutoplay();
                });
                nextBtn?.addEventListener('click', () => {
                    scrollNext();
                    resetAutoplay();
                });

                let autoplayTimer = null;
                let autoplayAllowed = false;

                function startAutoplay() {
                    if (prefersReducedMotion || !autoplayAllowed || autoplayTimer) return;
                    autoplayTimer = setInterval(scrollNext, AUTOPLAY_DELAY);
                }

                function stopAutoplay() {
                    clearInterval(autoplayTimer);
                    autoplayTimer = null;
                }

                function resetAutoplay() {
                    stopAutoplay();
                    startAutoplay();
                }

                root.addEventListener('mouseenter', stopAutoplay);
                root.addEventListener('mouseleave', startAutoplay);
                root.addEventListener('touchstart', stopAutoplay, { passive: true });
                root.addEventListener('touchend', () => setTimeout(startAutoplay, AUTOPLAY_DELAY));

                if (!prefersReducedMotion && 'IntersectionObserver' in window) {
                    const visibilityObserver = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            autoplayAllowed = entry.isIntersecting;
                            if (autoplayAllowed) {
                                startAutoplay();
                            } else {
                                stopAutoplay();
                            }
                        });
                    }, { threshold: 0.4 });
                    visibilityObserver.observe(root);
                } else if (!prefersReducedMotion) {
                    autoplayAllowed = true;
                    startAutoplay();
                }

                updateActiveDot();
            };

            document.querySelectorAll('.carousel').forEach(initCarousel);
        })();
    </script>
</body>
</html>
