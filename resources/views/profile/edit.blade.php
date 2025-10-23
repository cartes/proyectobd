@extends('layouts.app')

@section('page-title', 'Editar Perfil')

@section('content')
    <div class="mb-8">
        <h1 class="text-4xl font-playfair font-bold bg-gradient-to-r {{ Auth::user()->user_type === 'sugar_daddy' ? 'from-purple-600 to-purple-800' : 'from-pink-600 to-pink-800' }} bg-clip-text text-transparent mb-3">
            ✏️ Editar Perfil
        </h1>
        <p class="text-gray-600 text-lg">Completa tu información para destacar en Big-dad</p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" x-data="profileForm()">
        @csrf
        @method('PUT')

        {{-- Información Básica --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xl shadow-lg">
                    📋
                </div>
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Información Básica</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Ciudad --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Ciudad</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                           class="w-full px-5 py-3.5 bg-gradient-to-r from-blue-50 to-blue-100/50 border-2 border-blue-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-blue-200 focus:border-blue-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500"
                           placeholder="Tu ciudad">
                    @error('city')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Altura --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Altura (cm)</label>
                    <input type="number" name="height" value="{{ old('height', $user->profileDetail->height) }}"
                           min="100" max="250"
                           class="w-full px-5 py-3.5 bg-gradient-to-r from-indigo-50 to-indigo-100/50 border-2 border-indigo-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500"
                           placeholder="Ej: 175">
                    @error('height')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de cuerpo --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Tipo de cuerpo</label>
                    <select name="body_type"
                            class="w-full px-5 py-3.5 bg-gradient-to-r from-purple-50 to-purple-100/50 border-2 border-purple-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-purple-200 focus:border-purple-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach($bodyTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('body_type', $user->profileDetail->body_type) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('body_type')
                        <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado civil --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Estado civil</label>
                    <select name="relationship_status"
                            class="w-full px-5 py-3.5 bg-gradient-to-r from-pink-50 to-pink-100/50 border-2 border-pink-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-pink-200 focus:border-pink-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach($relationshipStatuses as $key => $label)
                            <option value="{{ $key }}" {{ old('relationship_status', $user->profileDetail->relationship_status) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Hijos --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Hijos</label>
                    <select name="children"
                            class="w-full px-5 py-3.5 bg-gradient-to-r from-rose-50 to-rose-100/50 border-2 border-rose-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-rose-200 focus:border-rose-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach($childrenOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('children', $user->profileDetail->children) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Educación --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Nivel educativo</label>
                    <select name="education"
                            class="w-full px-5 py-3.5 bg-gradient-to-r from-green-50 to-green-100/50 border-2 border-green-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach($educationLevels as $key => $label)
                            <option value="{{ $key }}" {{ old('education', $user->profileDetail->education) === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Ocupación --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Ocupación</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $user->profileDetail->occupation) }}"
                           placeholder="Ej: Empresario, Estudiante, etc."
                           class="w-full px-5 py-3.5 bg-gradient-to-r from-emerald-50 to-emerald-100/50 border-2 border-emerald-200 rounded-xl 
                                  focus:outline-none focus:ring-4 focus:ring-emerald-200 focus:border-emerald-400 
                                  transition-all duration-200 font-medium text-gray-900 placeholder-gray-500">
                </div>

                {{-- Ingresos (solo para Sugar Daddy) --}}
                @if($user->user_type === 'sugar_daddy')
                    <div>
                        <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Rango de ingresos</label>
                        <select name="income_range"
                                class="w-full px-5 py-3.5 bg-gradient-to-r from-amber-50 to-amber-100/50 border-2 border-amber-200 rounded-xl 
                                       focus:outline-none focus:ring-4 focus:ring-amber-200 focus:border-amber-400 
                                       transition-all duration-200 font-medium text-gray-900">
                            <option value="">Seleccionar...</option>
                            @foreach($incomeRanges as $key => $label)
                                <option value="{{ $key }}" {{ old('income_range', $user->profileDetail->income_range) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Disponibilidad --}}
                <div>
                    <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">Disponibilidad</label>
                    <select name="availability"
                            class="w-full px-5 py-3.5 bg-gradient-to-r from-teal-50 to-teal-100/50 border-2 border-teal-200 rounded-xl 
                                   focus:outline-none focus:ring-4 focus:ring-teal-200 focus:border-teal-400 
                                   transition-all duration-200 font-medium text-gray-900">
                        <option value="">Seleccionar...</option>
                        @foreach($availabilityOptions as $key => $label)
                            <option value="{{ $key }}" {{ old('availability', $user->profileDetail->availability) === $key ? 'selected' : '' }}>
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
                    <span class="text-xs font-normal text-gray-500 normal-case">(Cuéntanos sobre ti)</span>
                </label>
                <textarea name="bio" rows="5" maxlength="1000"
                          class="w-full px-5 py-4 bg-gradient-to-br from-violet-50 to-violet-100/50 border-2 border-violet-200 rounded-2xl 
                                 focus:outline-none focus:ring-4 focus:ring-violet-200 focus:border-violet-400 
                                 transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                          placeholder="Escribe algo sobre ti, tus pasiones, estilo de vida..."
                          x-model="bio">{{ old('bio', $user->bio) }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-gray-500 text-sm font-medium" x-text="`${bio.length}/1000 caracteres`"></p>
                    <div class="flex gap-1">
                        <div :class="bio.length > 0 ? 'bg-violet-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 250 ? 'bg-violet-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 500 ? 'bg-violet-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full transition-colors"></div>
                        <div :class="bio.length > 750 ? 'bg-violet-500' : 'bg-gray-300'" class="w-2 h-2 rounded-full transition-colors"></div>
                    </div>
                </div>
                @error('bio')
                    <p class="text-red-500 text-sm mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Qué busco --}}
            <div class="mt-6">
                <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wide">
                    ¿Qué buscas en Big-dad?
                </label>
                <textarea name="looking_for" rows="4" maxlength="500"
                          class="w-full px-5 py-4 bg-gradient-to-br from-fuchsia-50 to-fuchsia-100/50 border-2 border-fuchsia-200 rounded-2xl 
                                 focus:outline-none focus:ring-4 focus:ring-fuchsia-200 focus:border-fuchsia-400 
                                 transition-all duration-200 font-medium text-gray-900 placeholder-gray-500 resize-none"
                          placeholder="Describe el tipo de relación que buscas..."
                          x-model="lookingFor">{{ old('looking_for', $user->profileDetail->looking_for ?? '') }}</textarea>
                <p class="text-gray-500 text-sm mt-2 font-medium" x-text="`${lookingFor.length}/500 caracteres`"></p>
            </div>

        </div>

        {{-- Intereses --}}
        <div class="bg-white rounded-3xl shadow-lg p-8 mb-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white text-xl shadow-lg">
                    ❤️
                </div>
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900">Intereses</h2>
                    <p class="text-gray-600 text-sm">Selecciona tus intereses (máximo 8)</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($interestsOptions as $key => $label)
                    <label class="relative flex items-center gap-3 px-4 py-3 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:scale-105"
                           :class="interests.includes('{{ $key }}') ? 'bg-gradient-to-r {{ $user->user_type === 'sugar_daddy' ? 'from-purple-100 to-purple-200 border-purple-400' : 'from-pink-100 to-pink-200 border-pink-400' }} shadow-lg' : 'bg-gray-50 border-gray-200 hover:border-gray-300'">
                        <input type="checkbox" name="interests[]" value="{{ $key }}"
                               {{ in_array($key, old('interests', $user->profileDetail->interests ?? [])) ? 'checked' : '' }}
                               x-model="interests"
                               class="w-5 h-5 rounded-lg border-2 text-{{ $user->user_type === 'sugar_daddy' ? 'purple' : 'pink' }}-600 
                                      focus:ring-4 focus:ring-{{ $user->user_type === 'sugar_daddy' ? 'purple' : 'pink' }}-200">
                        <span class="text-sm font-bold text-gray-900">{{ $label }}</span>
                        <svg x-show="interests.includes('{{ $key }}')" class="absolute -top-2 -right-2 w-6 h-6 text-green-500 bg-white rounded-full shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('profile.show') }}"
               class="px-8 py-4 bg-gray-100 hover:bg-gray-200 border-2 border-gray-300 rounded-2xl text-gray-700 font-bold transition-all duration-300 hover:scale-105 shadow-lg">
                ← Cancelar
            </a>
            <button type="submit"
                    class="px-10 py-4 bg-gradient-to-r 
                           {{ $user->user_type === 'sugar_daddy' ? 'from-purple-500 to-purple-700 hover:from-purple-600 hover:to-purple-800' : 'from-pink-500 to-pink-700 hover:from-pink-600 hover:to-pink-800' }} 
                           rounded-2xl text-white font-bold transition-all duration-300 shadow-2xl hover:scale-105 border-2 border-white/20">
                💾 Guardar Cambios
            </button>
        </div>

    </form>

    @push('scripts')
    <script>
        function profileForm() {
            return {
                bio: '{{ old('bio', $user->bio ?? '') }}',
                lookingFor: '{{ old('looking_for', $user->profileDetail->looking_for ?? '') }}',
                interests: @json(old('interests', $user->profileDetail->interests ?? []))
            }
        }
    </script>
    @endpush
@endsection
