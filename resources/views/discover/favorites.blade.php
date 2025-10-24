@extends('layouts.mobile-app')

@section('page-title', 'Mis Favoritos')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-600 to-rose-600">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-700 via-pink-700 to-rose-700 text-white px-6 py-8 shadow-2xl">
            <h1 class="text-4xl font-playfair font-bold drop-shadow-lg mb-2">
                💖 Mis Favoritos
            </h1>
            <p class="text-white/90">{{ $favorites->total() }} perfiles que te gustan</p>
        </div>

        {{-- Grid de Favoritos --}}
        @if($favorites->count() > 0)
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($favorites as $profile)
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-pink-500 ring-4 ring-pink-200 hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">
                        
                        {{-- Badge Favorito --}}
                        <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-center py-2 px-4 font-bold text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            ❤️ Favorito
                        </div>
                        
                        {{-- Foto Principal --}}
                        <div class="relative aspect-[3/4] mt-10">
                            @if($profile->primaryPhoto)
                                <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                    <span class="text-8xl font-bold text-white/80">
                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/50 to-transparent pt-24 pb-6 px-6">
                                <h2 class="text-3xl font-playfair font-bold text-white mb-1">
                                    {{ $profile->name }}, {{ $profile->age }}
                                </h2>
                                @if($profile->city)
                                    <p class="text-white/90 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $profile->city }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="p-4 flex gap-3 bg-gradient-to-br from-gray-50 to-white">
                            <a href="{{ url('/profile/' . $profile->id) }}"
                                class="flex-1 px-4 py-4 bg-gradient-to-r from-purple-100 to-pink-100 hover:from-purple-200 hover:to-pink-200 text-purple-700 font-bold rounded-2xl text-center transition-all active:scale-95">
                                👀 Ver Perfil
                            </a>
                            
                            <form method="POST" action="{{ route('discover.unlike', $profile) }}" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold rounded-2xl transition-all shadow-lg active:scale-95">
                                    💔 Quitar
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($favorites->hasMorePages())
                <div class="p-6 flex justify-center">
                    <a href="{{ $favorites->nextPageUrl() }}"
                        class="px-10 py-4 bg-white/90 hover:bg-white text-purple-700 font-bold rounded-2xl shadow-xl transition-all">
                        Ver Más →
                    </a>
                </div>
            @endif

        @else
            {{-- Sin favoritos --}}
            <div class="p-8 flex justify-center items-center min-h-[60vh]">
                <div class="bg-white/90 backdrop-blur-lg rounded-3xl p-12 shadow-2xl max-w-md text-center">
                    <div class="text-6xl mb-4">💔</div>
                    <h3 class="text-2xl font-playfair font-bold text-gray-900 mb-3">
                        Aún no tienes favoritos
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Explora perfiles y da like a los que te gusten
                    </p>
                    <a href="{{ route('discover.index') }}"
                        class="inline-block px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg">
                        Explorar Ahora
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
