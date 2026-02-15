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
        $currentUser = function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'unknown';

        \Log::info('StorageController@showProfilePhoto: Entry', [
            'path' => $path,
            'user' => $currentUser,
        ]);

        $searchPaths = [
            Storage::disk('public')->path($path),
            storage_path('app/public/'.$path),
            storage_path($path),
            base_path('storage/app/public/'.$path),
            '/storage/app/public/'.$path,
            '/storage/'.$path,
        ];

        foreach ($searchPaths as $index => $fullPath) {
            $exists = file_exists($fullPath);
            $readable = $exists ? is_readable($fullPath) : false;

            \Log::info("StorageController@showProfilePhoto: Checking path #{$index}", [
                'fullPath' => $fullPath,
                'exists' => $exists,
                'readable' => $readable,
            ]);

            if ($exists && $readable) {
                \Log::info("StorageController@showProfilePhoto: SUCCESS at path #{$index}");

                return response()->file($fullPath);
            }
        }

        \Log::error('StorageController@showProfilePhoto: FAILURE - File not found or not readable', [
            'path' => $path,
            'checked' => $searchPaths,
        ]);

        abort(404);
    }

    /**
     * Serve other public storage files (blog images, etc.)
     */
    public function showPublicFile(string $path): BinaryFileResponse
    {
        $currentUser = function_exists('posix_getpwuid') ? posix_getpwuid(posix_geteuid())['name'] : 'unknown';

        \Log::info('StorageController: Entry', [
            'path' => $path,
            'user' => $currentUser,
            'base_path' => base_path(),
            'storage_path' => storage_path(),
        ]);

        $searchPaths = [
            Storage::disk('public')->path($path),
            storage_path('app/public/'.$path),
            storage_path($path),
            base_path('storage/app/public/'.$path),
            '/storage/app/public/'.$path,
            '/storage/'.$path,
        ];

        foreach ($searchPaths as $index => $fullPath) {
            $exists = file_exists($fullPath);
            $readable = $exists ? is_readable($fullPath) : false;

            \Log::info("StorageController: Checking path #{$index}", [
                'fullPath' => $fullPath,
                'exists' => $exists,
                'readable' => $readable,
            ]);

            if ($exists && $readable) {
                \Log::info("StorageController: SUCCESS at path #{$index}");

                return response()->file($fullPath);
            }
        }

        \Log::error('StorageController: FAILURE - File not found or not readable', [
            'path' => $path,
            'checked' => $searchPaths,
        ]);

        abort(404);
    }
}
