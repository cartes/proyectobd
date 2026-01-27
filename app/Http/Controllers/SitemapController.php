<?php

namespace App\Http\Controllers;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [
            // Páginas principales
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('register'), 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => route('login'), 'priority' => '0.7', 'changefreq' => 'monthly'],

            // Páginas legales
            ['loc' => route('legal.terms'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('legal.privacy'), 'priority' => '0.5', 'changefreq' => 'monthly'],

            // TODO: Agregar cuando crees estas páginas
            // ['loc' => url('/blog'), 'priority' => '0.8', 'changefreq' => 'daily'],
            // ['loc' => url('/sugar-daddy-santiago'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            // ['loc' => url('/sugar-baby-vina-del-mar'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            // ['loc' => url('/guia-sugar-dating-chile'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>'.htmlspecialchars($url['loc']).'</loc>';
            $xml .= '<lastmod>'.date('Y-m-d').'</lastmod>';
            $xml .= '<changefreq>'.$url['changefreq'].'</changefreq>';
            $xml .= '<priority>'.$url['priority'].'</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
