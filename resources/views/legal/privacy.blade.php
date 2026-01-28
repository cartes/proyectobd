<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - Big-Dad</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>

<body class="bg-[#0a0f1e] text-white font-outfit antialiased">
    <!-- Header -->
    <header class="border-b border-white/5 bg-[#0a0f1e]/80 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                        </svg>
                    </div>
                    <span
                        class="text-2xl font-black bg-gradient-to-r from-pink-400 to-rose-500 bg-clip-text text-transparent">
                        Big-Dad
                    </span>
                </a>

                <a href="/"
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
                class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-pink-400 via-rose-500 to-pink-600 bg-clip-text text-transparent">
                Política de Privacidad
            </h1>
            <p class="text-gray-400 text-lg">
                Última actualización: {{ now()->format('d/m/Y') }}
            </p>
        </div>

        <div class="prose prose-invert prose-pink max-w-none">
            <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 md:p-12 space-y-8">

                <section>
                    <h2 class="text-3xl font-bold text-pink-400 mb-4">1. Recopilación de Información</h2>
                    <p class="text-gray-300 leading-relaxed">
                        En Big-Dad, recopilamos información para proporcionar mejores servicios a nuestros usuarios.
                        Esto incluye información proporcionada por ti (como nombre, correo electrónico, fotos y
                        preferencias) e información recopilada automáticamente (como dirección IP, tipo de dispositivo y
                        patrones de uso).
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-pink-400 mb-4">2. Uso de la Información</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Utilizamos tu información para personalizar tu experiencia, facilitar conexiones con otros
                        usuarios, procesar pagos, mejorar nuestra plataforma y comunicarnos contigo sobre
                        actualizaciones o promociones.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-pink-400 mb-4">3. Compartir Información</h2>
                    <p class="text-gray-300 leading-relaxed">
                        No vendemos tu información personal a terceros. Compartimos datos solo según sea necesario para
                        operar la plataforma (por ejemplo, con procesadores de pago), cumplir con la ley o proteger los
                        derechos de Big-Dad y sus usuarios.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-pink-400 mb-4">4. Seguridad</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Implementamos medidas técnicas y organizativas líderes en la industria para proteger tus datos.
                        Sin embargo, ninguna transmisión por Internet es 100% segura, por lo que te instamos a usar
                        contraseñas robustas y no compartir información sensible innecesariamente.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-pink-400 mb-4">5. Tus Derechos</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Tienes derecho a acceder, rectificar o eliminar tu información personal en cualquier momento.
                        Puedes gestionar la mayoría de estos datos directamente desde tu perfil o contactándonos para
                        solicitudes específicas.
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