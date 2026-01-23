@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('content')
    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
             class="mb-8 glass-card rounded-3xl p-6 border-l-4 border-emerald-500 animate-in fade-in slide-in-from-top duration-500">
            <p class="flex items-center gap-3 font-bold text-gray-900 theme-sd:text-white">
                <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- Header del perfil --}}
    <div class="rounded-[2.5rem] p-10 mb-10 shadow-2xl text-white relative overflow-hidden group" style="background: var(--theme-gradient);">
        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-20 transition-opacity duration-700"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl transition-transform group-hover:scale-110 duration-700"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between flex-wrap gap-8 mb-8">
                <div class="flex items-center gap-8">
                    {{-- Avatar Premium --}}
                    <div class="relative">
                        <div class="w-32 h-32 rounded-[2rem] bg-white/20 backdrop-blur-xl border-4 border-white/30 flex items-center justify-center text-white text-5xl font-black shadow-2xl transform transition-transform group-hover:rotate-3">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 rounded-2xl border-4 border-white flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Info básica --}}
                    <div>
                        <h1 class="text-4xl md:text-6xl font-black text-white mb-3 tracking-tighter uppercase">
                            {{ $user->name }}
                        </h1>
                        <div class="flex items-center gap-4 text-white/80 text-sm font-bold flex-wrap">
                            @if($user->age)
                                <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl backdrop-blur-md">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $user->age }} años
                                </span>
                            @endif
                            @if($user->city)
                                <span class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl backdrop-blur-md">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $user->city }}
                                </span>
                            @endif
                            <span class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest bg-white text-indigo-600 shadow-xl" 
                                  style="color: var(--theme-primary);">
                                {{ $user->user_type === 'sugar_daddy' ? '👔 Sugar Daddy' : '💎 Sugar Baby' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Botón editar --}}
                <a href="{{ route('profile.edit') }}" 
                   class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl text-white font-black uppercase tracking-widest text-xs transition-all duration-300 hover:scale-105 active:scale-95 shadow-2xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Perfil
                    </span>
                </a>
            </div>

            {{-- Bio --}}
            @if($user->bio)
                <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] p-8 shadow-2xl">
                    <p class="text-white/95 text-xl font-medium leading-relaxed italic">
                        "{{ $user->bio }}"
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- Detalles del perfil --}}
    @if($user->profileDetail)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Información Personal --}}
            <div class="glass-card rounded-[2.5rem] p-10 border border-white/10 hover:shadow-2xl transition-all duration-500">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 flex items-center justify-center text-2xl shadow-inner border border-indigo-500/20">
                        👤
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 theme-sd:text-white uppercase tracking-tighter">Información</h2>
                </div>
                
                <div class="space-y-4">
                    @php $fields = [
                        'height' => ['label' => 'Altura', 'suffix' => ' cm', 'icon' => 'indigo'],
                        'body_type' => ['label' => 'Tipo de cuerpo', 'options' => \App\Models\ProfileDetail::bodyTypes(), 'icon' => 'purple'],
                        'relationship_status' => ['label' => 'Estado civil', 'options' => \App\Models\ProfileDetail::relationshipStatuses(), 'icon' => 'pink'],
                        'children' => ['label' => 'Hijos', 'options' => \App\Models\ProfileDetail::childrenOptions(), 'icon' => 'rose']
                    ]; @endphp

                    @foreach($fields as $field => $config)
                        @if($user->profileDetail->$field)
                            <div class="flex justify-between items-center py-4 px-6 bg-white/5 rounded-2xl border border-white/10 group/row hover:bg-white/10 transition-colors">
                                <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">{{ $config['label'] }}</span>
                                <span class="text-gray-900 theme-sd:text-white font-black text-lg">
                                    @if(isset($config['options']))
                                        {{ $config['options'][$user->profileDetail->$field] ?? $user->profileDetail->$field }}
                                    @else
                                        {{ $user->profileDetail->$field }}{{ $config['suffix'] ?? '' }}
                                    @endif
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Educación y Profesión --}}
            <div class="glass-card rounded-[2.5rem] p-10 border border-white/10 hover:shadow-2xl transition-all duration-500">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 flex items-center justify-center text-2xl shadow-inner border border-emerald-500/20">
                        🎓
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 theme-sd:text-white uppercase tracking-tighter">Carrera</h2>
                </div>
                
                <div class="space-y-4">
                    @if($user->profileDetail->education)
                        <div class="flex justify-between items-center py-4 px-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                            <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Educación</span>
                            <span class="text-gray-900 theme-sd:text-white font-black text-lg text-right">{{ \App\Models\ProfileDetail::educationLevels()[$user->profileDetail->education] ?? $user->profileDetail->education }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->occupation)
                        <div class="flex justify-between items-center py-4 px-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                            <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Ocupación</span>
                            <span class="text-gray-900 theme-sd:text-white font-black text-lg text-right">{{ $user->profileDetail->occupation }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->income_range && $user->user_type === 'sugar_daddy')
                        <div class="flex justify-between items-center py-4 px-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                            <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Ingresos</span>
                            <span class="text-emerald-500 font-black text-lg text-right">{{ \App\Models\ProfileDetail::incomeRanges()[$user->profileDetail->income_range] ?? $user->profileDetail->income_range }}</span>
                        </div>
                    @endif
                    @if($user->profileDetail->availability)
                        <div class="flex justify-between items-center py-4 px-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                            <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Disponibilidad</span>
                            <span class="text-gray-900 theme-sd:text-white font-black text-lg text-right">{{ \App\Models\ProfileDetail::availabilityOptions()[$user->profileDetail->availability] ?? $user->profileDetail->availability }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Intereses Premium --}}
            @if($user->profileDetail->interests && count($user->profileDetail->interests) > 0)
                <div class="glass-card rounded-[2.5rem] p-10 border border-white/10 hover:shadow-2xl transition-all duration-500 col-span-full lg:col-span-1">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-rose-500/20 flex items-center justify-center text-2xl shadow-inner border border-rose-500/20">
                            ❤️
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 theme-sd:text-white uppercase tracking-tighter">Intereses</h2>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        @foreach($user->profileDetail->interests as $interest)
                            <span class="px-6 py-3 bg-white/5 border border-white/10 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-700 theme-sd:text-gray-300 hover:scale-105 transition-all cursor-default shadow-sm hover:shadow-md" 
                                  style="border-bottom: 3px solid var(--theme-primary);">
                                {{ \App\Models\ProfileDetail::interestsOptions()[$interest] ?? $interest }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Qué busco (Estilo Mensaje) --}}
            @if($user->profileDetail->looking_for)
                <div class="glass-card rounded-[2.5rem] p-10 border border-white/10 hover:shadow-2xl transition-all duration-500 col-span-full lg:col-span-1">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/20 flex items-center justify-center text-2xl shadow-inner border border-amber-500/20">
                            💫
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 theme-sd:text-white uppercase tracking-tighter">Lo que busco</h2>
                    </div>
                    
                    <div class="bg-white/5 p-8 rounded-[2rem] border-l-8 border-amber-500/50">
                        <p class="text-gray-700 theme-sd:text-gray-300 leading-relaxed text-xl font-medium">
                            {{ $user->profileDetail->looking_for }}
                        </p>
                    </div>
                </div>
            @endif

        </div>
    @else
        {{-- Empty State Premium --}}
        <div class="rounded-[3rem] p-16 text-center text-white relative overflow-hidden shadow-2xl" style="background: var(--theme-gradient);">
            <div class="absolute inset-0 bg-white/10 backdrop-blur-3xl"></div>
            
            <div class="relative z-10 max-w-2xl mx-auto">
                <div class="text-8xl mb-8 animate-bounce">✨</div>
                <h3 class="text-4xl md:text-5xl font-black text-white mb-6 uppercase tracking-tighter">Tu historia comienza aquí</h3>
                <p class="text-white/80 mb-12 text-xl font-medium leading-relaxed">Completa los detalles de tu perfil para destacar y atraer las mejores conexiones en Big-dad.</p>
                <a href="{{ route('profile.edit') }}" 
                   class="inline-block px-12 py-5 bg-white text-indigo-600 font-black rounded-2xl transition-all duration-300 hover:scale-110 shadow-[0_20px_40px_rgba(0,0,0,0.3)] uppercase tracking-widest text-sm" 
                   style="color: var(--theme-primary);">
                    Completar Perfil Ahora →
                </a>
            </div>
        </div>
    @endif
@endsection

