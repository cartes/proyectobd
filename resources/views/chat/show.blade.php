@extends( 'layouts.mobile-app' )

@section('page-title')
    Chat con {{ $otherUser->name }}
@endsection

@section('content')
    <div class="min-h-screen bg-gray-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Chat Header -->
            <div class="bg-white rounded-lg shadow mb-6 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <x-user-avatar :user="$otherUser" size="lg" />
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $otherUser->name }}</h2>
                            <p class="text-sm text-gray-600">
                                @if ($otherUser->is_active)
                                    <span class="text-green-600">● En línea</span>
                                @else
                                    <span class="text-gray-400">● Última vez
                                        {{ $otherUser->last_seen_at?->diffForHumans() }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('profile.show', $otherUser->id) }}"
                        class="text-pink-600 hover:text-pink-800 font-medium">
                        Ver Perfil →
                    </a>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="bg-white rounded-lg shadow overflow-hidden" x-data="chatApp()" @load="initChat()">

                <!-- Messages Area -->
                <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4 bg-gray-50">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                            <div
                                class="flex items-end space-x-2 max-w-xs {{ $message->sender_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                @if ($message->sender_id !== auth()->id())
                                    <x-user-avatar :user="$otherUser" size="sm" />
                                @endif

                                <div
                                    class="flex flex-col {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div
                                        class="px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-900' }}">
                                        <p class="break-words">{{ $message->content }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500 mt-1">
                                        {{ $message->created_at->format('H:i') }}
                                        @if ($message->sender_id === auth()->id())
                                            <span class="ml-1 read-indicator">{{ $message->is_read ? '✓✓' : '✓' }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>No hay mensajes aún. ¡Sé el primero en escribir!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Typing Indicator -->
                <div id="typing-indicator" class="hidden px-6 py-2 text-sm text-gray-600">
                    <span class="animate-pulse">{{ $otherUser->name }} está escribiendo...</span>
                </div>

                <!-- Input Area -->
                <div class="border-t border-gray-200 p-4 bg-white">
                    <form @submit.prevent="sendMessage()" class="flex items-end space-x-2">
                        <!-- Emoji Picker Button -->
                        <button type="button" @click="$refs.emojiPicker.click()"
                            class="text-xl hover:text-pink-600 transition-colors" title="Emojis">
                            😊
                        </button>

                        <!-- Hidden Emoji Input -->
                        <input type="hidden" x-ref="emojiPicker" @change="insertEmoji($event)" style="display: none;">

                        <!-- Message Input -->
                        <input type="text" x-model="message" @keyup="notifyTyping()" @input="resetTypingTimeout()"
                            placeholder="Escribe tu mensaje..."
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent">

                        <!-- Send Button -->
                        <button type="submit" :disabled="!message.trim()"
                            class="px-4 py-2 bg-pink-500 hover:bg-pink-600 disabled:bg-gray-400 text-white rounded-lg font-medium transition-colors">
                            Enviar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Volver a Conversaciones
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function chatApp() {
            return {
                message: '',
                typingTimeout: null,
                conversationId: {{ $conversation->id }},
                userId: {{ auth()->id() }},
                otherUserName: @js($otherUser->name),
                otherUserAvatar: @js($otherUser->profile_photo_url ?? '/images/default-avatar.png'),

                initChat() {
                    this.scrollToBottom();
                    this.listenForMessages();
                },

                // ─── Suscripción a Reverb ───────────────────────────────────
                listenForMessages() {
                    if (!window.Echo) {
                        console.warn('Echo no está disponible. Reverb puede no estar corriendo.');
                        return;
                    }

                    window.Echo.private(`conversation.${this.conversationId}`)
                        // Nuevo mensaje recibido del otro usuario
                        .listen('.message.sent', (e) => {
                            // Evitar duplicar si Echo rebota el propio mensaje
                            if (e.message.sender_id !== this.userId) {
                                this.addMessageToDOM(e.message, false);
                                this.scrollToBottom();

                                // Notificar al servidor que lo leímos
                                fetch(`/chat/${this.conversationId}/read/${e.message.id}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    },
                                });
                            }
                        })
                        // El otro usuario leyó nuestro mensaje
                        .listen('.message.read', (e) => {
                            const msgEl = document.querySelector(`[data-message-id="${e.message_id}"] .read-indicator`);
                            if (msgEl) {
                                msgEl.textContent = '✓✓';
                            }
                        });
                },

                // ─── Enviar mensaje ──────────────────────────────────────────
                sendMessage() {
                    const content = this.message.trim();
                    if (!content) return;

                    // UI optimista: añadir el mensaje propio inmediatamente
                    const optimisticMsg = {
                        id: `temp-${Date.now()}`,
                        sender_id: this.userId,
                        content: content,
                        is_read: false,
                        created_at: new Date().toISOString(),
                    };
                    this.addMessageToDOM(optimisticMsg, true);
                    this.message = '';
                    this.scrollToBottom();

                    fetch(`/chat/${this.conversationId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ content }),
                    })
                    .then(r => r.json())
                    .then(data => {
                        // Actualizar el id temporal por el real
                        const tempEl = document.querySelector(`[data-message-id="temp-${optimisticMsg.id.split('-')[1]}"]`);
                        if (tempEl && data.message) {
                            tempEl.dataset.messageId = data.message.id;
                        }
                    })
                    .catch(() => {
                        // Si falla, avisar al usuario
                        alert('No se pudo enviar el mensaje. Verifica tu conexión.');
                    });
                },

                // ─── Construir y añadir mensaje al DOM ───────────────────────
                addMessageToDOM(msg, isMine) {
                    const container = document.getElementById('messages-container');
                    const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    const readIndicator = isMine
                        ? `<span class="ml-1 read-indicator">${msg.is_read ? '✓✓' : '✓'}</span>`
                        : '';

                    const avatarHtml = !isMine
                        ? `<img src="${this.otherUserAvatar}" alt="${this.otherUserName}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">`
                        : '';

                    const wrapper = document.createElement('div');
                    wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
                    wrapper.dataset.messageId = msg.id;
                    wrapper.innerHTML = `
                        <div class="flex items-end space-x-2 max-w-xs ${isMine ? 'flex-row-reverse space-x-reverse' : ''}">
                            ${avatarHtml}
                            <div class="flex flex-col ${isMine ? 'items-end' : 'items-start'}">
                                <div class="px-4 py-2 rounded-lg ${isMine ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-900'}">
                                    <p class="break-words">${this.escapeHtml(msg.content)}</p>
                                </div>
                                <span class="text-xs text-gray-500 mt-1">
                                    ${time}${readIndicator}
                                </span>
                            </div>
                        </div>`;

                    container.appendChild(wrapper);
                },

                // ─── Typing indicator (preparado para cuando se active) ──────
                notifyTyping() {},
                resetTypingTimeout() {
                    clearTimeout(this.typingTimeout);
                },

                // ─── Helpers ─────────────────────────────────────────────────
                insertEmoji(event) {
                    this.message += event.target.value;
                    event.target.value = '';
                },

                scrollToBottom() {
                    const container = document.getElementById('messages-container');
                    container.scrollTop = container.scrollHeight;
                },

                escapeHtml(text) {
                    const div = document.createElement('div');
                    div.appendChild(document.createTextNode(text));
                    return div.innerHTML;
                },
            };
        }
    </script>
@endpush