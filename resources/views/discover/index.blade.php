@extends('layouts.mobile-app')

@section('page-title', 'Explorar')

@section('content')
    {{-- CONTENEDOR PRINCIPAL CON DEGRADADO --}}
    <div class="min-h-screen bg-gradient-to-br from-purple-600 via-pink-600 to-rose-600">

        {{-- ✨ NOTIFICACIONES DE ÉXITO/ERROR --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="fixed top-6 right-6 z-[100] bg-white rounded-2xl shadow-2xl p-5 max-w-md border-l-4 border-green-500 animate-bounce">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-gray-900 font-bold text-lg">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="fixed top-6 right-6 z-[100] bg-white rounded-2xl shadow-2xl p-5 max-w-md border-l-4 border-red-500">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-gray-900 font-bold text-lg">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="fixed top-6 right-6 z-[100] bg-white rounded-2xl shadow-2xl p-5 max-w-md border-l-4 border-blue-500">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-gray-900 font-bold text-lg">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <div x-data="discoveryFilters()">
            {{-- Header Sticky --}}
            <div
                class="sticky top-0 z-40 bg-gradient-to-r from-purple-700 via-pink-700 to-rose-700 text-white px-6 shadow-2xl border-b-4 border-white/20">
                <div class="max-w-7xl mx-auto">
                    <div class="flex items-center justify-between py-6">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-playfair font-bold drop-shadow-lg">
                                @if (auth()->user()->user_type === 'sugar_daddy')
                                    💎 Sugar Babies
                                @else
                                    👔 Sugar Daddies
                                @endif
                            </h1>
                            <p class="text-white/90 text-sm md:text-base mt-1">{{ $users->total() }} perfiles disponibles
                            </p>
                        </div>

                        {{-- Botón Filtros --}}
                        <button @click="showFilters = !showFilters"
                            class="p-4 bg-white/20 hover:bg-white/30 rounded-2xl transition-all backdrop-blur-sm shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </button>
                    </div>

                    {{-- Panel de Filtros --}}
                    <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-2" class="pb-6">
                        <form method="GET" action="{{ route('discover.index') }}" class="space-y-3">

                            <div class="grid grid-cols-2 gap-3">
                                <input type="number" name="age_min" x-model="filters.age_min" placeholder="Edad mín"
                                    class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/60 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                                <input type="number" name="age_max" x-model="filters.age_max" placeholder="Edad máx"
                                    class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/60 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">

                                <select name="city" x-model="filters.city"
                                    class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                                    <option value="" class="text-gray-900">Todas las ciudades</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}" class="text-gray-900">{{ $city }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="interests" x-model="filters.interests"
                                    class="px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50">
                                    <option value="" class="text-gray-900">Todos los intereses</option>
                                    @foreach ($interestsOptions as $key => $value)
                                        <option value="{{ $key }}" class="text-gray-900">{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-3 bg-white text-purple-700 font-bold rounded-xl hover:bg-white/90 transition-all shadow-lg">
                                🔍 Aplicar Filtros
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Grid de Cards --}}
            @if ($users->count() > 0)
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($users as $profile)
                        @php
                            $hasLiked = auth()->user()->hasLiked($profile);
                        @endphp

                        <div
                            class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 
                        {{ $hasLiked ? 'border-pink-500 ring-4 ring-pink-200' : 'border-white/20' }} 
                        hover:shadow-3xl transition-all duration-300 transform hover:-translate-y-2 hover:border-white/40">

                            {{-- BADGE "YA LE DISTE LIKE" --}}
                            @if ($hasLiked)
                                <div
                                    class="absolute top-0 left-0 right-0 z-10 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-center py-2 px-4 font-bold text-sm flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    ¡Ya le diste Like!
                                </div>
                            @endif

                            {{-- Foto Principal --}}
                            <div class="relative aspect-[3/4] {{ $hasLiked ? 'mt-10' : '' }}">
                                @if ($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}"
                                        class="w-full h-full object-cover {{ $hasLiked ? 'opacity-90' : '' }}">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                                        <span class="text-8xl font-bold text-white/80">
                                            {{ strtoupper(substr($profile->name, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif

                                @if ($profile->is_verified)
                                    <div class="absolute top-4 right-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Verificado
                                        </span>
                                    </div>
                                @endif

                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/50 to-transparent pt-24 pb-6 px-6">
                                    <h2 class="text-3xl font-playfair font-bold text-white mb-1">
                                        {{ $profile->name }}, {{ $profile->age }}
                                    </h2>
                                    @if ($profile->city)
                                        <p class="text-white/90 flex items-center gap-1.5 mb-3">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $profile->city }}
                                        </p>
                                    @endif

                                    @if ($profile->profileDetail && $profile->profileDetail->interests)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach (array_slice($profile->profileDetail->interests, 0, 3) as $interest)
                                                <span
                                                    class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full border border-white/30">
                                                    {{ $interestsOptions[$interest] ?? $interest }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Botones --}}
                            <div class="p-4 flex gap-3 bg-gradient-to-br from-gray-50 to-white">
                                <a href="{{ url('/profile/' . $profile->id) }}"
                                    class="flex-1 px-4 py-4 bg-gradient-to-r from-purple-100 to-pink-100 hover:from-purple-200 hover:to-pink-200 text-purple-700 font-bold rounded-2xl text-center transition-all active:scale-95 shadow-md hover:shadow-lg border border-purple-200">
                                    👀 Ver Perfil
                                </a>

                                @if ($hasLiked)
                                    {{-- Botón Quitar Like --}}
                                    <form method="POST" action="{{ route('discover.unlike', $profile) }}"
                                        class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-600 font-bold rounded-2xl transition-all shadow-lg active:scale-95">
                                            💔 Quitar
                                        </button>
                                    </form>
                                @else
                                    {{-- Botón Dar Like - FORZAR SUBMIT --}}
                                    <form method="POST" action="{{ route('discover.like', $profile) }}" class="flex-1"
                                        onsubmit="console.log('Form submitted'); return true;">
                                        @csrf
                                        <button type="submit" onclick="console.log('Button clicked')"
                                            class="w-full px-6 py-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-bold rounded-2xl transition-all shadow-lg hover:shadow-xl transform hover:scale-105 active:scale-95">
                                            ❤️ Like
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($users->hasMorePages())
                    <div class="p-6 flex justify-center">
                        <a href="{{ $users->nextPageUrl() }}"
                            class="px-10 py-4 bg-white/90 hover:bg-white text-purple-700 font-bold rounded-2xl text-center shadow-xl hover:shadow-2xl border-2 border-white/50 transition-all hover:scale-105">
                            Ver Más Perfiles →
                        </a>
                    </div>
                @endif
            @else
                <div class="p-8 flex justify-center items-center min-h-[60vh]">
                    <div class="bg-white/90 backdrop-blur-lg rounded-3xl p-12 shadow-2xl max-w-md text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-2xl font-playfair font-bold text-gray-900 mb-3">
                            No hay perfiles disponibles
                        </h3>
                        <p class="text-gray-600 mb-6">Prueba ajustando tus filtros</p>
                        <a href="{{ route('discover.index') }}"
                            class="inline-block px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all">
                            Ver Todos
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- DEBUG: Mostrar todos los likes --}}
    <div class="fixed bottom-4 left-4 bg-black text-white p-4 rounded-lg z-50 text-xs max-w-md">
        <p class="font-bold mb-2">DEBUG INFO:</p>
        <p>Usuario: {{ auth()->user()->name }} (ID: {{ auth()->user()->id }})</p>
        <p>Likes dados: {{ auth()->user()->likes()->count() }}</p>
        <div class="mt-2 max-h-32 overflow-auto">
            @foreach (auth()->user()->likes as $liked)
                <p>→ {{ $liked->name }} (ID: {{ $liked->id }})</p>
            @endforeach
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function discoveryFilters() {
            return {
                showFilters: false,
                filters: {
                    city: '{{ request('city', '') }}',
                    age_min: '{{ request('age_min', '') }}',
                    age_max: '{{ request('age_max', '') }}',
                    interests: '{{ request('interests', '') }}'
                }
            }
        }
    </script>
@endpush
