<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Country;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

/**
 * Genera el sitemap.xml de forma dinámica (Railway usa contenedores efímeros,
 * así que un archivo estático en public/ se pierde o queda desactualizado en cada deploy).
 * Solo incluye URLs finales (sin redirecciones).
 */
class SitemapService
{
    public const CACHE_KEY = 'sitemap.xml';

    public const CACHE_TTL = 3600;

    public function cached(): string
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn () => $this->xml());
    }

    public function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function xml(): string
    {
        // Nunca publicar URLs de dominios temporales de ngrok
        if (str_contains(url('/'), 'ngrok') || str_contains((string) config('app.url'), 'ngrok')) {
            URL::forceRootUrl(env('PROD_URL', 'https://big-dad.com'));
            URL::forceScheme('https');
        }

        return $this->buildXml($this->collectUrls());
    }

    public function collectUrls(): array
    {
        $today = now()->toDateString();
        $urls = [];

        $static = [
            ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => route('register'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => route('login'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('plans.public'), 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => route('como-funciona'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => route('about.index'), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => route('blog.index'), 'changefreq' => 'daily', 'priority' => '0.9'],
        ];
        foreach ($static as $url) {
            $urls[] = $url + ['lastmod' => $today];
        }

        // Blog posts
        foreach (BlogPost::published()->orderBy('updated_at', 'desc')->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // Blog categories con posts publicados
        $categories = BlogPost::published()
            ->whereNotNull('category_id')
            ->with('category')
            ->get()
            ->pluck('category')
            ->filter()
            ->unique('id');
        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('blog.category', $category->slug),
                'lastmod' => $today,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Páginas por país: /sugar-babies para países activos o con contenido SEO;
        // /sugar-daddies solo para países con contenido propio (las demás son noindex).
        $babySlugs = Country::slugsWithSeoContent('sugar_baby');
        $daddySlugs = Country::slugsWithSeoContent('sugar_daddy');

        $countries = Country::where('is_active', true)
            ->orWhereIn('slug', array_merge($babySlugs, $daddySlugs))
            ->orderBy('name')
            ->get();

        foreach ($countries as $country) {
            $hasContent = in_array($country->slug, $babySlugs, true);
            if ($country->is_active || $hasContent) {
                $urls[] = [
                    'loc' => route('archive.country', $country->slug),
                    'lastmod' => $today,
                    'changefreq' => 'weekly',
                    'priority' => $hasContent ? '0.8' : '0.7',
                ];
            }
            if (in_array($country->slug, $daddySlugs, true)) {
                $urls[] = [
                    'loc' => route('archive.country.daddies', $country->slug),
                    'lastmod' => $today,
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        // Legales
        foreach (['legal.terms', 'legal.privacy', 'legal.rules', 'legal.safety'] as $routeName) {
            $urls[] = [
                'loc' => route($routeName),
                'lastmod' => $today,
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        return $urls;
    }

    protected function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>'.PHP_EOL;
            $xml .= '    <loc>'.htmlspecialchars($url['loc'], ENT_XML1).'</loc>'.PHP_EOL;
            $xml .= '    <lastmod>'.$url['lastmod'].'</lastmod>'.PHP_EOL;
            $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'.PHP_EOL;
            $xml .= '    <priority>'.$url['priority'].'</priority>'.PHP_EOL;
            $xml .= '  </url>'.PHP_EOL;
        }

        return $xml.'</urlset>';
    }
}
