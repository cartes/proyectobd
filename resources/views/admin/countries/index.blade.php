@extends('layouts.admin')

@section('title', 'Gestión de Países')

@section('content')
    <div class="space-y-6">

        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-outfit font-black text-white mb-2">🌍 Gestión de Países</h1>
            <p class="text-gray-400">Activa o desactiva países donde Big-dad puede ofrecer soporte.</p>
        </div>

        {{-- Country Management Table --}}
        <div class="bg-gray-900 rounded-3xl p-8 border border-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                ISO
                            </th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                País
                            </th>
                            <th class="text-left py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="text-right py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($countries as $country)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-4 px-4">
                                    <span class="text-2xl">{{ $country->flag ?? '🏳️' }}</span>
                                    <span class="ml-2 text-sm font-mono text-gray-400">{{ $country->iso_code }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-sm font-medium text-white">{{ $country->name }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    @if ($country->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-500 border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <form action="{{ route('admin.countries.toggle', $country) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $country->is_active ? 'bg-rose-500/10 text-rose-500 hover:bg-rose-500/20 border border-rose-500/20' : 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/20 border border-emerald-500/20' }}">
                                            {{ $country->is_active ? '🚫 Desactivar' : '✅ Activar' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">
                                    No hay países registrados en el sistema.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Info Box --}}
            <div class="mt-6 p-4 bg-blue-500/5 border border-blue-500/20 rounded-2xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-blue-400 font-medium">
                            Los países inactivos no aparecerán en el formulario de registro. Los usuarios
                            existentes de países inactivos mantendrán su acceso.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Quick Link to Config --}}
            <div class="mt-6 pt-6 border-t border-gray-800">
                <a href="{{ route('admin.system.config') }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    También disponible en Configuración Global
                </a>
            </div>
        </div>

    </div>
@endsection
