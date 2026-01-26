@extends('layouts.admin')

@section('title', 'Editar Plan: ' . $plan->name)

@section('content')
    <div class="space-y-8 max-w-4xl">
        <a href="{{ route('admin.plans.index') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a Planes
        </a>

        <div class="bg-[#0c111d] border border-white/5 rounded-[2.5rem] p-10 shadow-2xl">
            <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="space-y-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Info Básica -->
                    <div class="space-y-6">
                        <h4 class="font-outfit font-bold text-xl text-white border-b border-white/5 pb-4">Información
                            General</h4>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Nombre
                                Comercial</label>
                            <input type="text" name="name" value="{{ old('name', $plan->name) }}"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm font-bold focus:border-pink-500/50 focus:ring-0 transition-all text-white"
                                required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Estado del
                                Plan</label>
                            <select name="is_active"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm font-bold focus:border-pink-500/50 focus:ring-0 transition-all text-white">
                                <option value="1" {{ $plan->is_active ? 'selected' : '' }}>✅ Plan Activo (Visible en Front)
                                </option>
                                <option value="0" {{ !$plan->is_active ? 'selected' : '' }}>❌ Inactivo (Oculto)</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Descripción</label>
                            <textarea name="description" rows="5"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white"
                                required>{{ old('description', $plan->description) }}</textarea>
                        </div>
                    </div>

                    <!-- Tarifas -->
                    <div class="space-y-6">
                        <h4 class="font-outfit font-bold text-xl text-pink-500 border-b border-pink-500/10 pb-4">
                            Configuración de Tarifas</h4>

                        <div class="bg-white/2 p-8 rounded-[2rem] border border-white/5 space-y-8">
                            <div class="space-y-2">
                                <label
                                    class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1 text-center block">Precio
                                    Base ({{ $plan->currency }})</label>
                                <div class="relative">
                                    <span
                                        class="absolute left-6 top-4 text-xl font-outfit font-black text-gray-500">$</span>
                                    <input type="number" step="0.01" name="amount"
                                        value="{{ old('amount', $plan->amount) }}"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-12 pr-6 text-2xl font-outfit font-black text-white text-center focus:border-pink-500/50 focus:ring-0 transition-all"
                                        required>
                                </div>
                            </div>

                            <div class="w-full h-px bg-white/5 relative">
                                <span
                                    class="absolute left-1/2 -top-2 transform -translate-x-1/2 bg-[#0c111d] px-2 text-[10px] font-black text-gray-700">PROMOCIÓN</span>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-2 text-center">
                                    <label
                                        class="text-xs font-bold text-pink-500/70 uppercase tracking-widest px-1 block">Precio
                                        Oferta (Opcional)</label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-6 top-4 text-xl font-outfit font-black text-pink-500/50">$</span>
                                        <input type="number" step="0.01" name="sale_amount"
                                            value="{{ old('sale_amount', $plan->sale_amount) }}"
                                            class="w-full bg-pink-500/5 border border-pink-500/10 rounded-2xl py-4 pl-12 pr-6 text-2xl font-outfit font-black text-pink-400 text-center focus:border-pink-500/50 focus:ring-0 transition-all"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label
                                        class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Vencimiento de la Oferta
                                    </label>
                                    <input type="datetime-local" name="sale_expires_at"
                                        value="{{ old('sale_expires_at', $plan->sale_expires_at ? $plan->sale_expires_at->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm font-bold focus:border-pink-500/50 focus:ring-0 transition-all text-white">
                                    <p class="px-2 text-[9px] text-gray-500 leading-tight">Si la fecha es pasada o está
                                        vacía, se cobrará el precio base. Las ofertas son sensibles al huso horario del
                                        servidor.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-10 border-t border-white/5 flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-pink-500/20 transition-all uppercase tracking-[0.2em]">
                        Guardar Cambios en el Plan
                    </button>
                    <a href="{{ route('admin.plans.index') }}"
                        class="px-8 py-5 bg-white/5 hover:bg-white/10 border border-white/5 text-gray-400 font-bold rounded-2xl transition-all uppercase tracking-widest text-xs flex items-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection