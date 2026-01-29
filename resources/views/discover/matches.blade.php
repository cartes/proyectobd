@extends('layouts.mobile-app')

@section('page-title', 'Mis Matches')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-600 to-rose-600">
        {{-- Header --}}
        <div
            class="bg-gradient-to-r from-purple-700 via-pink-700 to-rose-700 text-white px-6 py-10 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="max-w-7xl mx-auto flex items-center gap-6 relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-xl">
                    <svg class="w-10 h-10 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter drop-shadow-xl leading-none mb-1"
                        style="font-family: 'Outfit', sans-serif;">
                        Mis Matches
                    </h1>
                    <p class="text-white/80 font-black uppercase tracking-widest text-sm italic">{{ $matches->total() }}
                        conexiones exitosas</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-12">

            @if ($matches->count() > 0)
                <!-- Grid de cards compactas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($matches as $profile)
                        <div
                            class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-pink-500 ring-4 ring-pink-200 hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2 relative">

                            {{-- Badge Match --}}
                            <div
                                class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-center py-2 px-4 font-bold text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-lg">
                                <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"
                                        clip-rule="evenodd" />
                                </svg>
                                ✨ Conexión Mutua
                            </div>

                            <div class="relative aspect-[3/4] mt-8 bg-gray-200 overflow-hidden">
                                @if ($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                        <span class="text-7xl font-bold text-white/40">
                                            {{ strtoupper(substr($profile->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info Content -->
                            <div class="p-6 bg-white relative">
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">
                                    {{ $profile->name }}, {{ $profile->age }}
                                </h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest flex items-center gap-1">
                                        <svg class="w-3 h-3 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $profile->city }}
                                    </p>

                                    @if ($profile->is_verified)
                                        <span
                                            class="flex items-center gap-1 text-[10px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-tighter">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Verificado
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Acciones -->
                            <div class="px-6 pb-6 pt-2 flex flex-col gap-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('profile.show', $profile) }}"
                                        class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 text-purple-700 font-black rounded-2xl text-center text-[10px] uppercase tracking-widest transition-all border border-purple-100 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Perfil
                                    </a>

                                    <a href="{{ route('chat.create', $profile) }}"
                                        class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-black rounded-2xl text-center text-[10px] uppercase tracking-widest transition-all shadow-md shadow-purple-200 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.855-1.246L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Chat
                                    </a>
                                </div>

                                <!-- Botones auxiliares -->
                                <div class="flex justify-between items-center mt-2 px-2">
                                    <form action="{{ route('matches.unmatch', $profile) }}" method="POST"
                                        onsubmit="return confirm('¿Deshacer match?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center gap-1 text-[9px] font-black text-red-500 uppercase tracking-widest hover:text-red-700 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Deshacer
                                        </button>
                                    </form>

                                    <button type="button" onclick="alert('Reportar a: {{ $profile->name }}')"
                                        class="flex items-center gap-1 text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Reportar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                @if ($matches->hasMorePages())
                    <div class="flex justify-center mt-12">
                        <a href="{{ $matches->nextPageUrl() }}"
                            class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl transition-all">
                            Ver Más →
                        </a>
                    </div>
                @endif
            @else
                <!-- Sin matches -->
                <div class="p-8 flex justify-center items-center min-h-[50vh]">
                    <div class="bg-white/20 backdrop-blur-xl rounded-3xl p-12 shadow-2xl max-w-lg text-center border border-white/30">
                        <div class="text-7xl mb-6 drop-shadow-lg">✨</div>
                        <h3 class="text-3xl font-black text-white mb-4 uppercase tracking-tighter" style="font-family: 'Outfit', sans-serif;">
                            Tu próxima conexión te espera
                        </h3>
                        <p class="text-pink-50 font-bold mb-8 uppercase tracking-widest text-xs opacity-90 italic">
                            Sigue explorando perfiles para encontrar tu match ideal.
                        </p>
                        <a href="{{ route('discover.index') }}"
                            class="inline-flex items-center gap-3 px-10 py-4 bg-white text-purple-700 font-extrabold rounded-2xl shadow-xl hover:scale-105 transition-all text-sm uppercase tracking-widest">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6 m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            Explorar Perfiles
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection