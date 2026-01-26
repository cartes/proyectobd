@extends('layouts.admin')

@section('title', 'Gestión de Planes de Suscripción')

@section('content')
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-outfit font-black text-white">Planes y Tarifas</h2>
                <p class="text-gray-500 mt-1">Configura precios base y ofertas temporales para tus miembros.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($plans as $plan)
                <div
                    class="bg-[#0c111d] border border-white/5 rounded-[2.5rem] p-8 relative overflow-hidden group hover:border-pink-500/20 transition-all">
                    <!-- Glow effect -->
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-pink-500/5 blur-3xl rounded-full -mr-16 -mt-16 group-hover:bg-pink-500/10 transition-all">
                    </div>

                    <div class="relative space-y-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3
                                    class="text-2xl font-outfit font-black text-white group-hover:text-pink-500 transition-colors">
                                    {{ $plan->name }}</h3>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-600 mt-1">
                                    {{ $plan->slug }}</p>
                            </div>
                            <span
                                class="px-3 py-1 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $plan->is_active ? 'text-emerald-500' : 'text-gray-500' }}">
                                {{ $plan->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="bg-white/[0.02] border border-white/5 rounded-3xl p-6 space-y-4">
                            <div class="flex items-baseline gap-2">
                                @if($plan->isOnSale())
                                    <span
                                        class="text-3xl font-outfit font-black text-pink-500">${{ number_format($plan->sale_amount, 2) }}</span>
                                    <span
                                        class="text-sm font-bold text-gray-500 line-through">${{ number_format($plan->amount, 2) }}</span>
                                    <span
                                        class="ml-auto text-[10px] font-black text-pink-500 bg-pink-500/10 px-2 py-1 rounded-lg">OFERTA</span>
                                @else
                                    <span
                                        class="text-3xl font-outfit font-black text-white">${{ number_format($plan->amount, 2) }}</span>
                                @endif
                                <span class="text-xs font-medium text-gray-500">/ {{ $plan->frequency }}
                                    {{ $plan->frequency_type === 'months' ? 'Meses' : 'Días' }}</span>
                            </div>

                            @if($plan->isOnSale() && $plan->sale_expires_at)
                                <div class="flex items-center gap-2 text-[10px] font-bold text-amber-500 uppercase tracking-tight">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Expira: {{ $plan->sale_expires_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4 pt-4 border-t border-white/5">
                            <p class="text-xs text-gray-500 leading-relaxed truncate">{{ $plan->description }}</p>
                            <a href="{{ route('admin.plans.edit', $plan) }}"
                                class="flex items-center justify-center gap-2 w-full py-4 bg-white/5 hover:bg-white/10 border border-white/5 rounded-2xl text-xs font-black uppercase tracking-widest transition-all text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Configurar Tarifas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection