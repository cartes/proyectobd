<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageController extends Controller
{
    /**
     * Serve profile photos with privacy checks.
     */
    public function showProfilePhoto(string $hash, string $file): BinaryFileResponse
    {
        $path = "profiles/{$hash}/{$file}";

        \Log::info('StorageController: showProfilePhoto attempting to serve file', [
            'requested_path' => $path,
            'storage_public_path' => Storage::disk('public')->path($path),
            'storage_public_exists' => Storage::disk('public')->exists($path),
        ]);

        // Try standard Laravel path first
        if (Storage::disk('public')->exists($path)) {
            \Log::info('StorageController: Profile photo found in public disk', ['path' => $path]);

            return response()->file(Storage::disk('public')->path($path));
        }

        // Fallback 1: Directly in storage/ (Sometimes volumes are mounted differently)
        $fallback1 = storage_path($path);
        \Log::info('StorageController: Profile photo trying fallback 1', [
            'path' => $fallback1,
            'exists' => file_exists($fallback1),
        ]);
        if (file_exists($fallback1)) {
            return response()->file($fallback1);
        }

        // Fallback 2: in storage/app/ (Sometimes people skip the 'public' subfolder)
        $fallback2 = storage_path('app/'.$path);
        \Log::info('StorageController: Profile photo trying fallback 2', [
            'path' => $fallback2,
            'exists' => file_exists($fallback2),
        ]);
        if (file_exists($fallback2)) {
            return response()->file($fallback2);
        }

        // Fallback 3: in storage/app/public/ (Another common structure)
        $fallback3 = storage_path('app/public/'.$path);
        \Log::info('StorageController: Profile photo trying fallback 3', [
            'path' => $fallback3,
            'exists' => file_exists($fallback3),
        ]);
        if (file_exists($fallback3)) {
            return response()->file($fallback3);
        }

        // Fallback 4: /storage/app/public/ (Railway volume mount point)
        $fallback4 = '/storage/app/public/'.$path;
        \Log::info('StorageController: Profile photo trying fallback 4 (Railway volume)', [
            'path' => $fallback4,
            'exists' => file_exists($fallback4),
        ]);
        if (file_exists($fallback4)) {
            return response()->file($fallback4);
        }

        \Log::error('StorageController: Profile photo not found in any location', [
            'requested_path' => $path,
            'checked_paths' => [
                Storage::disk('public')->path($path),
                $fallback1,
                $fallback2,
                $fallback3,
                $fallback4,
            ],
        ]);

        abort(404);
    }

    /**
     * Serve other public storage files (blog images, etc.)
     */
    public function showPublicFile(string $path): BinaryFileResponse
    {
        \Log::info('StorageController: Attempting to serve file', [
            'requested_path' => $path,
            'storage_public_path' => Storage::disk('public')->path($path),
            'storage_public_exists' => Storage::disk('public')->exists($path),
        ]);

        // Try standard Laravel path first
        if (Storage::disk('public')->exists($path)) {
            \Log::info('StorageController: File found in public disk', ['path' => $path]);

            return response()->file(Storage::disk('public')->path($path));
        }

        // Fallback 1: Directly in storage/ (Railway volumes sometimes mount here)
        $fallback1 = storage_path($path);
        \Log::info('StorageController: Trying fallback 1', [
            'path' => $fallback1,
            'exists' => file_exists($fallback1),
        ]);

        if (file_exists($fallback1)) {
            \Log::info('StorageController: File found in fallback 1');

            return response()->file($fallback1);
        }

        // Fallback 2: in storage/app/public/ (Standard Laravel structure)
        $fallback2 = storage_path('app/public/'.$path);
        \Log::info('StorageController: Trying fallback 2', [
            'path' => $fallback2,
            'exists' => file_exists($fallback2),
        ]);

        if (file_exists($fallback2)) {
            \Log::info('StorageController: File found in fallback 2');

            return response()->file($fallback2);
        }

        // Additional Railway-specific fallback
        $fallback3 = base_path('storage/app/public/'.$path);
        \Log::info('StorageController: Trying fallback 3 (base_path)', [
            'path' => $fallback3,
            'exists' => file_exists($fallback3),
        ]);

        if (file_exists($fallback3)) {
            \Log::info('StorageController: File found in fallback 3');

            return response()->file($fallback3);
        }

        // Railway volume mount fallback (Railway mounts volumes at /storage not /app/storage)
        $fallback4 = '/storage/app/public/'.$path;
        \Log::info('StorageController: Trying fallback 4 (Railway volume /storage)', [
            'path' => $fallback4,
            'exists' => file_exists($fallback4),
        ]);

        if (file_exists($fallback4)) {
            \Log::info('StorageController: File found in fallback 4 (Railway volume)');

            return response()->file($fallback4);
        }

        \Log::error('StorageController: File not found in any location', [
            'requested_path' => $path,
            'checked_paths' => [
                Storage::disk('public')->path($path),
                $fallback1,
                $fallback2,
                $fallback3,
                $fallback4,
            ],
        ]);

        abort(404);
    }
}
