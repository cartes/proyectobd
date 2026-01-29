@extends('layouts.mobile-app')

@section('page-title', 'Explorar')

@section('content')
    {{-- CONTENEDOR PRINCIPAL CON DEGRADADO DINÁMICO --}}
    <div class="min-h-screen" style="background: var(--theme-gradient-deep);">

        {{-- ✨ NOTIFICACIONES --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="fixed top-6 right-6 z-[100] glass-card rounded-2xl p-5 max-w-md border-l-4 border-emerald-500 animate-bounce">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="font-bold text-gray-900 theme-sd:text-white">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div x-data="discoveryFilters()">
            {{-- Header Sticky --}}
            <div class="sticky top-0 z-40 px-6 py-6 backdrop-blur-xl border-b border-white/10 shadow-2xl" 
                 style="background: rgba(var(--primary-rgb, 79, 70, 229), 0.15);">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center bg-white/10 backdrop-blur-md shadow-inner border border-white/20">
                            @if (auth()->user()->user_type === 'sugar_daddy')
                                {{-- Icono para Sugar Babies (Destellos/Diamante) --}}
                                <svg class="w-8 h-8 text-amber-300 drop-shadow-[0_0_8px_rgba(252,211,77,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                                </svg>
                            @else
                                {{-- Icono para Sugar Daddies (Corona/Ejecutivo) --}}
                                <svg class="w-8 h-8 text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14l-5-4.87 6.91-1.01L12 2z" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h1 class="text-3xl md:text-5xl font-black text-white uppercase tracking-tighter drop-shadow-xl leading-none">
                                @if (auth()->user()->user_type === 'sugar_daddy')
                                    Sugar Babies
                                @else
                                    Sugar Daddies
                                @endif
                            </h1>
                            <p class="text-white/80 text-sm font-black mt-1 uppercase tracking-widest">{{ $users->total() }} disponibles</p>
                        </div>
                    </div>

                    {{-- Botón Filtros --}}
                    <button @click="showFilters = !showFilters"
                        class="p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all backdrop-blur-md shadow-lg border border-white/20 group">
                        <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </button>
                </div>

                {{-- Panel de Filtros --}}
                <div x-show="showFilters" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4" class="max-w-7xl mx-auto pt-6">
                    <form method="GET" action="{{ route('discover.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-black/20 p-6 rounded-[2rem] border border-white/10 backdrop-blur-md">
                        <input type="number" name="age_min" x-model="filters.age_min" placeholder="Edad mín"
                            class="px-5 py-3 bg-white/10 border border-white/10 rounded-2xl text-white placeholder-white/40 focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30 font-bold">
                        <input type="number" name="age_max" x-model="filters.age_max" placeholder="Edad máx"
                            class="px-5 py-3 bg-white/10 border border-white/10 rounded-2xl text-white placeholder-white/40 focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30 font-bold">

                        <select name="city" x-model="filters.city"
                            class="px-5 py-3 bg-white/10 border border-white/10 rounded-2xl text-white focus:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30 font-bold appearance-none">
                            <option value="" class="bg-gray-900">Todas las ciudades</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" class="bg-gray-900">{{ $city }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="theme-btn py-3 px-6">🔍 Filtrar</button>
                    </form>
                </div>
            </div>

            {{-- Grid de Cards --}}
            @if ($users->count() > 0)
                <div class="p-6 md:p-10 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                    @foreach ($users as $profile)
                        @php $hasLiked = auth()->user()->hasLiked($profile); @endphp

                        <div class="glass-card rounded-[2.5rem] overflow-hidden group hover:scale-[1.02] transition-all duration-500 relative flex flex-col border border-white/10">
                            
                            {{-- Foto Principal con Efecto Hover --}}
                            <div class="relative aspect-[3/4] overflow-hidden">
                                @if ($profile->primaryPhoto)
                                    <img src="{{ $profile->primaryPhoto->url }}" alt="{{ $profile->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full flex items-center justify-center" style="background: var(--theme-gradient);">
                                        <span class="text-8xl font-black text-white/50 drop-shadow-2xl">{{ strtoupper(substr($profile->name, 0, 1)) }}</span>
                                    </div>
                                @endif

                                {{-- Badges --}}
                                <div class="absolute top-5 left-5 right-5 flex justify-between items-start">
                                    @if ($profile->is_verified)
                                        <span class="px-3 py-1.5 bg-emerald-500/90 backdrop-blur-md text-white text-[10px] font-black rounded-full uppercase tracking-tighter shadow-lg flex items-center gap-1.5 border border-white/20">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A3.33 3.33 0 0018.333 2.667h-2.14a3.33 3.33 0 01-2.14-1.18l-1.04-1.04a1.66 1.66 0 00-2.347 0L9.61 1.487a3.33 3.33 0 01-2.14 1.18H5.333a3.33 3.33 0 00-3.333 3.333v2.14a3.33 3.33 0 01-1.18 2.14l-1.04 1.04a1.66 1.66 0 000 2.347l1.04 1.04a3.33 3.33 0 011.18 2.14v2.14A3.33 3.33 0 005.333 21.333h2.14a3.33 3.33 0 012.14 1.18l1.04 1.04a1.66 1.66 0 002.347 0l1.04-1.04a3.33 3.33 0 012.14-1.18h2.14a3.33 3.33 0 003.333-3.333v-2.14a3.33 3.33 0 011.18-2.14l1.04-1.04a1.66 1.66 0 000-2.347l-1.04-1.04a3.33 3.33 0 01-1.18-2.14v-2.14z" />
                                            </svg>
                                            Verificado
                                        </span>
                                    @endif
                                    
                                    @if ($hasLiked)
                                        <div class="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center shadow-xl animate-pulse border-2 border-white/30">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Info Overlay --}}
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black via-black/40 to-transparent pt-32 pb-8 px-8">
                                    <h2 class="text-3xl font-black text-white mb-1 tracking-tight">
                                        {{ $profile->name }}, <span class="text-white/80 font-bold">{{ $profile->age }}</span>
                                    </h2>
                                    <p class="text-white/70 flex items-center gap-2 mb-4 font-bold text-sm">
                                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                        {{ $profile->city ?? 'Ubicación no disponible' }}
                                    </p>
                                    
                                    @if ($profile->profileDetail && $profile->profileDetail->interests)
                                        <div class="flex flex-wrap gap-2">
                                            @foreach (array_slice($profile->profileDetail->interests, 0, 2) as $interest)
                                                <span class="px-3 py-1 bg-gradient-to-r from-white/20 to-white/10 backdrop-blur-md text-white text-[10px] font-black rounded-full border border-white/20 uppercase tracking-tighter shadow-sm">
                                                    {{ $interestsOptions[$interest] ?? $interest }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Botones de Acción Premium --}}
                            <div class="p-6 grid grid-cols-2 gap-3 bg-black/10 backdrop-blur-xl border-t border-white/5">
                                <a href="{{ url('/profile/' . $profile->id) }}"
                                   class="px-4 py-4 bg-white/10 hover:bg-white/20 text-white font-black rounded-2xl text-center text-xs uppercase tracking-widest transition-all active:scale-95 border border-white/10 shadow-sm">
                                    Perfil
                                </a>

                                @if ($hasLiked)
                                    <form method="POST" action="{{ route('discover.unlike', $profile) }}" class="flex">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest transition-all shadow-lg shadow-red-500/25 flex items-center justify-center gap-2 group border border-white/10">
                                            <span class="group-hover:rotate-12 transition-transform">💔</span> 
                                            Quitar
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('discover.like', $profile) }}" class="flex">
                                        @csrf
                                        <button type="submit" class="w-full py-4 text-white font-black rounded-2xl text-xs uppercase tracking-widest transition-all transform hover:scale-105 active:scale-95 shadow-lg border border-white/20"
                                                style="background: var(--theme-gradient);">
                                            ❤️ Like
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if ($users->hasMorePages())
                    <div class="pb-20 flex justify-center">
                        <a href="{{ $users->nextPageUrl() }}" class="theme-btn px-12 py-5">
                            Cargar más perfiles
                        </a>
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="min-h-[60vh] flex items-center justify-center p-8">
                    <div class="glass-card rounded-[3rem] p-16 max-w-lg text-center border border-white/10">
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <h3 class="text-3xl font-black text-white uppercase tracking-tighter mb-4">Sin resultados</h3>
                        <p class="text-white/60 font-bold mb-10 leading-relaxed">No encontramos perfiles que coincidan con tu búsqueda actual. Prueba ajustando los filtros.</p>
                        <a href="{{ route('discover.index') }}" class="theme-btn px-10">Ver todos</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function discoveryFilters() {
            return {
                showFilters: false,
                filters: {
                    city: '{{ request('city', '') }}',
                    age_min: '{{ request('age_min', '') }}',
                    age_max: '{{ request('age_max', '') }}',
                    interests: '{{ request('interests', '') }}'
                }
            }
        }
    </script>
@endpush

