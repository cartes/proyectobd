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
                        <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
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
                                            <span class="ml-1">
                                                @if ($message->is_read)
                                                    ✓✓
                                                @else
                                                    ✓
                                                @endif
                                            </span>
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

                initChat() {
                    // Auto-scroll al último mensaje
                    this.scrollToBottom();

                    // Simular websocket (placeholder para Laravel Reverb)
                    // En producción, usar Reverb para actualizaciones en tiempo real
                },

                sendMessage() {
                    if (!this.message.trim()) return;

                    const currentMessage = this.message;
                    this.message = '';

                    fetch(`/chat/${this.conversationId}/send`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                content: currentMessage
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // El mensaje se agregar automáticamente con Livewire o recargar
                            location.reload();
                        });
                },

                notifyTyping() {
                    // Enviar notificación de escritura (para websockets)
                    // fetch(`/chat/${this.conversationId}/typing`);
                },

                resetTypingTimeout() {
                    clearTimeout(this.typingTimeout);
                    this.typingTimeout = setTimeout(() => {
                        // Notificar que dejó de escribir
                    }, 1000);
                },

                insertEmoji(event) {
                    const emoji = event.target.value;
                    this.message += emoji;
                    event.target.value = '';
                },

                scrollToBottom() {
                    const container = document.getElementById('messages-container');
                    container.scrollTop = container.scrollHeight;
                }
            }
        }
    </script>
@endpush