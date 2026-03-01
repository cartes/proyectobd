<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate
                            {--output=public/sitemap.xml : Output path for the sitemap}';

    /**
     * The console command description.
     */
    protected $description = 'Generate XML sitemap for SEO';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🗺️  Generating sitemap...');

        // Prevenir que el sitemap se genere con dominios temporales de ngrok (ya sea por APP_URL o por contexto HTTP)
        $currentUrl = url('/');
        if (str_contains($currentUrl, 'ngrok') || str_contains(config('app.url'), 'ngrok')) {
            $prodUrl = env('PROD_URL', 'https://big-dad.cl');
            \Illuminate\Support\Facades\URL::forceRootUrl($prodUrl);
            $this->info("⚠️  Ngrok detectado. Forzando URL base a: {$prodUrl}");
        }

        $urls = $this->collectUrls();
        $xml = $this->buildXml($urls);

        $outputPath = $this->option('output');
        File::put(base_path($outputPath), $xml);

        $this->info('✅ Sitemap generated successfully!');
        $this->info("📍 Location: {$outputPath}");
        $this->info('📊 Total URLs: '.count($urls));

        return Command::SUCCESS;
    }

    /**
     * Collect all URLs to include in sitemap
     */
    protected function collectUrls(): array
    {
        $urls = [];

        // Landing page (highest priority)
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '1.0',
        ];

        // Authentication pages
        $urls[] = [
            'loc' => route('register'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'monthly',
            'priority' => '0.8',
        ];

        $urls[] = [
            'loc' => route('login'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'monthly',
            'priority' => '0.6',
        ];

        // Public Plans page
        $urls[] = [
            'loc' => route('plans.public'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'weekly',
            'priority' => '0.8',
        ];

        // Blog index
        $urls[] = [
            'loc' => route('blog.index'),
            'lastmod' => now()->toDateString(),
            'changefreq' => 'daily',
            'priority' => '0.9',
        ];

        // Blog posts (dynamic)
        $this->info('  📝 Fetching blog posts...');
        $blogPosts = BlogPost::published()->orderBy('updated_at', 'desc')->get();
        foreach ($blogPosts as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }
        $this->info("  ✓ Added {$blogPosts->count()} blog posts");

        // Blog categories (dynamic)
        $categories = BlogPost::published()
            ->whereNotNull('category_id')
            ->with('category')
            ->get()
            ->pluck('category')
            ->unique('id')
            ->filter();

        foreach ($categories as $category) {
            $urls[] = [
                'loc' => route('blog.category', $category->slug),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }
        $this->info("  ✓ Added {$categories->count()} blog categories");

        // Country archives (dynamic)
        $this->info('  🌍 Fetching active countries...');
        $countries = Country::where('is_active', true)->get();
        foreach ($countries as $country) {
            $urls[] = [
                'loc' => route('archive.country', $country->iso_code),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }
        $this->info("  ✓ Added {$countries->count()} country archives");

        // Legal pages
        $legalPages = [
            ['route' => 'legal.terms', 'priority' => '0.5'],
            ['route' => 'legal.privacy', 'priority' => '0.5'],
            ['route' => 'legal.rules', 'priority' => '0.5'],
            ['route' => 'legal.safety', 'priority' => '0.5'],
        ];

        foreach ($legalPages as $page) {
            $urls[] = [
                'loc' => route($page['route']),
                'lastmod' => now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => $page['priority'],
            ];
        }
        $this->info('  ✓ Added 4 legal pages');

        return $urls;
    }

    /**
     * Build XML from URLs array
     */
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

        $xml .= '</urlset>';

        return $xml;
    }
}
