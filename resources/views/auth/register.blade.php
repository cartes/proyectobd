@section('meta_title', 'Registro Gratis | BigDad - Sugar Dating Elite en Latinoamérica')
@section('meta_description', 'Crea tu cuenta gratis en BigDad y descubre miles de Sugar Babies y Sugar Daddies
    verificados. Únete a la comunidad premium de citas exclusivas.')
@section('meta_keywords', 'registro sugar dating, crear cuenta sugar baby, registrarse sugar daddy gratis, bigdad
    registro')

    <x-guest-layout>
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Crear cuenta</h2>
            <p class="text-gray-600 mt-2">Únete a nuestra exclusiva comunidad</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="registrationForm()" class="space-y-6">
            @csrf

            <!-- Tipo de Usuario -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-4">¿Qué tipo de usuario eres?</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer group" @click="userType = 'sugar_daddy'">
                        <input type="radio" name="user_type" value="sugar_daddy" x-model="userType" class="sr-only">
                        <div class="p-4 border-2 rounded-xl text-center transition-all group-hover:shadow-md"
                            :class="userType === 'sugar_daddy' ? 'border-purple-500 bg-purple-50 shadow-md' :
                                'border-gray-200 group-hover:border-purple-300'">
                            <div class="text-2xl mb-2">👑</div>
                            <div class="font-semibold text-sm">Sugar Daddy</div>
                        </div>
                    </label>
                    <label class="cursor-pointer group" @click="userType = 'sugar_baby'">
                        <input type="radio" name="user_type" value="sugar_baby" x-model="userType" class="sr-only">
                        <div class="p-4 border-2 rounded-xl text-center transition-all group-hover:shadow-md"
                            :class="userType === 'sugar_baby' ? 'border-pink-500 bg-pink-50 shadow-md' :
                                'border-gray-200 group-hover:border-pink-300'">
                            <div class="text-2xl mb-2">💎</div>
                            <div class="font-semibold text-sm">Sugar Baby</div>
                        </div>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('user_type')" class="mt-2" />
            </div>

            <!-- Banner motivacional lúdico -->
            <div x-show="userType === 'sugar_baby'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="p-4 rounded-2xl bg-gradient-to-r from-pink-50 via-rose-50 to-pink-50 border border-pink-200/80 shadow-sm relative overflow-hidden">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center text-lg shrink-0 shadow-inner">
                        ✨
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-pink-700">
                            «Una mejor vida siempre es mejor acompañada»
                        </p>
                        <p class="text-xs text-pink-900/80 leading-relaxed">
                            <span x-text="gender === 'male' ? 'Relájate y sé tú mismo.' : 'Relájate y sé tú misma.'">Relájate y sé tú misma.</span>
                            Estás a punto de unirte a una comunidad exclusiva diseñada para conectar con personas que valoran tu estilo y compañía.
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="userType === 'sugar_daddy'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="p-4 rounded-2xl bg-gradient-to-r from-purple-50 via-indigo-50 to-purple-50 border border-purple-200/80 shadow-sm relative overflow-hidden">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-lg shrink-0 shadow-inner">
                        👑
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-purple-700">
                            «Una mejor vida siempre es mejor acompañada»
                        </p>
                        <p class="text-xs text-purple-900/80 leading-relaxed">
                            Conecta con personas extraordinarias y auténticas dispuestas a compartir momentos memorables.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información Personal -->
            <div class="space-y-4">
                <div>
                    <x-input-label for="name" :value="__('Nombre completo')" class="text-sm font-semibold text-gray-700" />
                    <x-text-input id="name"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                        type="text" name="name" :value="old('name')" required autofocus
                        placeholder="Tu nombre completo" />
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
                        <select id="gender" name="gender" x-model="gender"
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
                        <x-input-label for="birth_date" :value="__('Nacimiento')" class="text-sm font-semibold text-gray-700" />
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
                        x-model="selectedCountryId"
                        @change="loadCities(selectedCountryId); selectedCityId = ''"
                        required>
                        <option value="">Selecciona tu país</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @selected(old('country_id', $defaultCountryId) == $country->id)>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('country_id')" class="mt-1" />
                    @if (isset($defaultCountryId))
                        <p class="text-xs text-green-600 mt-1">
                            🌍 Detectamos que estás en {{ $countries->find($defaultCountryId)->name }}.
                            <span class="text-gray-500">¿No es correcto? Puedes cambiarlo.</span>
                        </p>
                    @endif
                </div>

                <div>
                    <x-input-label for="city_id" :value="__('Ciudad')" class="text-sm font-semibold text-gray-700" />
                    <input type="hidden" name="city_id" :value="selectedCityId === 'other' ? '' : selectedCityId">
                    <select id="city_id" x-model="selectedCityId"
                        class="block mt-1 w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500"
                        :disabled="!selectedCountryId || loadingCities">
                        <option value="">
                            <span x-text="loadingCities ? 'Cargando ciudades...' : (selectedCountryId ? 'Selecciona tu ciudad (opcional)' : 'Primero elige un país')"></span>
                        </option>
                        <template x-for="city in cities" :key="city.id">
                            <option :value="city.id" x-text="city.name" :selected="city.id == selectedCityId"></option>
                        </template>
                        <option value="other">✏️ Otra ciudad</option>
                    </select>
                    <div x-show="selectedCityId === 'other'" x-transition class="mt-2">
                        <input type="text" name="city" x-model="otherCity" maxlength="100"
                            placeholder="Escribe tu ciudad..."
                            class="block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 text-sm">
                    </div>
                    <x-input-error :messages="$errors->get('city_id')" class="mt-1" />
                </div>

                <!-- Foto de Perfil (Obligatoria para Sugar Babies) -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label for="photo" class="text-sm font-semibold text-gray-700 flex items-center gap-1.5">
                            <span>Foto de perfil</span>
                            <span x-show="userType === 'sugar_baby'" class="text-xs text-pink-600 font-bold bg-pink-50 px-2 py-0.5 rounded-full border border-pink-200">
                                * Obligatoria
                            </span>
                            <span x-show="userType === 'sugar_daddy'" class="text-xs text-gray-500 font-normal">
                                (Opcional)
                            </span>
                        </label>
                        <span x-show="userType === 'sugar_baby'" class="text-[11px] text-pink-600 font-semibold">
                            Al menos 1 foto requerida
                        </span>
                    </div>

                    <div class="border-2 border-dashed rounded-xl p-4 transition-all text-center relative"
                        :class="photoPreview ? 'border-pink-500 bg-pink-50/20' : (userType === 'sugar_baby' ? 'border-pink-300 hover:border-pink-400 bg-pink-50/10' : 'border-gray-300 hover:border-purple-300 bg-gray-50')">
                        
                        <!-- Preview si se seleccionó foto con feedback lúdico -->
                        <div x-show="photoPreview" class="space-y-3">
                            <div class="relative inline-block">
                                <img :src="photoPreview" alt="Vista previa" 
                                     class="w-28 h-28 object-cover rounded-2xl mx-auto shadow-md border-2 border-white ring-4 transition-all duration-300 hover:scale-105"
                                     :class="userType === 'sugar_baby' ? 'ring-pink-400 shadow-pink-200' : 'ring-purple-400 shadow-purple-200'">
                                
                                <!-- Insignia de estado lúdica -->
                                <span class="absolute -bottom-2 -left-2 bg-white rounded-full p-1 shadow border text-sm select-none">
                                    <span x-show="userType === 'sugar_baby'">🔥</span>
                                    <span x-show="userType === 'sugar_daddy'">👑</span>
                                </span>

                                <button type="button" @click="removePhoto()" 
                                        class="absolute -top-2 -right-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full p-1.5 shadow-md transition-transform hover:scale-110"
                                        title="Eliminar foto">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <p class="text-xs text-gray-500 font-medium truncate max-w-xs mx-auto" x-text="photoFileName"></p>

                            <!-- Mensaje lúdico y motivacional interactivo -->
                            <div class="p-3.5 rounded-2xl border text-left shadow-sm relative overflow-hidden transition-all duration-300"
                                 :class="userType === 'sugar_baby' ? 'bg-gradient-to-br from-pink-50 via-rose-50 to-white border-pink-200' : 'bg-gradient-to-br from-purple-50 via-indigo-50 to-white border-purple-200'">
                                
                                <div class="flex items-start gap-2.5">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm shadow-sm shrink-0"
                                         :class="userType === 'sugar_baby' ? 'bg-pink-500 text-white' : 'bg-purple-600 text-white'">
                                        <span x-text="userType === 'sugar_baby' ? '💎' : '👑'"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="text-[11px] font-bold tracking-wider uppercase"
                                                  :class="userType === 'sugar_baby' ? 'text-pink-600' : 'text-purple-600'">
                                                ¡Vibra BigDad 10/10! ✨
                                            </span>
                                            <button type="button" @click="nextCompliment()" 
                                                    class="text-[11px] font-semibold text-gray-500 hover:text-pink-600 flex items-center gap-1 transition-colors px-2 py-0.5 rounded-full hover:bg-white border border-transparent hover:border-pink-200"
                                                    title="Ver otro halago">
                                                <span>🎲 Otro halago</span>
                                            </button>
                                        </div>
                                        
                                        <p class="text-xs sm:text-sm font-semibold text-gray-800 leading-snug" 
                                           x-text="currentCompliment"></p>
                                        
                                        <p class="text-[11px] text-pink-600 font-medium mt-1.5 flex items-center gap-1">
                                            <span>✨</span>
                                            <span>«Una mejor vida siempre es mejor acompañada»</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="button" @click="$refs.photoInput.click()" class="text-xs text-pink-600 font-bold hover:underline">
                                    Cambiar foto
                                </button>
                            </div>
                        </div>

                        <!-- Dropzone cuando no hay foto -->
                        <div x-show="!photoPreview" @click="$refs.photoInput.click()" class="cursor-pointer py-4">
                            <div class="w-12 h-12 rounded-2xl mx-auto mb-2 flex items-center justify-center transition-transform hover:scale-105"
                                 :class="userType === 'sugar_baby' ? 'bg-pink-100 text-pink-500' : 'bg-purple-100 text-purple-500'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">
                                <span :class="userType === 'sugar_baby' ? 'text-pink-600 hover:underline' : 'text-purple-600 hover:underline'">
                                    Haz clic para subir tu mejor foto
                                </span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span x-show="userType === 'sugar_baby'">📸 ¡Sube una donde salgas sonriendo! Tu sonrisa abre todas las puertas.</span>
                                <span x-show="userType === 'sugar_daddy'">JPG, PNG o WEBP (máx. 20MB)</span>
                            </p>
                        </div>

                        <input type="file" id="photo" name="photo" x-ref="photoInput" class="hidden" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               @change="handlePhotoSelect($event)"
                               :required="userType === 'sugar_baby'">
                    </div>
                    <x-input-error :messages="$errors->get('photo')" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div x-data="{ show: false }">
                        <x-input-label for="password" :value="__('Contraseña')" class="text-sm font-semibold text-gray-700" />
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
                                <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-5 h-5">
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
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-5 h-5">
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
                    <span>Acepto los términos y condiciones y la política de privacidad de BigDad</span>
                </label>
            </div>

            <!-- Botones -->
            <div class="space-y-3">
                <button type="submit"
                    class="w-full py-3 px-4 rounded-lg font-semibold transition-all focus:outline-none focus:ring-4"
                    :class="{
                        'bg-purple-600 hover:bg-purple-700 text-white focus:ring-purple-200': userType === 'sugar_daddy',
                        'bg-pink-600 hover:bg-pink-700 text-white focus:ring-pink-200': userType === 'sugar_baby',
                        'bg-gray-300 text-gray-500 cursor-not-allowed': !userType
                    }"
                    :disabled="!userType">
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
                    userType: '{{ old('user_type', $preferredUserType ?? 'sugar_baby') }}',
                    gender: '{{ old('gender', '') }}',
                    selectedCountryId: '{{ old('country_id', $defaultCountryId ?? '') }}',
                    cities: [],
                    loadingCities: false,
                    selectedCityId: '{{ old('city_id', old('city') ? 'other' : '') }}',
                    otherCity: '{{ old('city', '') }}',
                    photoPreview: null,
                    photoFileName: '',
                    currentComplimentIndex: 0,
                    get activeCompliments() {
                        const isMale = this.gender === 'male';
                        const isFemale = this.gender === 'female' || (!this.gender && this.userType === 'sugar_baby');

                        if (this.userType === 'sugar_baby') {
                            if (isMale) {
                                return [
                                    '¡Wooow, sí que eres guapísimo! Con esa foto vas a causar sensación 🔥',
                                    '¡Qué bien te ves! Te va a ir súper bien por aquí ✨',
                                    '¡Esa sonrisa lo dice todo! Vas a conquistar muchas miradas en BigDad 💖',
                                    '¡Qué estilazo! Listo para vivir experiencias increíbles 😍',
                                    '¡Foto espectacular! Una mejor vida siempre es mejor acompañada 🥂',
                                    '¡Simplemente radiante! Listo para destacar en BigDad 💎'
                                ];
                            } else if (isFemale) {
                                return [
                                    '¡Wooow, sí que eres guapísima! Con esa foto vas a causar sensación 🔥',
                                    '¡Qué linda! Te va a ir súper bien por aquí ✨',
                                    '¡Esa sonrisa lo dice todo! Vas a conquistar miradas en BigDad 💖',
                                    '¡Qué estilazo! Lista para vivir experiencias increíbles 😍',
                                    '¡Foto espectacular! Una mejor vida siempre es mejor acompañada 🥂',
                                    '¡Simplemente radiante! Los Sugar Daddies más selectos van a querer conocerte ya 💎'
                                ];
                            } else {
                                return [
                                    '¡Wooow, te ves increíble! Con esa foto vas a causar sensación 🔥',
                                    '¡Qué gran estilo! Te va a ir súper bien por aquí ✨',
                                    '¡Esa sonrisa lo dice todo! Vas a conquistar miradas en BigDad 💖',
                                    '¡Foto espectacular! Una mejor vida siempre es mejor acompañada 🥂',
                                    '¡Vibra 10/10! Lista/o para vivir experiencias únicas en BigDad 💎'
                                ];
                            }
                        } else {
                            // Sugar Daddy / Sugar Mommy
                            if (this.gender === 'female') {
                                return [
                                    '¡Wooow, qué distinguida! Con esa foto vas a causar sensación 🔥',
                                    '¡Excelente presencia! Refleja todo tu éxito, porte y elegancia 👑',
                                    '¡Gran foto! Con esa presentación destacarás de inmediato 🥂',
                                    '¡Foto impecable! Lista para inspirar distinción y confianza ✨',
                                    '¡Elegancia total! Una mejor vida siempre es mejor acompañada 💎'
                                ];
                            } else {
                                return [
                                    '¡Wooow, sí que eres guapo y tienes porte! Con esa foto vas a causar sensación 🔥',
                                    '¡Excelente presencia! Refleja todo tu porte y estilo de vida 👑',
                                    '¡Gran foto! Con esa presentación destacarás de inmediato 🥂',
                                    '¡Foto impecable! Listo para inspirar distinción y confianza ✨',
                                    '¡Elegancia total! Una mejor vida siempre es mejor acompañada 💎'
                                ];
                            }
                        }
                    },
                    get currentCompliment() {
                        const list = this.activeCompliments;
                        return list[this.currentComplimentIndex % list.length];
                    },
                    pickRandomCompliment() {
                        const list = this.activeCompliments;
                        this.currentComplimentIndex = Math.floor(Math.random() * list.length);
                    },
                    nextCompliment() {
                        const list = this.activeCompliments;
                        this.currentComplimentIndex = (this.currentComplimentIndex + 1) % list.length;
                    },
                    async init() {
                        if (this.selectedCountryId) {
                            await this.loadCities(this.selectedCountryId);
                        }
                    },
                    async loadCities(countryId) {
                        if (!countryId) { this.cities = []; return; }
                        this.loadingCities = true;
                        try {
                            const res = await fetch(`/api/countries/${countryId}/cities`);
                            this.cities = await res.json();
                        } catch(e) { this.cities = []; }
                        this.loadingCities = false;
                    },
                    handlePhotoSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.photoFileName = file.name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.photoPreview = e.target.result;
                                this.pickRandomCompliment();
                            };
                            reader.readAsDataURL(file);
                        }
                    },
                    removePhoto() {
                        this.photoPreview = null;
                        this.photoFileName = '';
                        if (this.$refs.photoInput) {
                            this.$refs.photoInput.value = '';
                        }
                    }
                }
            }
        </script>
    </x-guest-layout>
