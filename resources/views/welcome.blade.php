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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #dbe6f3;
        }

        .topbar-inner {
            min-height: 72px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 1rem;
            position: relative;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-logo-wrap {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-name {
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.55rem;
            letter-spacing: -.02em;
            font-weight: 700;
            color: #0b2f61;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .2rem;
            flex-wrap: wrap;
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
            color: #233851;
            font-weight: 600;
            font-size: .9rem;
            padding: .45rem .62rem;
            border-radius: 8px;
            white-space: nowrap;
        }

        .nav a:hover {
            background: #edf4ff;
            color: #0d3f85;
        }

        .nav-mobile-only {
            display: none;
        }

        .nav-mobile-action {
            width: 100%;
            border: 1px solid #cbdcf0;
            background: #f7fbff;
            color: #113e7d;
            border-radius: 10px;
            padding: .58rem .7rem;
            font: inherit;
            font-weight: 700;
            text-align: left;
            cursor: pointer;
        }

        .header-right {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            min-width: 170px;
            gap: .55rem;
            position: relative;
        }

        .header-auth-link {
            text-decoration: none;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-2));
            box-shadow: 0 10px 20px rgba(10, 63, 143, 0.24);
            font-weight: 700;
            font-size: .9rem;
            border-radius: 10px;
            padding: .52rem .95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            cursor: pointer;
            font-family: inherit;
        }

        .account-toggle {
            border: 1px solid #d5e2f3;
            background: #fff;
            color: #0f2f59;
            border-radius: 999px;
            padding: .32rem .46rem .32rem .7rem;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            cursor: pointer;
            font-weight: 700;
            font-family: inherit;
        }

        .account-avatar {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            background: #e9f2ff;
            color: #0f4ea1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 800;
            border: 1px solid #cbe0fb;
        }

        .account-menu {
            position: absolute;
            top: calc(100% + .55rem);
            right: 0;
            width: 220px;
            background: #fff;
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            box-shadow: 0 18px 40px rgba(15, 35, 68, 0.14);
            overflow: hidden;
            display: none;
            z-index: 35;
        }

        .account-menu a,
        .account-menu button {
            width: 100%;
            text-align: left;
            border: 0;
            background: transparent;
            display: block;
            padding: .68rem .85rem;
            color: #243a56;
            text-decoration: none;
            font-size: .9rem;
            border-bottom: 1px solid #edf3fb;
            font-family: inherit;
            cursor: pointer;
        }

        .account-menu a:hover,
        .account-menu button:hover {
            background: #f5f9ff;
        }

        .account-menu .danger {
            color: #b91c1c;
            border-bottom: 0;
        }

        .account-open .account-menu {
            display: block;
        }

        .home-hero {
            padding: 1.3rem 0 1.6rem;
        }

        .hero-banner {
            min-height: 500px;
            border-radius: 18px;
            border: 1px solid #cddff5;
            overflow: hidden;
            position: relative;
            background: #0f294f;
            display: flex;
            align-items: flex-end;
        }

        .hero-banner img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(95deg, rgba(4, 22, 52, 0.92) 0%, rgba(7, 31, 68, 0.82) 38%, rgba(13, 58, 120, 0.25) 68%, rgba(15, 69, 138, 0.08) 100%);
        }

        .hero-overlay {
            position: relative;
            z-index: 1;
            color: #fff;
            width: min(760px, 100%);
            padding: 2.2rem 2.2rem 2.1rem;
        }

        .hero-overlay h1 {
            margin: 0 0 .8rem;
            max-width: 10ch;
            font-size: clamp(2.2rem, 5.8vw, 4.15rem);
            line-height: .94;
            color: #fff;
        }

        .hero-overlay p {
            margin: 0 0 1.1rem;
            color: #dbeafe;
            font-size: 1.18rem;
            max-width: 46ch;
        }

        .hero-search {
            background: #fff;
            border: 1px solid #d5e2f3;
            border-radius: 14px;
            padding: .36rem;
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr auto;
            gap: .28rem;
            width: min(740px, 100%);
            box-shadow: 0 12px 28px rgba(6, 23, 49, 0.2);
            overflow: hidden;
        }

        .hero-search .search-field {
            display: flex;
            flex-direction: column;
            gap: .2rem;
            min-width: 0;
            overflow: hidden;
        }

        .hero-search .search-field.date-field {
            position: relative;
        }

        .hero-search label {
            color: #607793;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin: 0 .45rem;
        }

        .hero-search input {
            width: 100%;
            border: 1px solid #cfddf0;
            border-radius: 10px;
            padding: .65rem .75rem;
            font: inherit;
            color: #102948;
            min-height: 42px;
            min-width: 0;
            max-width: 100%;
            display: block;
            box-sizing: border-box;
            background-clip: padding-box;
        }

        .hero-search input[type="date"] {
            width: 100% !important;
            min-width: 0 !important;
            max-width: 100% !important;
            overflow: hidden;
            -webkit-appearance: none;
            appearance: none;
            border-radius: 10px !important;
            -webkit-border-radius: 10px !important;
            position: relative;
            padding-right: 2.35rem;
            background-color: #f8fbff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%236b7f9a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .7rem center;
            background-size: 18px 18px;
        }

        .hero-search input[type="date"]::-webkit-datetime-edit {
            padding: 0;
            width: 100%;
        }

        .hero-search input[type="date"]::-webkit-date-and-time-value {
            text-align: left;
            width: 100%;
        }

        .hero-search input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            opacity: 0;
            cursor: pointer;
        }

        .hero-search input:focus {
            outline: 2px solid #b6d5ff;
            border-color: #7eb0ec;
        }

        .hero-search .search-submit {
            width: 100%;
            min-height: 42px;
            border: 0;
            border-radius: 10px;
            padding: .65rem 1rem;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .hero-search .field-error {
            display: block;
            height: 1.35rem;
            margin: .25rem .45rem 0;
            color: #b91c1c;
            font-size: .8rem;
            font-weight: 600;
            line-height: 1.25;
            visibility: hidden;
            overflow: hidden;
        }

        .hero-search .field-error.show {
            visibility: visible;
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

        .btn .btn-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 999px;
            animation: btn-spin .7s linear infinite;
        }

        .btn.is-loading .btn-spinner {
            display: inline-block;
        }

        .btn.is-loading .btn-label {
            opacity: .95;
        }

        .btn.is-loading {
            pointer-events: none;
        }

        @keyframes btn-spin {
            to { transform: rotate(360deg); }
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

        .trust-strip {
            margin-top: .9rem;
            padding: 0 .5rem;
        }

        .trust-grid {
            width: min(1180px, calc(100% - 1rem));
            margin: 0 auto;
            min-height: 64px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .7rem;
            align-items: center;
            padding: .45rem .85rem;
            background: #ffffff;
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(15, 35, 68, 0.06);
        }

        .trust-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            color: #0f2b52;
            font-weight: 700;
            font-size: .95rem;
        }

        .trust-item .dot {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            border: 1px solid #cde0f7;
            background: #edf5ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0d4ea8;
            font-size: .8rem;
            font-weight: 800;
        }

        .home-services {
            background: #f5f8fd;
            padding-bottom: .6rem;
            padding-top: .35rem;
        }

        .home-services.section {
            padding-top: 1.2rem;
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

        .modern-fleet {
            padding-bottom: .6rem;
        }

        .modern-fleet-head {
            margin-top: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
        }

        .modern-fleet-head h2 {
            margin: 0;
        }

        .fleet-head-link {
            margin-left: auto;
            text-decoration: none;
            color: #0a3f8f;
            font-size: .86rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .fleet-head-link:hover {
            text-decoration: underline;
        }

        .showcase-grid {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .72rem;
        }

        .showcase-card {
            background: #fff;
            border: 1px solid #d9e6f5;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 18px rgba(15, 23, 42, .07);
        }

        .showcase-photo {
            position: relative;
            height: 182px;
            background: #e9f2ff;
        }

        .showcase-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .showcase-photo-badge {
            position: absolute;
            right: .7rem;
            top: .7rem;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            background: rgba(255, 255, 255, .95);
            border: 1px solid #dbe6f3;
            border-radius: 999px;
            padding: .22rem .52rem;
            font-size: .66rem;
            color: #0a3f8f;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .showcase-body {
            padding: .7rem .75rem .78rem;
        }

        .showcase-topline {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .65rem;
            margin-bottom: .34rem;
        }

        .showcase-rate {
            color: #0a3f8f;
            font-weight: 800;
            font-size: 1.55rem;
            letter-spacing: -.02em;
            line-height: 1;
            white-space: nowrap;
        }

        .showcase-rate small {
            color: #6b7f97;
            font-size: .52em;
            font-weight: 600;
            margin-left: .2rem;
        }

        .showcase-title {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.35rem;
            line-height: 1.08;
            letter-spacing: -.01em;
        }

        .showcase-info-row {
            margin: .38rem 0 .62rem;
            padding-bottom: .48rem;
            border-bottom: 1px solid #e4edf8;
            color: #8b9bb0;
            font-size: .74rem;
            display: flex;
            flex-wrap: wrap;
            gap: .45rem .65rem;
            font-weight: 600;
        }

        .showcase-info-row span {
            display: inline-flex;
            align-items: center;
            gap: .2rem;
        }

        .showcase-spec-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .3rem .38rem;
            margin-bottom: .85rem;
            color: #334155;
            font-size: .74rem;
        }

        .showcase-spec-item {
            display: inline-flex;
            align-items: center;
            gap: .24rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .showcase-spec-item::before {
            content: "";
            width: 14px;
            height: 14px;
            flex: 0 0 14px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 14px 14px;
            opacity: .8;
        }

        .showcase-spec-item.spec-seats::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%235b728e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='12' cy='7' r='4'/%3E%3Cpath d='M5.5 21a6.5 6.5 0 0 1 13 0'/%3E%3C/svg%3E");
        }

        .showcase-spec-item.spec-bags::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%235b728e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='4' y='7' width='16' height='13' rx='2'/%3E%3Cpath d='M9 7V5a3 3 0 0 1 6 0v2'/%3E%3C/svg%3E");
        }

        .showcase-spec-item.spec-transmission::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%235b728e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='8' cy='6' r='2'/%3E%3Ccircle cx='16' cy='6' r='2'/%3E%3Ccircle cx='8' cy='12' r='2'/%3E%3Ccircle cx='16' cy='12' r='2'/%3E%3Cpath d='M8 8v2m8-2v8M8 14v4m0 0h8'/%3E%3C/svg%3E");
        }

        .showcase-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .65rem;
            align-items: center;
        }

        .showcase-details {
            color: #4c1d95;
            text-decoration: none;
            font-weight: 700;
            font-size: .9rem;
        }

        .showcase-details:hover {
            text-decoration: underline;
        }

        .showcase-book {
            text-decoration: none;
            text-align: center;
            border: 0;
            border-radius: 10px;
            padding: .62rem .8rem;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            box-shadow: 0 8px 18px rgba(10, 63, 143, .23);
            font-weight: 800;
            font-size: .95rem;
            line-height: 1;
        }

        .contact-cta {
            padding: 2.1rem 0 .3rem;
        }

        .contact-cta-card {
            border: 1px solid #dbe6f3;
            background: linear-gradient(135deg, #f6faff, #edf4ff);
            border-radius: 16px;
            padding: 1.2rem 1.15rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .9rem;
        }

        .contact-cta-card h3 {
            margin: 0 0 .25rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.28rem;
        }

        .contact-cta-card p {
            margin: 0;
            color: #5f738d;
        }

        .contact-cta-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 0;
            border-radius: 10px;
            padding: .66rem 1.1rem;
            color: #fff;
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            box-shadow: 0 10px 18px rgba(10, 63, 143, .24);
            font-weight: 700;
            white-space: nowrap;
        }

        .modern-footer {
            margin-top: 2.5rem;
            background: #081a3b;
            border-top: 1px solid #18386e;
        }

        .modern-footer .footer-inner {
            padding-top: 1.5rem;
            color: #d2e3ff;
        }

        .modern-footer .footer-grid {
            grid-template-columns: 1.2fr 1fr 1fr 1.2fr;
            border-bottom: 1px solid rgba(151, 182, 230, .2);
        }

        .modern-footer .newsletter-form input {
            background: #102956;
            border-color: #2a4f88;
            color: #e8f1ff;
        }

        .modern-footer .newsletter-btn {
            background: linear-gradient(135deg, #165ec3, #1a74dd);
        }

        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: 1fr; }
            .hero-side-card { height: auto; min-height: 340px; }
            .availability-wrap { grid-template-columns: 1fr; }
            .availability-grid { grid-template-columns: 1fr 1fr; }
            .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fleet-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .contact-card { grid-template-columns: 1fr; }
            .service-grid-modern { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .showcase-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 700px) {
            .container { width: min(1180px, calc(100% - 1.2rem)); }
            .topbar-inner {
                min-height: 64px;
                grid-template-columns: auto auto;
            }
            .brand-name { font-size: 1.2rem; }
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
            .home-hero { padding-top: 1rem; }
            .hero-banner { min-height: 440px; }
            .hero-overlay {
                padding: 1.2rem 1rem 1.2rem;
            }
            .hero-overlay p {
                font-size: 1rem;
            }
            .hero-search {
                grid-template-columns: 1fr;
                padding: .62rem;
                gap: .52rem;
            }
            .hero-search label {
                margin: 0 .12rem;
            }
            .hero-search input {
                min-height: 46px;
                padding: .72rem .78rem;
            }
            .hero-search .search-submit {
                min-height: 46px;
            }
            .hero-search .search-submit {
                width: 100%;
            }
            .trust-grid {
                grid-template-columns: 1fr;
                gap: .3rem;
                padding: .65rem .7rem;
                width: min(1180px, calc(100% - 1.2rem));
            }
            .trust-item {
                justify-content: flex-start;
            }
            .service-grid-modern { grid-template-columns: 1fr; }
            .showcase-grid { grid-template-columns: 1fr; }
            .showcase-photo { height: 178px; }
            .showcase-title { font-size: 1.2rem; }
            .showcase-rate { font-size: 1.35rem; }
            .showcase-spec-grid { font-size: .69rem; gap: .25rem .3rem; }
            .showcase-details { font-size: .86rem; }
            .showcase-book { font-size: .9rem; padding: .58rem .75rem; }
            .contact-cta-card {
                flex-direction: column;
                align-items: flex-start;
            }
            .fleet-head-link {
                margin-left: 0;
            }
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
                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }

            .nav a {
                width: 100%;
                text-align: left;
                padding: .62rem .7rem;
            }

            .nav-mobile-only {
                display: block;
            }

            .header-right {
                display: none;
            }

            body.nav-open .nav {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    @include('partials.public-header')
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
                <a href="{{ route('short-term-rentals.index') }}">Short-Term Rentals</a>
                <a href="{{ route('long-term-rentals.index') }}">Long-Term Rentals</a>
                <a href="{{ route('airport-hires.index') }}">Airport Hires</a>
                <a href="{{ route('group-packages.index') }}">Special Events</a>
                <a href="{{ route('medical-transport.index') }}">Hospital Service</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        @if(auth()->user()->canAccess('dashboard'))
                            <a class="nav-mobile-only" href="{{ route('dashboard') }}">Admin Dashboard</a>
                        @endif
                    @else
                        <a class="nav-mobile-only" href="{{ route('customer.dashboard') }}">My Dashboard</a>
                    @endif
                    <form class="nav-mobile-only" method="post" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-mobile-action">Log out</button>
                    </form>
                @else
                    <a class="nav-mobile-only" href="{{ route('login') }}">Sign In</a>
                    <a class="nav-mobile-only" href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
            <div class="header-right">
                @auth
                    <div class="account-wrap" id="accountWrap">
                        <button type="button" class="account-toggle" id="accountToggle" aria-haspopup="true" aria-expanded="false">
                            <span>Hi, {{ strtok(auth()->user()->name, ' ') }}</span>
                            <span class="account-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </button>
                        <div class="account-menu" id="accountMenu">
                            @if(auth()->user()->isAdmin())
                                @if(auth()->user()->canAccess('dashboard'))
                                    <a href="{{ route('dashboard') }}">Admin Dashboard</a>
                                @endif
                            @else
                                <a href="{{ route('customer.dashboard') }}">My Dashboard</a>
                            @endif
                            <a href="#fleet-section">Our Fleet</a>
                            <a href="#contact-section">Contact Us</a>
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="danger">Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a class="header-auth-link" href="{{ route('login') }}">Sign In</a>
                @endauth
            </div>
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

        <section id="home-section" class="section-anchor home-hero">
            <div class="container">
                <div class="hero-banner">
                    <img src="{{ asset('images/home.png') }}" alt="R&A Auto Rentals home hero image" onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';this.style.objectFit='contain';this.style.padding='2rem';this.style.background='#0b1f3a';">
                    <div class="hero-overlay">
                        <h1>Your Journey Starts Here</h1>
                        <p>Flexible and reliable rental solutions tailored to Sri Lankan travel needs, from city rides to airport and hospital trips.</p>
                        <form class="hero-search" id="availabilityForm" action="{{ route('fleet.index') }}" method="get" novalidate>
                            <div class="search-field">
                                <label for="start_location">Pickup Location</label>
                                <input id="start_location" name="start_location" type="text" placeholder="Pickup location" required aria-describedby="start_location_error">
                                <small class="field-error" id="start_location_error"></small>
                            </div>
                            <div class="search-field date-field">
                                <label for="start_date">Start Date</label>
                                <input id="start_date" name="start_date" type="date" required aria-describedby="start_date_error">
                                <small class="field-error" id="start_date_error"></small>
                            </div>
                            <div class="search-field date-field">
                                <label for="end_date">End Date</label>
                                <input id="end_date" name="end_date" type="date" required aria-describedby="end_date_error">
                                <small class="field-error" id="end_date_error"></small>
                            </div>
                            <div class="search-field">
                                <label aria-hidden="true" style="visibility:hidden;">Search</label>
                                <button class="search-submit btn" type="submit" id="availabilitySubmitBtn" data-loading-text="Checking...">
                                    <span class="btn-spinner" aria-hidden="true"></span>
                                    <span class="btn-label">Find Vehicle</span>
                                </button>
                                <small class="field-error">&nbsp;</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="trust-strip" aria-label="Key advantages">
            <div class="trust-grid">
                <div class="trust-item"><span class="dot">24</span>24/7 Support</div>
                <div class="trust-item"><span class="dot">Rs</span>No Hidden Fees</div>
                <div class="trust-item"><span class="dot">✓</span>Free Cancellation</div>
            </div>
        </section>

        <section id="payments-section" class="section section-anchor home-services">
            <div class="container">
                <h2>Our Premium Services</h2>
                <p class="head-note">Experience world-class transportation tailored for Sri Lankan daily, airport, family, and event travel.</p>
                <div class="service-grid-modern">
                    <article class="service-card-modern">
                        <span class="service-icon-modern">₨</span>
                        <h3>Monthly Savings</h3>
                        <p>Best rates for long-term rentals with cost-effective monthly packages and predictable pricing.</p>
                    </article>
                    <article class="service-card-modern">
                        <span class="service-icon-modern">✈</span>
                        <h3>Airport Transfers</h3>
                        <p>Fast airport pickups and drop-offs with reliable scheduling and professional trip handling.</p>
                    </article>
                    <article class="service-card-modern">
                        <span class="service-icon-modern">👨‍👩‍👧</span>
                        <h3>Family Travel</h3>
                        <p>Spacious vehicles and practical safety-focused options for family rides across the island.</p>
                    </article>
                    <article class="service-card-modern">
                        <span class="service-icon-modern">★</span>
                        <h3>Special Events</h3>
                        <p>Clean, premium fleet support for weddings, VIP travel, and business events in Sri Lanka.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="fleet-section" class="section section-anchor modern-fleet">
            <div class="container">
                <div class="modern-fleet-head">
                    <h2>Featured Fleet</h2>
                    <a class="fleet-head-link" href="{{ route('fleet.index') }}">See More</a>
                </div>
                <p class="head-note">Choose from our diverse range of high-performance and family-friendly vehicles.</p>
                <div class="showcase-grid">
                    @forelse($featuredCars as $car)
                        <article class="showcase-card" data-card-link="{{ route('fleet.show', $car['id']) }}" tabindex="0" role="link" aria-label="View details for {{ $car['name'] }}">
                            <div class="showcase-photo">
                                <img src="{{ $car['image'] }}" alt="{{ $car['name'] }}">
                                <span class="showcase-photo-badge">{{ $car['segment'] }}</span>
                            </div>
                            <div class="showcase-body">
                                <div class="showcase-topline">
                                    <h3 class="showcase-title">{{ $car['name'] }}</h3>
                                    <div class="showcase-rate">Rs {{ number_format($car['daily_rate'], 0) }} <small>/day</small></div>
                                </div>
                                <p class="showcase-info-row">
                                    <span>Vehicle No: {{ $car['plate_no'] ?: '-' }}</span>
                                    <span>Reg Year: {{ $car['year'] ?: '-' }}</span>
                                </p>
                                <div class="showcase-spec-grid">
                                    <span class="showcase-spec-item spec-seats">{{ $car['seats'] }}</span>
                                    <span class="showcase-spec-item spec-bags">{{ $car['bags'] }}</span>
                                    <span class="showcase-spec-item spec-transmission">{{ $car['transmission'] }}</span>
                                </div>
                                <div class="showcase-actions">
                                    <a class="showcase-details" href="{{ route('fleet.show', $car['id']) }}">Details</a>
                                    <a class="showcase-book" href="{{ route('fleet.show', $car['id']) }}">Book Now</a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <article class="showcase-card" style="grid-column:1 / -1;">
                            <div class="showcase-body">
                                <h3 class="showcase-title" style="margin:0;">No vehicles found.</h3>
                            </div>
                        </article>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="contact-section" class="section section-anchor">
            <div class="container">
                <h2>Find Us & Contact Support</h2>
                <p class="head-note">Send your request for booking help, pricing questions, or service support. Our team will respond quickly.</p>
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
                            info@rnaautorentals.com.lk
                        </div>
                        <div>
                            <strong>Working Hours</strong>
                            Monday - Sunday, 7.00 AM - 9.00 PM
                        </div>
                    </div>
                    <div class="contact-form">
                        <h3 class="contact-form-title">Contact Support Form</h3>
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
                                    <textarea id="contactMessage" name="message" placeholder="Tell us your rental need (daily, monthly, airport, medical)." required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <button type="submit" class="contact-submit">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.public-footer')

    <script>
        (function () {
            const toggle = document.getElementById('menuToggle');
            if (toggle) {
                toggle.addEventListener('click', function () {
                    const open = document.body.classList.toggle('nav-open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }

            const accountWrap = document.getElementById('accountWrap');
            const accountToggle = document.getElementById('accountToggle');
            if (accountWrap && accountToggle) {
                accountToggle.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const isOpen = accountWrap.classList.toggle('account-open');
                    accountToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                document.addEventListener('click', function (event) {
                    if (!accountWrap.contains(event.target)) {
                        accountWrap.classList.remove('account-open');
                        accountToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            const form = document.getElementById('availabilityForm');
            if (!form) {
                return;
            }

            const pickupInput = document.getElementById('start_location');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const availabilitySubmitBtn = document.getElementById('availabilitySubmitBtn');
            const pickupError = document.getElementById('start_location_error');
            const startDateError = document.getElementById('start_date_error');
            const endDateError = document.getElementById('end_date_error');
            let hasTriedSubmit = false;

            const setLoadingState = (button) => {
                if (!button) return;
                const label = button.querySelector('.btn-label');
                const loadingText = button.dataset.loadingText || 'Loading...';
                if (label) {
                    label.dataset.originalText = label.textContent;
                    label.textContent = loadingText;
                }
                button.classList.add('is-loading');
                button.disabled = true;
            };

            const clearLoadingState = (button) => {
                if (!button) return;
                const label = button.querySelector('.btn-label');
                if (label && label.dataset.originalText) {
                    label.textContent = label.dataset.originalText;
                }
                button.classList.remove('is-loading');
                button.disabled = false;
            };

            const resetAvailabilitySubmitState = () => {
                clearLoadingState(availabilitySubmitBtn);
            };

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

            const setTodayMin = () => {
                const today = new Date();
                const y = today.getFullYear();
                const m = String(today.getMonth() + 1).padStart(2, '0');
                const d = String(today.getDate()).padStart(2, '0');
                const isoToday = `${y}-${m}-${d}`;
                startDateInput.min = isoToday;
                if (!endDateInput.min || endDateInput.min < isoToday) {
                    endDateInput.min = isoToday;
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
                event.preventDefault();
                hasTriedSubmit = true;

                if (!validateAvailabilityForm()) {
                    clearLoadingState(availabilitySubmitBtn);
                    return;
                }

                setLoadingState(availabilitySubmitBtn);

                const params = new URLSearchParams({
                    start_location: pickupInput.value.trim(),
                    start_date: startDateInput.value,
                    end_date: endDateInput.value,
                });

                const targetUrl = `${form.action}?${params.toString()}`;
                setTimeout(() => {
                    if (document.visibilityState === 'visible') {
                        resetAvailabilitySubmitState();
                    }
                }, 5000);
                window.location.assign(targetUrl);
            });

            [pickupInput, startDateInput, endDateInput].forEach((input) => {
                const handleFieldChange = () => {
                    if (input === startDateInput) {
                        syncEndDateMin();
                    }
                    if (input === endDateInput && startDateInput.value && endDateInput.value < startDateInput.value) {
                        endDateInput.value = '';
                    }
                    if (!hasTriedSubmit) return;
                    validateAvailabilityForm(false);
                };
                input.addEventListener('input', handleFieldChange);
                input.addEventListener('change', handleFieldChange);
            });

            setTodayMin();
            syncEndDateMin();
            window.addEventListener('pageshow', resetAvailabilitySubmitState);
            window.addEventListener('focus', resetAvailabilitySubmitState);
        })();
    </script>
</body>
</html>
