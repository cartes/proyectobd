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

        {{-- Country Management Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <h3 class="text-xl font-bold text-white mb-2">🌍 Gestión de Países</h3>
                <p class="text-gray-400 text-sm">
                    Activa o desactiva países donde Big-dad puede ofrecer soporte. Los países inactivos no estarán
                    disponibles para nuevos registros.
                </p>
            </div>

            <div class="lg:col-span-2">
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
                </div>
            </div>
        </div>

        <hr class="border-gray-800 my-8">

        {{-- Notification Settings --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <h3 class="text-xl font-bold text-white mb-2">🔔 Notificaciones</h3>
                <p class="text-gray-400 text-sm">
                    Correo que recibe alertas automáticas cuando un nuevo usuario se registra en la plataforma.
                </p>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-3xl p-8 border border-gray-800">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">
                        Email de notificaciones de admin
                    </h4>

                    @php $adminEmail = config('app.admin_notification_email'); @endphp

                    @if ($adminEmail)
                        <div class="flex items-center gap-3 p-4 bg-emerald-500/5 border border-emerald-500/20 rounded-2xl mb-4">
                            <span class="text-2xl">✅</span>
                            <div>
                                <p class="text-sm font-semibold text-emerald-400">Notificaciones activas</p>
                                <p class="text-sm text-gray-300 font-mono mt-0.5">{{ $adminEmail }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 p-4 bg-yellow-500/5 border border-yellow-500/20 rounded-2xl mb-4">
                            <span class="text-2xl">⚠️</span>
                            <div>
                                <p class="text-sm font-semibold text-yellow-400">Sin email configurado</p>
                                <p class="text-sm text-gray-400 mt-0.5">No se enviarán notificaciones de nuevos registros.</p>
                            </div>
                        </div>
                    @endif

                    <div class="p-4 bg-blue-500/5 border border-blue-500/20 rounded-2xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-blue-400">
                                Para cambiar el email, define la variable <code class="bg-blue-500/10 px-1.5 py-0.5 rounded text-blue-300 font-mono text-xs">ADMIN_NOTIFICATION_EMAIL</code> en el archivo <code class="bg-blue-500/10 px-1.5 py-0.5 rounded text-blue-300 font-mono text-xs">.env</code> del servidor y reinicia la aplicación.
                            </p>
                        </div>
                    </div>
                </div>
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