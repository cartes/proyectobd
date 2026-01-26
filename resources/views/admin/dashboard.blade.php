@extends('layouts.admin')

@section('title', 'Super-admin Dashboard')

@section('content')
    <div class="space-y-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 blur-3xl rounded-full -mr-16 -mt-16 group-hover:bg-emerald-500/20 transition-all">
                </div>
                <div class="relative">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Ingresos (Mes)</p>
                    <h3 class="text-3xl font-outfit font-black text-emerald-400">
                        ${{ number_format($stats['monthly_revenue'], 2) }}</h3>
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                        <span class="px-2 py-1 bg-emerald-500/10 text-emerald-500 rounded-lg font-bold">Total:
                            ${{ number_format($stats['total_revenue'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 blur-3xl rounded-full -mr-16 -mt-16 group-hover:bg-blue-500/20 transition-all">
                </div>
                <div class="relative">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Usuarios Totales</p>
                    <h3 class="text-3xl font-outfit font-black text-blue-400">{{ number_format($stats['total_users']) }}
                    </h3>
                    <div class="mt-4 flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-pink-500"></div> {{ $stats['sd_percentage'] }}% Daddies
                        </span>
                        <span class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-purple-500"></div> {{ $stats['sb_percentage'] }}% Babies
                        </span>
                    </div>
                </div>
            </div>

            <!-- Subscriptions Card -->
            <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-amber-500/10 blur-3xl rounded-full -mr-16 -mt-16 group-hover:bg-amber-500/20 transition-all">
                </div>
                <div class="relative">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Suscripciones Activas</p>
                    <h3 class="text-3xl font-outfit font-black text-amber-400">
                        {{ number_format($stats['active_subscriptions']) }}</h3>
                    <p class="mt-4 text-xs text-gray-400 font-medium italic">Incluye PRO y VIP</p>
                </div>
            </div>

            <!-- Reports Card -->
            <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-rose-500/10 blur-3xl rounded-full -mr-16 -mt-16 group-hover:bg-rose-500/20 transition-all">
                </div>
                <div class="relative">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Reportes Pendientes</p>
                    <h3 class="text-3xl font-outfit font-black text-rose-400">{{ number_format($stats['pending_reports']) }}
                    </h3>
                    <div class="mt-4">
                        <a href="{{ route('admin.moderation.reports') }}"
                            class="text-xs text-rose-500 font-bold hover:underline">Ir a Moderación &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Transactions -->
            <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden">
                <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                    <h4 class="font-outfit font-bold text-lg">Últimas Transacciones</h4>
                    <a href="#"
                        class="text-xs text-gray-500 hover:text-white transition-colors uppercase font-bold tracking-widest">Ver
                        Todas</a>
                </div>
                <div class="p-4">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-gray-500 border-b border-white/5">
                                <th class="px-4 py-3 font-bold">Usuario</th>
                                <th class="px-4 py-3 font-bold">Concepto</th>
                                <th class="px-4 py-3 font-bold">Monto</th>
                                <th class="px-4 py-3 font-bold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($recentTransactions as $tx)
                                <tr class="hover:bg-white/2 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="font-bold">{{ $tx->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $tx->user->email }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-xs">{{ $tx->description }}</td>
                                    <td class="px-4 py-4 font-bold text-white">${{ number_format($tx->amount, 2) }}</td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] uppercase font-black {{ $tx->status === 'approved' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                                            {{ $tx->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">No hay transacciones
                                        recientes</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden">
                <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                    <h4 class="font-outfit font-bold text-lg">Nuevos Usuarios</h4>
                    <a href="{{ route('admin.moderation.users') }}"
                        class="text-xs text-gray-500 hover:text-white transition-colors uppercase font-bold tracking-widest">Gestionar</a>
                </div>
                <div class="p-8 space-y-4">
                    @foreach($recentUsers as $user)
                        <div
                            class="flex items-center justify-between p-4 bg-white/2 rounded-2xl border border-white/5 hover:border-pink-500/20 transition-all group">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-pink-500/10 flex items-center justify-center text-pink-500 font-bold group-hover:bg-pink-500 group-hover:text-white transition-all">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span
                                    class="px-2 py-0.5 bg-white/5 border border-white/5 rounded-lg text-[10px] uppercase font-bold text-gray-400">
                                    {{ $user->user_type }}
                                </span>
                                <a href="{{ route('admin.moderation.users.show', $user) }}"
                                    class="p-2 hover:text-pink-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection