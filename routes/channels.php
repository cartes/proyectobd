<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

// Canal de notificaciones del usuario
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal privado de conversación: solo los dos participantes pueden suscribirse
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (! $conversation) {
        return false;
    }

    return $conversation->user_one_id === $user->id
        || $conversation->user_two_id === $user->id;
});
