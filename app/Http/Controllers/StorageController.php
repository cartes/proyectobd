<?php

namespace App\Http\Controllers;

use App\Models\ProfilePhoto;
use App\Models\User;
use Illuminate\Http\Request;
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
        $absolutePath = Storage::disk('public')->path($path);

        \Log::info("StorageController: Request for $path", [
            'absolute_path' => $absolutePath,
            'exists' => Storage::disk('public')->exists($path)
        ]);

        if (!Storage::disk('public')->exists($path)) {
            $parentDir = dirname($path);
            $filesInDir = Storage::disk('public')->files($parentDir);
            \Log::warning("StorageController: File not found at $path", [
                'parent_dir' => $parentDir,
                'files_in_parent' => $filesInDir
            ]);
            abort(404);
        }

        $photo = ProfilePhoto::where('photo_path', $path)->first();

        if ($photo) {
            $owner = $photo->user;
            $authUser = Auth::user();

            \Log::info("StorageController: Photo found. Owner ID: " . ($owner->id ?? 'N/A') . ", Auth User ID: " . ($authUser->id ?? 'Guest'));

            // Check privacy: profile is private and user is not owner/match/admin
            if ($owner->profileDetail?->is_private) {
                \Log::info("StorageController: Profile is private.");
                $isOwner = $authUser && $authUser->id === $owner->id;
                $isAdmin = $authUser && $authUser->isAdmin();
                $hasMatch = $authUser && $authUser->hasMatchWith($owner);

                if (!$isOwner && !$isAdmin && !$hasMatch) {
                    \Log::warning("StorageController: Unauthorized access to private profile photo.");
                    abort(403, 'Este perfil es privado.');
                }
            } else {
                \Log::info("StorageController: Profile is public.");
            }
        } else {
            \Log::warning("StorageController: No ProfilePhoto record found for path $path");
        }

        return response()->file($absolutePath);
    }

    /**
     * Serve other public storage files (blog images, etc.)
     */
    public function showPublicFile(string $path): BinaryFileResponse
    {
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($path));
    }

    /**
     * Debugging: List all files in public storage.
     */
    public function listFiles()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403);
        }

        $allFiles = Storage::disk('public')->allFiles();
        return response()->json([
            'root' => Storage::disk('public')->path(''),
            'files' => $allFiles
        ]);
    }
}
