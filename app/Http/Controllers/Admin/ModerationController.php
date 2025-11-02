<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\User;
use App\Models\UserAction;
use App\Models\BlockedWord;
use App\Services\ModerationService;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function __construct(
        private ModerationService $moderationService
    ) {
    }

    // Dashboard principal
    public function dashboard()
    {
        $stats = [
            'pending_reports' => Report::pending()->count(),
            'total_reports' => Report::count(),
            'active_bans' => UserAction::bans()->count(),
            'active_suspensions' => UserAction::suspensions()->count(),
            'total_users' => User::count(),
            'blocked_words' => BlockedWord::active()->count(),
        ];

        $recentReports = Report::with(['reporter', 'reportedUser', 'message'])
            ->pending()
            ->latest()
            ->take(10)
            ->get();

        $recentActions = UserAction::with(['user', 'initiatedBy'])
            ->active()
            ->latest()
            ->take(10)
            ->get();

        return view('admin.moderation.dashboard', compact('stats', 'recentReports', 'recentActions'));
    }

    // Listar reportes
    public function reports(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'message', 'conversation'])
            ->latest();

        // Filtros
        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->reason) {
            $query->where('reason', $request->reason);
        }

        $reports = $query->paginate(20);

        return view('admin.moderation.reports', compact('reports'));
    }

    // Ver detalle de reporte
    public function showReport(Report $report)
    {
        $report->load(['reporter', 'reportedUser', 'message.conversation', 'reviewedBy']);

        // Historial del usuario reportado
        $userHistory = Report::where('reported_user_id', $report->reported_user_id)
            ->with('reporter')
            ->latest()
            ->take(10)
            ->get();

        $userActions = UserAction::where('user_id', $report->reported_user_id)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.moderation.report-detail', compact('report', 'userHistory', 'userActions'));
    }

    // Procesar reporte
    public function processReport(Request $request, Report $report)
    {
        $request->validate([
            'action' => 'required|in:dismiss,warn,suspend,ban',
            'notes' => 'nullable|string|max:1000',
            'days' => 'required_if:action,suspend|integer|min:1|max:365',
        ]);

        $adminId = auth()->id();

        // Ejecutar acción
        switch ($request->action) {
            case 'dismiss':
                $report->update([
                    'status' => 'dismissed',
                    'reviewed_by' => $adminId,
                    'admin_notes' => $request->notes,
                    'reviewed_at' => now(),
                ]);
                break;

            case 'warn':
                $this->moderationService->warnUser(
                    $report->reported_user_id,
                    "Admin warning: {$request->notes}"
                );
                $report->markAsReviewed($adminId, $request->notes);
                break;

            case 'suspend':
                $this->moderationService->suspendUser(
                    $report->reported_user_id,
                    $request->days,
                    "Admin suspension: {$request->notes}"
                );
                $report->update([
                    'status' => 'resolved',
                    'reviewed_by' => $adminId,
                    'admin_notes' => $request->notes,
                    'reviewed_at' => now(),
                ]);
                break;

            case 'ban':
                $this->moderationService->banUser(
                    $report->reported_user_id,
                    "Admin ban: {$request->notes}",
                    $adminId
                );
                $report->update([
                    'status' => 'resolved',
                    'reviewed_by' => $adminId,
                    'admin_notes' => $request->notes,
                    'reviewed_at' => now(),
                ]);
                break;
        }

        return redirect()->route('admin.moderation.reports')
            ->with('success', 'Reporte procesado exitosamente');
    }

    // Gestión de palabras bloqueadas
    public function blockedWords()
    {
        $words = BlockedWord::orderBy('severity', 'desc')
            ->orderBy('word')
            ->paginate(50);

        return view('admin.moderation.blocked-words', compact('words'));
    }

    // Agregar palabra bloqueada
    public function storeBlockedWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string|unique:blocked_words,word',
            'severity' => 'required|in:low,medium,high',
        ]);

        BlockedWord::create([
            'word' => strtolower(trim($request->word)),
            'severity' => $request->severity,
            'is_active' => true,
        ]);

        return redirect()->route('admin.moderation.blocked-words')
            ->with('success', 'Palabra bloqueada agregada');
    }

    // Eliminar palabra bloqueada
    public function destroyBlockedWord(BlockedWord $word)
    {
        $word->delete();

        return redirect()->route('admin.moderation.blocked-words')
            ->with('success', 'Palabra eliminada');
    }

    // Gestión de usuarios
    public function users(Request $request)
    {
        $query = User::withCount(['sentMessages', 'receivedMessages'])
            ->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->status === 'suspended') {
            $query->whereHas('actions', function ($q) {
                $q->where('action_type', 'suspension')->active();
            });
        } elseif ($request->status === 'banned') {
            $query->whereHas('actions', function ($q) {
                $q->where('action_type', 'ban')->active();
            });
        }

        $users = $query->paginate(20);

        return view('admin.moderation.users', compact('users'));
    }

    // Ver detalle de usuario
    public function showUser(User $user)
    {
        $user->loadCount(['sentMessages', 'receivedMessages']);

        $reports = Report::where('reported_user_id', $user->id)
            ->with('reporter')
            ->latest()
            ->paginate(10);

        $actions = UserAction::where('user_id', $user->id)
            ->with('initiatedBy')
            ->latest()
            ->paginate(10);

        return view('admin.moderation.user-detail', compact('user', 'reports', 'actions'));
    }

    // Acciones directas sobre usuarios
    public function userAction(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:warn,suspend,ban,unban',
            'reason' => 'required|string|max:500',
            'days' => 'required_if:action,suspend|integer|min:1|max:365',
        ]);

        $adminId = auth()->id();

        switch ($request->action) {
            case 'warn':
                $this->moderationService->warnUser($user->id, $request->reason);
                break;

            case 'suspend':
                $this->moderationService->suspendUser($user->id, $request->days, $request->reason);
                break;

            case 'ban':
                $this->moderationService->banUser($user->id, $request->reason, $adminId);
                break;

            case 'unban':
                UserAction::where('user_id', $user->id)
                    ->active()
                    ->update(['is_active' => false, 'resolved_at' => now()]);
                $user->update(['is_active' => true]);
                break;
        }

        return redirect()->back()
            ->with('success', 'Acción ejecutada exitosamente');
    }
}
