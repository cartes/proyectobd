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

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $photo = ProfilePhoto::where('photo_path', $path)->first();

        if ($photo) {
            $owner = $photo->user;
            $authUser = Auth::user();

            // Check privacy: profile is private and user is not owner/match/admin
            if ($owner->profileDetail?->is_private) {
                $isOwner = $authUser && $authUser->id === $owner->id;
                $isAdmin = $authUser && $authUser->isAdmin();
                $hasMatch = $authUser && $authUser->hasMatchWith($owner);

                if (!$isOwner && !$isAdmin && !$hasMatch) {
                    abort(403, 'Este perfil es privado.');
                }
            }
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
