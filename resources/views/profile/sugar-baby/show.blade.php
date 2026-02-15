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
                        <div
                            class="absolute -top-3 -left-3 w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/30 shadow-lg animate-pulse">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                            </svg>
                        </div>
                        <div
                            class="absolute -bottom-3 -left-2 w-8 h-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center border border-white/30 shadow-lg animate-bounce">
                            <svg class="w-5 h-5 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Info personal destacada --}}
                    <div>
                        <h1 class="text-5xl md:text-6xl font-black text-white mb-3 tracking-tight drop-shadow-lg"
                            style="font-family: 'Outfit', sans-serif;">
                            {{ $user->name }}
                        </h1>
                        <div class="flex items-center gap-3 text-white flex-wrap mb-3">
                            @if ($user->age)
                                <span class="text-2xl font-bold">{{ $user->age }} años</span>
                                <span class="text-white/60">•</span>
                            @endif
                            @if ($user->country)
                                <span class="text-xl flex items-center gap-2">
                                    <img src="https://flagcdn.com/w40/{{ strtolower($user->country->iso_code) }}.png"
                                        alt="{{ $user->country->name }}"
                                        class="w-8 h-8 rounded-full object-cover border-2 border-white/40 shadow-sm">
                                    {{ $user->country->name }}
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
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7l10 13 10-13-10-5zM4.18 7L12 3.65 19.82 7 12 18.06 4.18 7z" />
                                </svg>
                                Sugar Baby
                            </span>
                            @if ($user->profileDetail->fitness_level)
                                <span
                                    class="px-4 py-2 rounded-full bg-white/15 backdrop-blur-lg border border-white/20 font-semibold text-sm">
                                    💪
                                    {{ explode(' - ', \App\Models\ProfileDetail::fitnessLevels()[$user->profileDetail->fitness_level])[0] ?? 'Activa' }}
                                </span>
                            @endif

                            {{-- ✅ EMAIL PRIVACY --}}
                            @if ($isOwnProfile || $hasMatch)
                                <div
                                    class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-lg border border-white/30 font-bold shadow-lg flex items-center gap-2 text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ $user->email }}
                                </div>
                            @endif

                            {{-- ✅ INSTAGRAM PREMIUM --}}
                            @if ($user->profileDetail->social_instagram)
                                @if ($canSeeInstagram || $isOwnProfile)
                                    <a href="https://instagram.com/{{ str_replace('@', '', $user->profileDetail->social_instagram) }}"
                                        target="_blank"
                                        class="px-4 py-1.5 rounded-full bg-white/20 backdrop-blur-lg border border-white/30 font-bold shadow-lg flex items-center gap-2 hover:bg-white/30 transition-all text-sm">
                                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $user->profileDetail->social_instagram }}
                                    </a>
                                @else
                                    <div class="px-4 py-1.5 rounded-full bg-black/10 backdrop-blur-lg border border-white/10 text-white/60 text-xs flex items-center gap-2 cursor-help group relative"
                                        title="Suscríbete a Premium para ver Redes Sociales">
                                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Instagram oculto
                                        <div
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-black/80 rounded-lg text-[10px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none text-center">
                                            Suscríbete a Premium para <br> desbloquear redes sociales
                                        </div>
                                    </div>
                                @endif
                            @endif
                            {{-- ✅ WHATSAPP PREMIUM (SOLO BABIES) --}}
                            @if ($user->profileDetail->social_whatsapp)
                                @if ($canSeeWhatsapp || $isOwnProfile)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->profileDetail->social_whatsapp) }}"
                                        target="_blank"
                                        class="px-4 py-1.5 rounded-full bg-green-500/30 backdrop-blur-lg border border-green-400/50 font-bold shadow-lg flex items-center gap-2 hover:bg-green-500/50 transition-all text-sm">
                                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.18 3.03 14.69 2 12.04 2zM12.04 20.35c-1.57 0-3.1-.42-4.43-1.21l-.32-.19-3.29.86.88-3.2-.21-.33c-.87-1.39-1.33-3-1.33-4.66 0-4.63 3.77-8.4 8.4-8.4 2.24 0 4.35.87 5.94 2.46 1.58 1.58 2.46 3.69 2.46 5.94 0 4.63-3.77 8.4-8.4 8.4z" />
                                        </svg>
                                        WhatsApp
                                    </a>
                                @else
                                    <div class="px-4 py-1.5 rounded-full bg-black/10 backdrop-blur-lg border border-white/10 text-white/60 text-xs flex items-center gap-2 cursor-help group relative"
                                        title="Suscríbete a Premium para ver WhatsApp">
                                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        WhatsApp oculto
                                        <div
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-black/80 rounded-lg text-[10px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none text-center">
                                            Suscríbete a Premium para <br> desbloquear contacto directo
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                @if ($isOwnProfile)
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
                @else
                    <div class="flex gap-4">
                        {{-- Like Button --}}
                        @php $hasLiked = auth()->user()->hasLiked($user); @endphp
                        <form action="{{ $hasLiked ? route('discover.unlike', $user) : route('discover.like', $user) }}"
                            method="POST">
                            @csrf
                            @if ($hasLiked)
                                @method('DELETE')
                            @endif
                            <button type="submit"
                                class="px-8 py-4 {{ $hasLiked ? 'bg-pink-500/80' : 'bg-white/20' }} hover:bg-white/30 backdrop-blur-lg border border-white/30 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-xl flex items-center gap-2">
                                <svg class="w-6 h-6" fill="{{ $hasLiked ? 'currentColor' : 'none' }}"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $hasLiked ? 'Me Gusta' : 'Dar Like' }}
                            </button>
                        </form>

                        {{-- Chat Button --}}
                        <a href="{{ route('chat.show', $user) }}"
                            class="px-8 py-4 bg-white text-pink-600 font-bold rounded-2xl transition-all duration-300 hover:scale-105 shadow-xl flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Mensaje
                        </a>
                    </div>
                @endif
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
    @if (!empty($isRestricted) && $isRestricted)
        {{-- VISTA RESTRINGIDA (Perfil Privado) --}}
        <div class="min-h-[50vh] flex flex-col items-center justify-center text-center p-8 bg-gray-50 rounded-3xl mt-8">
            <div class="bg-white rounded-3xl p-12 max-w-2xl w-full shadow-xl border border-gray-100">
                <div
                    class="w-24 h-24 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg border border-white/10">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-4">Perfil Privado</h2>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    Este perfil es exclusivo y mantiene su información privada.
                    <br>
                    <span class="font-semibold text-gray-800">Da Like o envía un mensaje</span>. Si hacen Match, tendrás
                    acceso
                    completo a su información y fotos.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <form action="{{ route('discover.like', $user) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-pink-500/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dar Me Gusta
                        </button>
                    </form>

                    <a href="{{ route('chat.show', $user) }}"
                        class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-gray-50 text-pink-600 border-2 border-pink-100 font-bold rounded-2xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Enviar Mensaje
                    </a>
                </div>
            </div>
        </div>
    @else
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
                        <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Sobre Mí
                        </h2>
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
                                class="text-gray-900 font-bold">{{ explode(' - ', \App\Models\ProfileDetail::fitnessLevels()[$user->profileDetail->fitness_level])[0] ?? '' }}</span>
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
                        <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Educación
                        </h2>
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
                            <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Mis
                                Intereses
                            </h2>
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
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300"
                    x-data="lightboxGallery({{ json_encode($photosForLightbox) }})">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-2xl shadow-lg">
                                📸
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">
                                    Mi Galería
                                </h2>
                                <p class="text-sm text-gray-500">{{ $user->photos->count() }} fotos</p>
                            </div>
                        </div>
                        @if ($isOwnProfile)
                            <a href="{{ route('profile.photos.index') }}"
                                class="px-6 py-2.5 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold rounded-xl transition-colors duration-300">
                                Ver todas →
                            </a>
                        @else
                            <button @click="openLightbox(0)"
                                class="px-6 py-2.5 bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold rounded-xl transition-colors duration-300">
                                Ver todas →
                            </button>
                        @endif
                    </div>

                    {{-- Grid de fotos - 6 columnas --}}
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($user->photos->take(12) as $photo)
                            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-100 relative group {{ $photo->is_primary ? 'ring-4 ring-amber-500' : '' }}"
                                @click="openLightbox({{ $loop->index }})">
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

                        {{-- NUEVO: Modal Lightbox --}}
                        <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" @keydown.window.escape="closeLightbox"
                            @keydown.window.arrow-left="prev" @keydown.window.arrow-right="next"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm p-4"
                            @click="closeLightbox">

                            <div class="relative w-full max-w-6xl h-[90vh]" @click.stop>

                                {{-- Botón Cerrar --}}
                                <button @click="closeLightbox"
                                    class="absolute top-4 right-4 z-50 p-3 bg-white/10 hover:bg-white/25 rounded-full transition-all duration-300 backdrop-blur-lg"
                                    aria-label="Cerrar galería">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                {{-- Contador --}}
                                <div
                                    class="absolute top-4 left-1/2 transform -translate-x-1/2 z-50 px-5 py-2.5 bg-black/60 backdrop-blur-lg rounded-full text-white text-sm font-bold border border-white/20">
                                    <span x-text="currentIndex + 1"></span> / <span x-text="photos.length"></span>
                                </div>

                                {{-- Imagen Principal --}}
                                <div class="flex items-center justify-center h-full">
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <div x-show="currentIndex === index"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            class="flex items-center justify-center h-full">
                                            <img :src="photo.url" :alt="photo.alt"
                                                class="max-h-full max-w-full object-contain rounded-2xl shadow-2xl">
                                        </div>
                                    </template>
                                </div>

                                {{-- Botón Anterior --}}
                                <button @click.stop="prev" x-show="photos.length > 1"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 p-4 bg-white/10 hover:bg-white/25 rounded-full transition-all duration-300 backdrop-blur-lg"
                                    aria-label="Imagen anterior">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                {{-- Botón Siguiente --}}
                                <button @click.stop="next" x-show="photos.length > 1"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-4 bg-white/10 hover:bg-white/25 rounded-full transition-all duration-300 backdrop-blur-lg"
                                    aria-label="Imagen siguiente">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                {{-- Indicadores (Dots) --}}
                                <div x-show="photos.length > 1"
                                    class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2">
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <button @click="currentIndex = index"
                                            class="h-2.5 rounded-full transition-all duration-300"
                                            :class="index === currentIndex ? 'bg-white w-10' :
                                                'bg-white/50 hover:bg-white/75 w-2.5'"
                                            :aria-label="`Ir a imagen ${index + 1}`">
                                        </button>
                                    </template>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>
            @elseif($isOwnProfile)
                {{-- Si no tiene fotos --}}
                <div
                    class="bg-gradient-to-br from-pink-500 to-pink-700 rounded-3xl shadow-xl p-12 text-center text-white hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black mb-3" style="font-family: 'Outfit', sans-serif;">Sube tus fotos</h3>
                    <p class="text-white/90 mb-8 text-lg">Las fotos aumentan significativamente tus posibilidades de
                        conectar
                    </p>
                    <a href="{{ route('profile.photos.index') }}"
                        class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl font-bold transition-all duration-300 hover:scale-105 shadow-2xl">
                        📤 Subir Fotos
                    </a>
                </div>
            @endif
    @endif


    {{-- Mis Aspiraciones --}}
    @if ($user->profileDetail->aspirations)
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-3xl shadow-xl p-8 border-2 border-purple-200/50 hover:shadow-2xl transition-shadow duration-300 lg:col-span-2">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-2xl shadow-lg">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Mis
                        Aspiraciones</h2>
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
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Mi Daddy
                        Ideal
                    </h2>
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
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-gray-900" style="font-family: 'Outfit', sans-serif;">Qué Busco
                    </h2>
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
    @if ($isOwnProfile && (!$user->profileDetail->aspirations || !$user->profileDetail->ideal_daddy))
        <div
            class="mt-8 bg-gradient-to-br from-pink-500 via-rose-500 to-fuchsia-600 rounded-3xl p-12 shadow-2xl text-center text-white relative overflow-hidden group">
            <!-- Blur background -->
            <div class="absolute inset-0 bg-white/5 backdrop-blur-3xl"></div>
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl transition-transform duration-500 group-hover:scale-110">
            </div>

            <div class="relative z-10">
                <!-- Emoji animado -->
                <div class="text-7xl mb-6 text-white/50">
                    <svg class="w-24 h-24 mx-auto animate-float" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                    </svg>
                </div>

                <!-- Contenido -->
                <h3 class="text-3xl font-black text-white mb-3" style="font-family: 'Outfit', sans-serif;">
                    Haz tu perfil más atractivo
                </h3>
                <p class="text-white/90 mb-8 text-lg leading-relaxed">
                    Completa tu perfil para atraer a los mejores Sugar Daddies
                </p>

                <!-- Botón mejorado -->
                <a href="{{ route('profile.edit') }}"
                    class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 hover:border-white/60 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                    <span class="flex items-center gap-2">
                        Completar Perfil
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function lightboxGallery(photos) {
            return {
                photos: photos,
                currentIndex: 0,
                isOpen: false,

                openLightbox(index = 0) {
                    this.currentIndex = index;
                    this.isOpen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeLightbox() {
                    this.isOpen = false;
                    document.body.style.overflow = 'auto';
                },

                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.photos.length;
                },

                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
