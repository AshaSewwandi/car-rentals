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

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.92);
            border-bottom: 1px solid var(--line);
        }

        .topbar-inner {
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: relative;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .72rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-logo-wrap {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1px;
            flex-shrink: 0;
        }

        .brand img {
            width: 88%;
            height: 88%;
            object-fit: contain;
            filter: contrast(1.14) saturate(1.14) drop-shadow(0 1px 1px rgba(15, 23, 42, 0.18));
        }

        .brand-name {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.25rem;
            line-height: .88;
            letter-spacing: -.02em;
            font-weight: 700;
            color: #0b1f3a;
        }

        .nav {
            display: flex;
            gap: .45rem;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav form {
            margin: 0;
        }

        .menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border: 1px solid #c9d9ef;
            background: #f7fbff;
            color: #2b4f7d;
            border-radius: 10px;
            font-size: 1.25rem;
            line-height: 1;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
        }

        .nav a {
            text-decoration: none;
            color: #475569;
            font-weight: 600;
            font-size: .95rem;
            padding: .55rem .8rem;
            border-radius: 10px;
        }

        .nav button {
            border: 0;
            background: transparent;
            color: #475569;
            font-weight: 600;
            font-size: .95rem;
            padding: .55rem .8rem;
            border-radius: 10px;
            cursor: pointer;
            font-family: inherit;
        }

        .nav a:hover,
        .nav button:hover { background: var(--surface-soft); color: var(--text); }

        .nav .cta {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow: 0 10px 20px rgba(10, 63, 143, 0.24);
        }

        .hero {
            padding: 2rem 0 1rem;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.06fr .94fr;
            gap: 1.3rem;
            align-items: stretch;
        }

        .hero-copy {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1.5rem 1.5rem 1.1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .kicker {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .62rem;
            border: 1px solid #c7daf4;
            border-radius: 999px;
            font-size: .72rem;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: .08em;
            color: var(--primary-2);
            background: var(--primary-soft);
        }

        h1 {
            margin: .9rem 0 .8rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(2rem, 4.8vw, 3.55rem);
            line-height: .96;
            letter-spacing: -.03em;
            max-width: 12ch;
        }

        .brand-highlight {
            color: var(--primary);
            font-weight: 700;
        }

        .hero-copy p {
            color: var(--muted);
            line-height: 1.7;
            margin: 0 0 1.25rem;
            max-width: 54ch;
        }

        .hero-highlights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .6rem;
            margin-top: .35rem;
        }

        .hero-highlight-card {
            border: 1px solid #d7e4f4;
            background: #f8fbff;
            border-radius: 12px;
            padding: .62rem .65rem;
        }

        .hero-highlight-title {
            margin: 0 0 .15rem;
            font-size: .83rem;
            font-weight: 800;
            color: #0a3f8f;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .hero-highlight-text {
            margin: 0;
            color: #475569;
            font-size: .86rem;
            line-height: 1.35;
        }

        .hero-copy-image {
            margin: .85rem -1.5rem 0;
            height: 230px;
            border-top: 1px solid #dbe6f3;
            overflow: hidden;
            background: #edf4ff;
        }

        .hero-copy-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 58%;
            display: block;
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

        .btn-secondary {
            background: var(--surface-soft);
            color: #1e293b;
            border-color: var(--line);
        }

        .hero-side-card {
            position: relative;
            border-radius: var(--radius);
            border: 1px solid #c3d8f2;
            box-shadow: var(--shadow);
            overflow: hidden;
            height: 100%;
            min-height: 100%;
        }

        .hero-side-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            filter: saturate(1.06) contrast(1.03);
        }

        .hero-side-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(10, 63, 143, 0.25) 0%, rgba(10, 63, 143, 0.06) 38%, rgba(10, 63, 143, 0.35) 100%);
            z-index: 2;
            pointer-events: none;
        }

        .hero-side-overlay {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            right: 1.5rem;
            z-index: 3;
            color: #f8fbff;
        }

        .hero-side-title {
            margin: 0 0 .55rem;
            color: #f8fbff;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(2rem, 3.4vw, 3rem);
            line-height: .95;
            letter-spacing: -.02em;
            max-width: 11ch;
        }

        .hero-side-sub {
            margin: 0 0 .95rem;
            color: rgba(248, 251, 255, 0.95);
            font-size: 1.03rem;
            line-height: 1.45;
            max-width: 24ch;
        }

        .availability-wrap {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: 1.35fr .95fr;
            gap: .9rem;
            align-items: stretch;
        }

        .availability {
            margin-top: 0;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: 0 12px 34px rgba(15, 23, 42, 0.08);
            padding: 1rem;
        }

        .availability-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 .75rem;
        }

        .availability-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .65rem;
        }

        .availability-grid .full {
            grid-column: 1 / -1;
        }

        .control label {
            display: block;
            margin-bottom: .35rem;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #64748b;
        }

        .control input {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #f8fbff;
            border-radius: 10px;
            padding: .72rem .8rem;
            font: inherit;
            color: var(--text);
        }

        .control input.input-error {
            border-color: #dc2626;
            background: #fff7f7;
        }

        .field-error {
            display: none;
            margin-top: .35rem;
            color: #b91c1c;
            font-size: .8rem;
            font-weight: 600;
        }

        .field-error.show {
            display: block;
        }

        .control .btn {
            width: 100%;
            white-space: nowrap;
        }

        .benefits-card {
            margin-top: 0;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: 0 12px 34px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .benefits-body {
            padding: 1rem;
        }

        .benefits-title {
            margin: 0 0 .8rem;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .benefit-item {
            display: grid;
            grid-template-columns: 26px 1fr;
            gap: .55rem;
            margin-bottom: .65rem;
        }

        .benefit-icon {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            background: #edf3ff;
            border: 1px solid #d2e2f8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0f66c3;
            font-size: .82rem;
            font-weight: 700;
        }

        .benefit-name {
            margin: 0;
            font-size: .9rem;
            font-weight: 700;
            color: #0f172a;
        }

        .benefit-note {
            margin: 0;
            color: #64748b;
            font-size: .75rem;
            line-height: 1.35;
        }

        .benefits-footer {
            margin-top: auto;
            padding: .85rem 1rem 1rem;
            border-top: 1px solid #e2e8f0;
            background: #f8fbff;
        }

        .support-line {
            margin: 0 0 .55rem;
            font-size: .84rem;
            font-weight: 700;
            color: #334155;
        }

        .support-sub {
            margin: 0;
            color: #64748b;
            font-size: .75rem;
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

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
            margin-top: 1rem;
        }

        .feature {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: var(--radius-sm);
            padding: 1rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .feature .icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--primary-soft);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .55rem;
            color: #0f66c3;
        }

        .feature .icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .feature h3 {
            margin: 0 0 .35rem;
            font-size: 1.05rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
        }

        .feature p { margin: 0; color: var(--muted); line-height: 1.62; font-size: .93rem; }

        .fleet-head {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .7rem;
        }

        .fleet-head-link {
            color: var(--primary-2);
            text-decoration: none;
            font-weight: 700;
            font-size: .86rem;
        }

        .fleet-grid {
            margin-top: .7rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .9rem;
        }

        .fleet-card {
            display: block;
            border-radius: var(--radius-sm);
            border: 1px solid var(--line);
            overflow: hidden;
            background: var(--surface);
            box-shadow: 0 8px 18px rgba(15, 23, 42, .05);
        }

        .fleet-photo {
            position: relative;
            min-height: 170px;
            overflow: hidden;
            background: transparent;
        }

        .fleet-photo img {
            width: 100%;
            height: 100%;
            max-height: 170px;
            object-fit: cover;
            object-position: center;
            filter: saturate(1.05) contrast(1.02);
            display: block;
        }

        .fleet-tag {
            position: absolute;
            top: .45rem;
            left: .45rem;
            background: #eaf2ff;
            border: 1px solid #c8d9f2;
            color: #0a3f8f;
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            border-radius: 999px;
            padding: .1rem .4rem;
        }

        .fleet-body { padding: .68rem .75rem .7rem; }
        .fleet-title-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: .45rem;
            margin-bottom: .2rem;
        }

        .fleet-title-row h3 {
            margin: 0;
            font-size: .98rem;
        }

        .fleet-price { color: var(--primary-2); font-weight: 800; }
        .fleet-rate-unit {
            color: #64748b;
            font-size: .74rem;
            font-weight: 600;
            margin-left: .12rem;
        }

        .fleet-sub {
            color: #64748b;
            font-size: .76rem;
            margin-bottom: .32rem;
        }

        .fleet-policy {
            color: #1e3a8a;
            font-size: .73rem;
            line-height: 1.35;
            margin-bottom: .35rem;
            font-weight: 600;
        }

        .fleet-meta {
            color: var(--muted);
            font-size: .75rem;
            margin: .28rem 0 .05rem;
            display: flex;
            flex-wrap: wrap;
            gap: .35rem;
        }

        .fleet-meta span {
            display: inline-flex;
            align-items: center;
            gap: .2rem;
            color: #64748b;
            border-right: 1px solid #dbe6f3;
            padding-right: .35rem;
        }

        .fleet-meta span:last-child {
            border-right: 0;
            padding-right: 0;
        }

        .contact-card {
            margin-top: 1rem;
            background: linear-gradient(145deg, #eef6ff, #f7fbff);
            border: 1px solid #cfdef1;
            border-radius: var(--radius);
            padding: 1rem;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: 1rem;
            align-items: center;
        }

        .contact-lines div { margin-bottom: .8rem; color: #334155; }
        .contact-lines strong { display: block; margin-bottom: .2rem; color: #0f172a; }

        .contact-form {
            border-radius: 14px;
            border: 1px solid #d5e4f5;
            background: #ffffff;
            padding: .95rem;
        }

        .contact-form-title {
            margin: 0 0 .7rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
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

        .contact-field input,
        .contact-field textarea {
            width: 100%;
            border: 1px solid #c8d7ea;
            background: #f8fbff;
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

        .contact-submit {
            margin-top: .6rem;
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            box-shadow: 0 8px 16px rgba(10, 63, 143, 0.24);
            cursor: pointer;
        }

        footer {
            margin-top: 2.6rem;
            border-top: 1px solid #215fb2;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
        }

        .footer-inner {
            padding: 1.35rem 0 1.1rem;
            color: #d9e8ff;
            font-size: .9rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
            gap: 1.2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(219, 232, 255, 0.28);
        }

        .footer-brand {
            display: flex;
            align-items: flex-start;
            gap: .6rem;
        }

        .footer-logo {
            width: 36px;
            height: 24px;
            object-fit: contain;
            margin-top: .15rem;
        }

        .footer-title {
            margin: 0 0 .45rem;
            font-size: .75rem;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #bfdbff;
            font-weight: 800;
        }

        .footer-brand-name {
            margin: 0 0 .35rem;
            font-size: .97rem;
            font-weight: 700;
            color: #f8fbff;
        }

        .footer-copy {
            margin: 0;
            color: #d9e8ff;
            font-size: .83rem;
            line-height: 1.5;
            max-width: 28ch;
        }

        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            gap: .35rem;
        }

        .footer-links a {
            color: #e7f0ff;
            text-decoration: none;
            font-size: .85rem;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        .newsletter-note {
            margin: 0 0 .55rem;
            font-size: .82rem;
            color: #d9e8ff;
        }

        .newsletter-form {
            display: flex;
            gap: .4rem;
        }

        .newsletter-form input {
            flex: 1 1 auto;
            min-width: 0;
            border: 1px solid #ffffff;
            background: #ffffff;
            border-radius: 8px;
            padding: .52rem .62rem;
            font: inherit;
            color: #0f172a;
        }

        .newsletter-form input::placeholder {
            color: #64748b;
        }

        .newsletter-btn {
            border: 0;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
        }

        .footer-bottom {
            padding-top: .8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .6rem;
            flex-wrap: wrap;
            font-size: .82rem;
        }

        .footer-social {
            display: inline-flex;
            gap: .75rem;
        }

        .footer-social a {
            color: #d9e8ff;
            text-decoration: none;
            font-size: .76rem;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; }
            .hero-side-card { height: auto; min-height: 340px; }
            .availability-wrap { grid-template-columns: 1fr; }
            .availability-grid { grid-template-columns: 1fr 1fr; }
            .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fleet-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .contact-card { grid-template-columns: 1fr; }
        }

        @media (max-width: 700px) {
            .container { width: min(1180px, calc(100% - 1.2rem)); }
            .topbar-inner { min-height: 66px; }
            .brand-name { font-size: 1.6rem; }
            .availability-grid { grid-template-columns: 1fr; }
            .feature-grid { grid-template-columns: 1fr; }
            .fleet-grid { grid-template-columns: 1fr; }
            .hero-copy { padding: 1rem; }
            .hero-copy-image {
                margin-left: -1rem;
                margin-right: -1rem;
                height: 190px;
            }
            .hero-highlights { grid-template-columns: 1fr; }
            .hero-side-card { min-height: 280px; }
            .hero-side-overlay {
                top: 1rem;
                left: 1rem;
                right: 1rem;
            }
            .hero-side-sub {
                font-size: .95rem;
                max-width: 30ch;
            }
            .contact-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
            .nav { gap: .2rem; }
            .nav a { padding: .48rem .62rem; }
        }

        @media (max-width: 900px) {
            .menu-toggle {
                display: inline-flex;
            }

            .nav {
                display: none;
                position: absolute;
                top: calc(100% + .5rem);
                left: 0;
                right: 0;
                background: #ffffff;
                border: 1px solid var(--line);
                border-radius: 12px;
                padding: .5rem;
                box-shadow: 0 14px 28px rgba(10, 63, 143, 0.14);
                z-index: 30;
                gap: .3rem;
            }

            .nav a {
                width: 100%;
                text-align: left;
                padding: .62rem .7rem;
            }

            .nav button {
                width: 100%;
                text-align: left;
                padding: .62rem .7rem;
            }

            .nav .cta {
                text-align: center;
            }

            body.nav-open .nav {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-logo-wrap">
                    <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals logo">
                </span>
                <span class="brand-name">R&A Auto Rentals</span>
            </a>
            <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" id="menuToggle">&#9776;</button>
            <nav class="nav">
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a class="cta" href="{{ route('customer.dashboard') }}">My Dashboard</a>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Log out</button>
                        </form>
                    @else
                        @if(auth()->user()->canAccess('dashboard'))
                            <a class="cta" href="{{ route('dashboard') }}">Dashboard</a>
                        @endif
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Log out</button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a class="cta" href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>

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

        <section id="home-section" class="hero section-anchor">
            <div class="container">
                <div class="hero-grid">
                    <div class="hero-copy">
                        <span class="kicker">Premium Rental Service</span>
                        <h1>Drive your dreams with <span class="brand-highlight">R&A Auto Rentals.</span></h1>
                        <p>
                            <span class="brand-highlight">R&A Auto Rentals</span> offers reliable and affordable vehicles for any trip, available on a <strong>daily</strong> or <strong>monthly</strong> basis to match your travel needs.
                        </p>
                        <div class="hero-highlights">
                            <article class="hero-highlight-card">
                                <h3 class="hero-highlight-title">Daily Rental</h3>
                                <p class="hero-highlight-text">Short trips, airport pickups, and urgent travel plans.</p>
                            </article>
                            <article class="hero-highlight-card">
                                <h3 class="hero-highlight-title">Monthly Rental</h3>
                                <p class="hero-highlight-text">Long-term stays, business usage, and better monthly rates.</p>
                            </article>
                            <article class="hero-highlight-card">
                                <h3 class="hero-highlight-title">Any Trip Type</h3>
                                <p class="hero-highlight-text">Family tours, work travel, and weekend getaways.</p>
                            </article>
                        </div>
                    </div>

                    <div class="hero-side-card">
                        <img src="{{ asset('images/hero.png') }}" alt="R&A rental hero visual" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='2rem';">
                        <!-- <div class="hero-side-overlay">
                            <h2 class="hero-side-title">Drive your dreams with <span class="brand-highlight" style="color:#dbeafe;">R&A Auto Rentals</span></h2>
                            <p class="hero-side-sub">Reliable vehicles for any journey, available for daily or monthly rental.</p>
                            <a class="btn btn-primary" href="#fleet-section">Book Your Ride</a>
                        </div> -->
                    </div>
                </div>

                <div class="availability-wrap">
                    <div class="availability">
                        <h2 class="availability-title">Pickup & Dates</h2>
                        <form class="availability-grid" id="availabilityForm" action="{{ route('fleet.index') }}" method="get" novalidate>
                            <div class="control full">
                                <label for="start_location">Pickup Location</label>
                                <input id="start_location" name="start_location" type="text" placeholder="City, Airport, or Address" required aria-describedby="start_location_error">
                                <small class="field-error" id="start_location_error"></small>
                            </div>
                            <div class="control">
                                <label for="start_date">Start Date</label>
                                <input id="start_date" name="start_date" type="date" required aria-describedby="start_date_error">
                                <small class="field-error" id="start_date_error"></small>
                            </div>
                            <div class="control">
                                <label for="end_date">End Date</label>
                                <input id="end_date" name="end_date" type="date" required aria-describedby="end_date_error">
                                <small class="field-error" id="end_date_error"></small>
                            </div>
                            <div class="control full">
                                <button class="btn btn-primary" type="submit">Find Available Cars</button>
                            </div>
                        </form>
                    </div>

                    <aside class="benefits-card">
                        <div class="benefits-body">
                            <h3 class="benefits-title">Why book with R&A?</h3>
                            <div class="benefit-item">
                                <span class="benefit-icon">✓</span>
                                <div>
                                    <p class="benefit-name">Free Cancellation</p>
                                    <p class="benefit-note">Up to 48 hours before pickup time.</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <span class="benefit-icon">₨</span>
                                <div>
                                    <p class="benefit-name">No Hidden Fees</p>
                                    <p class="benefit-note">Transparent daily and monthly prices.</p>
                                </div>
                            </div>
                            <div class="benefit-item" style="margin-bottom:0;">
                                <span class="benefit-icon">24</span>
                                <div>
                                    <p class="benefit-name">24/7 Roadside Support</p>
                                    <p class="benefit-note">Quick assistance for every trip.</p>
                                </div>
                            </div>
                        </div>
                        <div class="benefits-footer">
                            <p class="support-line">Need help booking?</p>
                            <p class="support-sub">Call us: +94 77 717 3264</p>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section id="payments-section" class="section section-anchor">
            <div class="container">
                <h2>Why R&A Auto Rentals?</h2>
                <p class="head-note">A practical, fast, and team-friendly system to run rental operations without spreadsheet overload.</p>
                <div class="feature-grid">
                    <article class="feature">
                        <span class="icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 13l2-6h11l4 6"></path>
                                <path d="M5 13h15v4H5z"></path>
                                <circle cx="8" cy="17" r="2"></circle>
                                <circle cx="17" cy="17" r="2"></circle>
                            </svg>
                        </span>
                        <h3>Fleet Control</h3>
                        <p>Track active cars, status, and vehicle info in a clear list with quick actions.</p>
                    </article>
                    <article class="feature">
                        <span class="icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M7 3h7l5 5v13H7z"></path>
                                <path d="M14 3v6h5"></path>
                                <path d="M10 13h6"></path>
                                <path d="M10 17h6"></path>
                            </svg>
                        </span>
                        <h3>Smart Agreements</h3>
                        <p>Create and maintain rental agreements with date control and customer assignment.</p>
                    </article>
                    <article class="feature">
                        <span class="icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                <path d="M3 10h18"></path>
                                <path d="M7 15h3"></path>
                            </svg>
                        </span>
                        <h3>Payment Control</h3>
                        <p>Monitor expected vs received payments and identify upcoming dues early.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="fleet-section" class="section section-anchor">
            <div class="container">
                <h2>Explore Fleet</h2>
                <p class="head-note">Quick highlight cards for your most rented vehicles.</p>
                <div class="fleet-head">
                    <span class="small text-muted">Our most booked vehicles while you search.</span>
                    <a class="fleet-head-link" href="{{ route('fleet.index') }}">View Full Fleet</a>
                </div>
                <div class="fleet-grid">
                    <article class="fleet-card">
                        <div class="fleet-photo">
                            <span class="fleet-tag">Economy</span>
                            <img src="{{ asset('images/cak_9010.png') }}" alt="2015 Alto Red">
                        </div>
                        <div class="fleet-body">
                            <div class="fleet-title-row">
                                <h3>Alto 2015</h3>
                                <div class="fleet-price">Rs 4,000<span class="fleet-rate-unit">/day</span></div>
                            </div>
                            <div class="fleet-sub">Reliable city ride</div>
                            <div class="fleet-policy">With driver / Without driver · 150 km/day included · Rs 25 per extra km</div>
                            <div class="fleet-meta">
                                <span>Seats 4</span>
                                <span>Manual</span>
                                <span>Petrol</span>
                                <span>Black</span>
                            </div>
                        </div>
                    </article>
                    <article class="fleet-card">
                        <div class="fleet-photo">
                            <span class="fleet-tag">SUV</span>
                            <img src="{{ asset('images/58_8233.png') }}" alt="Nissan Largo Gray">
                        </div>
                        <div class="fleet-body">
                            <div class="fleet-title-row">
                                <h3>Nissan Largo</h3>
                                <div class="fleet-price">Rs 8,000<span class="fleet-rate-unit">/day</span></div>
                            </div>
                            <div class="fleet-sub">Comfortable group travel</div>
                            <div class="fleet-policy">With driver / Without driver · 150 km/day included · Rs 25 per extra km</div>
                            <div class="fleet-meta">
                                <span>Seats 10</span>
                                <span>Manual</span>
                                <span>Diesel</span>
                                <span>Gray</span>
                            </div>
                        </div>
                    </article>
                    <article class="fleet-card">
                        <div class="fleet-photo">
                            <span class="fleet-tag">Economy</span>
                            <img src="{{ asset('images/cak_8043.png') }}" alt="2015 Alto Red">
                        </div>
                        <div class="fleet-body">
                            <div class="fleet-title-row">
                                <h3>Alto 2015</h3>
                                <div class="fleet-price">Rs 4,000<span class="fleet-rate-unit">/day</span></div>
                            </div>
                            <div class="fleet-sub">Reliable city ride</div>
                            <div class="fleet-policy">With driver / Without driver · 150 km/day included · Rs 25 per extra km</div>
                            <div class="fleet-meta">
                                <span>Seats 4</span>
                                <span>Manual</span>
                                <span>Petrol</span>
                                <span>Red</span>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="contact-section" class="section section-anchor">
            <div class="container">
                <h2>Find Us</h2>
                <div class="contact-card">
                    <div class="contact-lines">
                        <div>
                            <strong>Main Office</strong>
                            Galle, Sri Lanka
                        </div>
                        <div>
                            <strong>Phone</strong>
                            +94 77 717 3264
                        </div>
                        <div>
                            <strong>Email</strong>
                            info@rnaautorentals.lk
                        </div>
                    </div>
                    <div class="contact-form">
                        <h3 class="contact-form-title">Contact Us</h3>
                        <form action="{{ route('support-requests.store') }}" method="post">
                            @csrf
                            <div class="contact-grid">
                                <div class="contact-field">
                                    <label for="contactName">Name</label>
                                    <input id="contactName" type="text" name="name" value="{{ old('name') }}" placeholder="Your name" required>
                                </div>
                                <div class="contact-field">
                                    <label for="contactPhone">Phone</label>
                                    <input id="contactPhone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+94 ...">
                                </div>
                                <div class="contact-field full">
                                    <label for="contactEmail">Email</label>
                                    <input id="contactEmail" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com">
                                </div>
                                <div class="contact-field full">
                                    <label for="contactMessage">Message</label>
                                    <textarea id="contactMessage" name="message" placeholder="Tell us your rental need (daily or monthly)." required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="contact-submit">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
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
                        <li><a href="#home-section">Home</a></li>
                        <li><a href="#fleet-section">Fleet</a></li>
                        <li><a href="{{ route('blogs') }}">Blogs</a></li>
                        <li><a href="#payments-section">Payments</a></li>
                        <li><a href="#contact-section">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Customer Care</p>
                    <ul class="footer-links">
                        <li><a href="#contact-section">Support Center</a></li>
                        <li><a href="{{ route('terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="#contact-section">FAQs</a></li>
                    </ul>
                </div>
                <div>
                    <p class="footer-title">Newsletter</p>
                    <p class="newsletter-note">Get exclusive rental updates to your inbox.</p>
                    <form class="newsletter-form" action="#" method="post">
                        <input type="email" placeholder="Email">
                        <button class="newsletter-btn" type="button" aria-label="Subscribe">&#10148;</button>
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

    <script>
        (function () {
            const toggle = document.getElementById('menuToggle');
            if (toggle) {
                toggle.addEventListener('click', function () {
                    const open = document.body.classList.toggle('nav-open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }

            const form = document.getElementById('availabilityForm');
            if (!form) {
                return;
            }

            const pickupInput = document.getElementById('start_location');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const pickupError = document.getElementById('start_location_error');
            const startDateError = document.getElementById('start_date_error');
            const endDateError = document.getElementById('end_date_error');
            let hasTriedSubmit = false;

            const showError = (input, errorEl, message) => {
                input.classList.add('input-error');
                errorEl.textContent = message;
                errorEl.classList.add('show');
            };

            const clearError = (input, errorEl) => {
                input.classList.remove('input-error');
                errorEl.textContent = '';
                errorEl.classList.remove('show');
            };

            const syncEndDateMin = () => {
                if (!startDateInput.value) {
                    endDateInput.min = '';
                    return;
                }

                endDateInput.min = startDateInput.value;
                if (endDateInput.value && endDateInput.value < startDateInput.value) {
                    endDateInput.value = '';
                }
            };

            const validateAvailabilityForm = (focusFirstInvalid = true) => {
                let isValid = true;
                let firstInvalidInput = null;

                clearError(pickupInput, pickupError);
                clearError(startDateInput, startDateError);
                clearError(endDateInput, endDateError);

                if (!pickupInput.value.trim()) {
                    showError(pickupInput, pickupError, 'Please enter pickup location.');
                    firstInvalidInput = firstInvalidInput || pickupInput;
                    isValid = false;
                }

                if (!startDateInput.value) {
                    showError(startDateInput, startDateError, 'Please select a start date.');
                    firstInvalidInput = firstInvalidInput || startDateInput;
                    isValid = false;
                }

                if (!endDateInput.value) {
                    showError(endDateInput, endDateError, 'Please select an end date.');
                    firstInvalidInput = firstInvalidInput || endDateInput;
                    isValid = false;
                }

                if (startDateInput.value && endDateInput.value && endDateInput.value < startDateInput.value) {
                    showError(endDateInput, endDateError, 'End date must be on or after start date.');
                    firstInvalidInput = firstInvalidInput || endDateInput;
                    isValid = false;
                }

                if (!isValid && firstInvalidInput && focusFirstInvalid) {
                    firstInvalidInput.focus();
                }

                return isValid;
            };

            form.addEventListener('submit', function (event) {
                hasTriedSubmit = true;
                if (!validateAvailabilityForm()) {
                    event.preventDefault();
                }
            });

            [pickupInput, startDateInput, endDateInput].forEach((input) => {
                input.addEventListener('input', function () {
                    if (input === startDateInput) {
                        syncEndDateMin();
                    }
                    if (!hasTriedSubmit) {
                        return;
                    }
                    validateAvailabilityForm(false);
                });
            });

            syncEndDateMin();
        })();
    </script>
</body>
</html>
