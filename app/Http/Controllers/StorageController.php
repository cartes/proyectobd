<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    /**
     * Browser/CDN cache lifetime for served media (7 days).
     */
    protected const CACHE_MAX_AGE = 604800;

    /**
     * Serve profile photos with privacy checks.
     */
    public function showProfilePhoto(Request $request, string $hash, string $file): Response
    {
        // Private cache: browsers may cache it, but Cloudflare must not keep serving
        // photos after they are rejected by moderation or the profile goes private.
        return $this->serve($request, "profiles/{$hash}/{$file}", public: false);
    }

    /**
     * Serve other public storage files (blog images, etc.)
     */
    public function showPublicFile(Request $request, string $path): Response
    {
        return $this->serve($request, $path);
    }

    /**
     * Locate the file in the storage volume and stream it with cache headers.
     */
    protected function serve(Request $request, string $path, bool $public = true): Response
    {
        if (str_contains($path, '..')) {
            abort(404);
        }

        $fullPath = $this->resolvePath($path);

        if (! $fullPath) {
            Log::warning('StorageController: file not found', ['path' => $path]);

            abort(404);
        }

        $response = new BinaryFileResponse($fullPath);
        $public ? $response->setPublic() : $response->setPrivate();
        $response->setMaxAge($public ? self::CACHE_MAX_AGE : 86400);
        $response->isNotModified($request);

        return $response;
    }

    protected function resolvePath(string $path): ?string
    {
        $searchPaths = [
            Storage::disk('public')->path($path),
            storage_path('app/public/'.$path),
            storage_path($path),
            '/storage/app/public/'.$path,
            '/storage/'.$path,
        ];

        foreach ($searchPaths as $fullPath) {
            if (is_file($fullPath) && is_readable($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }
}
