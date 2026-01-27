<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class ContentModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profileDetail')->whereHas('profileDetail')->whereNotNull('bio');

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $users = $query->latest()->paginate(20);

        return view('admin.moderation.content.index', compact('users'));
    }

    public function approve(User $user)
    {
        // Mark profile as reviewed/approved
        // $user->profileDetail->update(['moderation_status' => 'approved']);

        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action_type' => 'content_approve',
            'auditable_id' => $user->id,
            'auditable_type' => User::class,
            'new_values' => ['status' => 'approved'],
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Propuesta de perfil aprobada.');
    }

    public function reject(Request $request, User $user)
    {
        $request->validate(['reason' => 'required|string']);

        AdminAuditLog::create([
            'admin_id' => auth()->id(),
            'action_type' => 'content_reject',
            'auditable_id' => $user->id,
            'auditable_type' => User::class,
            'new_values' => ['status' => 'rejected', 'reason' => $request->reason],
            'reason' => $request->reason,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Propuesta de perfil rechazada y marcada para revisión.');
    }
}
