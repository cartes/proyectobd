@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('content')
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
            class="mb-6 bg-gradient-to-r from-purple-500/90 to-purple-600/90 backdrop-blur-lg rounded-2xl p-4 text-white shadow-xl border border-white/20">
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

    {{-- Header del perfil - Estilo Ejecutivo --}}
    <div
        class="bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 rounded-3xl p-8 mb-6 shadow-2xl text-white relative overflow-hidden">
        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 bg-black/10 backdrop-blur-3xl"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <div class="flex items-start justify-between flex-wrap gap-6 mb-6">
                <div class="flex items-center gap-6">
                    {{-- Avatar/Foto Ejecutivo --}}
                    <div class="relative">
                        @if ($user->primaryPhoto)
                            {{-- Si tiene foto principal, mostrarla --}}
                            <img src="{{ $user->primaryPhoto->url }}" alt="{{ $user->name }}"
                                class="w-32 h-32 rounded-2xl object-cover border-4 border-white/30 shadow-2xl">
                        @else
                            {{-- Si no tiene foto, mostrar inicial --}}
                            <div
                                class="w-32 h-32 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 border-4 border-white/30 flex items-center justify-center text-white text-5xl font-bold shadow-2xl backdrop-blur-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        {{-- Badge de verificación (siempre visible) --}}
                        <div
                            class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-full border-4 border-purple-700 flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    {{-- Info ejecutiva --}}
                    <div>
                        <h1 class="text-5xl font-playfair font-bold text-white mb-3 tracking-tight">
                            {{ $user->name }}
                        </h1>
                        @if ($user->profileDetail->occupation || $user->profileDetail->industry)
                            <p class="text-xl text-purple-100 font-medium mb-3">
                                {{ $user->profileDetail->occupation }}
                                @if ($user->profileDetail->industry)
                                    <span class="text-purple-200">•
                                        {{ \App\Models\ProfileDetail::industries()[$user->profileDetail->industry] ?? '' }}</span>
                                @endif
                            </p>
                        @endif
                        <div class="flex items-center gap-3 text-white/90 text-sm flex-wrap">
                            @if ($user->age)
                                <span
                                    class="px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-lg border border-white/20 font-semibold">
                                    {{ $user->age }} años
                                </span>
                            @endif
                            @if ($user->city)
                                <span
                                    class="px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-lg border border-white/20 font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $user->city }}
                                </span>
                            @endif
                            <span
                                class="px-4 py-1.5 rounded-full bg-amber-500/30 backdrop-blur-lg border border-amber-400/50 font-bold shadow-lg text-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z"
                                        transform="scale(0.8) translate(3,3)" />
                                    <path d="M2 20h20v2H2z" />
                                </svg>
                                Sugar Daddy
                            </span>

                            {{-- ✅ INSTAGRAM PREMIUM --}}
                            @if($user->profileDetail->social_instagram)
                                @if($canSeeInstagram || $isOwnProfile)
                                    <a href="https://instagram.com/{{ str_replace('@', '', $user->profileDetail->social_instagram) }}"
                                        target="_blank"
                                        class="px-4 py-1.5 rounded-full bg-pink-500/30 backdrop-blur-lg border border-pink-400/50 font-bold shadow-lg flex items-center gap-2 hover:bg-pink-500/50 transition-all text-sm">
                                        <span>📸</span> {{ $user->profileDetail->social_instagram }}
                                    </a>
                                @else
                                    <div class="px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-lg border border-white/20 text-white/50 text-xs flex items-center gap-2 cursor-help group relative"
                                        title="Suscríbete a Premium para ver Redes Sociales">
                                        <span>📸</span> Instagram oculto 🔒
                                        <div
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-black/80 rounded-lg text-[10px] opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none text-center">
                                            Suscríbete a Premium para <br> desbloquear redes sociales
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}"
                        class="px-8 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border border-white/30 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-xl">
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
                            @if($hasLiked) @method('DELETE') @endif
                            <button type="submit"
                                class="px-8 py-4 {{ $hasLiked ? 'bg-pink-500/80' : 'bg-white/20' }} hover:bg-white/30 backdrop-blur-lg border border-white/30 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-xl flex items-center gap-2">
                                <svg class="w-6 h-6" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                {{ $hasLiked ? 'Me Gusta' : 'Dar Like' }}
                            </button>
                        </form>

                        {{-- Chat Button --}}
                        <a href="{{ route('chat.show', $user) }}"
                            class="px-8 py-4 bg-white text-purple-700 font-bold rounded-2xl transition-all duration-300 hover:scale-105 shadow-xl flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Mensaje
                        </a>
                    </div>
                @endif
            </div>

            {{-- Bio --}}
            @if ($user->bio)
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-xl">
                    <p class="text-white/95 text-lg leading-relaxed">
                        {{ $user->bio }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Grid de secciones --}}
    @if(!empty($isRestricted) && $isRestricted)
        {{-- VISTA RESTRINGIDA (Perfil Privado) --}}
        <div class="min-h-[50vh] flex flex-col items-center justify-center text-center p-8 bg-gray-50 rounded-3xl mt-8">
            <div class="bg-white rounded-3xl p-12 max-w-2xl w-full shadow-xl border border-gray-100">
                <div
                    class="w-24 h-24 bg-gradient-to-br from-gray-800 to-black rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg border border-white/10">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-4">Perfil Privado</h2>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                    Este perfil es exclusivo y mantiene su información privada.
                    <br>
                    <span class="font-semibold text-gray-800">Da Like o envía un mensaje</span>. Si hacen Match, tendrás acceso
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
                        class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-gray-50 text-gray-900 font-bold rounded-2xl shadow-lg border border-gray-200 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- Perfil Profesional --}}
            <div
                class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-2xl shadow-lg">
                        💼
                    </div>
                    <div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Perfil Profesional</h2>
                        <p class="text-sm text-gray-500">Carrera y logros</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if ($user->profileDetail->occupation)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-purple-50 to-purple-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Ocupación</span>
                            <span class="text-gray-900 font-bold">{{ $user->profileDetail->occupation }}</span>
                        </div>
                    @endif
                    @if ($user->profileDetail->industry)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-indigo-50 to-indigo-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Industria</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::industries()[$user->profileDetail->industry] ?? $user->profileDetail->industry }}</span>
                        </div>
                    @endif
                    @if ($user->profileDetail->company_size)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Empresa</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::companySizes()[$user->profileDetail->company_size] ?? $user->profileDetail->company_size }}</span>
                        </div>
                    @endif
                    @if ($user->profileDetail->education)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-cyan-50 to-cyan-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Educación</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::educationLevels()[$user->profileDetail->education] ?? $user->profileDetail->education }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Estilo de Vida --}}
            <div
                class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-2xl shadow-lg">
                        ✈️
                    </div>
                    <div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Estilo de Vida</h2>
                        <p class="text-sm text-gray-500">Intereses y actividades</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @if ($user->profileDetail->income_range)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-green-50 to-green-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Ingresos</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::incomeRanges()[$user->profileDetail->income_range] ?? $user->profileDetail->income_range }}</span>
                        </div>
                    @endif
                    @if ($user->profileDetail->travel_frequency)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-sky-50 to-sky-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Viajes</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::travelFrequencies()[$user->profileDetail->travel_frequency] ?? $user->profileDetail->travel_frequency }}</span>
                        </div>
                    @endif
                    @if ($user->profileDetail->availability)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-teal-50 to-teal-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Disponibilidad</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::availabilityOptions()[$user->profileDetail->availability] ?? $user->profileDetail->availability }}</span>
                        </div>
                    @endif
                    @if ($user->profileDetail->relationship_status)
                        <div
                            class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-rose-50 to-rose-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Estado civil</span>
                            <span
                                class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::relationshipStatuses()[$user->profileDetail->relationship_status] ?? $user->profileDetail->relationship_status }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Qué puedo ofrecer --}}
            @if ($user->profileDetail->what_i_offer)
                <div
                    class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-3xl shadow-xl p-8 border-2 border-amber-200/50 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white text-2xl shadow-lg">
                            🎁
                        </div>
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-gray-900">Qué Puedo Ofrecer</h2>
                            <p class="text-sm text-gray-600">Generosidad y experiencias</p>
                        </div>
                    </div>

                    <p class="text-gray-800 leading-relaxed text-lg font-medium">
                        {{ $user->profileDetail->what_i_offer }}
                    </p>
                </div>
            @endif

            {{-- Áreas de Mentoría --}}
            @if ($user->profileDetail->mentorship_areas && count($user->profileDetail->mentorship_areas) > 0)
                <div
                    class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-2xl shadow-lg">
                            🏆
                        </div>
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-gray-900">Mentoría</h2>
                            <p class="text-sm text-gray-500">Áreas de experiencia</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @foreach ($user->profileDetail->mentorship_areas as $area)
                            <span
                                class="px-5 py-2.5 bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-800 border-2 border-indigo-300 rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-shadow">
                                {{ \App\Models\ProfileDetail::mentorshipAreasOptions()[$area] ?? $area }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Mis Intereses (ancho completo) --}}
            @if ($user->profileDetail->interests && count($user->profileDetail->interests) > 0)
                <div
                    class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-2xl shadow-lg">
                            ❤️
                        </div>
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-gray-900">Mis Intereses</h2>
                            <p class="text-sm text-gray-500">Pasiones y hobbies</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @foreach ($user->profileDetail->interests as $interest)
                            <span
                                class="px-5 py-2.5 bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 border-2 border-purple-300 rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-shadow">
                                {{ \App\Models\ProfileDetail::interestsOptions()[$interest] ?? $interest }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Galería de Fotos (ancho completo, debajo de intereses) --}}
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
                                <h2 class="text-2xl font-playfair font-bold text-gray-900">Mi Galería</h2>
                                <p class="text-sm text-gray-500">{{ $user->photos->count() }} fotos</p>
                            </div>
                        </div>
                        @if ($isOwnProfile)
                            <a href="{{ route('profile.photos.index') }}"
                                class="px-6 py-2.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded-xl transition-colors duration-300">
                                Ver todas →
                            </a>
                        @else
                            <button @click="openLightbox(0)"
                                class="px-6 py-2.5 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded-xl transition-colors duration-300">
                                Ver todas →
                            </button>
                        @endif
                    </div>

                    {{-- Grid de fotos - Más grande --}}
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
                                class="aspect-square rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex flex-col items-center justify-center text-white hover:scale-105 transition-transform duration-300 shadow-xl">
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
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <div x-show="currentIndex === index" x-transition:enter="transition ease-out duration-300"
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
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>

                                {{-- Botón Siguiente --}}
                                <button @click.stop="next" x-show="photos.length > 1"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 p-4 bg-white/10 hover:bg-white/25 rounded-full transition-all duration-300 backdrop-blur-lg"
                                    aria-label="Imagen siguiente">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                {{-- Indicadores (Dots) --}}
                                <div x-show="photos.length > 1"
                                    class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2">
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <button @click="currentIndex = index" class="h-2.5 rounded-full transition-all duration-300"
                                            :class="index === currentIndex ? 'bg-white w-10' : 'bg-white/50 hover:bg-white/75 w-2.5'"
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
                    class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-3xl shadow-xl p-12 text-center text-white hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-playfair font-bold mb-3">Sube tus fotos</h3>
                    <p class="text-white/90 mb-8 text-lg">Las fotos aumentan significativamente tus posibilidades de conectar
                    </p>
                    <a href="{{ route('profile.photos.index') }}"
                        class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl font-bold transition-all duration-300 hover:scale-105 shadow-2xl">
                        📤 Subir Fotos
                    </a>
                </div>
            @endif

            {{-- Qué busco --}}
            @if ($user->profileDetail->looking_for)
                <div
                    class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-3xl shadow-xl p-8 border-2 border-purple-200/50 hover:shadow-2xl transition-shadow duration-300 lg:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-800 flex items-center justify-center text-white text-2xl shadow-lg">
                            💫
                        </div>
                        <div>
                            <h2 class="text-2xl font-playfair font-bold text-gray-900">Qué Busco</h2>
                            <p class="text-sm text-gray-600">Mi ideal de relación</p>
                        </div>
                    </div>

                    <p class="text-gray-800 leading-relaxed text-lg font-medium">
                        {{ $user->profileDetail->looking_for }}
                    </p>
                </div>
            @endif

        </div>

    @endif

    {{-- CTA si no tiene perfil completo --}}
    @if ($isOwnProfile && (!$user->profileDetail->occupation || !$user->profileDetail->what_i_offer))
        <div
            class="mt-8 bg-gradient-to-br from-purple-600 to-indigo-800 rounded-3xl p-12 shadow-2xl text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-white/5 backdrop-blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-3xl font-playfair font-bold text-white mb-3">
                    Destaca tu perfil profesional
                </h3>
                <p class="text-white/90 mb-8 text-lg">
                    Completa tu perfil para atraer a las mejores Sugar Babies
                </p>
                <a href="{{ route('profile.edit') }}"
                    class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-2xl">
                    Completar Perfil →
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