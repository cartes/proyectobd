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

        \Log::info("StorageController: Request for $path");

        if (!Storage::disk('public')->exists($path)) {
            \Log::warning("StorageController: File not found at $path");
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
            // If it's in storage but no DB record, we might still want to serve it or not.
            // But if it's a profile photo, lack of record is suspicious.
        }

        return response()->file(Storage::disk('public')->path($path));
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
}
