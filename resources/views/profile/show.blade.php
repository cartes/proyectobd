@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('content')
    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
             class="mb-6 bg-gradient-to-r {{ $user->user_type === 'sugar_daddy' ? 'from-purple-500/90 to-purple-600/90' : 'from-pink-500/90 to-pink-600/90' }} backdrop-blur-lg rounded-2xl p-4 text-white shadow-xl border border-white/20">
            <p class="flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- Header del perfil --}}
    <div class="bg-gradient-to-br {{ $user->user_type === 'sugar_daddy' ? 'from-purple-500 to-purple-700' : 'from-pink-500 to-pink-700' }} rounded-3xl p-8 mb-6 shadow-2xl text-white relative overflow-hidden">
        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 bg-white/5 backdrop-blur-3xl"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex items-start justify-between flex-wrap gap-4 mb-6">
                <div class="flex items-center gap-6">
                    {{-- Avatar --}}
                    <div class="relative">
                        <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-lg border-4 border-white/30 flex items-center justify-center text-white text-4xl font-bold shadow-2xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Info básica --}}
                    <div>
                        <h1 class="text-4xl md:text-5xl font-playfair font-bold text-white mb-2">
                            {{ $user->name }}
                        </h1>
                        <div class="flex items-center gap-3 text-white/90 text-sm flex-wrap">
                            @if($user->age)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $user->age }} años
                                </span>
                            @endif
                            @if($user->city)
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $user->city }}
                                </span>
                            @endif
                            <span class="px-4 py-1.5 rounded-full text-xs font-bold bg-white/20 backdrop-blur-lg border border-white/30 shadow-lg">
                                {{ $user->user_type === 'sugar_daddy' ? '👔 Sugar Daddy' : '💎 Sugar Baby' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Botón editar --}}
                <a href="{{ route('profile.edit') }}" 
                   class="px-6 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-lg border border-white/30 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-lg">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Perfil
                    </span>
                </a>
            </div>

            {{-- Bio --}}
            @if($user->bio)
                <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-6 shadow-xl">
                    <p class="text-white/95 text-lg leading-relaxed">
                        {{ $user->bio }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Detalles del perfil --}}
    @if($user->profileDetail)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Información Personal --}}
            <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xl shadow-lg">
                        👤
                    </div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Información Personal</h2>
                </div>
                
                <div class="space-y-4">
                    @if($user->profileDetail->height)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Altura</span>
                            <span class="text-gray-900 font-bold">{{ $user->profileDetail->height }} cm</span>
                        </div>
                    @endif
                    @if($user->profileDetail->body_type)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-indigo-50 to-indigo-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Tipo de cuerpo</span>
                            <span class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::bodyTypes()[$user->profileDetail->body_type] ?? $user->profileDetail->body_type }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->relationship_status)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-purple-50 to-purple-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Estado civil</span>
                            <span class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::relationshipStatuses()[$user->profileDetail->relationship_status] ?? $user->profileDetail->relationship_status }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->children)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-pink-50 to-pink-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Hijos</span>
                            <span class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::childrenOptions()[$user->profileDetail->children] ?? $user->profileDetail->children }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Educación y Profesión --}}
            <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-xl shadow-lg">
                        🎓
                    </div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Educación y Profesión</h2>
                </div>
                
                <div class="space-y-4">
                    @if($user->profileDetail->education)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-green-50 to-green-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Educación</span>
                            <span class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::educationLevels()[$user->profileDetail->education] ?? $user->profileDetail->education }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->occupation)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-emerald-50 to-emerald-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Ocupación</span>
                            <span class="text-gray-900 font-bold">{{ $user->profileDetail->occupation }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->income_range && $user->user_type === 'sugar_daddy')
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-teal-50 to-teal-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Ingresos</span>
                            <span class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::incomeRanges()[$user->profileDetail->income_range] ?? $user->profileDetail->income_range }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->availability)
                        <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-cyan-50 to-cyan-100/50 rounded-xl">
                            <span class="text-gray-700 font-medium">Disponibilidad</span>
                            <span class="text-gray-900 font-bold">{{ \App\Models\ProfileDetail::availabilityOptions()[$user->profileDetail->availability] ?? $user->profileDetail->availability }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Intereses --}}
            @if($user->profileDetail->interests && count($user->profileDetail->interests) > 0)
                <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white text-xl shadow-lg">
                            ❤️
                        </div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Intereses</h2>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        @foreach($user->profileDetail->interests as $interest)
                            <span class="px-5 py-2.5 bg-gradient-to-r {{ $user->user_type === 'sugar_daddy' ? 'from-purple-100 to-purple-200 text-purple-700 border-purple-300' : 'from-pink-100 to-pink-200 text-pink-700 border-pink-300' }} border rounded-full text-sm font-bold shadow-sm hover:shadow-md transition-shadow">
                                {{ \App\Models\ProfileDetail::interestsOptions()[$interest] ?? $interest }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Qué busco --}}
            @if($user->profileDetail->looking_for)
                <div class="bg-white rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xl shadow-lg">
                            💫
                        </div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900">Qué busco</h2>
                    </div>
                    
                    <p class="text-gray-700 leading-relaxed text-lg">
                        {{ $user->profileDetail->looking_for }}
                    </p>
                </div>
            @endif

        </div>
    @else
        {{-- Sin detalles de perfil --}}
        <div class="bg-gradient-to-br {{ $user->user_type === 'sugar_daddy' ? 'from-purple-500 to-purple-700' : 'from-pink-500 to-pink-700' }} rounded-3xl p-12 shadow-2xl text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-white/5 backdrop-blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="text-7xl mb-4">✨</div>
                <h3 class="text-3xl font-playfair font-bold text-white mb-3">
                    Completa tu perfil
                </h3>
                <p class="text-white/90 mb-8 text-lg">
                    Agrega más información para que otros usuarios te conozcan mejor
                </p>
                <a href="{{ route('profile.edit') }}" 
                   class="inline-block px-10 py-4 bg-white/20 hover:bg-white/30 backdrop-blur-lg border-2 border-white/40 rounded-2xl text-white font-bold transition-all duration-300 hover:scale-105 shadow-2xl">
                    Completar Perfil →
                </a>
            </div>
        </div>
    @endif
@endsection
