@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="space-y-8" x-data="userAdminManager()">
        <!-- Header Summary -->
        <div class="flex items-center justify-between bg-[#0c111d] border border-white/5 p-8 rounded-3xl">
            <div>
                <h2 class="text-3xl font-outfit font-black">Base de Usuarios</h2>
                <p class="text-gray-500 mt-1">Total registrados: <span
                        class="text-white font-bold">{{ $users->total() }}</span></p>
            </div>
            <div class="flex gap-4">
                <!-- Stats -->
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Activos</p>
                    <p class="text-xl font-outfit font-bold text-emerald-500">
                        {{ number_format($activeCount) }}
                    </p>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Baneados</p>
                    <p class="text-xl font-outfit font-bold text-rose-500">{{ number_format($bannedCount) }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-[#0c111d] border border-white/5 p-6 rounded-3xl">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Búsqueda</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Nombre o email..." value="{{ request('search') }}"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-10 text-sm focus:border-pink-500/50 focus:ring-0 transition-all">
                        <svg class="w-4 h-4 absolute left-4 top-3.5 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Tipo de Usuario</label>
                    <select name="user_type"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all">
                        <option value="">Cualquiera</option>
                        <option value="sugar_daddy" {{ request('user_type') === 'sugar_daddy' ? 'selected' : '' }}>Sugar
                            Daddy
                        </option>
                        <option value="sugar_baby" {{ request('user_type') === 'sugar_baby' ? 'selected' : '' }}>Sugar Baby
                        </option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">Estado Cuenta</label>
                    <select name="status"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all">
                        <option value="">Todos</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendidos
                        </option>
                        <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Baneados</option>
                        <option value="pending_verification"
                            {{ request('status') === 'pending_verification' ? 'selected' : '' }}>Pendientes Verif.</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest px-1">País</label>
                    <select name="country_id"
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-3 px-4 text-sm focus:border-pink-500/50 focus:ring-0 appearance-none transition-all">
                        <option value="">Cualquier País</option>
                        <option value="none" {{ request('country_id') === 'none' ? 'selected' : '' }}>⚠️ Sin País Asignado
                        </option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex items-end">
                    <button type="submit"
                        class="w-full bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 rounded-2xl shadow-lg shadow-pink-500/20 transition-all">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-[#0c111d] border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left min-w-[980px]">
                    <thead>
                        <tr class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-white/5">
                            <th class="px-5 py-4">Perfil</th>
                            <th class="px-5 py-4">País</th>
                            <th class="px-5 py-4">Verificación</th>
                            <th class="px-5 py-4">Nivel</th>
                            <th class="px-5 py-4">Actividad</th>
                            <th class="px-5 py-4">Estado</th>
                            <th class="px-5 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/2">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="relative shrink-0">
                                        <div
                                            class="w-11 h-11 rounded-2xl bg-gradient-to-br from-pink-500/20 to-purple-500/20 border border-white/10 flex items-center justify-center text-pink-500 font-black text-base overflow-hidden">
                                            @if ($user->primary_photo_url)
                                                <img src="{{ $user->primary_photo_url }}" alt="{{ $user->name }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                {{ substr($user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        @if ($user->is_premium)
                                            <div
                                                class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 rounded-lg flex items-center justify-center border-2 border-[#0c111d]">
                                                <span class="text-[9px] text-white">👑</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-white group-hover:text-pink-500 transition-colors truncate">
                                            {{ $user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($user->country)
                                    <div class="flex items-center gap-2 text-xs text-gray-300">
                                        <img src="https://flagcdn.com/w20/{{ strtolower($user->country->iso_code) }}.png"
                                            width="20" height="15" alt="{{ $user->country->name }}"
                                            class="rounded-[2px] shrink-0">
                                        <span>{{ $user->country->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-rose-500 font-bold italic">Sin País</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($user->is_verified)
                                    <span class="flex items-center gap-1.5 text-xs text-blue-400 font-bold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.64.304 1.24.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="hidden xl:inline">Verificado</span>
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap {{ $user->isSugarDaddy() ? 'text-purple-400' : 'text-pink-400' }}">
                                    <span class="sm:hidden">{{ $user->isSugarDaddy() ? 'SD' : 'SB' }}</span>
                                    <span
                                        class="hidden sm:inline">{{ $user->isSugarDaddy() ? 'Sugar Daddy' : 'Sugar Baby' }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-400 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span title="Mensajes">{{ $user->sentMessages->count() }} ✉️</span>
                                    <span class="text-gray-700 hidden lg:inline">|</span>
                                    <span class="hidden lg:inline"
                                        title="Creación">{{ $user->created_at->format('d/m/y') }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($user->isBanned())
                                    <div class="flex items-center gap-2 text-rose-500">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                                        <span class="text-xs font-bold uppercase">Baneado</span>
                                    </div>
                                @elseif($user->isSuspended())
                                    <div class="flex items-center gap-2 text-amber-500">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                        <span class="text-xs font-bold uppercase">Suspendido</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 text-emerald-500">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                        <span class="text-xs font-bold uppercase">Activo</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botón Enviar Mensaje -->
                                    <button type="button"
                                        @click="openMessageModal({{ json_encode(['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'has_photo' => $user->photos()->count() > 0]) }})"
                                        title="Enviar mensaje / advertencia"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-pink-500/10 hover:bg-pink-500/20 text-pink-400 border border-pink-500/20 rounded-xl text-xs font-bold transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="hidden xl:inline">Mensaje</span>
                                    </button>

                                    <!-- Botón Ver Detalles -->
                                    <a href="{{ route('admin.moderation.users.show', $user) }}"
                                        title="Ver perfil completo"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white/5 hover:bg-white/10 border border-white/5 text-gray-300 rounded-xl text-xs font-bold transition-all">
                                        <span>Detalles</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>

                                    <!-- Botón Eliminar (no permitido para cuenta propia) -->
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                            @click="openDeleteModal({{ json_encode(['id' => $user->id, 'name' => $user->name, 'email' => $user->email]) }})"
                                            title="Eliminar usuario definitivamente"
                                            class="inline-flex items-center justify-center p-2 bg-rose-500/10 hover:bg-rose-500/25 text-rose-400 border border-rose-500/20 rounded-xl text-xs transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div
                                    class="inline-flex items-center justify-center w-20 h-20 bg-white/5 rounded-3xl mb-4 text-gray-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No se encontraron usuarios con esos filtros.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            @if ($users->hasPages())
                <div class="px-6 py-5 border-t border-white/5 bg-white/[0.01]">
                    {{ $users->links() }}
                </div>
            @endif
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
                            <span>✉️ Enviar Mensaje</span>
                        </h3>
                        <p class="text-sm text-gray-400 mt-1">
                            Destinatario: <span class="text-pink-400 font-bold" x-text="targetUser?.name"></span> 
                            (<span class="text-gray-400 font-mono text-xs" x-text="targetUser?.email"></span>)
                        </p>
                    </div>
                    <button type="button" @click="closeMessageModal()" class="text-gray-500 hover:text-white p-1 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="targetUser ? `/admin/moderation/users/${targetUser.id}/message` : '#'" method="POST" @submit="isSending = true">
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
                    Estás a punto de eliminar a <span class="text-white font-bold" x-text="targetUser?.name"></span> 
                    (<span class="text-gray-300 font-mono text-xs" x-text="targetUser?.email"></span>).
                </p>

                <div class="my-4 p-3.5 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-xs text-rose-400 leading-relaxed">
                    ⚠️ <strong>Esta acción es irreversible:</strong> Se eliminarán definitivamente sus fotos en almacenamiento, perfil, likes, coincidencias y mensajes.
                </div>

                <form :action="targetUser ? `/admin/moderation/users/${targetUser.id}` : '#'" method="POST" @submit="isDeleting = true">
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
            function userAdminManager() {
                return {
                    showMessageModal: false,
                    showDeleteModal: false,
                    targetUser: null,
                    selectedTemplate: 'photo_required',
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
                            message: 'Hola {name},\n\nNotamos que tu perfil aún no cuenta con una foto de perfil. Para garantizar la seguridad y autenticidad de nuestra comunidad, y para que tu cuenta sea visible para otros miembros, es necesario que subas al menos una foto clara.\n\nPor favor, ingresa a tu cuenta y sube tu foto lo antes posible.',
                            actionUrl: '{{ route('profile.photos.index') }}',
                            actionText: 'Subir mi foto de perfil'
                        },
                        profile_incomplete: {
                            key: 'profile_incomplete',
                            title: '💎 Completar Perfil',
                            subject: '💎 Impulsa tus conexiones: Completa tu información en BigDad',
                            message: 'Hola {name},\n\nTu perfil está casi listo, pero aún te faltan detalles importantes como tu descripción o estilo de vida. Los perfiles completos reciben significativamente más atención y matches de calidad.\n\nTe invitamos a actualizar tus datos hoy mismo.',
                            actionUrl: '{{ route('profile.edit') }}',
                            actionText: 'Completar mi perfil'
                        },
                        community_warning: {
                            key: 'community_warning',
                            title: '⚠️ Aviso de Normas',
                            subject: '⚠️ Aviso importante sobre tu cuenta en BigDad',
                            message: 'Hola {name},\n\nHemos detectado que parte del contenido de tu cuenta o actividad no cumple con las reglas de convivencia y términos de BigDad. Te solicitamos revisar y corregir tu información para evitar la suspensión temporal o definitiva de tu cuenta.',
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
                    openMessageModal(user) {
                        this.targetUser = user;
                        this.isSending = false;
                        this.applyTemplate(user.has_photo ? 'profile_incomplete' : 'photo_required');
                        this.showMessageModal = true;
                    },
                    closeMessageModal() {
                        this.showMessageModal = false;
                        this.targetUser = null;
                    },
                    applyTemplate(key) {
                        this.selectedTemplate = key;
                        const t = this.templates[key];
                        const name = this.targetUser ? this.targetUser.name : 'Usuario';
                        this.subject = t.subject;
                        this.message = t.message.replace(/{name}/g, name);
                        this.actionUrl = t.actionUrl;
                        this.actionText = t.actionText;
                    },
                    openDeleteModal(user) {
                        this.targetUser = user;
                        this.deleteReason = '';
                        this.isDeleting = false;
                        this.showDeleteModal = true;
                    },
                    closeDeleteModal() {
                        this.showDeleteModal = false;
                        this.targetUser = null;
                    }
                };
            }
        </script>
    @endpush
@endsection
