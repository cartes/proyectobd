@extends('layouts.mobile-app')

@section('page-title', 'Mis Matches')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-purple-50 py-12">
        <div class="max-w-7xl mx-auto px-4">

            <!-- Header -->
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">
                    Mis Matches
                </h1>
                <p class="text-gray-600 mt-2">{{ $matches->total() }} conexiones</p>
            </div>

            @if ($matches->count() > 0)
                <!-- Grid de cards compactas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($matches as $profile)
                        <div
                            class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100">

                            <!-- Foto (rectangular, no vertical) -->
                            <div class="relative aspect-square bg-gray-300 overflow-hidden">
                                @if ($profile->primaryPhoto)
                                    <img src="{{ asset('storage/' . $profile->primaryPhoto->photo_path) }}"
                                        alt="{{ $profile->name }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center">
                                        <span class="text-7xl font-bold text-white/40">
                                            {{ strtoupper(substr($profile->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="p-4 bg-white">
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $profile->name }}
                                </h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    📍 {{ $profile->city }}
                                </p>

                                @if ($profile->is_verified)
                                    <span
                                        class="inline-block mt-2 px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                                        ✓ Verificado
                                    </span>
                                @endif

                                <p class="text-xs text-gray-400 mt-2">Nuevo</p>
                            </div>

                            <!-- Botones principales -->
                            <div class="px-4 pb-4 flex gap-2">
                                <a href="{{ route('profile.show', $profile) }}"
                                    class="flex-1 px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded-xl text-center text-sm transition-colors">
                                    👀 Ver
                                </a>

                                <a href="{{ route('chat.create', $profile) }}"
                                    class="flex-1 px-4 py-2 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold rounded-xl text-center text-sm transition-colors">
                                    💬 Chat
                                </a>
                            </div>

                            <!-- Botones circulares -->
                            <div class="px-2 pb-4 flex justify-center items-center gap-12">
                                <!-- Deshacer (rojo/izquierda) -->
                                <form action="{{ route('matches.unmatch', $profile) }}" method="POST"
                                    onsubmit="return confirm('¿Deshacer match?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Like/Report (azul/derecha) -->
                                <button type="button" onclick="alert('Reportar a: {{ $profile->name }}')"
                                    class="w-10 h-10 rounded-full bg-blue-500 hover:bg-blue-600 text-white flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                    </svg>
                                </button>
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
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">💔</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                        Aún no tienes matches
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Sigue explorando para encontrar conexiones
                    </p>
                    <a href="{{ route('discover.index') }}"
                        class="inline-block px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-full">
                        Explorar Perfiles
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
