@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')

@section('content')
    <div class="space-y-8">
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
            <table class="w-full text-left">
                <thead>
                    <tr class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-white/5">
                        <th class="px-8 py-5">Perfil</th>
                        <th class="px-8 py-5 hidden lg:table-cell">País</th>
                        <th class="px-8 py-5 hidden md:table-cell">Verificación</th>
                        <th class="px-8 py-5">Nivel</th>
                        <th class="px-8 py-5 hidden sm:table-cell">Actividad</th>
                        <th class="px-8 py-5">Estado</th>
                        <th class="px-8 py-5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/2">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500/20 to-purple-500/20 border border-white/10 flex items-center justify-center text-pink-500 font-black text-lg">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        @if ($user->is_premium)
                                            <div
                                                class="absolute -top-1 -right-1 w-5 h-5 bg-amber-500 rounded-lg flex items-center justify-center border-2 border-[#0c111d]">
                                                <span class="text-[10px] text-white">👑</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-white group-hover:text-pink-500 transition-colors">
                                            {{ $user->name }}
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                            @if ($user->country)
                                                <img src="https://flagcdn.com/w20/{{ strtolower($user->country->iso_code) }}.png"
                                                    class="w-4 h-3 rounded-sm lg:hidden"
                                                    title="{{ $user->country->name }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 hidden lg:table-cell">
                                @if ($user->country)
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <img src="https://flagcdn.com/w20/{{ strtolower($user->country->iso_code) }}.png"
                                            width="20" height="15" alt="{{ $user->country->name }}"
                                            class="rounded-[2px]">
                                        <span>{{ $user->country->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-rose-500 font-bold italic">Sin País</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 hidden md:table-cell">
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
                            <td class="px-8 py-6">
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap {{ $user->isSugarDaddy() ? 'text-purple-400' : 'text-pink-400' }}">
                                    <span class="sm:hidden">{{ $user->isSugarDaddy() ? 'SD' : 'SB' }}</span>
                                    <span
                                        class="hidden sm:inline">{{ $user->isSugarDaddy() ? 'Sugar Daddy' : 'Sugar Baby' }}</span>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-xs text-gray-400 hidden sm:table-cell">
                                <div class="flex items-center gap-2">
                                    <span title="Mensajes">{{ $user->sentMessages->count() }} ✉️</span>
                                    <span class="text-gray-700 hidden lg:inline">|</span>
                                    <span class="hidden lg:inline"
                                        title="Creación">{{ $user->created_at->format('d/m/y') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
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
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('admin.moderation.users.show', $user) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-xl text-sm font-bold transition-all">
                                    Detalles
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            @if ($users->hasPages())
                <div class="px-8 py-6 border-t border-white/5 bg-white/[0.01]">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
