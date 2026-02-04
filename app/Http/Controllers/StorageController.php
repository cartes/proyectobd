<?php

namespace App\Http\Controllers;

use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        // Try standard Laravel path first
        if (Storage::disk('public')->exists($path)) {
            return response()->file(Storage::disk('public')->path($path));
        }

        // Fallback 1: Directly in storage/ (Sometimes volumes are mounted differently)
        $fallback1 = storage_path($path);
        if (file_exists($fallback1)) {
            return response()->file($fallback1);
        }

        // Fallback 2: in storage/app/ (Sometimes people skip the 'public' subfolder)
        $fallback2 = storage_path('app/'.$path);
        if (file_exists($fallback2)) {
            return response()->file($fallback2);
        }

        abort(404);
    }

    /**
     * Serve other public storage files (blog images, etc.)
     */
    public function showPublicFile(string $path): BinaryFileResponse
    {
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($path));
    }
}
