<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate {--all : Include routes that require authentication}';

    protected $description = 'Generate public/sitemap.xml from application routes';

    public function handle(): int
    {
        $includeAuthRoutes = (bool) $this->option('all');
        $timestamp = now()->toAtomString();
        $entries = [];
        $pageSchemas = $this->pageSchemas();

        foreach (Route::getRoutes() as $route) {
            if (!in_array('GET', $route->methods(), true)) {
                continue;
            }

            $uri = $route->uri();
            $middleware = $route->gatherMiddleware();
            $name = (string) ($route->getName() ?? '');

            // Skip dynamic URLs and framework/internal routes.
            if (str_contains($uri, '{') || str_starts_with($uri, '_')) {
                continue;
            }

            // Keep sitemap focused on website pages only.
            if (in_array('api', $middleware, true) || str_starts_with($uri, 'api/')) {
                continue;
            }

            if (str_starts_with($uri, 'sanctum/')) {
                continue;
            }

            if ($this->isAdminRoute($name, $uri, $middleware)) {
                continue;
            }

            if (!$includeAuthRoutes && in_array('auth', $middleware, true)) {
                continue;
            }

            $loc = $uri === '/' ? url('/') : url($uri);
            $path = '/' . ltrim($uri, '/');
            $schema = $pageSchemas[$name] ?? [
                'changefreq' => $path === '/' ? 'daily' : 'weekly',
                'priority' => $path === '/' ? '1.0' : '0.8',
            ];

            $entries[$loc] = [
                'loc' => $loc,
                'lastmod' => $timestamp,
                'changefreq' => $schema['changefreq'],
                'priority' => $schema['priority'],
            ];
        }

        if (empty($entries)) {
            $this->warn('No sitemap entries found.');
            return self::FAILURE;
        }

        usort($entries, function (array $a, array $b): int {
            if ($a['loc'] === url('/')) {
                return -1;
            }
            if ($b['loc'] === url('/')) {
                return 1;
            }
            return strcmp($a['loc'], $b['loc']);
        });

        $xml = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($entries as $entry) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . e($entry['loc']) . '</loc>';
            $xml[] = '    <lastmod>' . $entry['lastmod'] . '</lastmod>';
            $xml[] = '    <changefreq>' . $entry['changefreq'] . '</changefreq>';
            $xml[] = '    <priority>' . $entry['priority'] . '</priority>';
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        $targetPath = public_path('sitemap.xml');
        file_put_contents($targetPath, implode(PHP_EOL, $xml) . PHP_EOL);

        $this->info('Sitemap generated: ' . $targetPath);
        $this->line('Total URLs: ' . count($entries));

        return self::SUCCESS;
    }

    /**
     * Page-level sitemap schema defaults.
     *
     * @return array<string, array{changefreq:string, priority:string}>
     */
    private function pageSchemas(): array
    {
        return [
            'home' => ['changefreq' => 'daily', 'priority' => '1.0'],
            'fleet.index' => ['changefreq' => 'daily', 'priority' => '0.9'],
            'blogs' => ['changefreq' => 'weekly', 'priority' => '0.8'],
            'terms-of-service' => ['changefreq' => 'monthly', 'priority' => '0.5'],
            'privacy-policy' => ['changefreq' => 'monthly', 'priority' => '0.5'],
            'login' => ['changefreq' => 'monthly', 'priority' => '0.4'],
            'register' => ['changefreq' => 'monthly', 'priority' => '0.4'],
            'customer.dashboard' => ['changefreq' => 'daily', 'priority' => '0.7'],
            'profile.edit' => ['changefreq' => 'weekly', 'priority' => '0.6'],
        ];
    }

    /**
     * Exclude admin/management pages from sitemap output.
     */
    private function isAdminRoute(string $name, string $uri, array $middleware): bool
    {
        if (in_array('role:admin', $middleware, true)) {
            return true;
        }

        foreach ($middleware as $item) {
            if (Str::startsWith($item, 'permission:')) {
                return true;
            }
        }

        $adminNamePrefixes = [
            'dashboard',
            'cars.',
            'customers.',
            'payments.',
            'agreements.',
            'expenses.',
            'gps-logs.',
            'users.',
            'permissions.',
            'support-requests.',
            'rent-requests.',
            'availability-check.',
            'rental-trips.',
        ];

        foreach ($adminNamePrefixes as $prefix) {
            if (Str::startsWith($name, $prefix)) {
                return true;
            }
        }

        $adminUriPrefixes = [
            'dashboard',
            'cars',
            'customers',
            'payments',
            'agreements',
            'expenses',
            'gps-logs',
            'users',
            'permissions',
            'support-requests',
            'rent-requests',
            'availability-check',
            'rental-trips',
        ];

        foreach ($adminUriPrefixes as $prefix) {
            if ($uri === $prefix || Str::startsWith($uri, $prefix . '/')) {
                return true;
            }
        }

        return false;
    }
}
