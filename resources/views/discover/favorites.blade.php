@extends('layouts.mobile-app')

@section('page-title', 'Mis Favoritos')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-600 to-rose-600">

        {{-- Header --}}
        <div
            class="bg-gradient-to-r from-purple-700 via-pink-700 to-rose-700 text-white px-6 py-10 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="max-w-7xl mx-auto flex items-center gap-6 relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/30 shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="w-10 h-10 text-white animate-pulse">
                        <path
                            d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter drop-shadow-xl leading-none mb-1">
                        Mis Favoritos
                    </h1>
                    <p class="text-white/80 font-black uppercase tracking-widest text-sm italic">{{ $favorites->total() }}
                        matches potenciales</p>
                </div>
            </div>
        </div>

        {{-- Grid de Favoritos --}}
        @if($favorites->count() > 0)
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($favorites as $profile)
                    <div
                        class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-pink-500 ring-4 ring-pink-200 hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2">

                        {{-- Badge Favorito --}}
                        <div
                            class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-center py-2 px-4 font-bold text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                            ❤️ Favorito
                        </div>

                        {{-- Foto Principal --}}
                        <div class="relative aspect-[3/4] mt-10">
                            @if($profile->primaryPhoto)
                                <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                    <span class="text-8xl font-bold text-white/80">
                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/50 to-transparent pt-24 pb-6 px-6">
                                <h2 class="text-3xl font-bold text-white mb-1">
                                    {{ $profile->name }}, {{ $profile->age }}
                                </h2>
                                @if($profile->city)
                                    <p class="text-white/90 flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
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
                                    class="w-full py-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest transition-all shadow-lg shadow-red-500/25 flex items-center justify-center gap-2 group border border-white/10">
                                    <span class="group-hover:rotate-12 transition-transform">💔</span>
                                    Quitar
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
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
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