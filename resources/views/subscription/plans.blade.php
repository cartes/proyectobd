@extends('layouts.subscription')

@section('content')
    <!-- ✅ MERCADO PAGO SDK - AGREGAR AL HEAD -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        // ✅ INICIALIZAR MERCADO PAGO CON TU PUBLIC KEY
        const mp = new MercadoPago('{{ config('services.mercadopago.public_key') }}', {
            locale: 'es-CL' // Para Chile
        });
    </script>

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto py-12">
            <!-- Título -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-white mb-4">Plans Premium</h1>
                <p class="text-lg text-gray-400">Desbloquea funciones premium y aumenta tus oportunidades</p>
            </div>

            <!-- Plan Activo (si existe) -->
            @if ($activeSubscription)
                <div class="px-4 sm:px-6 lg:px-8 mb-8">
                    <div class="max-w-7xl mx-auto bg-emerald-500/20 border border-emerald-500/50 rounded-xl p-6">
                        <p class="text-emerald-300">
                            <strong>✓ Plan Activo:</strong> {{ $activeSubscription->plan->name }}
                            (Expira: {{ $activeSubscription->ends_at->format('d/m/Y') }})
                        </p>
                    </div>
                </div>
            @endif

            <!-- Plans Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($plans as $plan)
                    <div
                        class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <!-- Plan Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                            <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                            <p class="text-sm text-gray-200 mt-1">{{ $plan->description }}</p>
                        </div>

                        <!-- Plan Body -->
                        <div class="p-6">
                            <!-- Precio -->
                            <div class="mb-6">
                                <div class="text-4xl font-bold text-white">
                                    ${{ number_format($plan->amount, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-400 mt-1">
                                    {{ $plan->currency }}
                                    @if ($plan->frequency_type === 'months')
                                        {{ $plan->frequency == 1 ? '/mes' : '/' . $plan->frequency . ' meses' }}
                                    @else
                                        {{ $plan->frequency == 1 ? '/año' : '/' . $plan->frequency . ' años' }}
                                    @endif
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="mb-6">
                                <ul class="space-y-2">
                                    @foreach ($plan->features as $feature)
                                        <li class="flex items-center text-gray-300">
                                            <svg class="w-4 h-4 text-teal-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Botón de Suscripción -->
                            <div class="mt-auto">
                                @if ($activeSubscription && $activeSubscription->plan_id === $plan->id)
                                    <!-- Plan Actual - Deshabilitado -->
                                    <button type="button" disabled
                                        class="w-full bg-gray-500 cursor-not-allowed text-white font-semibold py-3 px-6 rounded-lg">
                                        ✓ Plan Actual
                                    </button>
                                @else
                                    <!-- ✅ BOTÓN QUE INICIA CHECKOUT CON SDK -->
                                    <button type="button" id="checkout-btn-{{ $plan->id }}"
                                        class="w-full bg-teal-500 hover:bg-teal-600 active:bg-teal-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 transform hover:scale-105"
                                        onclick="handleCheckout({{ $plan->id }}, '{{ route('subscription.checkout', $plan->id) }}')"
                                        <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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

            // Obtener botón y mostrar loading
            const btn = document.getElementById('checkout-btn-' + planId);
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>Procesando...</span>';

            // ✅ PASO 1: Enviar POST a tu backend para crear preferencia
            fetch(checkoutUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Server response:', data);

                    // ✅ PASO 2: Si tenemos preference_id, abrir Mercado Pago
                    if (data.success && data.preference_id) {
                        console.log('Preference ID obtenido:', data.preference_id);

                        // ✅ PASO 3: Usar SDK para abrir Checkout Pro
                        mp.checkout({
                            preference: {
                                id: data.preference_id
                            },
                            render: 'wallet', // Renderiza wallet Mercado Pago
                            onError: (error) => {
                                console.error('Error en Mercado Pago:', error);
                                alert('Error al procesar el pago');
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            },
                            onPending: () => {
                                console.log('Pago pendiente');
                                alert('Pago pendiente. Te notificaremos pronto.');
                                btn.disabled = false;
                                btn.innerHTML = originalText;
                            },
                        });
                    } else {
                        // Error en la respuesta
                        const errorMsg = data.error || 'Error desconocido';
                        console.error('Error del servidor:', data);
                        alert('Error: ' + errorMsg);
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error en fetch:', error);
                    alert('Error al crear la preferencia de pago: ' + error.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        }
    </script>
@endsection
