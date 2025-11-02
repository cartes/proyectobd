@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-red-600" style="font-family: 'Playfair Display', serif;">
                    📋 Gestión de Reportes
                </h1>
                <p class="text-gray-600 mt-2">Total de reportes: {{ $reports->total() }}</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">Todos</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                        <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Revisados</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resueltos</option>
                        <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Rechazados</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">Todos</option>
                        <option value="message" {{ request('type') === 'message' ? 'selected' : '' }}>Mensaje</option>
                        <option value="conversation" {{ request('type') === 'conversation' ? 'selected' : '' }}>Conversación</option>
                        <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>Usuario</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Razón</label>
                    <select name="reason" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                        <option value="">Todas</option>
                        <option value="profanity" {{ request('reason') === 'profanity' ? 'selected' : '' }}>Profanidad</option>
                        <option value="harassment" {{ request('reason') === 'harassment' ? 'selected' : '' }}>Acoso</option>
                        <option value="inappropriate" {{ request('reason') === 'inappropriate' ? 'selected' : '' }}>Inapropiado</option>
                        <option value="spam" {{ request('reason') === 'spam' ? 'selected' : '' }}>Spam</option>
                        <option value="other" {{ request('reason') === 'other' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Reportes -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Razón</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $report->reportedUser->getPrimaryPhotoUrlAttribute ?? '/images/default-avatar.png' }}" 
                                         class="w-10 h-10 rounded-full mr-3">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $report->reportedUser->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $report->reportedUser->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $report->reason === 'profanity' ? 'bg-red-100 text-red-800' : 
                                       ($report->reason === 'harassment' ? 'bg-orange-100 text-orange-800' : 
                                       ($report->reason === 'spam' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $report->type }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800' :
                                       ($report->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.moderation.reports.show', $report) }}" 
                                   class="text-red-600 hover:text-red-800 font-medium">
                                    Revisar →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No hay reportes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
