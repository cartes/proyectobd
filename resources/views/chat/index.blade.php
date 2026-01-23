@extends('layouts.mobile-app')

@section('page-title', 'Mensajes')

@section('content')
    <div class="min-h-screen py-10" style="background: var(--theme-gradient-deep);">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Header Premium -->
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter drop-shadow-lg">
                        Mensajes
                    </h1>
                    <p class="text-white/60 mt-2 font-bold uppercase tracking-widest text-[10px]">Tus conexiones activas</p>
                </div>
            </div>

            <!-- Conversaciones -->
            <div class="glass-card rounded-[3rem] overflow-hidden border border-white/10 shadow-2xl">
                @forelse($conversations as $conversation)
                    <a href="{{ route('chat.show', $conversation) }}"
                        class="flex items-center p-6 border-b border-white/5 hover:bg-white/5 transition-all duration-300 group">

                        <!-- Avatar Premium -->
                        <div class="relative flex-shrink-0">
                            <x-user-avatar :user="$conversation->other_user" size="lg"
                                class="rounded-2xl group-hover:scale-110 transition-transform duration-500 ring-2 ring-white/10" />

                            @if ($conversation->unread_count > 0)
                                <span
                                    class="absolute -top-2 -right-2 bg-rose-500 text-white text-[10px] font-black rounded-full w-6 h-6 flex items-center justify-center shadow-xl border-2 border-white animate-bounce">
                                    {{ $conversation->unread_count }}
                                </span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="ml-6 flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3
                                    class="text-xl font-black text-gray-900 theme-sd:text-white truncate tracking-tight uppercase">
                                    {{ $conversation->other_user->name }}
                                </h3>
                                @if ($conversation->latestMessage)
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">
                                        {{ $conversation->latestMessage->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                                {{ strtoupper($conversation->other_user->user_type) }} • {{ $conversation->other_user->city }}
                            </p>

                            @if ($conversation->latestMessage)
                                <p
                                    class="text-sm text-gray-400 theme-sd:text-gray-500 truncate group-hover:text-gray-200 transition-colors">
                                    @if ($conversation->latestMessage->sender_id === auth()->id())
                                        <span class="font-black text-xs uppercase tracking-widest text-white/30 mr-1">Tú:</span>
                                    @endif
                                    {{ $conversation->latestMessage->content }}
                                </p>
                            @endif
                        </div>

                        <!-- Indicador de nuevo mensaje Premium -->
                        @if ($conversation->unread_count > 0)
                            <div class="ml-6 flex-shrink-0">
                                <div class="w-3 h-3 rounded-full shadow-[0_0_15px_rgba(255,51,102,1)]"
                                    style="background: var(--theme-primary);"></div>
                            </div>
                        @else
                            <div class="ml-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        @endif
                    </a>
                @empty
                    <!-- Empty State Premium -->
                    <div class="p-20 text-center">
                        <div
                            class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                            <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-3xl font-black text-white uppercase tracking-tighter mb-4">Silencio absoluto...</h3>
                        <p class="text-white/40 font-bold mb-10 max-w-sm mx-auto">Tus próximas conexiones están a un "Like" de
                            distancia. Empieza a descubrir perfiles increíbles.</p>
                        <a href="{{ route('discover.index') }}" class="theme-btn px-10 py-5">
                            Comenzar a Explorar
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection