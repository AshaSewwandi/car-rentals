<style>
    a,
    a:hover,
    a:focus,
    a:active,
    a:visited {
        text-decoration: none !important;
        cursor: pointer;
    }

    .site-header-common {
        position: sticky;
        top: 0;
        z-index: 70;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        border-bottom: 1px solid #dbe6f3;
    }

    .site-header-common .header-wrap {
        width: min(1220px, calc(100% - 2rem));
        margin: 0 auto;
        min-height: 70px;
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    .site-header-common .brand {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        text-decoration: none;
    }

    .site-header-common .brand img {
        width: 36px;
        height: 36px;
        object-fit: contain;
        border-radius: 9px;
        border: 1px solid #d8e5f5;
        background: #f8fbff;
        padding: 3px;
    }

    .site-header-common .brand-name {
        color: #0b3f88;
        font-weight: 700;
        letter-spacing: -.02em;
        font-size: 1.32rem;
        font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
    }

    .site-header-common .main-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: .2rem;
    }

    .site-header-common .main-nav a {
        text-decoration: none;
        color: #203a5b;
        font-weight: 600;
        font-size: .9rem;
        padding: .44rem .62rem;
        border-radius: 8px;
        white-space: nowrap;
    }

    .site-header-common .main-nav a:hover {
        color: #0f4b9e;
        background: #edf4ff;
    }

    .site-header-common .right {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        min-width: 150px;
        position: relative;
    }

    .site-header-common .auth-link {
        text-decoration: none;
        border: 0;
        border-radius: 10px;
        padding: .52rem .95rem;
        color: #fff;
        background: linear-gradient(135deg, #0a3f8f, #0f66c3);
        box-shadow: 0 10px 20px rgba(10, 63, 143, 0.24);
        font-weight: 700;
        font-size: .9rem;
        cursor: pointer;
        font-family: inherit;
    }

    .site-header-common .account-btn {
        border: 1px solid #d5e2f3;
        background: #fff;
        color: #0f2f59;
        border-radius: 999px;
        padding: .3rem .46rem .3rem .7rem;
        display: inline-flex;
        align-items: center;
        gap: .42rem;
        cursor: pointer;
        font-weight: 700;
        font-family: inherit;
    }

    .site-header-common .avatar {
        width: 24px;
        height: 24px;
        border-radius: 999px;
        background: #e9f2ff;
        color: #0f4ea1;
        border: 1px solid #cbe0fb;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .76rem;
        font-weight: 800;
    }

    .site-header-common .account-menu {
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
        z-index: 80;
    }

    .site-header-common .account-menu a,
    .site-header-common .account-menu button {
        width: 100%;
        text-align: left;
        border: 0;
        background: transparent;
        display: block;
        padding: .66rem .84rem;
        color: #243a56;
        text-decoration: none;
        font-size: .89rem;
        border-bottom: 1px solid #edf3fb;
        font-family: inherit;
        cursor: pointer;
    }

    .site-header-common .account-menu .danger {
        color: #b91c1c;
        border-bottom: 0;
    }

    .site-header-common .account-open .account-menu {
        display: block;
    }

    .site-header-common .menu-toggle {
        display: none;
        width: 40px;
        height: 40px;
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

    .site-header-common .mobile-auth {
        display: none;
    }

    [data-card-link] {
        cursor: pointer;
    }

    .topbar {
        display: none !important;
    }

    @media (max-width: 920px) {
        .site-header-common .header-wrap {
            grid-template-columns: auto auto;
        }

        .site-header-common .menu-toggle {
            display: inline-flex;
            justify-self: end;
        }

        .site-header-common .right {
            display: none;
        }

        .site-header-common .main-nav {
            display: none;
            position: absolute;
            top: calc(100% + .5rem);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #dbe6f3;
            border-radius: 12px;
            padding: .5rem;
            box-shadow: 0 14px 28px rgba(10, 63, 143, 0.14);
            z-index: 85;
            gap: .3rem;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            justify-content: flex-start;
        }

        .site-header-common .main-nav a {
            width: 100%;
            text-align: left;
            padding: .62rem .7rem;
        }

        .site-header-common .mobile-auth {
            display: block;
        }

        .site-header-common.nav-open .main-nav {
            display: flex;
            flex-direction: column;
        }
    }
</style>

<header class="site-header-common site-header-hide-legacy" id="publicHeaderCommon">
    <div class="header-wrap">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="R&A Auto Rentals logo">
            <span class="brand-name">R&amp;A Auto Rentals</span>
        </a>
        <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" id="publicHeaderMenuToggle">&#9776;</button>
        <nav class="main-nav" aria-label="Primary navigation">
            <a href="{{ route('short-term-rentals.index') }}">Short-Term Rentals</a>
            <a href="{{ route('long-term-rentals.index') }}">Long-Term Rentals</a>
            <a href="{{ route('airport-hires.index') }}">Airport Hires</a>
            <a href="{{ route('group-packages.index') }}">Special Events</a>
            <a href="{{ route('medical-transport.index') }}">Hospital Service</a>
            @auth
                @if(auth()->user()->isAdmin())
                    @if(auth()->user()->canAccess('dashboard'))
                        <a class="mobile-auth" href="{{ route('dashboard') }}">Admin Dashboard</a>
                    @endif
                @else
                    <a class="mobile-auth" href="{{ route('customer.dashboard') }}">My Dashboard</a>
                @endif
                <form class="mobile-auth" method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="auth-link" style="width:100%;text-align:left;">Log out</button>
                </form>
            @else
                <a class="mobile-auth" href="{{ route('login') }}">Sign In</a>
            @endauth
        </nav>

        <div class="right">
            @auth
                <div class="account-wrap" id="publicHeaderAccountWrap">
                    <button type="button" class="account-btn" id="publicHeaderAccountBtn" aria-haspopup="true" aria-expanded="false">
                        <span>Hi, {{ strtok(auth()->user()->name, ' ') }}</span>
                        <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </button>
                    <div class="account-menu">
                        @if(auth()->user()->isAdmin())
                            @if(auth()->user()->canAccess('dashboard'))
                                <a href="{{ route('dashboard') }}">Admin Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('customer.dashboard') }}">My Dashboard</a>
                        @endif
                        <a href="{{ route('fleet.index') }}">Our Fleet</a>
                        <a href="{{ route('home') }}#contact-section">Contact Us</a>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="danger">Log out</button>
                        </form>
                    </div>
                </div>
            @else
                <a class="auth-link" href="{{ route('login') }}">Sign In</a>
            @endauth
        </div>
    </div>
</header>

<script>
    (function () {
        const header = document.getElementById('publicHeaderCommon');
        const menuToggle = document.getElementById('publicHeaderMenuToggle');
        if (header && menuToggle) {
            menuToggle.addEventListener('click', function () {
                const isOpen = header.classList.toggle('nav-open');
                menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        const accountWrap = document.getElementById('publicHeaderAccountWrap');
        const accountBtn = document.getElementById('publicHeaderAccountBtn');
        if (accountWrap && accountBtn) {
            accountBtn.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                const isOpen = accountWrap.classList.toggle('account-open');
                accountBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            document.addEventListener('click', function (event) {
                if (!accountWrap.contains(event.target)) {
                    accountWrap.classList.remove('account-open');
                    accountBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        const shouldIgnoreCardClick = (target) => {
            return !!target.closest('a, button, input, select, textarea, label, form');
        };

        document.addEventListener('click', function (event) {
            const card = event.target.closest('[data-card-link]');
            if (!card || shouldIgnoreCardClick(event.target)) {
                return;
            }

            const url = card.getAttribute('data-card-link');
            if (url) {
                window.location.href = url;
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Enter') {
                return;
            }

            const card = event.target.closest('[data-card-link]');
            if (!card || shouldIgnoreCardClick(event.target)) {
                return;
            }

            const url = card.getAttribute('data-card-link');
            if (url) {
                window.location.href = url;
            }
        });
    })();
</script>
