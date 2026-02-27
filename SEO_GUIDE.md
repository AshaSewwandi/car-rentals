# SEO Guide (R&A Auto Rentals)

This project now includes reusable SEO metadata and a sitemap command.

## 1. Reusable Metadata Partial

File:

- `resources/views/partials/seo-meta.blade.php`

What it adds:

- `<title>`
- `meta description`
- `meta keywords`
- `meta tags`
- `meta robots`
- `canonical URL`
- Open Graph tags
- Twitter card tags

### Usage in a Blade page

```blade
@include('partials.seo-meta', [
    'title' => 'Page Title | R&A Auto Rentals',
    'description' => 'Short SEO description for this page.',
    'keywords' => ['car rental', 'vehicle hire', 'fleet booking'],
    'tags' => ['car rental', 'booking', 'Sri Lanka'],
    'type' => 'website',
    'robots' => 'index,follow',
    'canonical' => url()->current(),
    'image' => asset('images/logo.png'),
])
```

If `keywords` or `tags` are not passed, default rental-platform words are used.

## 2. Pages Already Connected

- `resources/views/welcome.blade.php`
- `resources/views/fleet/index.blade.php`
- `resources/views/blogs.blade.php`
- `resources/views/terms-of-service.blade.php`
- `resources/views/privacy-policy.blade.php`
- `resources/views/booking/confirm.blade.php`
- `resources/views/booking/success.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`

## 3. Sitemap Command

Command file:

- `app/Console/Commands/GenerateSitemap.php`

Commands:

```bash
php artisan sitemap:generate
php artisan sitemap:generate --all
```

Output:

- `public/sitemap.xml`

Rules:

- Admin/management routes are excluded.
- API and Sanctum routes are excluded.
- Route-level schema defaults (priority/changefreq) are applied.

## 4. Recommended robots settings

- Public pages: `index,follow`
- Login/Register/Booking flow pages: `noindex,follow`

