<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad - Big-Dad</title>
    <meta name="description"
        content="Tu seguridad es nuestra prioridad. Conoce las medidas de seguridad, consejos y recursos de Big-Dad para citas seguras y discretas.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('legal.safety') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
</head>

<body class="bg-[#0a0f1e] text-white font-outfit antialiased">
    <!-- Header -->
    <header class="border-b border-white/5 bg-[#0a0f1e]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span
                        class="text-2xl font-black bg-gradient-to-r from-emerald-400 to-teal-500 bg-clip-text text-transparent">
                        Big-Dad
                    </span>
                </a>

                <a href="{{ route('welcome') }}"
                    class="px-6 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all text-sm font-bold">
                    Volver al Inicio
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-16">
        <div class="text-center mb-16">
            <h1
                class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-600 bg-clip-text text-transparent">
                Consejos de Seguridad
            </h1>
            <p class="text-gray-400 text-lg">
                Tu bienestar es nuestra prioridad. Sigue estas recomendaciones.
            </p>
        </div>

        <div class="prose prose-invert prose-emerald max-w-none">
            <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 md:p-12 space-y-8">

                <section>
                    <h2 class="text-3xl font-bold text-emerald-400 mb-4">1. Primera Cita en Público</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Siempre acuerda encontrarte en un lugar público y concurrido (restaurantes, cafés, hoteles de
                        renombre) para la primera cita. Nunca aceptes ir a un lugar privado en el primer encuentro.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-emerald-400 mb-4">2. Informa a un Amigo</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Comparte tu ubicación en tiempo real con una persona de confianza y dile con quién te vas a
                        encontrar. Ten un plan de salida preparado por si te sientes incómodo.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-emerald-400 mb-4">3. Cuidado con el Dinero</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Nunca envíes dinero a otros miembros por adelantado y desconfía de historias de "emergencia" que
                        requieran transferencias rápidas. Las estafas románticas son una realidad y la prevención es
                        clave.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-emerald-400 mb-4">4. Confía en tu Instinto</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Si algo parece demasiado bueno para ser verdad, o si la comunicación te genera dudas, detente.
                        Es mejor ser precavido que lamentar.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-emerald-400 mb-4">5. Reporta Comportamientos Sospechosos</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Si un usuario te presiona, te pide fotos explícitas de inmediato o se comporta de manera
                        extraña, repórtalo inmediatamente a nuestro equipo.
                    </p>
                </section>

            </div>
        </div>
    </main>

    <footer class="border-t border-white/5 mt-24">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="text-center space-y-4">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} Big-Dad. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
