@extends('layouts.admin')

@section('title', 'Gestión de Reportes')

@section('content')
    <div class="space-y-8">
        <!-- Header Summary -->
        <div class="flex items-center justify-between bg-[#0c111d] border border-white/5 p-8 rounded-3xl">
            <div>
                <h2 class="text-3xl font-outfit font-black text-white">Centro de Reportes</h2>
                <p class="text-gray-500 mt-1">Monitorea y resuelve incidentes reportados por la comunidad.</p>
            </div>
            <div class="flex gap-4">
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pendientes</p>
                    <p class="text-xl font-outfit font-bold text-rose-500">
                        {{ $reports->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-right text-gray-400">
                    <p class="text-xs font-bold uppercase tracking-widest">Total</p>
                    <p class="text-xl font-outfit font-bold">{{ $reports->total() }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl shadow-xl">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Estado</label>
                    <select name="status"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm font-bold focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                        <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>En Revisión</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resueltos</option>
                        <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Desestimados
                        </option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Tipo de Objeto</label>
                    <select name="type"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm font-bold focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                        <option value="">Cualquier tipo</option>
                        <option value="message" {{ request('type') === 'message' ? 'selected' : '' }}>MensajeDirecto</option>
                        <option value="conversation" {{ request('type') === 'conversation' ? 'selected' : '' }}>Conversación
                        </option>
                        <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>Perfil de Usuario</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Motivo Principal</label>
                    <select name="reason"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm font-bold focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                        <option value="">Todos los motivos</option>
                        <option value="profanity" {{ request('reason') === 'profanity' ? 'selected' : '' }}>Profanidad /
                            Lenguaje</option>
                        <option value="harassment" {{ request('reason') === 'harassment' ? 'selected' : '' }}>Acoso /
                            Hostigamiento</option>
                        <option value="inappropriate" {{ request('reason') === 'inappropriate' ? 'selected' : '' }}>Contenido
                            Inapropiado</option>
                        <option value="spam" {{ request('reason') === 'spam' ? 'selected' : '' }}>Spam / Estafa</option>
                        <option value="other" {{ request('reason') === 'other' ? 'selected' : '' }}>Otro Motivo</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-pink-500 hover:bg-pink-600 text-white font-black py-3 rounded-2xl shadow-lg shadow-pink-500/20 transition-all uppercase tracking-widest text-xs">
                        Filtrar Reportes
                    </button>
                </div>
            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-white/[0.02]">
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-white/5">
                        <th class="px-8 py-5">Reportado</th>
                        <th class="px-8 py-5">Detalle del Incidente</th>
                        <th class="px-8 py-5">Gravedad / Tipo</th>
                        <th class="px-8 py-5">Estado</th>
                        <th class="px-8 py-5">Fecha de Envío</th>
                        <th class="px-8 py-5 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/2">
                    @forelse($reports as $report)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500/20 to-purple-500/20 border border-white/10 flex items-center justify-center text-rose-500 font-black text-lg overflow-hidden">
                                        @if($report->reportedUser->primary_photo_url)
                                            <img src="{{ $report->reportedUser->primary_photo_url }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ substr($report->reportedUser->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-white group-hover:text-rose-400 transition-colors">
                                            {{ $report->reportedUser->name }}</p>
                                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">ID:
                                            #{{ $report->reported_user_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="max-w-[200px]">
                                    <p class="text-sm font-bold text-gray-300 truncate">
                                        {{ ucfirst(str_replace('_', ' ', $report->reason)) }}</p>
                                    <p class="text-xs text-gray-500 truncate mt-1 italic">Reporter:
                                        {{ $report->reporter->name }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1.5">
                                    <span
                                        class="px-2 py-0.5 bg-rose-500/10 text-rose-500 rounded-md text-[10px] font-black uppercase tracking-widest border border-rose-500/20">
                                        {{ $report->type }}
                                    </span>
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-tight">Motivo:
                                        {{ $report->reason }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($report->status === 'pending')
                                    <div class="flex items-center gap-2 text-amber-500">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Pendiente</span>
                                    </div>
                                @elseif($report->status === 'resolved')
                                    <div class="flex items-center gap-2 text-emerald-500">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Resuelto</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 text-gray-500">
                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-500"></div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $report->status }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs text-gray-400 font-medium">{{ $report->created_at->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-gray-600 font-bold uppercase">
                                    {{ $report->created_at->format('H:i') }} hs</p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.moderation.reports.show', $report) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-rose-500 hover:text-white border border-white/5 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-gray-400">
                                    Revisar
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-20 h-20 bg-white/5 rounded-3xl mb-4 text-gray-600 border border-white/5">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium font-outfit">No hay reportes que requieran atención en este
                                    momento.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($reports->hasPages())
                <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection