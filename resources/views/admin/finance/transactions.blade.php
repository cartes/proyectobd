@extends('layouts.admin')

@section('title', 'Transacciones Financieras')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between bg-[#0c111d] border border-white/5 p-8 rounded-3xl">
            <div>
                <h2 class="text-3xl font-outfit font-black text-white">Flujo de Caja</h2>
                <p class="text-gray-500 mt-1">Historial de pagos procesados vía Mercado Pago.</p>
            </div>
            <div class="flex gap-4">
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Aprobados</p>
                    <p class="text-xl font-outfit font-bold text-emerald-500">
                        ${{ number_format(\App\Models\Transaction::where('status', 'approved')->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Buscar</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="ID Pago o Usuario..." value="{{ request('search') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-10 text-sm focus:border-pink-500/50 focus:ring-0 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Estado</label>
                    <select name="status"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all">
                        <option value="">Todos</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobado</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-2xl border border-white/10 transition-all">
                        Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-white/5">
                        <th class="px-8 py-5">Fecha</th>
                        <th class="px-8 py-5">Usuario</th>
                        <th class="px-8 py-5">ID MP</th>
                        <th class="px-8 py-5">Monto</th>
                        <th class="px-8 py-5">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/2 text-sm">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-8 py-6 text-gray-400">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-white">{{ $tx->user->name }}</p>
                                <p class="text-[10px] text-gray-600 uppercase font-black">{{ $tx->type }}</p>
                            </td>
                            <td class="px-8 py-6 font-mono text-xs text-gray-500">{{ $tx->mp_payment_id }}</td>
                            <td class="px-8 py-6 font-black text-white">${{ number_format($tx->amount, 2) }}</td>
                            <td class="px-8 py-6">
                                <span
                                    class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $tx->status === 'approved' ? 'bg-emerald-500/10 text-emerald-500' : ($tx->status === 'pending' ? 'bg-amber-500/10 text-amber-500' : 'bg-rose-500/10 text-rose-500') }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-gray-500 italic">No se encontraron transacciones.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($transactions->hasPages())
                <div class="px-8 py-6 border-t border-white/5">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection