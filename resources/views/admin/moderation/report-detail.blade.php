@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.moderation.reports') }}" class="text-red-600 hover:text-red-800 font-medium mb-4 inline-block">
                ← Volver a reportes
            </a>
            <h1 class="text-4xl font-bold text-red-600" style="font-family: 'Playfair Display', serif;">
                Detalle del Reporte #{{ $report->id }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <!-- Reporte Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Información del Reporte</h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $report->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($report->status === 'reviewed' ? 'bg-blue-100 text-blue-800' :
                                       ($report->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <p class="mt-1 text-gray-900 capitalize">{{ $report->type }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Razón</label>
                                <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                <p class="mt-1 text-gray-900">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <p class="mt-1 text-gray-700 bg-gray-50 p-3 rounded">{{ $report->description ?? 'Sin descripción' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Usuario Reportado -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Usuario Reportado</h3>
                    
                    <div class="flex items-start space-x-4">
                        <img src="{{ $report->reportedUser->getPrimaryPhotoUrlAttribute ?? '/images/default-avatar.png' }}" 
                             class="w-20 h-20 rounded-full object-cover">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $report->reportedUser->name }}</h4>
                            <p class="text-gray-600">{{ $report->reportedUser->email }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="capitalize">{{ $report->reportedUser->user_type }}</span> • 
                                {{ $report->reportedUser->age }} años • {{ $report->reportedUser->city }}
                            </p>
                            
                            <div class="mt-3 space-y-2">
                                <p class="text-sm"><strong>Mensajes enviados:</strong> {{ $report->reportedUser->sentMessages()->count() }}</p>
                                <p class="text-sm"><strong>Reportes en su contra:</strong> {{ $userHistory->count() }}</p>
                            </div>

                            <a href="{{ route('admin.moderation.users.show', $report->reportedUser) }}" 
                               class="mt-3 inline-block text-red-600 hover:text-red-800 font-medium">
                                Ver perfil completo →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mensaje Reportado -->
                @if($report->message)
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Mensaje Reportado</h3>
                        
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-800">{{ $report->message->content }}</p>
                            <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $report->message->created_at->format('d/m/Y H:i') }}</span>
                                @if($report->message->is_flagged)
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">
                                        ⚠️ Moderado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Acciones Laterales -->
            <div class="lg:col-span-1">
                <!-- Histórico del Usuario -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Histórico de Reportes</h3>
                    
                    <div class="space-y-3">
                        @forelse($userHistory as $hist)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $hist->reason)) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $hist->created_at->diffForHumans() }}</p>
                                <p class="text-xs text-gray-600 mt-1">Por: {{ $hist->reporter->name }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay reportes anteriores</p>
                        @endforelse
                    </div>
                </div>

                <!-- Acciones Disponibles -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
                    
                    @if($report->status === 'pending')
                        <form method="POST" action="{{ route('admin.moderation.reports.process', $report) }}" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                                <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" required>
                                    <option value="">Selecciona una acción</option>
                                    <option value="dismiss">Rechazar reporte</option>
                                    <option value="warn">Advertencia</option>
                                    <option value="suspend">Suspender</option>
                                    <option value="ban">Banear</option>
                                </select>
                            </div>

                            <div id="days-container" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Días de suspensión</label>
                                <input type="number" name="days" min="1" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Notas sobre la acción..."></textarea>
                            </div>

                            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all">
                                Procesar Reporte
                            </button>
                        </form>

                        <script>
                            document.querySelector('[name="action"]').addEventListener('change', function() {
                                document.getElementById('days-container').style.display = 
                                    this.value === 'suspend' ? 'block' : 'none';
                            });
                        </script>
                    @else
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600"><strong>Revisado por:</strong> {{ $report->reviewedBy->name ?? 'Sistema' }}</p>
                            <p class="text-sm text-gray-600 mt-2"><strong>Fecha:</strong> {{ $report->reviewed_at?->format('d/m/Y H:i') }}</p>
                            @if($report->admin_notes)
                                <p class="text-sm text-gray-600 mt-2"><strong>Notas:</strong></p>
                                <p class="text-sm text-gray-700 bg-white p-2 rounded mt-1">{{ $report->admin_notes }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
