@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-red-50 via-purple-50 to-pink-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-red-600" style="font-family: 'Playfair Display', serif;">
                    🛡️ Panel de Moderación
                </h1>
                <p class="text-gray-600 mt-2">Sistema de administración y seguridad</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Reportes Pendientes -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Reportes Pendientes</p>
                            <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['pending_reports'] }}</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-full">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('admin.moderation.reports', ['status' => 'pending']) }}"
                        class="text-red-600 text-sm font-medium mt-4 inline-block hover:underline">
                        Ver todos →
                    </a>
                </div>

                <!-- Usuarios Baneados -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Usuarios Baneados</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_bans'] }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-full">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Suspensiones Activas -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Suspensiones Activas</p>
                            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $stats['active_suspensions'] }}</p>
                        </div>
                        <div class="bg-orange-100 p-4 rounded-full">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('admin.moderation.reports') }}"
                    class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition-all">
                    <svg class="w-10 h-10 mx-auto text-red-600 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-semibold text-gray-700">Revisar Reportes</span>
                </a>

                <a href="{{ route('admin.moderation.users') }}"
                    class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition-all">
                    <svg class="w-10 h-10 mx-auto text-purple-600 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="font-semibold text-gray-700">Gestionar Usuarios</span>
                </a>

                <a href="{{ route('admin.moderation.blocked-words') }}"
                    class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition-all">
                    <svg class="w-10 h-10 mx-auto text-pink-600 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                    <span class="font-semibold text-gray-700">Palabras Bloqueadas</span>
                </a>

                <a href="{{ route('admin.moderation.dashboard') }}"
                    class="bg-white rounded-xl shadow p-6 text-center hover:shadow-lg transition-all">
                    <svg class="w-10 h-10 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="font-semibold text-gray-700">Dashboard Principal</span>
                </a>
            </div>

            <!-- Recent Reports -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Reportes Recientes</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario
                                    Reportado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Razón</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentReports as $report)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <img src="{{ $report->reportedUser->getPrimaryPhotoUrlAttribute ?? '/images/default-avatar.png' }}"
                                                class="w-10 h-10 rounded-full mr-3">
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $report->reportedUser->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $report->reportedUser->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $report->reason === 'profanity'
                                            ? 'bg-red-100 text-red-800'
                                            : ($report->reason === 'harassment'
                                                ? 'bg-orange-100 text-orange-800'
                                                : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($report->reason) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 capitalize">{{ $report->type }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $report->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.moderation.reports.show', $report) }}"
                                            class="text-purple-600 hover:text-purple-800 font-medium">
                                            Revisar →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No hay reportes pendientes
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Actions -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Acciones Recientes</h2>
                <div class="space-y-4">
                    @forelse($recentActions as $action)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="p-2 rounded-full 
                                {{ $action->action_type === 'ban'
                                    ? 'bg-red-100 text-red-600'
                                    : ($action->action_type === 'suspension'
                                        ? 'bg-orange-100 text-orange-600'
                                        : 'bg-yellow-100 text-yellow-600') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">
                                        {{ ucfirst($action->action_type) }} - {{ $action->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $action->reason }}</p>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $action->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">No hay acciones recientes</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
