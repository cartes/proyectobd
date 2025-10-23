<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfilePhotoController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validar que pueda subir más fotos
        if (!$user->canUploadMorePhotos()) {
            return back()->withErrors(['photo' => 'Has alcanzado el límite máximo de ' . ProfilePhoto::MAX_PHOTOS . ' fotos.']);
        }

        // Validación
        $request->validate([
            'photo' => [
                'required',
                'image',
                'mimes:' . implode(',', ProfilePhoto::ALLOWED_TYPES),
                'max:' . ProfilePhoto::MAX_FILE_SIZE,
            ],
        ], [
            'photo.required' => 'Debes seleccionar una foto',
            'photo.image' => 'El archivo debe ser una imagen',
            'photo.mimes' => 'Solo se permiten fotos en formato: ' . implode(', ', ProfilePhoto::ALLOWED_TYPES),
            'photo.max' => 'La foto no debe superar 5MB',
        ]);

        try {
            // Guardar archivo
            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("profiles/{$user->id}", $filename, 'public');

            // Crear registro
            $photo = $user->photos()->create([
                'photo_path' => $path,
                'order' => $user->photos()->count(),
                'moderation_status' => 'approved', // Auto-aprobar por ahora
            ]);

            // Si es la primera foto, hacerla principal automáticamente
            if ($user->photos()->count() === 1) {
                $photo->setAsPrimary();
            }

            return back()->with('success', '¡Foto subida exitosamente! 📸');

        } catch (\Exception $e) {
            return back()->withErrors(['photo' => 'Error al subir la foto: ' . $e->getMessage()]);
        }
    }

    /**
     * Establecer foto principal
     */
    public function setPrimary(ProfilePhoto $photo)
    {
        $user = Auth::user();

        // Verificar que la foto pertenece al usuario
        if ($photo->user_id !== $user->id) {
            abort(403, 'No autorizado');
        }

        $photo->setAsPrimary();

        return back()->with('success', '¡Foto principal actualizada! ⭐');
    }

    /**
     * Reordenar las imagenes
     */
    public function reorder(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:profile_photos,id',
        ]);

        foreach ($request->order as $index => $photoId) {
            $photo = ProfilePhoto::find($photoId);
            if ($photo && $photo->user_id === $user->id) {
                $photo->update(['order' => $index]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Eliminar foto
     */
    public function destroy(ProfilePhoto $photo)
    {
        $user = Auth::user();

        // Verificar que la foto pertenece al usuario
        if ($photo->user_id !== $user->id) {
            abort(403, 'No autorizado');
        }

        try {
            // Elminiar archivo fisico
            if (Storage::disk('public')->exists($photo->photo_path)) {
                Storage::disk('public')->delete($photo->photo_path);
            }

            // Si erea la foto principal, establecer otra como principal
            if ($photo->is_primary) {
                $nextPhoto = $user->photos()->where('id', '!=', $photo->id)->first();

                if ($nextPhoto) {
                    $nextPhoto->setAsPrimary();
                }
            }

            // Eliminar registro
            $photo->delete();

            // Reordenar las fotos restantes
            $user->photos()->ordered()->get()->each(function ($p, $index) {
                $p->update(['order' => $index]);
            });

            return back()->with('success', '¡Foto eliminada correctamente! 🗑️');
        } catch (\Exception $e) {
            return back()->withErrors(['photo' => 'Error al eliminar la foto: ' . $e->getMessage()]);
        }
    }
}
