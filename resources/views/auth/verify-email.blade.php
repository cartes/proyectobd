@extends('layouts.app')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-rose-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            {{-- Card --}}
            <div class="bg-white rounded-3xl shadow-2xl p-8 space-y-6">
                {{-- Icon --}}
                <div class="flex justify-center">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-pink-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                {{-- Title --}}
                <div class="text-center">
                    <h1 class="text-3xl font-black text-gray-900 mb-2" style="font-family: 'Outfit', sans-serif;">
                        Verifica tu Email
                    </h1>
                    <p class="text-gray-600">
                        ¡Gracias por registrarte! Antes de comenzar, verifica tu dirección de email haciendo clic en el
                        enlace que te acabamos de enviar.
                    </p>
                </div>

                {{-- Success Message --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-emerald-800 font-medium">
                            ¡Nuevo enlace enviado! Revisa tu bandeja de entrada.
                        </p>
                    </div>
                @endif

                {{-- Email Display --}}
                <div class="bg-gray-50 rounded-2xl p-4 text-center">
                    <p class="text-sm text-gray-500 mb-1">Email enviado a:</p>
                    <p class="text-lg font-bold text-gray-900">{{ auth()->user()->email }}</p>
                </div>

                {{-- Actions --}}
                <div class="space-y-3">
                    {{-- Resend Button --}}
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 transition-all transform hover:-translate-y-0.5">
                            Reenviar Email de Verificación
                        </button>
                    </form>

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-white border-2 border-gray-200 text-gray-700 font-bold py-4 rounded-2xl hover:bg-gray-50 transition-all">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>

                {{-- Help Text --}}
                <div class="text-center pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">
                        ¿No recibiste el email? Revisa tu carpeta de spam o haz clic en "Reenviar"
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
