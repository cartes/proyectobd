<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\ModerationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ModerationService $moderationService
    ) {}

    // Crear reporte desde el chat
    public function reportMessage(Request $request, Message $message)
    {
        $request->validate([
            'reason' => 'required|in:profanity,harassment,inappropriate,spam,other',
            'description' => 'nullable|string|max:500',
        ]);

        $report = $this->moderationService->createReport(
            reporterId: auth()->id(),
            reportedUserId: $message->sender_id,
            type: 'message',
            id: $message->id,
            reason: $request->reason,
            description: $request->description
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Reporte enviado exitosamente',
        ]);
    }

    // Reportar conversación
    public function reportConversation(Request $request, Conversation $conversation)
    {
        $request->validate([
            'reason' => 'required|in:profanity,harassment,inappropriate,spam,other',
            'description' => 'nullable|string|max:500',
        ]);

        $reportedUserId = $conversation->user_one_id === auth()->id()
            ? $conversation->user_two_id
            : $conversation->user_one_id;

        $report = $this->moderationService->createReport(
            reporterId: auth()->id(),
            reportedUserId: $reportedUserId,
            type: 'conversation',
            id: $conversation->id,
            reason: $request->reason,
            description: $request->description
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Conversación reportada',
        ]);
    }

    // Reportar usuario
    public function reportUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|in:profanity,harassment,inappropriate,spam,other',
            'description' => 'nullable|string|max:500',
        ]);

        $report = $this->moderationService->createReport(
            reporterId: auth()->id(),
            reportedUserId: $request->user_id,
            type: 'user',
            id: $request->user_id,
            reason: $request->reason,
            description: $request->description
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario reportado',
        ]);
    }
}
