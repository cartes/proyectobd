@extends('layouts.subscription', ['hideSidebar' => true])

@section('content')
    <!-- ✅ MERCADO PAGO SDK - AGREGAR AL HEAD -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        // ✅ INICIALIZAR MERCADO PAGO CON TU PUBLIC KEY
        const mp = new MercadoPago('{{ config('services.mercadopago.public_key') }}', {
            locale: 'es-CL' // Para Chile
        });
    </script>

    <div class="px-4 sm:px-6 lg:px-8 min-h-screen"
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

            <!-- Plan Activo (si existe) -->
            @if ($activeSubscription)
                <div class="max-w-3xl mx-auto mb-12">
                    <div
                        class="glass-card bg-emerald-500/10 border border-emerald-500/30 rounded-3xl p-6 flex items-center justify-between shadow-2xl">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-black uppercase tracking-widest text-xs">Suscripción Activa</p>
                                <p class="text-emerald-400 font-bold text-lg">{{ $activeSubscription->plan->name }}</p>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm font-bold">Expira: {{ $activeSubscription->ends_at->format('d/m/Y') }}
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
                            <h3 class="text-2xl font-black text-white uppercase tracking-tight relative z-10">{{ $plan->name }}
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
                                            <svg class="w-3.5 h-3.5" style="color: var(--theme-primary);" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span class="text-sm font-bold tracking-tight">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Botón de Suscripción -->
                            <div class="mt-auto">
                                @if ($activeSubscription && $activeSubscription->plan_id === $plan->id)
                                    <button type="button" disabled
                                        class="w-full bg-emerald-500/20 text-emerald-400 font-black py-5 px-8 rounded-2xl border border-emerald-500/30 uppercase tracking-widest text-xs">
                                        ✓ Plan Actual
                                    </button>
                                @else
                                    <button type="button" id="checkout-btn-{{ $plan->id }}"
                                        class="w-full text-white font-black py-5 px-8 rounded-2xl shadow-xl transition-all duration-300 transform hover:translate-y-[-2px] active:scale-95 uppercase tracking-widest text-xs relative overflow-hidden group/btn"
                                        style="background: var(--theme-gradient);"
                                        onclick="handleCheckout({{ $plan->id }}, '{{ route('subscription.checkout', $plan->id) }}')">
                                        <div
                                            class="absolute inset-0 bg-white/20 opacity-0 group-hover/btn:opacity-100 transition-opacity">
                                        </div>
                                        <span class="relative flex items-center justify-center gap-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Suscribirse
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ✅ FUNCIÓN JAVASCRIPT PARA MANEJAR CHECKOUT CON SDK -->
    <script>
        function handleCheckout(planId, checkoutUrl) {
            console.log('Iniciando checkout para plan:', planId);

            const btn = document.getElementById('checkout-btn-' + planId);
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Procesando...</span>';

            fetch(checkoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.is_free && data.redirect) {
                            alert(data.message);
                            window.location.href = data.redirect;
                            return;
                        }

                        if (data.init_point) {
                            window.location.href = data.init_point;
                            return;
                        }
                    }

                    // Fallback para error
                    alert('Error: ' + (data.error || 'Error desconocido'));
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar el pago');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }
    </script>
@endsection