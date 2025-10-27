@extends('layouts.mobile-app')

@section('page-title', 'Mis Matches')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-600 to-rose-600">
        
        {{-- Notificaciones --}}
        @if(session('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 3000)"
                 x-transition
                 class="fixed top-6 right-6 z-[100] bg-white rounded-2xl shadow-2xl p-5 border-l-4 border-green-500">
                <p class="text-gray-900 font-bold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-700 via-pink-700 to-rose-700 text-white px-6 py-8 shadow-2xl border-b-4 border-white/20">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-3 mb-2">
                    <div class="text-5xl animate-pulse">🎉</div>
                    <h1 class="text-4xl font-playfair font-bold drop-shadow-lg">
                        ¡Matches!
                    </h1>
                </div>
                <p class="text-white/90 text-lg">
                    {{ $matches->total() }} 
                    @if(auth()->user()->user_type === 'sugar_daddy')
                        Sugar Babies que también te dieron like
                    @else
                        Sugar Daddies que también te dieron like
                    @endif
                </p>
                <div class="mt-4 flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/30">
                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <p class="text-white/90 text-sm font-semibold">
                        ¡Es un match! Ahora pueden chatear 💬
                    </p>
                </div>
            </div>
        </div>

        {{-- Grid de Matches --}}
        @if($matches->count() > 0)
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($matches as $profile)
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-yellow-400 ring-4 ring-yellow-200 hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105">
                        
                        {{-- Badge Match con Animación --}}
                        <div class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-yellow-400 via-orange-400 to-pink-500 text-white text-center py-2 px-4 font-bold text-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-black uppercase tracking-wide">¡Match!</span>
                            <svg class="w-5 h-5 animate-bounce" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        
                        {{-- Foto Principal --}}
                        <div class="relative aspect-[3/4] mt-10">
                            @if($profile->primaryPhoto)
                                <img src="{{ $profile->primaryPhoto->url }}" 
                                     alt="{{ $profile->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                                    <span class="text-8xl font-bold text-white/80">
                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            @if($profile->is_verified)
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Verificado
                                    </span>
                                </div>
                            @endif

                            {{-- Info del perfil --}}
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/50 to-transparent pt-24 pb-6 px-6">
                                <h2 class="text-3xl font-playfair font-bold text-white mb-1">
                                    {{ $profile->name }}, {{ $profile->age }}
                                </h2>
                                @if($profile->city)
                                    <p class="text-white/90 flex items-center gap-1.5 mb-3">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $profile->city }}
                                    </p>
                                @endif

                                @if($profile->profileDetail && $profile->profileDetail->interests)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(array_slice($profile->profileDetail->interests, 0, 3) as $interest)
                                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full border border-white/30">
                                                {{ $interestsOptions[$interest] ?? $interest }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="p-4 flex gap-3 bg-gradient-to-br from-yellow-50 to-orange-50">
                            <a href="{{ url('/profile/' . $profile->id) }}"
                                class="flex-1 px-4 py-4 bg-gradient-to-r from-purple-100 to-pink-100 hover:from-purple-200 hover:to-pink-200 text-purple-700 font-bold rounded-2xl text-center transition-all shadow-md hover:shadow-lg border border-purple-200">
                                👀 Ver Perfil
                            </a>
                            
                            {{-- Botón Chatear (próximo hito) --}}
                            <button disabled
                                class="flex-1 px-6 py-4 bg-gray-300 text-gray-500 font-bold rounded-2xl text-center cursor-not-allowed">
                                💬 Próximamente
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if($matches->hasMorePages())
                <div class="p-6 flex justify-center">
                    <a href="{{ $matches->nextPageUrl() }}"
                        class="px-10 py-4 bg-white/90 hover:bg-white text-purple-700 font-bold rounded-2xl shadow-xl hover:shadow-2xl border-2 border-white/50 transition-all hover:scale-105">
                        Ver Más Matches →
                    </a>
                </div>
            @endif

        @else
            {{-- Sin matches aún --}}
            <div class="p-8 flex justify-center items-center min-h-[60vh]">
                <div class="bg-white/90 backdrop-blur-lg rounded-3xl p-12 shadow-2xl max-w-md text-center">
                    <div class="text-6xl mb-4">😢</div>
                    <h3 class="text-2xl font-playfair font-bold text-gray-900 mb-3">
                        Aún no tienes matches
                    </h3>
                    <p class="text-gray-600 mb-6">
                        Sigue dando likes y espera que ellos también te den like para hacer match
                    </p>
                    <a href="{{ route('discover.index') }}"
                        class="inline-block px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all hover:scale-105">
                        🔍 Explorar Perfiles
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
