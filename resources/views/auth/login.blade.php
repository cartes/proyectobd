<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Iniciar Sesión</h2>
        <p class="text-gray-600 mt-2">Bienvenido de vuelta a Big-dad</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-gray-700" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 transition-colors"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                placeholder="tu@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Contraseña')" class="text-sm font-semibold text-gray-700" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 transition-colors"
                type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-purple-600 hover:text-purple-800 transition-colors"
                    href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="space-y-4">
            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-4 focus:ring-purple-200">
                Entrar a Big-dad
            </button>

            <div class="text-center">
                <a href="{{ route('register') }}" class="text-purple-600 hover:text-purple-800">
                    ¿No tienes cuenta? Registrarse
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
