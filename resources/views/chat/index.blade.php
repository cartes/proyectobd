@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-purple-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent"
                    style="font-family: 'Playfair Display', serif;">
                    Mensajes
                </h1>
                <p class="text-gray-600 mt-2">Tus conversaciones activas</p>
            </div>

            <!-- Conversaciones -->
            <div
                class="bg-white rounded-3xl shadow-xl overflow-hidden @if ($conversations->isEmpty()) max-h-[500px] @endif">
                @forelse($conversations as $conversation)
                    <a href="{{ route('chat.show', $conversation) }}"
                        class="flex items-center p-4 border-b border-gray-100 hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition-all duration-300 group">

                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <!-- Esto maneja TODO: foto o iniciales -->
                            <x-user-avatar :user="$conversation->other_user" size="lg"
                                class="group-hover:scale-110 transition-transform" />

                            @if ($conversation->unread_count > 0)
                                <span
                                    class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                    {{ $conversation->unread_count }}
                                </span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="ml-4 flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    {{ $conversation->other_user->name }}
                                </h3>
                                @if ($conversation->latestMessage)
                                    <span class="text-xs text-gray-500">
                                        {{ $conversation->latestMessage->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-sm text-gray-600 capitalize">
                                {{ $conversation->other_user->user_type }} • {{ $conversation->other_user->city }}
                            </p>

                            @if ($conversation->latestMessage)
                                <p class="text-sm text-gray-500 truncate mt-1">
                                    @if ($conversation->latestMessage->sender_id === auth()->id())
                                        <span class="font-medium">Tú:</span>
                                    @endif
                                    {{ Str::limit($conversation->latestMessage->content, 50) }}
                                </p>
                            @endif
                        </div>

                        <!-- Indicador de nuevo mensaje -->
                        @if ($conversation->unread_count > 0)
                            <div class="ml-4 flex-shrink-0">
                                <div
                                    class="w-3 h-3 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full animate-pulse">
                                </div>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="p-16 text-center">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No tienes conversaciones</h3>
                        <p class="text-gray-500 mb-6">Empieza a conectar con otros usuarios</p>
                        <a href="{{ route('discover.index') }}"
                            class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full hover:shadow-lg transition-all">
                            Explorar perfiles
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
