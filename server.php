<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @author   Taylor Otwell <taylor@laravel.com>
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$file = __DIR__.'/public'.$uri;

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. Static files are served here with explicit cache
// headers so Cloudflare (and browsers) can cache them instead of hitting PHP.
if ($uri !== '/' && is_file($file)) {
    $mimeTypes = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'mjs' => 'application/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'xml' => 'application/xml; charset=utf-8',
        'txt' => 'text/plain; charset=utf-8',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if (! isset($mimeTypes[$extension])) {
        return false;
    }

    // Vite assets are content-hashed: cache forever. Everything else: 1 week
    // (robots.txt / sitemap.xml: 1 hour so SEO changes propagate quickly).
    if (str_starts_with($uri, '/build/assets/')) {
        $cacheControl = 'public, max-age=31536000, immutable';
    } elseif (in_array($extension, ['xml', 'txt', 'json'], true)) {
        $cacheControl = 'public, max-age=3600';
    } else {
        $cacheControl = 'public, max-age=604800';
    }

    $lastModified = filemtime($file);
    $etag = '"'.dechex($lastModified).'-'.dechex(filesize($file)).'"';

    header('Content-Type: '.$mimeTypes[$extension]);
    header('Cache-Control: '.$cacheControl);
    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastModified).' GMT');
    header('ETag: '.$etag);

    if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? null) === $etag) {
        http_response_code(304);

        return true;
    }

    header('Content-Length: '.filesize($file));

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
        readfile($file);
    }

    return true;
}

require_once __DIR__.'/public/index.php';
