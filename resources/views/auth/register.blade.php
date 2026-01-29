<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Crear cuenta</h2>
        <p class="text-gray-600 mt-2">Únete a nuestra exclusiva comunidad</p>
    </div>

    <form method="POST" action="{{ route('register') }}" x-data="registrationForm()" class="space-y-6">
        @csrf

        <!-- Tipo de Usuario -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-4">¿Qué tipo de usuario eres?</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer group" @click="userType = 'sugar_daddy'">
                    <input type="radio" name="user_type" value="sugar_daddy" x-model="userType" class="sr-only">
                    <div class="p-4 border-2 rounded-xl text-center transition-all group-hover:shadow-md"
                        :class="userType === 'sugar_daddy' ? 'border-purple-500 bg-purple-50 shadow-md' : 'border-gray-200 group-hover:border-purple-300'">
                        <div class="text-2xl mb-2">👑</div>
                        <div class="font-semibold text-sm">Sugar Daddy</div>
                    </div>
                </label>
                <label class="cursor-pointer group" @click="userType = 'sugar_baby'">
                    <input type="radio" name="user_type" value="sugar_baby" x-model="userType" class="sr-only">
                    <div class="p-4 border-2 rounded-xl text-center transition-all group-hover:shadow-md"
                        :class="userType === 'sugar_baby' ? 'border-pink-500 bg-pink-50 shadow-md' : 'border-gray-200 group-hover:border-pink-300'">
                        <div class="text-2xl mb-2">💎</div>
                        <div class="font-semibold text-sm">Sugar Baby</div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
        </div>

        <!-- Información Personal -->
        <div class="space-y-4">
            <div>
                <x-input-label for="name" :value="__('Nombre completo')" class="text-sm font-semibold text-gray-700" />
                <x-text-input id="name"
                    class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    type="text" name="name" :value="old('name')" required autofocus placeholder="Tu nombre completo" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-gray-700" />
                <x-text-input id="email"
                    class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    type="email" name="email" :value="old('email')" required placeholder="tu@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="gender" :value="__('Género')" class="text-sm font-semibold text-gray-700" />
                    <select id="gender" name="gender"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                        required>
                        <option value="">Seleccionar</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="birth_date" :value="__('Nacimiento')"
                        class="text-sm font-semibold text-gray-700" />
                    <x-text-input id="birth_date"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                        type="date" name="birth_date" :value="old('birth_date')" required />
                    <x-input-error :messages="$errors->get('birth_date')" class="mt-1" />
                </div>
            </div>

            <div>
                <x-input-label for="country_id" :value="__('País')" class="text-sm font-semibold text-gray-700" />
                <select id="country_id" name="country_id"
                    class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                    required>
                    <option value="">Selecciona tu país</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            @selected(old('country_id', $defaultCountryId) == $country->id)
                        >
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('country_id')" class="mt-1" />
                @if(isset($defaultCountryId))
                    <p class="text-xs text-green-600 mt-1">
                        🌍 Detectamos que estás en {{ $countries->find($defaultCountryId)->name }}.
                        <span class="text-gray-500">¿No es correcto? Puedes cambiarlo.</span>
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div x-data="{ show: false }">
                    <x-input-label for="password" :value="__('Contraseña')"
                        class="text-sm font-semibold text-gray-700" />
                    <div class="relative">
                        <x-text-input id="password"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 pr-10"
                            ::type="show ? 'text' : 'password'" name="password" required />
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none mt-1">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div x-data="{ show: false }">
                    <x-input-label for="password_confirmation" :value="__('Confirmar')"
                        class="text-sm font-semibold text-gray-700" />
                    <div class="relative">
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 pr-10"
                            ::type="show ? 'text' : 'password'" name="password_confirmation" required />
                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none mt-1">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Términos -->
        <div>
            <label class="flex items-start space-x-2 text-xs text-gray-600">
                <input type="checkbox" required
                    class="mt-1 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                <span>Acepto los términos y condiciones y la política de privacidad de Big-dad</span>
            </label>
        </div>

        <!-- Botones -->
        <div class="space-y-3">
            <button type="submit"
                class="w-full py-3 px-4 rounded-lg font-semibold transition-all focus:outline-none focus:ring-4" :class="{
                        'bg-purple-600 hover:bg-purple-700 text-white focus:ring-purple-200': userType === 'sugar_daddy',
                        'bg-pink-600 hover:bg-pink-700 text-white focus:ring-pink-200': userType === 'sugar_baby',
                        'bg-gray-300 text-gray-500 cursor-not-allowed': !userType
                    }" :disabled="!userType">
                <span
                    x-text="userType === 'sugar_daddy' ? '👑 Crear cuenta como Sugar Daddy' : (userType === 'sugar_baby' ? '💎 Crear cuenta como Sugar Baby' : 'Selecciona tipo de usuario')"></span>
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-purple-600 transition-colors">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>
        </div>
    </form>

    <script>
        function registrationForm() {
            return {
                userType: '{{ old("user_type", "sugar_baby") }}'
            }
        }
    </script>
</x-guest-layout>