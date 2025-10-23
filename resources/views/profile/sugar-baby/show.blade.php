@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('content')
    {{-- NUEVO: Preparar datos para lightbox --}}
    @php
        $photosForLightbox = [];
        if (isset($user) && $user->photos && $user->photos->count() > 0) {
            $photosForLightbox = $user->photos
                ->map(function ($photo, $index) use ($user) {
                    return [
                        'url' => $photo->url,
                        'alt' => $user->name . ' - Foto ' . ($index + 1),
                    ];
                })
                ->toArray();
        }
    @endphp

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
            class="mb-6 bg-gradient-to-r from-pink-500/90 to-pink-600/90 backdrop-blur-lg rounded-2xl p-4 text-white shadow-xl border border-white/20">
            <p class="flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- Header del perfil - Estilo Vibrante y Atractivo --}}
    <div
        class="bg-gradient-to-br from-pink-500 via-rose-500 to-fuchsia-600 rounded-3xl p-8 mb-6 shadow-2xl text-white relative overflow-hidden">
        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 bg-white/10 backdrop-blur-3xl"></div>
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-24 w-80 h-80 bg-fuchsia-300/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/3 w-64 h-64 bg-rose-300/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex items-start justify-between flex-wrap gap-6 mb-6">
                <div class="flex items-center gap-6">
                    {{-- Avatar Femenino y Elegante --}}
                    <div class="relative">
                        @if ($user->primaryPhoto)
                            {{-- Si tiene foto principal, mostrarla --}}
                            <img src="{{ $user->primaryPhoto->url }}" alt="{{ $user->name }}"
                                class="w-32 h-32 rounded-full object-cover border-4 border-white/40 shadow-2xl ring-4 ring-pink-300/30">
                        @else
                            {{-- Si no tiene foto, mostrar inicial --}}
                            <div
                                class="w-32 h-32 rounded-full bg-gradient-to-br from-white/30 to-white/20 border-4 border-white/40 flex items-center justify-center text-white text-5xl font-bold shadow-2xl backdrop-blur-xl ring-4 ring-pink-300/30">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        {{-- Badge de verificación (siempre visible) --}}
                        <div
                            class="absolute -bottom-2 -right-2 w-12 h-12 bg-gradient-to-br from-green-400 to-green-500 rounded-full border-4 border-pink-600 flex items-center justify-center shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        {{-- Sparkles decorativos (siempre visibles) --}}
                        <div class="absolute -top-3 -left-3 text-2xl animate-pulse">✨</div>
                        <div class="absolute -bottom-3 -left-2 text-xl animate-bounce">💫</div>
                    </div>

                    {{-- Info personal destacada --}}
                    <div>
                        <h1
                            class="text-5xl md:text-6xl font-playfair font-bold text-white mb-3 tracking-tight drop-shadow-lg">
                            {{ $user->name }}
                        </h1>
                        <div class="flex items-center gap-3 text-white flex-wrap mb-3">
                            @if ($user->age)
                                <span class="text-2xl font-bold">{{ $user->age }} años</span>
                                <span class="text-white/60">•</span>
                            @endif
                            @if ($user->city)
                                <span class="text-xl flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $user->city }}
                                </span>
                            @endif
                        </div>
                        @if ($user->profileDetail->personal_style)
                            <p class="text-lg text-white/90 mb-2 font-medium">
                                {{ \App\Models\ProfileDetail::personalStyles()[$user->profileDetail->personal_style] ?? '' }}
                            </p>
                        @endif
                        <div class="flex gap-2">
                            <span
                                class="px-4 py-2 rounded-full bg-white/20 backdrop-blur-lg border border-white/30 font-bold shadow-lg text-sm">
                                💎 Sugar Baby
                            </span>
                            @if ($user->profileDetail->fitness_level)
                                <span
                                    class="px-4 py-2 rounded-full bg-white/15 backdrop-blur-lg border border-white/20 font-semibold text-sm">
                                    💪
                                    {{ explode(' - ', ProfileDetail::fitnessLevels()[$user->profileDetail->fitness_level])[0] ?? 'Activa' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Botón editar --}}
                <a href="{{ route('profile.edit') }}"
                    class="px-8 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Perfil
                    </span>
                </a>
            </div>

            {{-- Bio destacada --}}
            @if ($user->bio)
                <div class="bg-white/15 backdrop-blur-xl border-2 border-white/30 rounded-2xl p-6 shadow-2xl">
                    <p class="text-white text-lg leading-relaxed font-medium">
                        {{ $user->bio }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Grid de secciones --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Sobre Mí --}}
        <div
            class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-2xl shadow-lg">
                    ✨
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Sobre Mí</h2>
                    <p class="text-sm text-gray-500">Información personal</p>
                </div>
            </div>

            <div class="space-y-4">
                @if ($user->profileDetail->height)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-pink-50 to-pink-100/50 rounded-xl border border-pink-200/50">
                        <span class="text-gray-700 font-medium">Altura</span>
                        <span class="text-gray-900 font-bold">{{ $user->profileDetail->height }} cm</span>
                    </div>
                @endif
                @if ($user->profileDetail->body_type)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-rose-50 to-rose-100/50 rounded-xl border border-rose-200/50">
                        <span class="text-gray-700 font-medium">Tipo de cuerpo</span>
                        <span
                            class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::bodyTypes()[$user->profileDetail->body_type] ?? $user->profileDetail->body_type }}</span>
                    </div>
                @endif
                @if ($user->profileDetail->personal_style)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-fuchsia-50 to-fuchsia-100/50 rounded-xl border border-fuchsia-200/50">
                        <span class="text-gray-700 font-medium">Estilo</span>
                        <span
                            class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::personalStyles()[$user->profileDetail->personal_style] ?? $user->profileDetail->personal_style }}</span>
                    </div>
                @endif
                @if ($user->profileDetail->fitness_level)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-purple-50 to-purple-100/50 rounded-xl border border-purple-200/50">
                        <span class="text-gray-700 font-medium">Fitness</span>
                        <span
                            class="text-gray-900 font-bold">{{ explode(' - ', ProfileDetail::fitnessLevels()[$user->profileDetail->fitness_level])[0] ?? '' }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Educación y Ocupación --}}
        <div
            class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-2xl shadow-lg">
                    📚
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Educación</h2>
                    <p class="text-sm text-gray-500">Formación y actividades</p>
                </div>
            </div>

            <div class="space-y-4">
                @if ($user->profileDetail->education)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-indigo-50 to-indigo-100/50 rounded-xl border border-indigo-200/50">
                        <span class="text-gray-700 font-medium">Nivel educativo</span>
                        <span
                            class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::educationLevels()[$user->profileDetail->education] ?? $user->profileDetail->education }}</span>
                    </div>
                @endif
                @if ($user->profileDetail->occupation)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl border border-blue-200/50">
                        <span class="text-gray-700 font-medium">Ocupación</span>
                        <span class="text-gray-900 font-bold">{{ $user->profileDetail->occupation }}</span>
                    </div>
                @endif
                @if ($user->profileDetail->availability)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-teal-50 to-teal-100/50 rounded-xl border border-teal-200/50">
                        <span class="text-gray-700 font-medium">Disponibilidad</span>
                        <span
                            class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::availabilityOptions()[$user->profileDetail->availability] ?? $user->profileDetail->availability }}</span>
                    </div>
                @endif
                @if ($user->profileDetail->relationship_status)
                    <div
                        class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-cyan-50 to-cyan-100/50 rounded-xl border border-cyan-200/50">
                        <span class="text-gray-700 font-medium">Estado</span>
                        <span
                            class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::relationshipStatuses()[$user->profileDetail->relationship_status] ?? $user->profileDetail->relationship_status }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Mis Intereses --}}
        @if ($user->profileDetail->interests && count($user->profileDetail->interests) > 0)
            <div
                class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300 lg:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white text-2xl shadow-lg">
                        💖
                    </div>
                    <div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Mis Intereses</h2>
                        <p class="text-sm text-gray-500">Lo que me apasiona</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    @foreach ($user->profileDetail->interests as $interest)
                        <span
                            class="px-5 py-3 bg-gradient-to-r from-pink-100 to-pink-200 text-pink-800 border-2 border-pink-300 rounded-full text-sm font-bold shadow-sm hover:shadow-lg transition-shadow">
                            {{ \App\Models\ProfileDetail::interestsOptions()[$interest] ?? $interest }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- Galería de Fotos --}}
        @if ($user->photos->count() > 0)
            <div
                class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-2xl shadow-lg">
                            📸
                        </div>
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-gray-900">Mi Galería</h2>
                            <p class="text-sm text-gray-500">{{ $user->photos->count() }} fotos</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.photos.index') }}"
                        class="px-6 py-2.5 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold rounded-xl transition-colors duration-300">
                        Ver todas →
                    </a>
                </div>

                {{-- Grid de fotos - 6 columnas --}}
                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($user->photos->take(12) as $photo)
                        <div
                            class="aspect-square rounded-2xl overflow-hidden bg-gray-100 relative group {{ $photo->is_primary ? 'ring-4 ring-amber-500' : '' }}">
                            <img src="{{ $photo->url }}" alt="Foto {{ $loop->iteration }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer">

                            {{-- Badge foto principal --}}
                            @if ($photo->is_primary)
                                <div
                                    class="absolute top-2 right-2 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center shadow-xl">
                                    <span class="text-sm">⭐</span>
                                </div>
                            @endif

                            {{-- Overlay al hover --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                <span class="text-white text-xs font-semibold">Foto {{ $loop->iteration }}</span>
                            </div>
                        </div>
                    @endforeach

                    {{-- Si tiene más fotos, mostrar card +X --}}
                    @if ($user->photos->count() > 12)
                        <a href="{{ route('profile.photos.index') }}"
                            class="aspect-square rounded-2xl bg-gradient-to-br from-pink-500 to-pink-700 flex flex-col items-center justify-center text-white hover:scale-105 transition-transform duration-300 shadow-xl">
                            <span class="text-4xl font-bold">+{{ $user->photos->count() - 12 }}</span>
                            <span class="text-xs mt-1">más fotos</span>
                        </a>
                    @endif
                </div>
            </div>
        @else
            {{-- Si no tiene fotos --}}
            <div
                class="bg-gradient-to-br from-pink-500 to-pink-700 rounded-3xl shadow-xl p-12 text-center text-white hover:shadow-2xl transition-shadow duration-300">
                <div class="text-6xl mb-4">📸</div>
                <h3 class="text-3xl font-playfair font-bold mb-3">Sube tus fotos</h3>
                <p class="text-white/90 mb-8 text-lg">Las fotos aumentan significativamente tus posibilidades de conectar
                </p>
                <a href="{{ route('profile.photos.index') }}"
                    class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl font-bold transition-all duration-300 hover:scale-105 shadow-2xl">
                    📤 Subir Fotos
                </a>
            </div>
        @endif


        {{-- Mis Aspiraciones --}}
        @if ($user->profileDetail->aspirations)
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-3xl shadow-xl p-8 border-2 border-purple-200/50 hover:shadow-2xl transition-shadow duration-300 lg:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-2xl shadow-lg">
                        🌟
                    </div>
                    <div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Mis Aspiraciones</h2>
                        <p class="text-sm text-gray-600">Sueños y metas</p>
                    </div>
                </div>

                <p class="text-gray-800 leading-relaxed text-lg font-medium">
                    {{ $user->profileDetail->aspirations }}
                </p>
            </div>
        @endif

        {{-- Mi Daddy Ideal --}}
        @if ($user->profileDetail->ideal_daddy)
            <div
                class="bg-gradient-to-br from-pink-50 to-rose-100/50 rounded-3xl shadow-xl p-8 border-2 border-pink-200/50 hover:shadow-2xl transition-shadow duration-300 lg:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-700 flex items-center justify-center text-white text-2xl shadow-lg">
                        💕
                    </div>
                    <div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Mi Daddy Ideal</h2>
                        <p class="text-sm text-gray-600">Lo que busco en una relación</p>
                    </div>
                </div>

                <p class="text-gray-800 leading-relaxed text-lg font-medium">
                    {{ $user->profileDetail->ideal_daddy }}
                </p>
            </div>
        @endif

        {{-- Qué busco (genérico) --}}
        @if ($user->profileDetail->looking_for && !$user->profileDetail->ideal_daddy)
            <div
                class="bg-gradient-to-br from-pink-50 to-rose-100/50 rounded-3xl shadow-xl p-8 border-2 border-pink-200/50 hover:shadow-2xl transition-shadow duration-300 lg:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-700 flex items-center justify-center text-white text-2xl shadow-lg">
                        💫
                    </div>
                    <div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Qué Busco</h2>
                        <p class="text-sm text-gray-600">Mi relación ideal</p>
                    </div>
                </div>

                <p class="text-gray-800 leading-relaxed text-lg font-medium">
                    {{ $user->profileDetail->looking_for }}
                </p>
            </div>
        @endif

    </div>

    {{-- CTA si no tiene perfil completo --}}
    @if (!$user->profileDetail->aspirations || !$user->profileDetail->ideal_daddy)
        <div
            class="mt-8 bg-gradient-to-br from-pink-500 via-rose-500 to-fuchsia-600 rounded-3xl p-12 shadow-2xl text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-white/5 backdrop-blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="text-7xl mb-4">✨</div>
                <h3 class="text-3xl font-playfair font-bold text-white mb-3">
                    Haz tu perfil más atractivo
                </h3>
                <p class="text-white/90 mb-8 text-lg">
                    Completa tu perfil para atraer a los mejores Sugar Daddies
                </p>
                <a href="{{ route('profile.edit') }}"
                    class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-2xl">
                    Completar Perfil →
                </a>
            </div>
        </div>
    @endif
@endsection
