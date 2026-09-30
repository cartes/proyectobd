{{-- Perfiles públicos de Sugar Babies del país. Si no hay perfiles, CTA en lugar de un contador en 0. --}}
@if ($users && $users->count() > 0)
    <section aria-labelledby="perfiles-heading" class="px-6 md:px-10 pb-10 max-w-7xl mx-auto">
        <h2 id="perfiles-heading" class="text-2xl md:text-3xl font-black text-white tracking-tight mb-8">
            Perfiles de Sugar Babies en {{ $country->name }}
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                    @foreach ($users as $profile)
                        <div
                            class="glass-card rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all duration-500 relative flex flex-col border border-white/10 shadow-2xl">

                            {{-- Foto Principal --}}
                            <div class="relative aspect-[3/4] overflow-hidden">
                                @if ($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}"
                                        width="480" height="640" decoding="async"
                                        loading="{{ $loop->index < 4 ? 'eager' : 'lazy' }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-500 to-rose-600">
                                        <span
                                            class="text-8xl font-black text-white/50 drop-shadow-2xl">{{ strtoupper(substr($profile->name, 0, 1)) }}</span>
                                    </div>
                                @endif

                                {{-- Badges --}}
                                <div class="absolute top-5 left-5 right-5 flex justify-between items-start">
                                    @if ($profile->is_verified)
                                        <span
                                            class="px-3 py-1.5 bg-emerald-500/90 backdrop-blur-md text-white text-[10px] font-black rounded-full uppercase tracking-tighter shadow-lg flex items-center gap-1.5 border border-white/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M9 12l2 2 4-4m5.618-4.016A3.33 3.33 0 0018.333 2.667h-2.14a3.33 3.33 0 01-2.14-1.18l-1.04-1.04a1.66 1.66 0 00-2.347 0L9.61 1.487a3.33 3.33 0 01-2.14 1.18H5.333a3.33 3.33 0 00-3.333 3.333v2.14a3.33 3.33 0 01-1.18 2.14l-1.04 1.04a1.66 1.66 0 000 2.347l1.04 1.04a3.33 3.33 0 011.18 2.14v2.14A3.33 3.33 0 005.333 21.333h2.14a3.33 3.33 0 012.14 1.18l1.04 1.04a1.66 1.66 0 002.347 0l1.04-1.04a3.33 3.33 0 012.14-1.18h2.14a3.33 3.33 0 003.333-3.333v-2.14a3.33 3.33 0 011.18-2.14l1.04-1.04a1.66 1.66 0 000-2.347l-1.04-1.04a3.33 3.33 0 01-1.18-2.14v-2.14z" />
                                            </svg>
                                            Verificada
                                        </span>
                                    @endif

                                    <div
                                        class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-full flex items-center justify-center border border-white/20">
                                        <img src="https://flagcdn.com/w40/{{ strtolower($country->iso_code) }}.png"
                                            width="24" height="24" loading="lazy" decoding="async"
                                            alt="{{ $country->name }}" class="w-6 h-6 rounded-full object-cover">
                                    </div>
                                </div>

                                {{-- Info Overlay --}}
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/40 to-transparent pt-32 pb-8 px-8">
                                    <h2 class="text-3xl font-black text-white mb-1 tracking-tight">
                                        {{ $profile->name }}, <span
                                            class="text-white/80 font-bold">{{ $profile->age }}</span>
                                    </h2>
                                    <p class="text-white/70 flex items-center gap-2 mb-4 font-bold text-sm">
                                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $profile->city ?? $country->name }}
                                    </p>

                                    @if ($profile->profileDetail && $profile->profileDetail->interests)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach (array_slice($profile->profileDetail->interests, 0, 2) as $interest)
                                                <span
                                                    class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-black rounded-full border border-white/10 uppercase tracking-tighter shadow-sm">
                                                    {{ $interestsOptions[$interest] ?? $interest }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Botón CTA --}}
                            <div class="p-6 bg-black/10 backdrop-blur-xl border-t border-white/5">
                                <a href="{{ url('/profile/' . $profile->id) }}"
                                    class="block w-full py-4 text-white font-black rounded-2xl text-xs uppercase tracking-widest transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg border border-white/20 text-center"
                                    style="background: var(--theme-gradient);">
                                    Ver Perfil Completo
                                </a>
                            </div>
                        </div>
                    @endforeach
        </div>

        @if ($users->hasMorePages())
            <div class="pt-12 flex justify-center">
                <a href="{{ $users->nextPageUrl() }}" class="theme-btn px-12 py-5">
                    Ver más Sugar Babies
                </a>
            </div>
        @endif
    </section>
@else
    @include('archive._cta', ['type' => $type, 'country' => $country])
@endif
