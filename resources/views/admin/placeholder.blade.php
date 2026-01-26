@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div
            class="w-32 h-32 bg-pink-500/10 rounded-full flex items-center justify-center text-pink-500 mb-8 border border-pink-500/20">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <h2 class="text-3xl font-outfit font-black text-white mb-4">{{ $title }}</h2>
        <p class="text-gray-500 max-w-md mx-auto leading-relaxed">
            Este módulo se encuentra actualmente en desarrollo como parte de la consolidación del panel de Super Admin.
            Estará disponible próximamente con funcionalidades detalladas.
        </p>
        <div class="mt-8 flex gap-4">
            <a href="{{ route('admin.dashboard') }}"
                class="px-8 py-3 bg-pink-500 hover:bg-pink-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-pink-500/20">
                Volver al Dashboard
            </a>
        </div>
    </div>
@endsection