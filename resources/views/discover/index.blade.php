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
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tighter drop-shadow-md">
                            @if (auth()->user()->user_type === 'sugar_daddy')
                                💎 Sugar Babies
                            @else
                                👔 Sugar Daddies
                            @endif
                        </h1>
                        <p class="text-white/70 text-sm font-bold mt-1">{{ $users->total() }} perfiles disponibles</p>
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
                                        <span class="px-3 py-1.5 bg-emerald-500/90 backdrop-blur-md text-white text-[10px] font-black rounded-full uppercase tracking-tighter shadow-lg flex items-center gap-1.5">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            Verificado
                                        </span>
                                    @endif
                                    
                                    @if ($hasLiked)
                                        <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center shadow-xl animate-pulse border-2 border-white/20">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
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
                                                <span class="px-3 py-1 bg-white/10 backdrop-blur-md text-white text-[10px] font-black rounded-full border border-white/5 uppercase tracking-tighter">
                                                    {{ $interestsOptions[$interest] ?? $interest }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Botones de Acción Premium --}}
                            <div class="p-6 grid grid-cols-2 gap-3 bg-white/5 backdrop-blur-md">
                                <a href="{{ url('/profile/' . $profile->id) }}"
                                   class="px-4 py-4 bg-white/10 hover:bg-white/20 text-white font-black rounded-2xl text-center text-xs uppercase tracking-widest transition-all active:scale-95 border border-white/10">
                                    Perfil
                                </a>

                                @if ($hasLiked)
                                    <form method="POST" action="{{ route('discover.unlike', $profile) }}" class="flex">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full py-4 bg-gray-800/50 hover:bg-red-500/20 text-red-400 font-black rounded-2xl text-xs uppercase tracking-widest transition-all border border-red-500/30">
                                            💔 Quitar
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

