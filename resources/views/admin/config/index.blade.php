@extends('layouts.admin')

@section('title', 'Configuración Global')

@section('content')
    <div class="space-y-6">

        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-outfit font-black text-white mb-2">⚙️ Configuración</h1>
            <p class="text-gray-400">Gestiona la seguridad y parámetros globales del sistema.</p>
        </div>

        {{-- Password Change Section (User Request: First Section) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <h3 class="text-xl font-bold text-white mb-2">Seguridad de la Cuenta</h3>
                <p class="text-gray-400 text-sm">
                    Mantén tu cuenta de administrador segura actualizando tu contraseña periódicamente.
                </p>
            </div>

            <div class="lg:col-span-2">
                @include('profile.partials.premium-password-form')
            </div>
        </div>

        <hr class="border-gray-800 my-8">

        {{-- Placeholder for Future Modules --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 opacity-50 pointer-events-none grayscale">
            <div class="lg:col-span-1">
                <h3 class="text-xl font-bold text-white mb-2">Parámetros del Sistema</h3>
                <p class="text-gray-400 text-sm">
                    Configuraciones generales del sitio (Próximamente).
                </p>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-3xl p-8 border border-gray-800 text-center">
                    <p class="text-gray-500 italic">Módulo de parámetros en desarrollo...</p>
                </div>
            </div>
        </div>

    </div>
@endsection