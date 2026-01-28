@extends('layouts.app')

@section('page-title', 'Editar Perfil')

@section('content')
    <div class="mb-8">
        <h1
            class="text-4xl font-playfair font-bold bg-gradient-to-r from-purple-600 to-indigo-800 bg-clip-text text-transparent mb-3">
            💼 Editar Perfil Profesional
        </h1>
        <p class="text-gray-600 text-lg">Destaca tu experiencia y estilo de vida ejecutivo</p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" x-data="profileForm()">
        @csrf
        @method('PUT')

        {{-- Información Profesional --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-xl shadow-lg">
                    💼
                </div>
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Perfil Profesional</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Ocupación --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Ocupación *</label>
                    <input type="text" name="occupation"
                        value="{{ old('occupation', $user->profileDetail->occupation) }}"
                        placeholder="Ej: CEO, Empresario, Inversionista"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-purple-50 to-purple-100/50 border-2 border-purple-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                    @error('occupation')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Industria --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Industria</label>
                    <select name="industry"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-indigo-50 to-indigo-100/50 border-2 border-indigo-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($industries as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('industry', $user->profileDetail->industry) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tamaño de empresa --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Tamaño de
                        Empresa</label>
                    <select name="company_size"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-blue-50 to-blue-100/50 border-2 border-blue-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($companySizes as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('company_size', $user->profileDetail->company_size) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Educación --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Educación</label>
                    <select name="education"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-cyan-50 to-cyan-100/50 border-2 border-cyan-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-cyan-200 focus:border-cyan-400 
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

            </div>
        </div>

        {{-- Estilo de Vida y Finanzas --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xl shadow-lg">
                    💰
                </div>
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Estilo de Vida</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Ingresos --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Rango de
                        Ingresos</label>
                    <select name="income_range"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-green-50 to-green-100/50 border-2 border-green-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($incomeRanges as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('income_range', $user->profileDetail->income_range) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Frecuencia de viajes --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Frecuencia de
                        Viajes</label>
                    <select name="travel_frequency"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-sky-50 to-sky-100/50 border-2 border-sky-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-sky-200 focus:border-sky-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach ($travelFrequencies as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('travel_frequency', $user->profileDetail->travel_frequency) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Ciudad --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="Tu ciudad"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-teal-50 to-teal-100/50 border-2 border-teal-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                </div>

                {{-- Disponibilidad --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Disponibilidad</label>
                    <select name="availability"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-violet-50 to-violet-100/50 border-2 border-violet-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-violet-200 focus:border-violet-400 
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
        </div>

        {{-- Información Personal --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xl shadow-lg">
                    👤
                </div>
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Información Personal</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        min="100" max="250" placeholder="Ej: 180"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-indigo-50 to-indigo-100/50 border-2 border-indigo-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                </div>

                {{-- Tipo de cuerpo --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Tipo de cuerpo</label>
                    <select name="body_type"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-purple-50 to-purple-100/50 border-2 border-purple-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 
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

                {{-- Estado civil --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Estado civil</label>
                    <select name="relationship_status"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-rose-50 to-rose-100/50 border-2 border-rose-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-rose-200 focus:border-rose-400 
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
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-pink-50 to-pink-100/50 border-2 border-pink-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-400 
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

            </div>

            {{-- Bio --}}
            <div class="mt-8">
                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    Biografía
                    <span class="text-xs font-normal text-gray-500 normal-case">(Presenta tu historia)</span>
                </label>
                <textarea name="bio" rows="5" maxlength="1000"
                    class="w-full px-5 py-4 bg-gradient-to-br from-violet-50 to-violet-100/50 border-2 border-violet-200 rounded-2xl 
                                 focus:outline-none focus:ring-4 focus:ring-violet-200 focus:border-violet-400 
                                 transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                    placeholder="Cuéntale al mundo sobre tu trayectoria, logros y personalidad..." x-model="bio">{{ old('bio', $user->bio) }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-gray-500 text-sm font-medium" x-text="`${bio.length}/1000 caracteres`"></p>
                    <div class="flex gap-1">
                        <div :class="bio.length > 0 ? 'bg-violet-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 250 ? 'bg-violet-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 500 ? 'bg-violet-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 750 ? 'bg-violet-500' : 'bg-gray-300'"
                            class="w-2 h-2 rounded-full transition-colors"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Qué puedo ofrecer --}}
        <div
            class="bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-3xl shadow-lg p-8 mb-6 border-2 border-amber-200/50">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-white text-xl shadow-lg">
                    🎁
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Qué Puedo Ofrecer</h2>
                    <p class="text-sm text-gray-600">Tu propuesta de valor</p>
                </div>
            </div>

            <textarea name="what_i_offer" rows="5" maxlength="1000"
                class="w-full px-5 py-4 bg-white border-2 border-amber-300 rounded-2xl 
                             focus:outline-none focus:ring-4 focus:ring-amber-200 focus:border-amber-500 
                             transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                placeholder="Experiencias, mentoría, apoyo financiero, viajes, networking..." x-model="whatIOffer">{{ old('what_i_offer', $user->profileDetail->what_i_offer) }}</textarea>
            <p class="text-gray-600 text-sm mt-2 font-medium" x-text="`${whatIOffer.length}/1000 caracteres`"></p>
        </div>

        {{-- Áreas de Mentoría --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white text-xl shadow-lg">
                    🏆
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Áreas de Mentoría</h2>
                    <p class="text-sm text-gray-500">En qué puedes asesorar</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach ($mentorshipAreasOptions as $key => $label)
                    <label
                        class="relative flex items-center gap-3 px-4 py-3 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:scale-105"
                        :class="mentorshipAreas.includes('{{ $key }}') ?
                            'bg-gradient-to-r from-indigo-100 to-indigo-200 border-indigo-400 shadow-lg' :
                            'bg-gray-50 border-gray-200 hover:border-gray-300'">
                        <input type="checkbox" name="mentorship_areas[]" value="{{ $key }}"
                            {{ in_array($key, old('mentorship_areas', $user->profileDetail->mentorship_areas ?? [])) ? 'checked' : '' }}
                            x-model="mentorshipAreas"
                            class="w-5 h-5 rounded-lg border-2 text-indigo-600 focus:ring-4 focus:ring-indigo-200">
                        <span class="text-sm font-bold text-gray-900">{{ $label }}</span>
                        <svg x-show="mentorshipAreas.includes('{{ $key }}')"
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

        {{-- Intereses --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-xl shadow-lg">
                    ❤️
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Mis Intereses</h2>
                    <p class="text-sm text-gray-500">Hobbies y pasiones</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach ($interestsOptions as $key => $label)
                    <label
                        class="relative flex items-center gap-3 px-4 py-3 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:scale-105"
                        :class="interests.includes('{{ $key }}') ?
                            'bg-gradient-to-r from-purple-100 to-purple-200 border-purple-400 shadow-lg' :
                            'bg-gray-50 border-gray-200 hover:border-gray-300'">
                        <input type="checkbox" name="interests[]" value="{{ $key }}"
                            {{ in_array($key, old('interests', $user->profileDetail->interests ?? [])) ? 'checked' : '' }}
                            x-model="interests"
                            class="w-5 h-5 rounded-lg border-2 text-purple-600 focus:ring-4 focus:ring-purple-200">
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

        {{-- Qué busco --}}
        <div
            class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-3xl shadow-lg p-8 mb-6 border-2 border-purple-200/50">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-800 flex items-center justify-center text-white text-xl shadow-lg">
                    💫
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Qué Busco</h2>
                    <p class="text-sm text-gray-600">Tu relación ideal</p>
                </div>
            </div>

            <textarea name="looking_for" rows="4" maxlength="500"
                class="w-full px-5 py-4 bg-white border-2 border-purple-300 rounded-2xl 
                             focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-500 
                             transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                placeholder="Describe el tipo de Sugar Baby que buscas, cualidades, expectativas..." x-model="lookingFor">{{ old('looking_for', $user->profileDetail->looking_for ?? '') }}</textarea>
            <p class="text-gray-600 text-sm mt-2 font-medium" x-text="`${lookingFor.length}/500 caracteres`"></p>
        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-between gap-4 mt-8">
            <a href="{{ route('profile.show') }}"
                class="px-8 py-4 bg-gray-100 hover:bg-gray-200 border-2 border-gray-300 rounded-2xl text-gray-700 font-bold transition-all duration-300 hover:scale-105 shadow-lg">
                ← Cancelar
            </a>
            <button type="submit"
                class="px-10 py-4 bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 
                           rounded-2xl text-white font-bold transition-all duration-300 shadow-2xl hover:scale-105 border-2 border-purple-400/30">
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
                    bio: '{{ old('bio', $user->bio ?? '') }}',
                    whatIOffer: '{{ old('what_i_offer', $user->profileDetail->what_i_offer ?? '') }}',
                    lookingFor: '{{ old('looking_for', $user->profileDetail->looking_for ?? '') }}',
                    interests: @json(old('interests', $user->profileDetail->interests ?? [])),
                    mentorshipAreas: @json(old('mentorship_areas', $user->profileDetail->mentorship_areas ?? []))
                }
            }
        </script>
    @endpush
@endsection
