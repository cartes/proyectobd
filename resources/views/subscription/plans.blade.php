@extends('layouts.public-static')

@section('meta_title', 'Planes Premium | Big-dad')
@section('meta_description',
    'Conoce los planes premium de Big-dad y descubre cómo potenciar tu perfil, visibilidad y conexiones exclusivas en Latinoamérica.')
@section('meta_keywords',
    'planes big-dad, premium sugar daddy, suscripción sugar dating, planes premium latam, membresía big-dad')
@section('canonical_url', route(request()->routeIs('subscription.plans') ? 'subscription.plans' : 'plans.public'))

@section('og_title', 'Planes Premium | Big-dad')
@section('og_description',
    'Explora los planes premium de Big-dad para desbloquear beneficios exclusivos, mayor visibilidad y una experiencia superior dentro de la plataforma.')
@section('og_url', url()->current())
@section('og_image', asset('images/og-image.jpg'))

@section('twitter_title', 'Planes Premium | Big-dad')
@section('twitter_description',
    'Descubre los planes premium de Big-dad y eleva tu experiencia con funciones exclusivas, más visibilidad y mejores conexiones.')
@section('twitter_image', asset('images/og-image.jpg'))

@section('content')
    {{-- ⚡ MODO LANZAMIENTO: SDK de Mercado Pago desactivado temporalmente --}}
    {{-- <script src="https://sdk.mercadopago.com/js/v2"></script> --}}
    {{-- <script>const mp = new MercadoPago('...', { locale: 'es-CL' });</script> --}}

    <div class="min-h-screen px-4 sm:px-6 lg:px-8"
        style="background: radial-gradient(circle at top right, rgba(var(--primary-rgb, 79, 70, 229), 0.1), transparent);">
        <div class="max-w-7xl mx-auto py-16">
            <!-- Título -->
            <div class="text-center mb-16">
                <h1 class="text-5xl font-black text-white mb-6 uppercase tracking-tighter">
                    @guest
                        Planes Sugar Daddy
                    @else
                        Planes Premium
                    @endguest
                </h1>
                <p class="text-xl text-gray-400 font-medium max-w-2xl mx-auto leading-relaxed">
                    @guest
                        Potencia tu perfil, obtén mayor visibilidad y conecta con Sugar Babies exclusivas hoy mismo.
                    @else
                        Desbloquea funciones exclusivas y eleva tu experiencia en Big-dad al siguiente nivel.
                    @endguest
                </p>
            </div>

            {{-- ⚡ BANNER MODO LANZAMIENTO --}}
            <div class="max-w-4xl mx-auto mb-14">
                <div class="relative rounded-[2rem] overflow-hidden px-8 py-10 text-center shadow-2xl"
                    style="background: linear-gradient(135deg, #7c3aed 0%, #db2777 50%, #f59e0b 100%);">
                    {{-- Efecto brillo animado --}}
                    <div class="absolute inset-0 animate-pulse opacity-20"
                        style="background: radial-gradient(circle at 30% 50%, white 0%, transparent 60%);"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-3">🎉</div>
                        <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight leading-tight">
                            ¡Fin de Semana de Lanzamiento!
                        </h2>
                        <p class="mt-4 text-lg md:text-xl text-white/90 font-semibold max-w-2xl mx-auto leading-relaxed">
                            Disfruta de todos los beneficios <span class="text-yellow-300">Premium</span>,
                            <span class="text-yellow-300">Super Likes</span> y
                            <span class="text-yellow-300">Boosts</span> totalmente
                            <span class="underline decoration-wavy decoration-yellow-300">GRATIS</span>
                        </p>
                        <div
                            class="mt-6 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full">
                            <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="text-white font-bold text-sm uppercase tracking-widest">Activo ahora mismo</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Plan Activo (si existe) --}}
            @if ($activeSubscription)
                <div class="max-w-3xl mx-auto mb-12">
                    <div
                        class="glass-card bg-emerald-500/10 border border-emerald-500/30 rounded-3xl p-6 flex items-center justify-between shadow-2xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-black uppercase tracking-widest text-xs">Suscripción Activa</p>
                                <p class="text-emerald-400 font-bold text-lg">{{ $activeSubscription->plan->name }}</p>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm font-bold">Expira:
                            {{ $activeSubscription->ends_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach ($plans as $plan)
                    <div
                        class="glass-card bg-slate-900/40 rounded-[2.5rem] overflow-hidden hover:shadow-[0_20px_50px_rgba(0,0,0,0.3)] transition-all duration-500 transform hover:scale-[1.02] border border-white/5 flex flex-col group">
                        <!-- Plan Header -->
                        <div class="px-8 py-10 text-center relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20 group-hover:opacity-30 transition-opacity"
                                style="background: var(--theme-gradient);"></div>
                            <h3 class="text-2xl font-black text-white uppercase tracking-tight relative z-10">
                                {{ $plan->name }}
                            </h3>
                            <div class="mt-4 relative z-10">
                                <span
                                    class="text-5xl font-black text-white tracking-tighter">${{ number_format($plan->amount, 0, ',', '.') }}</span>
                                <span class="text-gray-400 font-bold ml-1 uppercase text-xs tracking-widest">
                                    @if ($plan->frequency_type === 'months')
                                        {{ $plan->frequency == 1 ? '/ mes' : '/ ' . $plan->frequency . ' meses' }}
                                    @else
                                        {{ $plan->frequency == 1 ? '/ año' : '/ ' . $plan->frequency . ' años' }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Plan Body -->
                        <div class="p-8 flex-1 flex flex-col">
                            <p class="text-gray-400 text-center text-sm font-medium mb-8 leading-relaxed px-4">
                                {{ $plan->description }}
                            </p>

                            <!-- Features -->
                            <ul class="space-y-4 mb-10 flex-1">
                                @foreach ($plan->features as $feature)
                                    <li class="flex items-start text-gray-300 group/item">
                                        <div
                                            class="w-6 h-6 rounded-full bg-white/5 flex items-center justify-center mr-3 mt-0.5 group-hover/item:bg-white/10 transition-colors">
                                            <svg class="w-3.5 h-3.5" style="color: var(--theme-primary);"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-bold tracking-tight">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="mt-auto">
                                {{-- ⚡ MODO LANZAMIENTO: Ocultar botones de pago --}}
                                <div
                                    class="w-full py-5 px-8 rounded-2xl text-center font-black uppercase tracking-widest text-sm border-2 border-dashed border-yellow-400/50 bg-yellow-400/10 text-yellow-300">
                                    ✨ ¡Ya lo tienes gratis!
                                </div>
                                {{-- BOTONES ORIGINALES (ocultos durante lanzamiento) --}}
                                {{-- @if ($activeSubscription && $activeSubscription->plan_id === $plan->id) --}}
                                {{--     <button disabled ...>✓ Plan Actual</button> --}}
                                {{-- @else --}}
                                {{--     <button id="checkout-btn-{{ $plan->id }}" ...>Suscribirse</button> --}}
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ⚡ MODO LANZAMIENTO: JS de checkout desactivado --}}
    {{-- <script>function handleCheckout(planId, checkoutUrl) { ... }</script> --}}
@endsection
