@extends('layouts.app')

@section('page-title', 'Editar Perfil')

@section('content')
    <div class="mb-8">
        <h1
            class="text-4xl font-playfair font-bold bg-gradient-to-r from-pink-600 to-fuchsia-800 bg-clip-text text-transparent mb-3">
            ✨ Editar Mi Perfil
        </h1>
        <p class="text-gray-600 text-lg">Haz que tu perfil brille y atraiga a los mejores Sugar Daddies</p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" x-data="profileForm()">
        @csrf
        @method('PUT')

        {{-- Sobre Mí --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center text-white text-xl shadow-lg">
                    ✨
                </div>
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Sobre Mí</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Ciudad --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Tu ciudad"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-pink-50 to-pink-100/50 border-2 border-pink-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                </div>
                
                {{-- Fecha de Nacimiento --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                        Fecha de Nacimiento
                        <span class="text-xs font-normal text-gray-500 normal-case">({{ $user->age }} años)</span>
                    </label>
                    <input type="date" name="birth_date"
                        value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-amber-50 to-amber-100/50 border-2 border-amber-200 rounded-xl 
                      focus:outline-none focus:ring-4 focus:ring-amber-200 focus:border-amber-400 
                      transition-all duration-200 font-medium text-gray-900">
                </div>

                {{-- Altura --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Altura (cm)</label>
                    <input type="number" name="height" value="{{ old('height', $user->profileDetail->height) }}"
                        min="100" max="250" placeholder="Ej: 165"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-rose-50 to-rose-100/50 border-2 border-rose-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-rose-200 focus:border-rose-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                </div>

                {{-- Tipo de cuerpo --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Tipo de cuerpo</label>
                    <select name="body_type"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-fuchsia-50 to-fuchsia-100/50 border-2 border-fuchsia-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-fuchsia-200 focus:border-fuchsia-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($bodyTypes as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('body_type', $user->profileDetail->body_type) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Estilo personal --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Estilo
                        Personal</label>
                    <select name="personal_style"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-purple-50 to-purple-100/50 border-2 border-purple-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($personalStyles as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('personal_style', $user->profileDetail->personal_style) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nivel de fitness --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Nivel de
                        Fitness</label>
                    <select name="fitness_level"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-2 border-emerald-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-emerald-200 focus:border-emerald-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($fitnessLevels as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('fitness_level', $user->profileDetail->fitness_level) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado civil --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Estado civil</label>
                    <select name="relationship_status"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-cyan-50 to-cyan-100/50 border-2 border-cyan-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-cyan-200 focus:border-cyan-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($relationshipStatuses as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('relationship_status', $user->profileDetail->relationship_status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hijos --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Hijos</label>
                    <select name="children"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-sky-50 to-sky-100/50 border-2 border-sky-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-sky-200 focus:border-sky-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($childrenOptions as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('children', $user->profileDetail->children) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Disponibilidad --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Disponibilidad</label>
                    <select name="availability"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-teal-50 to-teal-100/50 border-2 border-teal-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($availabilityOptions as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('availability', $user->profileDetail->availability) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Bio --}}
            <div class="mt-8">
                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    Mi Biografía
                    <span class="text-xs font-normal text-gray-500 normal-case">(Cuéntale al mundo quién eres)</span>
                </label>
                <textarea name="bio" rows="5" maxlength="1000"
                    class="w-full px-5 py-4 bg-gradient-to-br from-violet-50 to-violet-100/50 border-2 border-violet-200 rounded-2xl 
                                 focus:outline-none focus:ring-4 focus:ring-violet-200 focus:border-violet-400 
                                 transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                    placeholder="Habla sobre tu personalidad, pasiones, qué te hace única y especial..." x-model="bio">{{ old('bio', $user->bio) }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-gray-500 text-sm font-medium" x-text="`${bio.length}/1000 caracteres`"></p>
                    <div class="flex gap-1">
                        <div :class="bio.length > 0 ? 'bg-pink-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 250 ? 'bg-pink-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 500 ? 'bg-pink-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 750 ? 'bg-pink-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                    </div>
                </div>
            </div>

            {{-- Detalles de apariencia --}}
            <div class="mt-6">
                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    Detalles de Apariencia
                    <span class="text-xs font-normal text-gray-500 normal-case">(Opcional)</span>
                </label>
                <textarea name="appearance_details" rows="3" maxlength="500"
                    class="w-full px-5 py-4 bg-gradient-to-br from-pink-50 to-pink-100/50 border-2 border-pink-200 rounded-2xl 
                                 focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-400 
                                 transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                    placeholder="Color de ojos, cabello, características únicas que te definen..." x-model="appearanceDetails">{{ old('appearance_details', $user->profileDetail->appearance_details) }}</textarea>
                <p class="text-gray-500 text-sm mt-2 font-medium" x-text="`${appearanceDetails.length}/500 caracteres`"></p>
            </div>

        </div>

        {{-- Educación y Ocupación --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xl shadow-lg">
                    📚
                </div>
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Educación y Ocupación</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Educación --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Nivel
                        educativo</label>
                    <select name="education"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-indigo-50 to-indigo-100/50 border-2 border-indigo-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($educationLevels as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('education', $user->profileDetail->education) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Ocupación --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Ocupación</label>
                    <input type="text" name="occupation"
                        value="{{ old('occupation', $user->profileDetail->occupation) }}"
                        placeholder="Ej: Estudiante, Modelo, Empresaria"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-blue-50 to-blue-100/50 border-2 border-blue-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                </div>

            </div>
        </div>

        {{-- Mis Aspiraciones --}}
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-3xl shadow-lg p-8 mb-6 border-2 border-purple-200/50">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-xl shadow-lg">
                    🌟
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Mis Aspiraciones y Metas</h2>
                    <p class="text-sm text-gray-600">Qué quieres lograr en la vida</p>
                </div>
            </div>

            <textarea name="aspirations" rows="5" maxlength="1000"
                class="w-full px-5 py-4 bg-white border-2 border-purple-300 rounded-2xl 
                             focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-500 
                             transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                placeholder="Tus sueños, metas profesionales, experiencias que quieres vivir, cómo quieres crecer..."
                x-model="aspirations">{{ old('aspirations', $user->profileDetail->aspirations) }}</textarea>
            <p class="text-gray-600 text-sm mt-2 font-medium" x-text="`${aspirations.length}/1000 caracteres`"></p>
        </div>

        {{-- Mis Intereses --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white text-xl shadow-lg">
                    💖
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Mis Intereses</h2>
                    <p class="text-sm text-gray-500">Selecciona lo que te apasiona (máximo 8)</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach ($interestsOptions as $key => $label)
                    <label
                        class="relative flex items-center gap-3 px-4 py-3 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:scale-105"
                        :class="interests.includes('{{ $key }}') ?
                            'bg-gradient-to-r from-pink-100 to-pink-200 border-pink-400 shadow-lg' :
                            'bg-gray-50 border-gray-200 hover:border-gray-300'">
                        <input type="checkbox" name="interests[]" value="{{ $key }}"
                            {{ in_array($key, old('interests', $user->profileDetail->interests ?? [])) ? 'checked' : '' }}
                            x-model="interests"
                            class="w-5 h-5 rounded-lg border-2 text-pink-600 focus:ring-4 focus:ring-pink-200">
                        <span class="text-sm font-bold text-gray-900">{{ $label }}</span>
                        <svg x-show="interests.includes('{{ $key }}')"
                            class="absolute -top-2 -right-2 w-6 h-6 text-green-500 bg-white rounded-full shadow-lg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Mi Daddy Ideal --}}
        <div
            class="bg-gradient-to-br from-pink-50 to-rose-100/50 rounded-3xl shadow-lg p-8 mb-6 border-2 border-pink-200/50">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-700 flex items-center justify-center text-white text-xl shadow-lg">
                    💕
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Mi Sugar Daddy Ideal</h2>
                    <p class="text-sm text-gray-600">Describe a tu pareja perfecta</p>
                </div>
            </div>

            <textarea name="ideal_daddy" rows="5" maxlength="500"
                class="w-full px-5 py-4 bg-white border-2 border-pink-300 rounded-2xl 
                             focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-500 
                             transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                placeholder="Cualidades, personalidad, estilo de vida, tipo de relación que buscas..." x-model="idealDaddy">{{ old('ideal_daddy', $user->profileDetail->ideal_daddy) }}</textarea>
            <p class="text-gray-600 text-sm mt-2 font-medium" x-text="`${idealDaddy.length}/500 caracteres`"></p>
        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-between gap-4 sticky bottom-4">
            <a href="{{ route('profile.show') }}"
                class="px-8 py-4 bg-gray-100 hover:bg-gray-200 border-2 border-gray-300 rounded-2xl text-gray-700 font-bold transition-all duration-300 hover:scale-105 shadow-lg">
                ← Cancelar
            </a>
            <button type="submit"
                class="px-10 py-4 bg-gradient-to-r from-pink-500 to-fuchsia-600 hover:from-pink-600 hover:to-fuchsia-700 
                           rounded-2xl text-white font-bold transition-all duration-300 shadow-2xl hover:scale-105 border-2 border-pink-300/30">
                💾 Guardar Cambios
            </button>
        </div>

    </form>

    {{-- Seguridad --}}
    @include('profile.partials.premium-password-form')

    @push('scripts')
        <script>
            function profileForm() {
                return {
                    bio: {!! json_encode(old('bio', $user->bio ?? '')) !!},
                    appearanceDetails: {!! json_encode(old('appearance_details', $user->profileDetail->appearance_details ?? '')) !!},
                    aspirations: {!! json_encode(old('aspirations', $user->profileDetail->aspirations ?? '')) !!},
                    idealDaddy: {!! json_encode(old('ideal_daddy', $user->profileDetail->ideal_daddy ?? '')) !!},
                    interests: @json(old('interests', $user->profileDetail->interests ?? []))
                }
            }
        </script>
    @endpush
@endsection
