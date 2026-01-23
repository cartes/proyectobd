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
    public function store(\App\Http\Requests\StorePhotoRequest $request)
    {
        $user = Auth::user();

        try {
            // Guardar archivo
            $file = $request->file('photo');
            // ✅ USAR extension() EN LUGAR DE getClientOriginalExtension() PARA MAYOR SEGURIDAD
            $extension = $file->extension();
            $filename = Str::uuid() . '.' . $extension;

            // ✅ USAR RUTA OFUSCADA
            $path = $file->storeAs($user->getStoragePath(), $filename, 'public');

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
            // ✅ ASEGURAR QUE LA FOTO PERTENECE AL USUARIO ANTES DE ACTUALIZAR
            $photo = ProfilePhoto::where('id', $photoId)
                ->where('user_id', $user->id)
                ->first();

            if ($photo) {
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
