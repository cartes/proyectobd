@extends('layouts.admin')

@section('title', 'Mi Perfil')

@section('content')
    <div class="max-w-2xl space-y-8">

        {{-- ── Datos Básicos ── --}}
        <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8">
            <div class="mb-6">
                <h2 class="text-lg font-outfit font-bold">Información de la Cuenta</h2>
                <p class="text-sm text-gray-500 mt-1">Actualiza tu nombre y dirección de correo.</p>
            </div>

            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Nombre</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500/50 transition-colors
                               @error('name') border-red-500/50 @enderror"
                    />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Correo Electrónico</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="username"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500/50 transition-colors
                               @error('email') border-red-500/50 @enderror"
                    />
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-sm font-semibold rounded-xl transition-colors"
                    >
                        Guardar cambios
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="text-sm text-emerald-400"
                        >
                            ✓ Guardado correctamente.
                        </p>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Contraseña ── --}}
        <div id="update-password" class="bg-[#0c111d] border border-white/5 rounded-3xl p-8">
            <div class="mb-6">
                <h2 class="text-lg font-outfit font-bold">Cambiar Contraseña</h2>
                <p class="text-sm text-gray-500 mt-1">Usa una contraseña larga y aleatoria para mayor seguridad.</p>
            </div>

            <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Contraseña actual --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-300 mb-1.5">Contraseña Actual</label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        autocomplete="current-password"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500/50 transition-colors
                               @error('current_password') border-red-500/50 @enderror"
                    />
                    @error('current_password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nueva contraseña --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Nueva Contraseña</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500/50 transition-colors
                               @error('password') border-red-500/50 @enderror"
                    />
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Confirmar Nueva Contraseña</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500/50 transition-colors"
                    />
                </div>

                <div class="flex items-center gap-4 pt-1">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-sm font-semibold rounded-xl transition-colors"
                    >
                        Actualizar contraseña
                    </button>

                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 3000)"
                            class="text-sm text-emerald-400"
                        >
                            ✓ Contraseña actualizada.
                        </p>
                    @endif
                </div>
            </form>
        </div>

    </div>
@endsection
