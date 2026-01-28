<div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
    <div class="flex items-center gap-3 mb-8">
        <div
            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white text-xl shadow-lg">
            🔒
        </div>
        <div>
            <h2 class="text-2xl font-playfair font-bold text-gray-900">Seguridad</h2>
            <p class="text-gray-600 text-sm">Actualiza tu contraseña</p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Current Password --}}
            <div>
                <label for="current_password"
                    class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    Contraseña Actual
                </label>
                <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                    class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl 
                           focus:outline-none focus:ring-4 focus:ring-gray-200 focus:border-gray-400 
                           transition-all duration-200 font-medium text-gray-900 placeholder-gray-400"
                    placeholder="••••••••">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    Nueva Contraseña
                </label>
                <input type="password" name="password" id="password" autocomplete="new-password" class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl 
                           focus:outline-none focus:ring-4 focus:ring-gray-200 focus:border-gray-400 
                           transition-all duration-200 font-medium text-gray-900 placeholder-gray-400"
                    placeholder="••••••••">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation"
                    class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    Confirmar Contraseña
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    autocomplete="new-password" class="w-full px-5 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl 
                           focus:outline-none focus:ring-4 focus:ring-gray-200 focus:border-gray-400 
                           transition-all duration-200 font-medium text-gray-900 placeholder-gray-400"
                    placeholder="••••••••">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-6">
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizada correctamente
                </p>
            @endif

            <button type="submit"
                class="px-8 py-3 bg-gray-900 hover:bg-gray-800 rounded-xl text-white font-bold transition-all duration-300 shadow-lg hover:scale-105">
                Actualizar Contraseña
            </button>
        </div>
    </form>
</div>