<style>
    a,
    a:hover,
    a:focus,
    a:active,
    a:visited {
        text-decoration: none !important;
        cursor: pointer;
    }

    .site-footer-common {
        margin-top: 2.2rem;
        background: #071b44;
        border-top: 1px solid #153465;
        color: #d6e6ff;
    }

    .site-footer-common .footer-wrap {
        width: min(1480px, calc(100% - 3rem));
        margin: 0 auto;
        padding: 1.35rem 0 .85rem;
    }

    .site-footer-common .footer-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 1fr 1fr;
        gap: 1.4rem;
        border-bottom: 1px solid rgba(198, 219, 252, 0.2);
        padding-bottom: .9rem;
    }

    .site-footer-common h3,
    .site-footer-common h4 {
        margin: 0 0 .5rem;
        color: #f6fbff;
    }

    .site-footer-common h3 {
        font-size: 1.05rem;
        line-height: 1.05;
        letter-spacing: -.02em;
        font-family: "Space Grotesk", "Segoe UI", Tahoma, sans-serif;
    }

    .site-footer-common h4 {
        font-size: .76rem;
        line-height: 1.12;
        letter-spacing: .02em;
        text-transform: uppercase;
        color: #d7e8ff;
    }

    .site-footer-common p {
        margin: 0;
        font-size: .84rem;
        line-height: 1.45;
        color: #dbe9ff;
        max-width: 40ch;
    }

    .site-footer-common ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: .45rem;
    }

    .site-footer-common a {
        color: #f3f8ff;
        text-decoration: none;
        font-size: .86rem;
        line-height: 1.3;
    }

    .site-footer-common a:hover {
        color: #ffffff;
        text-decoration: underline;
        text-underline-offset: .15em;
    }

    .site-footer-common .footer-bottom {
        text-align: center;
        color: #d6e6ff;
        font-size: .86rem;
        padding-top: .8rem;
    }

    @media (max-width: 1100px) {
        .site-footer-common .footer-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 700px) {
        .site-footer-common .footer-wrap {
            width: min(1480px, calc(100% - 1.2rem));
        }

        .site-footer-common .footer-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .site-footer-common h3 {
            font-size: 1rem;
        }

        .site-footer-common h4 {
            font-size: .74rem;
        }

        .site-footer-common p {
            font-size: .84rem;
        }

        .site-footer-common a {
            font-size: .86rem;
        }

        .site-footer-common .footer-bottom {
            font-size: .82rem;
        }
    }
</style>

<footer class="site-footer-common">
    <div class="footer-wrap">
        <div class="footer-grid">
            <div>
                <h3>R&amp;A Auto Rentals</h3>
                <p>Providing premium mobility solutions with transparent pricing and reliable support.</p>
            </div>
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('fleet.index') }}">Fleet</a></li>
                    <li><a href="{{ route('pricing.index') }}">Pricing</a></li>
                </ul>
            </div>
            <div>
                <h4>Support</h4>
                <ul>
                    <li><a href="{{ route('home') }}#contact-section">Contact</a></li>
                    <li><a href="{{ route('terms-of-service') }}">Terms</a></li>
                    <li><a href="{{ route('privacy-policy') }}">Policy</a></li>
                </ul>
            </div>
            <div>
                <h4>Explore</h4>
                <ul>
                    <a href="{{ route('short-term-rentals.index') }}">Short-Term Rentals</a>
                    <a href="{{ route('long-term-rentals.index') }}">Long-Term Rentals</a>
                    <a href="{{ route('airport-hires.index') }}">Airport Hires</a>
                    <a href="{{ route('group-packages.index') }}">Special Events</a>
                    <a href="{{ route('medical-transport.index') }}">Hospital Service</a>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ now()->year }} R&amp;A Auto Rentals. All rights reserved.
        </div>
    </div>
</footer>
