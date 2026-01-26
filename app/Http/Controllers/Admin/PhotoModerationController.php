<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilePhoto;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;

class PhotoModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = ProfilePhoto::with('user')->where('moderation_status', 'pending');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $photos = $query->latest()->paginate(24);

        return view('admin.moderation.photos.index', compact('photos'));
    }

    public function approve(ProfilePhoto $photo)
    {
        $oldStatus = $photo->moderation_status;
        $photo->update(['moderation_status' => 'approved']);

        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action_type' => 'photo_approve',
            'auditable_id' => $photo->id,
            'auditable_type' => ProfilePhoto::class,
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => 'approved'],
            'ip_address' => request()->ip()
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto aprobada Correctamente',
                'status' => 'approved'
            ]);
        }

        return redirect()->back()->with('success', 'Foto aprobada correctamente.');
    }

    public function reject(Request $request, ProfilePhoto $photo)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $oldStatus = $photo->moderation_status;
        $photo->update([
            'moderation_status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action_type' => 'photo_reject',
            'auditable_id' => $photo->id,
            'auditable_type' => ProfilePhoto::class,
            'old_values' => ['status' => $oldStatus],
            'new_values' => [
                'status' => 'rejected',
                'reason' => $request->reason
            ],
            'reason' => $request->reason,
            'ip_address' => request()->ip()
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto rechazada exitosamente',
                'status' => 'rejected'
            ]);
        }

        return redirect()->back()->with('success', 'Foto rechazada.');
    }
}
