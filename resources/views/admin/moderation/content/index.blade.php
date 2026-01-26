@extends('layouts.admin')

@section('title', 'Moderación de Propuestas de Perfil')

@section('content')
    <div class="space-y-8">
        <div class="flex items-center justify-between bg-[#0c111d] border border-white/5 p-8 rounded-3xl">
            <div>
                <h2 class="text-3xl font-outfit font-black text-white">Revisión de Textos y Propuestas</h2>
                <p class="text-gray-500 mt-1">Supervisa las biografías y propuestas de valor para asegurar el cumplimiento
                    de las normas.</p>
            </div>
        </div>

        <!-- Content Table -->
        <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-white/[0.02]">
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-white/5">
                        <th class="px-8 py-5">Usuario</th>
                        <th class="px-8 py-5">Biografía</th>
                        <th class="px-8 py-5">Qué Ofrece / Busca</th>
                        <th class="px-8 py-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/2">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-8 py-6 align-top">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-pink-500/10 flex items-center justify-center text-pink-500 font-black">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                                            {{ $user->user_type }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 align-top">
                                <div class="max-w-md bg-white/[0.03] p-4 rounded-2xl border border-white/5">
                                    <p class="text-xs text-gray-400 leading-relaxed italic line-clamp-4">
                                        "{{ $user->bio ?? 'Sin biografía' }}"</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 align-top">
                                <div class="space-y-4 max-w-md">
                                    @if($user->profileDetail?->what_i_offer)
                                        <div class="bg-amber-500/5 p-3 rounded-xl border border-amber-500/10">
                                            <p class="text-[9px] font-black text-amber-500 uppercase tracking-widest mb-1">🎁 Ofrece
                                            </p>
                                            <p class="text-xs text-gray-400 line-clamp-2">{{ $user->profileDetail->what_i_offer }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($user->profileDetail?->looking_for)
                                        <div class="bg-purple-500/5 p-3 rounded-xl border border-purple-500/10">
                                            <p class="text-[9px] font-black text-purple-500 uppercase tracking-widest mb-1">💫 Busca
                                            </p>
                                            <p class="text-xs text-gray-400 line-clamp-2">{{ $user->profileDetail->looking_for }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 align-top text-right">
                                <div class="flex flex-col gap-2">
                                    <form action="{{ route('admin.moderation.proposals.approve', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-2 bg-emerald-500/10 hover:bg-emerald-500 text-emerald-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                            Aprobar
                                        </button>
                                    </form>
                                    <button onclick="openContentRejectModal({{ $user->id }})"
                                        class="w-full py-2 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                        Rechazar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-500 font-outfit">No hay propuestas de
                                perfiles pendientes de revisión.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div id="content-reject-modal"
        class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#0c111d] border border-white/10 w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl">
            <h3 class="text-2xl font-outfit font-black text-white mb-2">Rechazar Contenido</h3>
            <p class="text-gray-500 text-sm mb-8">Indica por qué este contenido no es apto para el sitio.</p>

            <form id="content-reject-form" method="POST" class="space-y-6">
                @csrf
                <textarea name="reason" rows="4"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:border-rose-500/50 focus:ring-0 transition-all text-white placeholder:text-gray-700"
                    placeholder="Ej: Contenido ofensivo, spam, información de contacto prohibida..." required></textarea>

                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-rose-500 to-rose-700 text-white font-black py-4 rounded-3xl transition-all uppercase tracking-widest text-xs">
                        Confirmar
                    </button>
                    <button type="button" onclick="closeContentRejectModal()"
                        class="px-8 py-4 bg-white/5 text-gray-400 font-bold rounded-3xl transition-all uppercase tracking-widest text-xs">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openContentRejectModal(id) {
            document.getElementById('content-reject-form').action = `/admin/moderation/proposals/${id}/reject`;
            document.getElementById('content-reject-modal').classList.remove('hidden');
        }

        function closeContentRejectModal() {
            document.getElementById('content-reject-modal').classList.add('hidden');
        }
    </script>
@endsection