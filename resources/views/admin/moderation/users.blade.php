@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-red-50 via-purple-50 to-pink-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-red-600" style="font-family: 'Playfair Display', serif;">
                    👥 Gestión de Usuarios
                </h1>
                <p class="text-gray-600 mt-2">Total de usuarios: {{ $users->total() }}</p>
            </div>

            <!-- Filtros -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <input type="text" name="search" placeholder="Nombre o email..." value="{{ request('search') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select name="user_type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <option value="">Todos</option>
                            <option value="sugar_daddy" {{ request('user_type') === 'sugar_daddy' ? 'selected' : '' }}>Sugar
                                Daddy</option>
                            <option value="sugar_baby" {{ request('user_type') === 'sugar_baby' ? 'selected' : '' }}>Sugar
                                Baby</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            <option value="">Todos</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendidos
                            </option>
                            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Baneados</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Mensajes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Reportes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center mr-4">
                                        <x-user-avatar :user="$user" size="md" class="mr-3" />
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->user_type === 'sugar_daddy' ? 'bg-purple-100 text-purple-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $user->user_type === 'sugar_daddy' ? '💎 Daddy' : '👶 Baby' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->sent_messages_count }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->reports()->count() }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->isBanned())
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            🚫 Baneado
                                        </span>
                                    @elseif($user->isSuspended())
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            ⏸️ Suspendido
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Activo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.moderation.users.show', $user) }}"
                                        class="text-red-600 hover:text-red-800 font-medium">
                                        Ver →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No hay usuarios
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
