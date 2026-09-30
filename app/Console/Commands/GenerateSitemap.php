<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate
                            {--output= : Escribe además el XML en un archivo (ruta relativa al proyecto). No usar public/sitemap.xml: taparía la ruta dinámica}';

    /**
     * The console command description.
     */
    protected $description = 'Regenera la caché del sitemap dinámico (/sitemap.xml)';

    /**
     * Execute the console command.
     */
    public function handle(SitemapService $sitemap): int
    {
        $this->info('🗺️  Generating sitemap...');

        $sitemap->forget();
        $xml = $sitemap->cached();

        if ($output = $this->option('output')) {
            File::put(base_path($output), $xml);
            $this->info("📍 Location: {$output}");
        }

        $this->info('✅ Sitemap regenerated. Total URLs: '.substr_count($xml, '<loc>'));

        return Command::SUCCESS;
    }
}
