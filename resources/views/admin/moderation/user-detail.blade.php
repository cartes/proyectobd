@extends('layouts.admin')

@section('title', 'Detalle de Usuario')

@section('content')
    <div class="space-y-8" x-data="userDetailAdminManager()">
        <!-- Back Link & Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('admin.moderation.users') }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver al Listado
            </a>

            <div class="flex items-center gap-3">
                <button type="button" @click="openMessageModal()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-pink-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Enviar Mensaje</span>
                </button>

                @if(auth()->id() !== $user->id)
                    <button type="button" @click="openDeleteModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-rose-500/10 hover:bg-rose-500/25 text-rose-400 border border-rose-500/20 rounded-xl text-xs font-bold transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Eliminar</span>
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Stats & Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- User Profile Card -->
                <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500/5 blur-3xl rounded-full -mr-32 -mt-32"></div>

                    <div class="relative flex flex-col md:flex-row gap-8 items-start">
                        <!-- Photo -->
                        <div class="relative">
                            <img src="{{ $user->primary_photo_url ?? '/images/default-avatar.png' }}"
                                class="w-32 h-32 rounded-3xl object-cover border-2 border-white/10 shadow-2xl">
                            @if ($user->is_verified)
                                <div
                                    class="absolute -bottom-2 -right-2 bg-blue-500 p-1.5 rounded-xl border-4 border-[#0c111d]">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h2 class="text-4xl font-outfit font-black">{{ $user->name }}</h2>
                                    <span
                                        class="px-3 py-1 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400">ID:
                                        #{{ $user->id }}</span>
                                </div>
                                <p class="text-gray-500 font-medium">{{ $user->email }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="px-3 py-1 bg-pink-500/10 text-pink-500 border border-pink-500/20 rounded-xl text-xs font-bold uppercase tracking-tight">
                                    {{ $user->isSugarDaddy() ? '💎 Sugar Daddy' : '👶 Sugar Baby' }}
                                </span>
                                @if ($user->is_premium)
                                    <span
                                        class="px-3 py-1 bg-amber-500/10 text-amber-500 border border-amber-500/20 rounded-xl text-xs font-bold uppercase tracking-tight italic">
                                        👑 Miembro Premium
                                    </span>
                                @endif
                                <span
                                    class="px-3 py-1 bg-white/5 border border-white/10 text-gray-400 rounded-xl text-xs font-medium">
                                    {{ $user->age }} años • {{ $user->city }}
                                </span>
                            </div>

                            <!-- Mini Stats -->
                            <div class="grid grid-cols-3 gap-6 pt-4">
                                <div class="p-4 bg-white/2 border border-white/5 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Mensajes</p>
                                    <p class="text-xl font-outfit font-bold">{{ $user->sent_messages_count }}</p>
                                </div>
                                <div class="p-4 bg-white/2 border border-white/5 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Reportes</p>
                                    <p
                                        class="text-xl font-outfit font-bold {{ $reports->total() > 0 ? 'text-rose-500' : '' }}">
                                        {{ $reports->total() }}
                                    </p>
                                </div>
                                <div class="p-4 bg-white/2 border border-white/5 rounded-2xl">
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Acciones</p>
                                    <p
                                        class="text-xl font-outfit font-bold {{ $actions->total() > 0 ? 'text-amber-500' : '' }}">
                                        {{ $actions->total() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-white/5">
                        <h4 class="font-outfit font-bold text-lg">Reportes contra el usuario</h4>
                    </div>
                    <div class="divide-y divide-white/2">
                        @forelse($reports as $report)
                            <div class="p-6 hover:bg-white/[0.01] transition-colors flex items-center justify-between">
                                <div class="space-y-1">
                                    <p class="font-bold text-white">{{ ucfirst(str_replace('_', ' ', $report->reason)) }}
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                                        <span
                                            class="px-1.5 py-0.5 bg-rose-500/10 text-rose-500 rounded-md font-bold uppercase">{{ $report->status }}</span>
                                        <span>Por: <span class="text-gray-300">{{ $report->reporter->name }}</span></span>
                                        <span>•</span>
                                        <span>{{ $report->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.moderation.reports.show', $report) }}"
                                    class="p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl text-xs font-bold uppercase transition-all">Ver
                                    Detalle</a>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-500 italic">No hay reportes para este usuario.</div>
                        @endforelse
                    </div>
                    @if ($reports->hasPages())
                        <div class="px-8 py-4 bg-white/[0.01] border-t border-white/5">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>

                <!-- Photos Gallery -->
                <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden" x-data="{
                    viewType: 'grid',
                    isProcessing: null,
                    photoStatuses: {
                        @foreach ($user->photos as $p)
                                                                                    {{ $p->id }}: '{{ $p->moderation_status }}', @endforeach
                    },
                    async moderatePhoto(photoId, action) {
                        if (this.isProcessing) return;
                
                        let reason = '';
                        if (action === 'reject') {
                            reason = prompt('Indica el motivo del rechazo:');
                            if (reason === null) return;
                            if (reason.trim() === '') {
                                alert('El motivo de rechazo es obligatorio para poder rechazar la imagen.');
                                return;
                            }
                        }
                
                        this.isProcessing = photoId;
                
                        try {
                            const response = await fetch(`{{ url('/admin/moderation/photos') }}/${photoId}/${action}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ reason: reason })
                            });
                
                            const data = await response.json();
                
                            if (!response.ok) {
                                throw new Error(data.message || 'Error al procesar la solicitud');
                            }
                
                            if (data.success) {
                                this.photoStatuses[photoId] = data.status;
                
                                // La UI se actualizará automáticamente vía Alpine
                            }
                        } catch (e) {
                            console.error('Error in moderation:', e);
                            alert('Ocurrió un problema: ' + e.message);
                        } finally {
                            this.isProcessing = null;
                        }
                    }
                }">
                    <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between">
                        <div>
                            <h4 class="font-outfit font-bold text-lg">Galería de Fotos</h4>
                            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest">{{ $user->photos->count() }}
                                fotos en total</p>
                        </div>

                        {{-- View Toggle Buttons --}}
                        <div class="flex bg-white/5 p-1 rounded-xl border border-white/10">
                            <button @click="viewType = 'grid'"
                                :class="viewType === 'grid' ? 'bg-pink-500 text-white shadow-lg' :
                                    'text-gray-400 hover:text-white'"
                                class="p-2 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewType = 'list'"
                                :class="viewType === 'list' ? 'bg-pink-500 text-white shadow-lg' :
                                    'text-gray-400 hover:text-white'"
                                class="p-2 rounded-lg transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-8">
                        {{-- GRID VIEW --}}
                        <div x-show="viewType === 'grid'" class="grid grid-cols-2 md:grid-cols-3 gap-6" x-transition>
                            @forelse($user->photos as $photo)
                                <div
                                    class="relative group aspect-[3/4] rounded-2xl overflow-hidden bg-white/5 border border-white/5 shadow-2xl transition-all hover:border-pink-500/30">
                                    <img src="{{ $photo->url }}" alt="Foto de {{ $user->name }}"
                                        class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110"
                                        :class="isProcessing === {{ $photo->id }} ? 'opacity-50 blur-sm' : (photoStatuses[
                                                {{ $photo->id }}] === 'rejected' ?
                                            'blur-xl brightness-50 grayscale' : '')">

                                    {{-- Warning Overlay for Rejected --}}
                                    <div x-show="photoStatuses[{{ $photo->id }}] === 'rejected'"
                                        class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
                                        <div class="bg-rose-500/80 p-3 rounded-full shadow-2xl scale-110">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Loading Overlay --}}
                                    <div x-show="isProcessing === {{ $photo->id }}"
                                        class="absolute inset-0 flex items-center justify-center z-20">
                                        <div
                                            class="w-8 h-8 border-4 border-pink-500/30 border-t-pink-500 rounded-full animate-spin">
                                        </div>
                                    </div>

                                    <!-- BADGES OVERLAY -->
                                    <div class="absolute top-2 left-2 right-2 flex justify-between items-start z-10">
                                        @if ($photo->is_primary)
                                            <span
                                                class="px-2 py-1 bg-amber-500 text-white text-[8px] font-black uppercase rounded-lg shadow-lg">Estrella</span>
                                        @endif

                                        @if ($photo->potential_nudity)
                                            <div class="bg-amber-500 text-white p-1.5 rounded-xl border border-white/20 animate-pulse shadow-xl"
                                                title="IA: Posible Desnudo">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- STATUS BADGE -->
                                    <div class="absolute bottom-14 left-2 z-10">
                                        <span id="status-badge-{{ $photo->id }}"
                                            x-text="photoStatuses[{{ $photo->id }}]"
                                            class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest"
                                            :class="{
                                                'bg-emerald-500/20 text-emerald-500': photoStatuses[
                                                    {{ $photo->id }}] === 'approved',
                                                'bg-amber-500/20 text-amber-500': photoStatuses[
                                                    {{ $photo->id }}] === 'pending',
                                                'bg-rose-500/20 text-rose-500': photoStatuses[
                                                    {{ $photo->id }}] === 'rejected'
                                            }">
                                        </span>
                                    </div>

                                    {{-- ACTIONS --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0c111d] via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3 gap-2"
                                        x-show="!isProcessing">
                                        <div class="flex gap-2">
                                            <button @click="moderatePhoto({{ $photo->id }}, 'approve')"
                                                class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase transition-all shadow-lg ring-1 ring-emerald-400/20"
                                                :class="photoStatuses[{{ $photo->id }}] === 'approved' ?
                                                    'bg-emerald-600 ring-2 ring-white/20' :
                                                    'bg-emerald-500 hover:bg-emerald-600'">
                                                Aprobar
                                            </button>
                                            <button @click="moderatePhoto({{ $photo->id }}, 'reject')"
                                                class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase transition-all shadow-lg ring-1 ring-rose-400/20"
                                                :class="photoStatuses[{{ $photo->id }}] === 'rejected' ?
                                                    'bg-rose-600 ring-2 ring-white/20' : 'bg-rose-500 hover:bg-rose-600'">
                                                Rechazar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="col-span-3 py-12 text-center text-gray-500 italic bg-white/2 rounded-3xl border border-dashed border-white/10">
                                    El usuario no tiene fotos subidas.</div>
                            @endforelse
                        </div>

                        {{-- LIST VIEW --}}
                        <div x-show="viewType === 'list'" class="space-y-4" x-transition>
                            @forelse($user->photos as $photo)
                                <div
                                    class="flex items-center gap-6 p-4 bg-white/2 border border-white/5 rounded-2xl hover:bg-white/5 transition-all">
                                    <div
                                        class="w-20 h-20 rounded-xl overflow-hidden shadow-lg border border-white/10 relative">
                                        <img src="{{ $photo->url }}" class="w-full h-full object-cover"
                                            :class="photoStatuses[{{ $photo->id }}] === 'rejected' ?
                                                'blur-sm grayscale brightness-50' : ''">

                                        <div x-show="photoStatuses[{{ $photo->id }}] === 'rejected'"
                                            class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                            <svg class="w-6 h-6 text-rose-500 drop-shadow-lg" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        </div>
                                        <div x-show="isProcessing === {{ $photo->id }}"
                                            class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                            <div
                                                class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <span x-text="photoStatuses[{{ $photo->id }}]"
                                                class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest"
                                                :class="{
                                                    'bg-emerald-500/20 text-emerald-500': photoStatuses[
                                                        {{ $photo->id }}] === 'approved',
                                                    'bg-amber-500/20 text-amber-500': photoStatuses[
                                                        {{ $photo->id }}] === 'pending',
                                                    'bg-rose-500/20 text-rose-500': photoStatuses[
                                                        {{ $photo->id }}] === 'rejected'
                                                }"
                                                id="list-status-badge-{{ $photo->id }}">
                                            </span>
                                            @if ($photo->is_primary)
                                                <span
                                                    class="text-[10px] text-amber-500 font-black uppercase tracking-widest">★
                                                    Principal</span>
                                            @endif
                                            @if ($photo->potential_nudity)
                                                <span
                                                    class="text-[10px] text-rose-500 font-black uppercase tracking-widest flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    IA: Sospechosa
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500">Subida el
                                            {{ $photo->created_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-3 mr-4">
                                        <button @click="moderatePhoto({{ $photo->id }}, 'approve')"
                                            class="px-4 py-2 border rounded-xl text-[10px] font-black uppercase transition-all"
                                            :class="photoStatuses[{{ $photo->id }}] === 'approved' ?
                                                'bg-emerald-500 text-white border-white/20' :
                                                'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white border-emerald-500/20'">
                                            Aprobar
                                        </button>
                                        <button @click="moderatePhoto({{ $photo->id }}, 'reject')"
                                            class="px-4 py-2 border rounded-xl text-[10px] font-black uppercase transition-all"
                                            :class="photoStatuses[{{ $photo->id }}] === 'rejected' ?
                                                'bg-rose-500 text-white border-white/20' :
                                                'bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white border-rose-500/20'">
                                            Rechazar
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="py-12 text-center text-gray-500 italic bg-white/2 rounded-3xl border border-dashed border-white/10">
                                    El usuario no tiene fotos subidas.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-8">
                <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 space-y-6">
                    <h4 class="font-outfit font-bold text-lg border-b border-white/5 pb-4">Gestión Premium</h4>

                    <form action="{{ route('admin.moderation.users.toggle-premium', $user) }}" method="POST"
                        class="space-y-4">
                        @csrf
                        <input type="hidden" name="is_premium" value="{{ $user->is_premium ? 0 : 1 }}">

                        <div class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-2xl">
                            <div>
                                <p class="text-sm font-bold {{ $user->is_premium ? 'text-amber-500' : 'text-gray-400' }}">
                                    Estatus Premium
                                </p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5 font-bold">
                                    {{ $user->is_premium ? 'Activado' : 'Desactivado' }}
                                </p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center {{ $user->is_premium ? 'bg-amber-500/10 text-amber-500' : 'bg-gray-500/10 text-gray-500' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                                </svg>
                            </div>
                        </div>

                        @if (!$user->is_premium)
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Válido
                                    hasta (Opcional)</label>
                                <input type="date" name="premium_until"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm focus:border-amber-500/50 focus:ring-0 transition-all text-white">
                                <p class="text-[10px] text-gray-500 italic">Por defecto: 30 días</p>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Razón del
                                Cambio</label>
                            <textarea name="reason" rows="2"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-4 text-sm focus:border-amber-500/50 focus:ring-0 transition-all text-white"
                                placeholder="Ej: Recompensa por actividad..." required></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-2xl font-black uppercase tracking-widest text-xs transition-all shadow-lg {{ $user->is_premium ? 'bg-gray-700 hover:bg-gray-800 text-white' : 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/20' }}">
                            {{ $user->is_premium ? 'Revocar Premium' : 'Activar Premium Gratis' }}
                        </button>
                    </form>
                </div>

                <!-- Administrative Quick Actions -->
                <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 space-y-6">
                    <h4 class="font-outfit font-bold text-lg border-b border-white/5 pb-4">Configuración de Cuenta</h4>

                    <!-- Verification Toggle -->
                    <form action="{{ route('admin.moderation.users.verify', $user) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/5 rounded-2xl transition-all group">
                            <div class="text-left">
                                <p class="text-sm font-bold {{ $user->is_verified ? 'text-gray-400' : 'text-blue-400' }}">
                                    {{ $user->is_verified ? 'Revocar Verificación' : 'Verificar Usuario' }}
                                </p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5 font-bold">Manual
                                    override</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center {{ $user->is_verified ? 'bg-gray-500/10 text-gray-500' : 'bg-blue-500/10 text-blue-500' }} group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </button>
                    </form>

                    <!-- Private Profile Toggle -->
                    <form action="{{ route('admin.moderation.users.toggle-private', $user) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-between p-4 bg-white/5 hover:bg-white/10 border border-white/5 rounded-2xl transition-all group">
                            <div class="text-left">
                                <p
                                    class="text-sm font-bold {{ $user->profileDetail->is_private ?? false ? 'text-gray-400' : 'text-purple-400' }}">
                                    {{ $user->profileDetail->is_private ?? false ? 'Quitar Perfil Privado' : 'Hacer Perfil Privado' }}
                                </p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5 font-bold">Visibilidad
                                    manual</p>
                            </div>
                            <div
                                class="w-10 h-10 rounded-xl flex items-center justify-center {{ $user->profileDetail->is_private ?? false ? 'bg-purple-500/10 text-purple-500' : 'bg-gray-500/10 text-gray-500' }} group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </button>
                    </form>

                    <!-- Country Change -->
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Cambiar País</p>
                        <form action="{{ route('admin.moderation.users.change-country', $user) }}" method="POST"
                            class="flex gap-2">
                            @csrf
                            <select name="country_id"
                                class="flex-1 bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-xs font-bold focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                                <option value="">Seleccionar País...</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ $user->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="p-2.5 bg-white/5 hover:bg-pink-500 text-gray-400 hover:text-white border border-white/10 rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    <!-- Role Change -->
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Cambiar Tipo</p>
                        <form action="{{ route('admin.moderation.users.change-role', $user) }}" method="POST"
                            class="flex gap-2">
                            @csrf
                            <select name="user_type"
                                class="flex-1 bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-xs font-bold focus:border-pink-500/50 focus:ring-0 appearance-none transition-all">
                                <option value="sugar_daddy" {{ $user->user_type === 'sugar_daddy' ? 'selected' : '' }}>
                                    Sugar
                                    Daddy</option>
                                <option value="sugar_baby" {{ $user->user_type === 'sugar_baby' ? 'selected' : '' }}>Sugar
                                    Baby</option>
                            </select>
                            <button type="submit"
                                class="p-2.5 bg-white/5 hover:bg-pink-500 text-gray-400 hover:text-white border border-white/10 rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Edit Profile Data -->
                <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 space-y-6">
                    <h4 class="font-outfit font-bold text-lg border-b border-white/5 pb-4">Editar Datos de Perfil</h4>

                    <form action="{{ route('admin.moderation.users.update-profile', $user) }}" method="POST"
                        class="space-y-4">
                        @csrf

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Bio</label>
                            <textarea name="bio" rows="3"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white"
                                placeholder="Descripción del perfil...">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Ciudad</label>
                            <input type="text" name="city" value="{{ old('city', $user->city) }}"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white"
                                placeholder="Ciudad">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Fecha de
                                Nacimiento</label>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Género</label>
                            <select name="gender"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                                <option value="">Sin especificar</option>
                                <option value="male"
                                    {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Masculino</option>
                                <option value="female"
                                    {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Femenino</option>
                                <option value="other"
                                    {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Ocupación</label>
                            <input type="text" name="occupation"
                                value="{{ old('occupation', $user->profileDetail?->occupation) }}"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 transition-all text-white"
                                placeholder="Ocupación / profesión">
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Educación</label>
                            <select name="education"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                                <option value="">Sin especificar</option>
                                @foreach (\App\Models\ProfileDetail::educationLevels() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('education', $user->profileDetail?->education) === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($user->isSugarDaddy())
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Rango de
                                    Ingresos</label>
                                <select name="income_range"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                                    <option value="">Sin especificar</option>
                                    @foreach (\App\Models\ProfileDetail::incomeRanges() as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('income_range', $user->profileDetail?->income_range) === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <label
                                class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Disponibilidad</label>
                            <select name="availability"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all text-white">
                                <option value="">Sin especificar</option>
                                @foreach (\App\Models\ProfileDetail::availabilityOptions() as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('availability', $user->profileDetail?->availability) === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full py-3 rounded-2xl font-black uppercase tracking-widest text-xs bg-pink-500 hover:bg-pink-600 text-white shadow-lg shadow-pink-500/20 transition-all">
                            Actualizar Perfil
                        </button>
                    </form>
                </div>

                <!-- Moderation Action Form -->
                <div class="bg-rose-500/5 border border-rose-500/10 rounded-3xl p-8 space-y-6">
                    <h4 class="font-outfit font-bold text-lg text-rose-500 border-b border-rose-500/10 pb-4">Moderación &
                        Sanciones</h4>

                    <form method="POST" action="{{ route('admin.moderation.users.action', $user) }}" class="space-y-4">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Tipo de
                                Sanción</label>
                            <select name="action"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm font-bold focus:border-rose-500/50 focus:ring-0 transition-all text-white"
                                required>
                                <option value="">Selecciona...</option>
                                <option value="warn">⚠️ Advertencia</option>
                                <option value="suspend">⏸️ Suspender Acceso</option>
                                <option value="ban">🚫 Banear Permanentemente</option>
                                @if ($user->isBanned() || $user->isSuspended())
                                    <option value="unban">🔓 Levantar Sanción</option>
                                @endif
                            </select>
                        </div>

                        <div id="days-container" class="space-y-2" style="display: none;">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Duración
                                (Días)</label>
                            <input type="number" name="days" min="1" max="365"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm focus:border-rose-500/50 focus:ring-0 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Notas
                                Administrativas</label>
                            <textarea name="reason" rows="4"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-sm focus:border-rose-500/50 focus:ring-0 transition-all"
                                placeholder="Razón de la sanción..." required></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-rose-500/20 transition-all uppercase tracking-widest text-xs">
                            Ejecutar Acción Disciplinaria
                        </button>
                    </form>

                    <script>
                        document.querySelector('[name="action"]').addEventListener('change', function() {
                            document.getElementById('days-container').style.display =
                                this.value === 'suspend' ? 'block' : 'none';
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Modal Enviar Mensaje -->
        <div x-show="showMessageModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="closeMessageModal()"></div>

            <div class="relative bg-[#0c111d] border border-white/10 rounded-3xl max-w-xl w-full p-8 shadow-2xl z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-outfit font-black text-white flex items-center gap-2">
                            <span>✉️ Enviar Mensaje a {{ $user->name }}</span>
                        </h3>
                        <p class="text-sm text-gray-400 mt-1">
                            Correo: <span class="text-pink-400 font-mono">{{ $user->email }}</span>
                        </p>
                    </div>
                    <button type="button" @click="closeMessageModal()" class="text-gray-500 hover:text-white p-1 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.moderation.users.message', $user) }}" method="POST" @submit="isSending = true">
                    @csrf
                    <input type="hidden" name="template_key" :value="selectedTemplate">

                    <!-- Selector de Plantillas Prehechas -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                            Seleccionar Plantilla Prehecha
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="(tpl, key) in templates" :key="key">
                                <button type="button" 
                                    @click="applyTemplate(key)"
                                    class="p-2.5 rounded-xl border text-left text-xs font-bold transition-all"
                                    :class="selectedTemplate === key ? 'border-pink-500 bg-pink-500/10 text-pink-400 shadow-sm' : 'border-white/10 bg-white/5 text-gray-400 hover:border-white/20 hover:text-white'">
                                    <span x-text="tpl.title"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Asunto -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                            Asunto del Correo
                        </label>
                        <input type="text" name="subject" x-model="subject" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm text-white focus:border-pink-500 focus:ring-0 transition-all placeholder-gray-600">
                    </div>

                    <!-- Cuerpo del Mensaje -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">
                            Cuerpo del Mensaje
                        </label>
                        <textarea name="message" x-model="message" rows="5" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white focus:border-pink-500 focus:ring-0 transition-all placeholder-gray-600 leading-relaxed"></textarea>
                        <p class="text-[11px] text-gray-500 mt-1">
                            Este mensaje se enviará directamente por correo electrónico y se registrará como notificación del sistema.
                        </p>
                    </div>

                    <!-- Botón de acción opcional -->
                    <div class="grid grid-cols-2 gap-3 mb-6 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">
                                Texto del Botón CTA
                            </label>
                            <input type="text" name="action_text" x-model="actionText" placeholder="Ej: Subir foto"
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-pink-500 focus:ring-0">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">
                                Enlace del Botón CTA
                            </label>
                            <input type="text" name="action_url" x-model="actionUrl" placeholder="https://..."
                                class="w-full bg-white/5 border border-white/10 rounded-xl py-2 px-3 text-xs text-white focus:border-pink-500 focus:ring-0">
                        </div>
                    </div>

                    <!-- Botones acción -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/5">
                        <button type="button" @click="closeMessageModal()"
                            class="px-5 py-2.5 rounded-xl border border-white/10 text-gray-400 hover:text-white text-sm font-bold transition-all">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="isSending"
                            class="px-6 py-2.5 rounded-xl bg-pink-500 hover:bg-pink-600 text-white text-sm font-bold shadow-lg shadow-pink-500/25 transition-all flex items-center gap-2">
                            <span x-show="!isSending">Enviar Mensaje</span>
                            <span x-show="isSending">Enviando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Eliminar Usuario -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="closeDeleteModal()"></div>

            <div class="relative bg-[#0c111d] border border-rose-500/30 rounded-3xl max-w-md w-full p-8 shadow-2xl z-10">
                <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center mx-auto mb-4 text-2xl">
                    🗑️
                </div>

                <h3 class="text-xl font-outfit font-black text-white text-center">
                    ¿Eliminar Usuario?
                </h3>
                
                <p class="text-sm text-gray-400 text-center mt-2">
                    Estás a punto de eliminar permanentemente a <span class="text-white font-bold">{{ $user->name }}</span> 
                    (<span class="text-gray-300 font-mono text-xs">{{ $user->email }}</span>).
                </p>

                <div class="my-4 p-3.5 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-xs text-rose-400 leading-relaxed">
                    ⚠️ <strong>Esta acción es irreversible:</strong> Se eliminarán todas sus fotos en almacenamiento, perfil, likes, coincidencias y mensajes.
                </div>

                <form action="{{ route('admin.moderation.users.destroy', $user) }}" method="POST" @submit="isDeleting = true">
                    @csrf
                    @method('DELETE')

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                            Motivo de la eliminación (opcional)
                        </label>
                        <input type="text" name="reason" x-model="deleteReason" placeholder="Ej: Solicitud del usuario, spam o fotos falsas"
                            class="w-full bg-white/5 border border-white/10 rounded-xl py-2.5 px-3 text-xs text-white focus:border-rose-500 focus:ring-0">
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="closeDeleteModal()"
                            class="flex-1 py-3 rounded-xl border border-white/10 text-gray-400 hover:text-white text-sm font-bold transition-all text-center">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="isDeleting"
                            class="flex-1 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold shadow-lg shadow-rose-600/25 transition-all text-center">
                            <span x-show="!isDeleting">Sí, Eliminar</span>
                            <span x-show="isDeleting">Eliminando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function userDetailAdminManager() {
                return {
                    showMessageModal: false,
                    showDeleteModal: false,
                    selectedTemplate: '{{ $user->photos()->count() > 0 ? "profile_incomplete" : "photo_required" }}',
                    subject: '',
                    message: '',
                    actionUrl: '',
                    actionText: '',
                    deleteReason: '',
                    isSending: false,
                    isDeleting: false,
                    templates: {
                        photo_required: {
                            key: 'photo_required',
                            title: '📸 Solicitud de Foto',
                            subject: '📸 Acción requerida: Sube una foto a tu perfil de BigDad',
                            message: 'Hola {{ addslashes($user->name) }},\n\nNotamos que tu perfil aún no cuenta con una foto de perfil. Para garantizar la seguridad y autenticidad de nuestra comunidad, y para que tu cuenta sea visible para otros miembros, es necesario que subas al menos una foto clara.\n\nPor favor, ingresa a tu cuenta y sube tu foto lo antes posible.',
                            actionUrl: '{{ route('profile.photos.index') }}',
                            actionText: 'Subir mi foto de perfil'
                        },
                        profile_incomplete: {
                            key: 'profile_incomplete',
                            title: '💎 Completar Perfil',
                            subject: '💎 Impulsa tus conexiones: Completa tu información en BigDad',
                            message: 'Hola {{ addslashes($user->name) }},\n\nTu perfil está casi listo, pero aún te faltan detalles importantes como tu descripción o estilo de vida. Los perfiles completos reciben significativamente más atención y matches de calidad.\n\nTe invitamos a actualizar tus datos hoy mismo.',
                            actionUrl: '{{ route('profile.edit') }}',
                            actionText: 'Completar mi perfil'
                        },
                        community_warning: {
                            key: 'community_warning',
                            title: '⚠️ Aviso de Normas',
                            subject: '⚠️ Aviso importante sobre tu cuenta en BigDad',
                            message: 'Hola {{ addslashes($user->name) }},\n\nHemos detectado que parte del contenido de tu cuenta o actividad no cumple con las reglas de convivencia y términos de BigDad. Te solicitamos revisar y corregir tu información para evitar la suspensión temporal o definitiva de tu cuenta.',
                            actionUrl: '{{ route('legal.rules') }}',
                            actionText: 'Ver reglas de la comunidad'
                        },
                        custom: {
                            key: 'custom',
                            title: '✍️ Personalizado',
                            subject: '',
                            message: '',
                            actionUrl: '',
                            actionText: ''
                        }
                    },
                    init() {
                        this.applyTemplate(this.selectedTemplate);
                    },
                    openMessageModal() {
                        this.isSending = false;
                        this.showMessageModal = true;
                    },
                    closeMessageModal() {
                        this.showMessageModal = false;
                    },
                    applyTemplate(key) {
                        this.selectedTemplate = key;
                        const t = this.templates[key];
                        this.subject = t.subject;
                        this.message = t.message;
                        this.actionUrl = t.actionUrl;
                        this.actionText = t.actionText;
                    },
                    openDeleteModal() {
                        this.deleteReason = '';
                        this.isDeleting = false;
                        this.showDeleteModal = true;
                    },
                    closeDeleteModal() {
                        this.showDeleteModal = false;
                    }
                };
            }
        </script>
    @endpush
@endsection
