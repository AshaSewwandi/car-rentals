<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogs | R&A Auto Rentals</title>
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
            --soft: #f8fbff;
            --radius: 14px;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", "Segoe UI", Tahoma, sans-serif;
            color: var(--text);
            background: radial-gradient(55rem 24rem at 100% -10%, rgba(15, 102, 195, .15), transparent 70%), var(--bg);
        }
        .container { width: min(1120px, calc(100% - 2rem)); margin: 0 auto; }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(255,255,255,.94);
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(8px);
        }
        .topbar-inner {
            min-height: 70px;
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
            color: inherit;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }
        .brand img { width: 52px; height: 32px; object-fit: contain; }
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
        }
        .page { padding: 1.4rem 0 2rem; }
        .hero {
            background: linear-gradient(135deg, #0a3f8f, #0f66c3);
            color: #fff;
            border-radius: var(--radius);
            padding: 1.4rem 1.25rem;
            margin-bottom: 1rem;
        }
        .hero h1 {
            margin: 0 0 .4rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(1.6rem, 3.1vw, 2.2rem);
            letter-spacing: -.02em;
        }
        .hero p {
            margin: 0;
            opacity: .95;
            line-height: 1.6;
            max-width: 70ch;
        }
        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 1rem;
        }
        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .article-cover {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
            border-bottom: 1px solid var(--line);
        }
        .article-body {
            padding: 1rem 1rem 1.2rem;
        }
        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            margin-bottom: .65rem;
        }
        .tag {
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            color: #1e3a8a;
            background: #eaf2ff;
            padding: .24rem .55rem;
        }
        .article-title {
            margin: 0 0 .65rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(1.24rem, 2.1vw, 1.55rem);
            letter-spacing: -.01em;
        }
        .article-body p {
            margin: 0 0 .75rem;
            color: #334155;
            line-height: 1.65;
        }
        .article-body h3 {
            margin: 1rem 0 .45rem;
            font-size: 1.05rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
        }
        .article-body ul {
            margin: 0 0 .75rem 1.1rem;
            color: #334155;
            line-height: 1.65;
            padding: 0;
        }
        .tip-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .6rem;
            margin-top: .85rem;
        }
        .tip {
            border: 1px solid var(--line);
            border-radius: 11px;
            background: var(--soft);
            padding: .65rem .65rem .7rem;
        }
        .tip strong {
            display: block;
            margin-bottom: .3rem;
            color: #0a3f8f;
            font-size: .86rem;
        }
        .tip span {
            color: #475569;
            font-size: .84rem;
            line-height: 1.45;
        }
        .side {
            display: grid;
            gap: .8rem;
            align-content: start;
        }
        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: .9rem;
        }
        .panel h2 {
            margin: 0 0 .55rem;
            font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
            font-size: 1rem;
        }
        .post-item {
            border-top: 1px solid #eaf0f8;
            padding-top: .65rem;
            margin-top: .65rem;
        }
        .post-item:first-of-type {
            border-top: 0;
            margin-top: 0;
            padding-top: 0;
        }
        .post-item h4 {
            margin: 0 0 .2rem;
            font-size: .92rem;
        }
        .post-item p {
            margin: 0;
            color: #64748b;
            font-size: .83rem;
            line-height: 1.45;
        }
        @media (max-width: 920px) {
            .layout { grid-template-columns: 1fr; }
            .tip-grid { grid-template-columns: 1fr; }
            .article-cover { height: 220px; }
        }
        @media (max-width: 640px) {
            .container { width: calc(100% - 1rem); }
            .topbar-inner { min-height: 62px; gap: .45rem; }
            .brand { font-size: 1rem; gap: .5rem; }
            .brand img { width: 44px; height: 28px; }
            .btn { font-size: .9rem; padding: .52rem .76rem; border-radius: 9px; }
            .article-cover { height: 190px; }
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
                <h1>R&A Blog</h1>
                <p>Useful guides for daily rentals, monthly rentals, pickup planning, and mileage cost control.</p>
            </section>

            <section class="layout">
                <article class="card">
                    <img class="article-cover" src="{{ asset('images/hero.png') }}" alt="R&A Auto Rentals Blog Cover">
                    <div class="article-body">
                        <div class="meta">
                            <span class="tag">Featured</span>
                            <span class="tag">Rental Tips</span>
                            <span class="tag">Updated {{ now()->format('Y-m-d') }}</span>
                        </div>
                        <h2 class="article-title">How to choose the right vehicle for daily or monthly rental</h2>
                        <p>Choosing the right car depends on trip duration, passenger count, luggage needs, and travel distance. For short city tasks, a compact car saves cost. For family or group travel, choose a larger vehicle with better comfort and space.</p>
                        <h3>Daily rental is best when</h3>
                        <ul>
                            <li>You need a vehicle for short-term travel, airport runs, or urgent plans.</li>
                            <li>Your schedule changes often and you need flexibility.</li>
                            <li>You want low commitment and quick booking.</li>
                        </ul>
                        <h3>Monthly rental is best when</h3>
                        <ul>
                            <li>You have long business visits or project-based travel.</li>
                            <li>You need stable pricing over a longer period.</li>
                            <li>You prefer one confirmed vehicle for routine use.</li>
                        </ul>
                        <h3>Important mileage note</h3>
                        <p>R&A rental plans include 150 km per day. Extra distance is charged at Rs. 25 per km. Planning your routes early helps control final cost.</p>

                        <div class="tip-grid">
                            <div class="tip">
                                <strong>Trip Type</strong>
                                <span>City usage: compact car. Family / group trips: larger car or van.</span>
                            </div>
                            <div class="tip">
                                <strong>Date Planning</strong>
                                <span>Confirm start/end dates in advance to improve availability.</span>
                            </div>
                            <div class="tip">
                                <strong>Cost Control</strong>
                                <span>Track daily mileage to avoid unexpected extra-km charges.</span>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="side">
                    <div class="panel">
                        <h2>More Posts</h2>
                        <div class="post-item">
                            <h4>Pickup and drop-off checklist</h4>
                            <p>Essential checks before collecting and returning your vehicle.</p>
                        </div>
                        <div class="post-item">
                            <h4>How to plan long-distance rental trips</h4>
                            <p>Route planning, rest stops, and budget-friendly travel advice.</p>
                        </div>
                        <div class="post-item">
                            <h4>With driver or without driver?</h4>
                            <p>Understand which option fits your travel purpose and schedule.</p>
                        </div>
                    </div>
                    <div class="panel">
                        <h2>Need Help?</h2>
                        <p style="margin:0;color:#475569;line-height:1.55;">Contact R&A support for booking assistance, location updates, and rental period adjustments.</p>
                    </div>
                </aside>
            </section>
        </div>
    </main>
</body>
</html>
