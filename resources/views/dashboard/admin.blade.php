<div class="bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">¡Hola, {{ Auth::user()->name }}! 🛡️</h2>
                    <p class="text-red-100 mt-1">Gestiona Big-dad desde tu panel de control</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-red-100">{{ now()->format('d M Y') }}</p>
                    <p class="text-lg font-semibold">{{ now()->format('H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                        <p class="text-2xl font-bold text-gray-900">{{ App\Models\User::count() }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">
                        +{{ App\Models\User::where('created_at', '>=', now()->startOfMonth())->count() }} este mes
                    </span>
                </div>
            </div>

            <!-- Active Premium -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Premium Activos</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ App\Models\User::where('is_premium', true)->count() }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">
                        +{{ App\Models\User::where('is_premium', true)->where('created_at', '>=', now()->startOfMonth())->count() }}
                        este mes
                    </span>
                </div>
            </div>

            <!-- Pending Reports -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Reportes Pendientes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ App\Models\Report::pending()->count() }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-red-600 text-sm font-medium">Requiere atención</span>
                </div>
            </div>

            <!-- Revenue (Placeholder para HITO 5) -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ingresos Mensuales</p>
                        <p class="text-2xl font-bold text-gray-900">
                            ${{ number_format(App\Models\User::where('is_premium', true)->count() * 29.99, 2) }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">HITO 5 - Próximamente</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actividad Reciente</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Nuevos Usuarios -->
                        @php
                            $newUsers = App\Models\User::latest()->take(3)->get();
                            $pendingReports = App\Models\Report::pending()->with('reporter', 'reportedUser')->latest()->take(3)->get();
                        @endphp

                        @forelse($newUsers as $user)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-green-400 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">Nuevo usuario registrado: <span
                                            class="font-medium">{{ $user->name }}</span></p>
                                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Sin nuevos usuarios</p>
                        @endforelse

                        <!-- Reportes Pendientes -->
                        @forelse($pendingReports as $report)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-yellow-400 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">Reporte de usuario: <span
                                            class="font-medium">{{ ucfirst(str_replace('_', ' ', $report->reason)) }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Sin reportes pendientes</p>
                        @endforelse

                        <!-- Suscripciones Premium -->
                        @php
                            $newPremium = App\Models\User::where('is_premium', true)->latest()->take(1)->first();
                        @endphp
                        @if($newPremium)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">Nueva suscripción premium: <span
                                            class="font-medium">{{ $newPremium->name }}</span></p>
                                    <p class="text-xs text-gray-500">{{ $newPremium->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.moderation.reports') }}"
                            class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-8 h-8 text-red-500 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Ver Reportes</span>
                        </a>

                        <a href="{{ route('admin.moderation.users') }}"
                            class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-8 h-8 text-blue-500 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Gestionar Usuarios</span>
                        </a>

                        <a href="{{ route('admin.moderation.blocked-words') }}"
                            class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-8 h-8 text-green-500 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Palabras Bloqueadas</span>
                        </a>

                        <a href="{{ route('admin.moderation.dashboard') }}"
                            class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <svg class="w-8 h-8 text-purple-500 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Moderación</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Photos Moderation -->
        @if(isset($recentActivity['recent_photos']) && $recentActivity['recent_photos']->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Moderación de Fotos Recientes</h3>
                    <a href="{{ route('admin.moderation.photos') }}"
                        class="text-sm font-bold text-red-600 hover:text-red-700 uppercase tracking-widest">Ver Todas</a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($recentActivity['recent_photos'] as $photo)
                            <div
                                class="relative group aspect-[3/4] rounded-xl overflow-hidden bg-gray-100 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                                <img src="{{ $photo->url }}" class="w-full h-full object-cover">

                                <!-- Warning Sign for Potential Nudity -->
                                @if($photo->potential_nudity)
                                    <div class="absolute top-2 right-2 z-10">
                                        <div class="bg-amber-500 text-white p-1 rounded-full shadow-lg border-2 border-white animate-pulse"
                                            title="Posible Desnudo">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif

                                <!-- Photo Info Overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3">
                                    <p class="text-[10px] font-bold text-white truncate">{{ $photo->user->name }}</p>
                                    <p class="text-[8px] text-gray-300 uppercase tracking-widest">
                                        {{ $photo->created_at->diffForHumans() }}</p>

                                    <div class="mt-2 flex gap-1">
                                        <form action="{{ route('admin.moderation.photos.approve', $photo) }}" method="POST"
                                            class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                class="w-full py-1 bg-emerald-500 hover:bg-emerald-600 text-white rounded text-[8px] font-black uppercase tracking-widest transition-all">
                                                Aceptar
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.moderation.photos') }}?user_id={{ $photo->user_id }}"
                                            class="flex-1 py-1 bg-gray-500 hover:bg-gray-600 text-white rounded text-[8px] font-black uppercase tracking-widest transition-all text-center">
                                            Ver más
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>