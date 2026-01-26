@extends('layouts.admin')

@section('title', 'Expediente de Reporte #' . $report->id)

@section('content')
    <div class="space-y-8">
        <!-- Navigation & Badge -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.moderation.reports') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver a Reportes
            </a>
            <div class="flex items-center gap-2">
                <span
                    class="px-3 py-1 bg-rose-500/10 text-rose-500 border border-rose-500/20 rounded-xl text-xs font-black uppercase tracking-widest">
                    PRIORIDAD ALTA
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Case Data -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Incident Overview Card -->
                <div class="bg-[#0c111d] border border-white/5 rounded-[2.5rem] p-10 relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500/5 blur-3xl rounded-full -mr-32 -mt-32"></div>

                    <div class="relative space-y-8">
                        <div class="flex items-start justify-between">
                            <div class="space-y-2">
                                <h2 class="text-xs font-black text-rose-500 uppercase tracking-[0.2em]">Incidente de
                                    {{ ucfirst($report->type) }}</h2>
                                <h3 class="text-4xl font-outfit font-black text-white italic">
                                    "{{ ucfirst(str_replace('_', ' ', $report->reason)) }}"</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Fecha del Suceso
                                </p>
                                <p class="text-white font-bold">{{ $report->created_at->format('d M, Y - H:i') }} hs</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 border-y border-white/5">
                            <!-- Reporter -->
                            <div class="space-y-4">
                                <p class="text-xs font-black text-gray-500 uppercase tracking-widest px-1">Enviado por</p>
                                <div class="flex items-center gap-4 bg-white/[0.02] border border-white/5 p-4 rounded-3xl">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-500 font-black">
                                        {{ substr($report->reporter->name, 0, 1) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-white truncate">{{ $report->reporter->name }}</p>
                                        <p class="text-[10px] text-gray-500 uppercase font-black truncate">ID:
                                            #{{ $report->reporter_id }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Reported User -->
                            <div class="space-y-4">
                                <p class="text-xs font-black text-rose-500 uppercase tracking-widest px-1">Usuario Acusado
                                </p>
                                <div
                                    class="flex items-center gap-4 bg-rose-500/5 border border-rose-500/10 p-4 rounded-3xl">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-500 font-black overflow-hidden">
                                        @if($report->reportedUser->primary_photo_url)
                                            <img src="{{ $report->reportedUser->primary_photo_url }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ substr($report->reportedUser->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-white truncate">{{ $report->reportedUser->name }}
                                        </p>
                                        <a href="{{ route('admin.moderation.users.show', $report->reportedUser) }}"
                                            class="text-[10px] text-rose-400 uppercase font-black hover:underline">Ver
                                            Historial Penal</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Evidence Section -->
                        <div class="space-y-4">
                            <p class="text-xs font-black text-gray-500 uppercase tracking-widest px-1">Evidencia & Contexto
                            </p>

                            <!-- Description given by reporter -->
                            <div class="bg-white/2 p-6 rounded-3xl border border-white/5 space-y-4 italic text-gray-400">
                                <p class="text-sm font-medium leading-relaxed">
                                    "{{ $report->description ?? 'El denunciante no proporcionó comentarios adicionales.' }}"
                                </p>
                            </div>

                            <!-- Actual Content Reported (if it's a message) -->
                            @if($report->message)
                                <div class="space-y-3">
                                    <p class="text-[10px] font-black text-rose-500/70 uppercase tracking-widest px-1">Mensaje en
                                        Disputa</p>
                                    <div class="bg-[#05070a] p-6 rounded-[2rem] border border-rose-500/20 shadow-inner">
                                        <p class="text-lg text-rose-200 font-medium">"{{ $report->message->content }}"</p>
                                        <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between">
                                            <p class="text-[10px] text-gray-600 font-bold uppercase">Enviado en conversación
                                                #{{ $report->message->conversation_id }}</p>
                                            <p class="text-[10px] text-gray-600 font-bold uppercase">
                                                {{ $report->message->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subject Stats Summary -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 space-y-4 shadow-xl">
                        <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest">Antecedentes del denunciado
                        </h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-3xl font-outfit font-black {{ $userHistory->count() > 1 ? 'text-rose-500' : 'text-white' }}">
                                    {{ $userHistory->count() }}</p>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest italic">Reportes
                                    totales</p>
                            </div>
                            <div class="text-right">
                                <p
                                    class="text-3xl font-outfit font-black {{ $userActions->count() > 0 ? 'text-amber-500' : 'text-white' }}">
                                    {{ $userActions->count() }}</p>
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest italic">Sanciones
                                    previas</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/2 border border-white/5 rounded-3xl p-8 flex items-center justify-center">
                        <p class="text-xs text-gray-500 font-medium italic text-center leading-relaxed">"Recuerda revisar
                            siempre el histórico del usuario antes de aplicar un baneo permanente."</p>
                    </div>
                </div>
            </div>

            <!-- Moderation Sidebar -->
            <div class="space-y-8">
                <!-- Verdict Form -->
                <div
                    class="bg-[#0c111d] border border-white/5 rounded-[2.5rem] p-8 space-y-8 shadow-2xl relative overflow-hidden">
                    @if($report->status === 'pending')
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 blur-3xl rounded-full -mr-16 -mt-16">
                        </div>

                        <div class="relative space-y-6">
                            <div class="border-b border-white/5 pb-4">
                                <h4 class="font-outfit font-black text-xl text-white">Veredicto Final</h4>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">Cierre de
                                    expediente</p>
                            </div>

                            <form method="POST" action="{{ route('admin.moderation.reports.process', $report) }}"
                                class="space-y-6">
                                @csrf

                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Resolución</label>
                                    <select name="action"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-xs font-black uppercase tracking-widest focus:border-pink-500/50 focus:ring-0 transition-all text-white focus:bg-[#0c111d]"
                                        required>
                                        <option value="">Seleccionar Acción...</option>
                                        <option value="dismiss">🚫 Desestimar / Sin Faltas</option>
                                        <option value="warn">⚠️ Emitir Advertencia</option>
                                        <option value="suspend">⏸️ Suspender Temporalmente</option>
                                        <option value="ban">💀 Baneo Permanente</option>
                                    </select>
                                </div>

                                <div id="days-container" class="space-y-2" style="display: none;">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Duración
                                        (Días)</label>
                                    <input type="number" name="days" min="1" max="365" placeholder="7"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm font-bold focus:border-pink-500/50 focus:ring-0 transition-all text-white">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Notas
                                        Administrativas</label>
                                    <textarea name="notes" rows="6"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white placeholder:text-gray-700 resize-none"
                                        placeholder="Justifica tu decisión aquí (será visible para otros admins)..."></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black py-5 rounded-3xl shadow-xl shadow-emerald-500/20 transition-all uppercase tracking-[0.2em] text-xs">
                                    Ejecutar Sentencia
                                </button>
                            </form>

                            <script>
                                document.querySelector('[name="action"]').addEventListener('change', function () {
                                    document.getElementById('days-container').style.display =
                                        this.value === 'suspend' ? 'block' : 'none';
                                });
                            </script>
                        </div>
                    @else
                        <!-- Post-Verdict Info -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs font-black text-emerald-500 uppercase tracking-widest">CASO CERRADO:
                                    {{ strtoupper($report->status) }}</p>
                            </div>

                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest italic">Moderador
                                        Responsable</p>
                                    <p class="text-sm font-bold text-white">{{ $report->reviewedBy->name ?? 'Sistema' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest italic">Fecha de
                                        Resolución</p>
                                    <p class="text-sm font-bold text-white">{{ $report->reviewed_at?->format('d/m/Y H:i') }} hs
                                    </p>
                                </div>
                                @if($report->admin_notes)
                                    <div class="space-y-2 pt-4 border-t border-white/5">
                                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest italic">Dictamen
                                        </p>
                                        <p class="text-xs text-gray-400 leading-relaxed italic">"{{ $report->admin_notes }}"</p>
                                    </div>
                                @endif
                            </div>

                            <button disabled
                                class="w-full py-4 border border-white/5 rounded-2xl text-[10px] font-black text-gray-600 uppercase tracking-[0.2em]">
                                Expediente Archivado
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Historical Context (Small List) -->
                <div class="bg-[#0c111d] border border-white/5 rounded-[2rem] p-8 space-y-6 shadow-xl">
                    <h4 class="text-xs font-black text-gray-500 uppercase tracking-widest px-1">Línea de Tiempo del Acusado
                    </h4>
                    <div class="space-y-4">
                        @forelse($userHistory->slice(0, 3) as $hist)
                            <div class="pb-4 last:pb-0 border-b last:border-0 border-white/5 space-y-1">
                                <p class="text-xs font-bold text-white">{{ ucfirst(str_replace('_', ' ', $hist->reason)) }}</p>
                                <div class="flex items-center justify-between text-[10px] font-black text-gray-600 uppercase">
                                    <span>{{ $hist->created_at->diffForHumans() }}</span>
                                    <span class="text-rose-500/50">#{{ $hist->id }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-600 italic">Sin incidentes previos registrados.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection