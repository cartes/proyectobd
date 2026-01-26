@extends('layouts.admin')

@section('title', 'Moderación de Galería')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between bg-[#0c111d] border border-white/5 p-8 rounded-3xl">
            <div>
                <h2 class="text-3xl font-outfit font-black text-white">Cola de Moderación Visual</h2>
                <p class="text-gray-500 mt-1">Revisa y aprueba las fotos subidas por los usuarios para mantener los
                    estándares de calidad.</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Pendientes</p>
                <p class="text-4xl font-outfit font-black text-amber-500">{{ $photos->total() }}</p>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @forelse($photos as $photo)
                <div
                    class="group bg-[#0c111d] border border-white/10 rounded-3xl overflow-hidden relative shadow-2xl transition-all hover:border-pink-500/50">
                    <!-- Photo Container -->
                    <div class="aspect-[3/4] relative overflow-hidden bg-black">
                        <img src="{{ $photo->url }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60">
                        </div>

                        <!-- Quick Info -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <p class="text-xs font-bold text-white truncate">{{ $photo->user->name }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                {{ $photo->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Actions Row -->
                    <div class="p-3 grid grid-cols-2 gap-2 bg-[#0c111d]">
                        <form action="{{ route('admin.moderation.photos.approve', $photo) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 bg-emerald-500/10 hover:bg-emerald-500 text-emerald-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                Aprobar
                            </button>
                        </form>
                        <button onclick="openRejectModal({{ $photo->id }})"
                            class="w-full py-2 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Rechazar
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-white/5 rounded-3xl mb-4 text-gray-600 border border-white/5">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium font-outfit">No hay fotos que requieran moderación en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($photos->hasPages())
            <div class="px-8 py-6 border-t border-white/5">
                {{ $photos->links() }}
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal"
        class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#0c111d] border border-white/10 w-full max-w-lg rounded-[2.5rem] p-10 shadow-2xl">
            <h3 class="text-2xl font-outfit font-black text-white mb-2">Rechazar Fotografía</h3>
            <p class="text-gray-500 text-sm mb-8">Selecciona o escribe el motivo del rechazo. El usuario recibirá esta
                notificación.</p>

            <form id="reject-form" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="setReason('Contenido sugerente / Desvío')"
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl text-[10px] font-bold text-gray-400 text-left transition-all italic">🔞
                            Sugerente</button>
                        <button type="button" onclick="setReason('Baja calidad / Borrosa')"
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl text-[10px] font-bold text-gray-400 text-left transition-all italic">📸
                            Mala Calidad</button>
                        <button type="button" onclick="setReason('No se ve el rostro')"
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl text-[10px] font-bold text-gray-400 text-left transition-all italic">🎭
                            Rostro Oculto</button>
                        <button type="button" onclick="setReason('Contenido promocional')"
                            class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl text-[10px] font-bold text-gray-400 text-left transition-all italic">📢
                            Spam / Promo</button>
                    </div>

                    <textarea name="reason" id="reject-reason" rows="4"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-sm focus:border-rose-500/50 focus:ring-0 transition-all text-white placeholder:text-gray-700"
                        placeholder="Escribe el motivo detallado..." required></textarea>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-rose-500 to-rose-700 text-white font-black py-4 rounded-3xl shadow-xl shadow-rose-500/20 transition-all uppercase tracking-widest text-xs">
                        Confirmar Rechazo
                    </button>
                    <button type="button" onclick="closeRejectModal()"
                        class="px-8 py-4 bg-white/5 hover:bg-white/10 text-gray-400 font-bold rounded-3xl transition-all uppercase tracking-widest text-xs">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(id) {
            document.getElementById('reject-form').action = `/admin/moderation/photos/${id}/reject`;
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }

        function setReason(text) {
            document.getElementById('reject-reason').value = text;
        }
    </script>
@endsection