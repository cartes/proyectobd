@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.moderation.users') }}" class="text-red-600 hover:text-red-800 font-medium mb-4 inline-block">
                ← Volver a usuarios
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <!-- Perfil -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="flex items-start space-x-6">
                        <img src="{{ $user->primary_photo_url ?? '/images/default-avatar.png' }}" 
                             class="w-24 h-24 rounded-full object-cover">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                            <p class="text-gray-600 mt-1">
                                <span class="capitalize">{{ $user->user_type === 'sugar_daddy' ? '💎 Sugar Daddy' : '👶 Sugar Baby' }}</span> • 
                                {{ $user->age }} años • {{ $user->city }}
                            </p>
                            
                            <div class="mt-4 grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Mensajes</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $user->sent_messages_count }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Reportes</p>
                                    <p class="text-2xl font-bold text-red-600">{{ $reports->total() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Acciones</p>
                                    <p class="text-2xl font-bold text-orange-600">{{ $actions->total() }}</p>
                                </div>
                            </div>

                            @if($user->isBanned())
                                <div class="mt-4 p-3 bg-red-100 text-red-800 rounded-lg">
                                    🚫 <strong>Baneado</strong>
                                </div>
                            @elseif($user->isSuspended())
                                <div class="mt-4 p-3 bg-orange-100 text-orange-800 rounded-lg">
                                    ⏸️ <strong>Suspendido</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reportes -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Reportes Recientes</h2>
                    
                    <div class="space-y-3">
                        @forelse($reports as $report)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $report->reason)) }}</p>
                                        <p class="text-sm text-gray-600 mt-1">Por: {{ $report->reporter->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ route('admin.moderation.reports.show', $report) }}" 
                                       class="text-red-600 hover:text-red-800 font-medium">
                                        Ver →
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay reportes</p>
                        @endforelse
                    </div>

                    {{ $reports->links() }}
                </div>
            </div>

            <!-- Acciones Laterales -->
            <div class="lg:col-span-1">
                <!-- Acciones -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Acciones</h3>
                    
                    <form method="POST" action="{{ route('admin.moderation.users.action', $user) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Acción</label>
                            <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" required>
                                <option value="">Selecciona una acción</option>
                                <option value="warn">Advertencia</option>
                                <option value="suspend">Suspender</option>
                                <option value="ban">Banear</option>
                                @if($user->isBanned() || $user->isSuspended())
                                    <option value="unban">Levantar restricción</option>
                                @endif
                            </select>
                        </div>

                        <div id="days-container" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Días</label>
                            <input type="number" name="days" min="1" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Razón</label>
                            <textarea name="reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="Razón de la acción..." required></textarea>
                        </div>

                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all">
                            Ejecutar Acción
                        </button>
                    </form>

                    <script>
                        document.querySelector('[name="action"]').addEventListener('change', function() {
                            document.getElementById('days-container').style.display = 
                                this.value === 'suspend' ? 'block' : 'none';
                        });
                    </script>
                </div>

                <!-- Histórico de Acciones -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Histórico</h3>
                    
                    <div class="space-y-3">
                        @forelse($actions as $action)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm font-medium text-gray-900 capitalize">
                                    {{ str_replace('_', ' ', $action->action_type) }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">{{ $action->reason }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $action->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Sin acciones</p>
                        @endforelse
                    </div>

                    {{ $actions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
