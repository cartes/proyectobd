<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\ModerationService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private ModerationService $moderationService
    ) {}

    /**
     * Listado de conversaciones del usuario autenticado
     */
    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::forUser($userId)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) use ($userId) {
                $otherUser = $conversation->getOtherUser($userId);
                $conversation->other_user = $otherUser;
                $conversation->unread_count = $conversation->unreadCount($userId);

                return $conversation;
            });

        return view('chat.index', compact('conversations'));
    }

    /**
     * Mostrar conversación específica
     */
    public function show(Conversation $conversation)
    {
        $userId = auth()->id();

        // Verificar que el usuario pertenece a la conversación
        abort_unless(
            $conversation->user_one_id === $userId ||
            $conversation->user_two_id === $userId,
            403,
            'No tienes acceso a esta conversación'
        );

        $otherUser = $conversation->getOtherUser($userId);

        // USAR TU MÉTODO hasMatchWith
        if (! auth()->user()->hasMatchWith($otherUser)) {
            return redirect()->route('chat.index')
                ->with('error', 'No puedes chatear con este usuario sin un match.');
        }

        // Verificar si está bloqueada
        if ($conversation->is_blocked) {
            return redirect()->route('chat.index')
                ->with('error', 'Esta conversación está bloqueada.');
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->get();

        // Marcar mensajes como leídos
        $this->markMessagesAsRead($conversation);

        return view('chat.show', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Enviar mensaje en una conversación
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'type' => 'sometimes|in:text,image,emoji',
        ]);

        $userId = auth()->id();
        $currentUser = auth()->user();

        abort_unless(
            $conversation->user_one_id === $userId ||
            $conversation->user_two_id === $userId,
            403
        );

        $receiverId = $conversation->user_one_id === $userId
            ? $conversation->user_two_id
            : $conversation->user_one_id;

        $receiver = User::find($receiverId);

        if (! $currentUser->hasMatchWith($receiver)) {
            return response()->json(['error' => 'No hay match con este usuario'], 403);
        }

        if ($conversation->is_blocked) {
            return response()->json(['error' => 'Conversación bloqueada'], 403);
        }

        // ✅ ESCANEAR MENSAJE
        $scanResult = $this->moderationService->scanMessage($request->content);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'content' => $request->content,
            'type' => $request->type ?? 'text',
        ]);

        // ✅ PROCESAR SI ESTÁ FLAGGED
        if ($scanResult['is_flagged']) {
            $this->moderationService->processMessage($message, $scanResult);
            $message->refresh();
        }

        $conversation->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message->load('sender')))->toOthers();

        return response()->json([
            'message' => $message->load('sender'),
            'flagged' => $scanResult['is_flagged'],
            'severity' => $scanResult['severity_level'],
        ]);
    }

    /**
     * Crear o encontrar conversación y enviar mensaje
     */
    public function createOrFind(User $user)
    {
        $currentUser = auth()->user();

        // No permitir chat con uno mismo
        if ($user->id === $currentUser->id) {
            return redirect()->route('chat.index')
                ->with('error', 'No puedes iniciar un chat contigo mismo.');
        }

        // VALIDAR QUE EXISTE MATCH usando tu método
        if (! $currentUser->hasMatchWith($user)) {
            return redirect()->route('matches.index')
                ->with('error', '❌ Necesitas hacer match con este usuario para poder chatear.');
        }

        // ⛔ Límite de conversaciones para Sugar Daddies free
        $existingConversation = Conversation::where(function ($query) use ($user, $currentUser) {
            $query->where('user_one_id', $currentUser->id)
                ->where('user_two_id', $user->id);
        })->orWhere(function ($query) use ($user, $currentUser) {
            $query->where('user_one_id', $user->id)
                ->where('user_two_id', $currentUser->id);
        })->first();

        if (! $existingConversation && ! $currentUser->canStartConversation()) {
            return redirect()->route('matches.index')
                ->with('limit', '🔒 Con el plan gratuito solo puedes chatear con '.\App\Models\User::FREE_DADDY_CONVERSATION_LIMIT.' Sugar Baby. ¡Hazte Premium para mensajes ilimitados!');
        }

        $conversation = $existingConversation;

        // Crear nueva conversación si no existe
        if (! $conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $currentUser->id,
                'user_two_id' => $user->id,
                'last_message_at' => now(),
            ]);

            // Crear mensaje de bienvenida automático
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $currentUser->id,
                'receiver_id' => $user->id,
                'content' => '¡Hola! 👋 Es genial que hayamos hecho match.',
                'type' => 'system',
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Marcar mensajes como leídos
     */
    private function markMessagesAsRead(Conversation $conversation)
    {
        $unreadMessages = $conversation->messages()
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $message) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            broadcast(new MessageRead(
                $conversation->id,
                $message->id,
                auth()->id()
            ))->toOthers();
        }
    }

    /**
     * Marcar mensaje individual como leído
     */
    public function markAsRead(Conversation $conversation, Message $message)
    {
        abort_unless($message->receiver_id === auth()->id(), 403);

        $message->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        broadcast(new MessageRead(
            $conversation->id,
            $message->id,
            auth()->id()
        ))->toOthers();

        return response()->json(['status' => 'success']);
    }

    /**
     * Bloquear conversación
     */
    public function blockConversation(Conversation $conversation)
    {
        abort_unless(
            $conversation->user_one_id === auth()->id() ||
            $conversation->user_two_id === auth()->id(),
            403
        );

        $conversation->update([
            'is_blocked' => true,
            'blocked_by' => auth()->id(),
        ]);

        return response()->json(['status' => 'blocked']);
    }
}
