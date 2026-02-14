<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reglas de la Comunidad - Big-Dad</title>
    <meta name="description"
        content="Revisa las reglas de la comunidad Big-Dad para mantener un ambiente seguro, respetuoso y positivo en nuestra plataforma de citas exclusivas.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('legal.rules') }}">
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
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                        </svg>
                    </div>
                    <span
                        class="text-2xl font-black bg-gradient-to-r from-purple-400 to-indigo-500 bg-clip-text text-transparent">
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
                class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-purple-400 via-indigo-500 to-purple-600 bg-clip-text text-transparent">
                Reglas de la Comunidad
            </h1>
            <p class="text-gray-400 text-lg">
                Manteniendo un entorno seguro y exclusivo para todos.
            </p>
        </div>

        <div class="prose prose-invert prose-purple max-w-none">
            <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 md:p-12 space-y-8">

                <section>
                    <h2 class="text-3xl font-bold text-purple-400 mb-4">1. Respeto Mutuo</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Todos los miembros deben tratarse con dignidad y respeto. El acoso, la discriminación o el
                        comportamiento abusivo no serán tolerados bajo ninguna circunstancia.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-purple-400 mb-4">2. Honestidad y Perfiles Reales</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Big-Dad se basa en la confianza. Proporcionar información falsa, usar fotos de terceros o
                        intentar engañar a otros miembros resultará en la expulsión permanente.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-purple-400 mb-4">3. Discreción</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Valoramos la privacidad. No compartas capturas de pantalla, información personal o
                        conversaciones privadas con terceros sin el consentimiento explícito del otro miembro.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-purple-400 mb-4">4. Prohibición de Ilegalidades</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Cualquier actividad ilegal, incluyendo el tráfico de personas, la explotación o el intercambio
                        de servicios prohibidos por la ley, será reportada a las autoridades competentes.
                    </p>
                </section>

                <section>
                    <h2 class="text-3xl font-bold text-purple-400 mb-4">5. Moderación Activa</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Nuestro equipo de moderación revisa constantemente la actividad de la plataforma. Si ves algo
                        que viola nuestras reglas, utiliza la función de reporte.
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
