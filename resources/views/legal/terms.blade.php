<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - Big-Dad</title>
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
                <a href="{{ route('landing') }}" class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                        </svg>
                    </div>
                    <span
                        class="text-2xl font-black bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">
                        Big-Dad
                    </span>
                </a>

                <a href="{{ route('landing') }}"
                    class="px-6 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all text-sm font-bold">
                    Volver al Inicio
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-16">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1
                class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-amber-400 via-orange-500 to-amber-600 bg-clip-text text-transparent">
                Términos y Condiciones
            </h1>
            <p class="text-gray-400 text-lg">
                Última actualización: {{ now()->format('d/m/Y') }}
            </p>
        </div>

        <!-- Content -->
        <div class="prose prose-invert prose-amber max-w-none">
            <div class="bg-[#0c111d] border border-white/5 rounded-3xl p-8 md:p-12 space-y-8">

                <!-- Introducción -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">1. Introducción</h2>
                    <p class="text-gray-300 leading-relaxed">
                        Bienvenido a <strong class="text-white">Big-Dad</strong>, una plataforma de conexión social
                        diseñada para facilitar relaciones mutuamente beneficiosas entre adultos. Al acceder y utilizar
                        nuestros servicios, aceptas estar sujeto a estos Términos y Condiciones ("Términos"). Si no
                        estás de acuerdo con alguna parte de estos términos, no debes utilizar nuestra plataforma.
                    </p>
                </section>

                <!-- Elegibilidad -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">2. Elegibilidad</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">Para utilizar Big-Dad, debes:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Tener al menos <strong class="text-white">18 años de edad</strong></li>
                            <li>Tener capacidad legal para celebrar contratos vinculantes</li>
                            <li>No estar prohibido de usar el servicio bajo las leyes aplicables</li>
                            <li>Proporcionar información veraz y precisa durante el registro</li>
                        </ul>
                        <p class="leading-relaxed mt-4">
                            Nos reservamos el derecho de solicitar verificación de edad en cualquier momento. El
                            incumplimiento de estos requisitos resultará en la suspensión o terminación inmediata de tu
                            cuenta.
                        </p>
                    </div>
                </section>

                <!-- Cuenta de Usuario -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">3. Cuenta de Usuario</h2>
                    <div class="space-y-4 text-gray-300">
                        <h3 class="text-xl font-bold text-white">3.1 Registro</h3>
                        <p class="leading-relaxed">
                            Al crear una cuenta, te comprometes a proporcionar información precisa, completa y
                            actualizada. Eres responsable de mantener la confidencialidad de tus credenciales de acceso.
                        </p>

                        <h3 class="text-xl font-bold text-white mt-6">3.2 Responsabilidad</h3>
                        <p class="leading-relaxed">
                            Eres el único responsable de todas las actividades que ocurran bajo tu cuenta. Debes
                            notificarnos inmediatamente sobre cualquier uso no autorizado de tu cuenta.
                        </p>

                        <h3 class="text-xl font-bold text-white mt-6">3.3 Tipos de Usuario</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li><strong class="text-amber-400">Sugar Daddy:</strong> Usuario que busca ofrecer apoyo y
                                beneficios</li>
                            <li><strong class="text-amber-400">Sugar Baby:</strong> Usuario que busca recibir apoyo y
                                beneficios</li>
                        </ul>
                    </div>
                </section>

                <!-- Uso Aceptable -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">4. Uso Aceptable</h2>
                    <div class="space-y-4 text-gray-300">
                        <h3 class="text-xl font-bold text-white">4.1 Conducta Permitida</h3>
                        <p class="leading-relaxed">Big-Dad es una plataforma para adultos que buscan relaciones
                            consensuadas y mutuamente beneficiosas. Debes usar el servicio de manera respetuosa y legal.
                        </p>

                        <h3 class="text-xl font-bold text-white mt-6">4.2 Conducta Prohibida</h3>
                        <p class="leading-relaxed">Está estrictamente prohibido:</p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Publicar contenido ilegal, ofensivo, difamatorio o que incite al odio</li>
                            <li>Acosar, amenazar o intimidar a otros usuarios</li>
                            <li>Hacerse pasar por otra persona o entidad</li>
                            <li>Solicitar o promover servicios sexuales explícitos a cambio de dinero</li>
                            <li>Compartir información personal de terceros sin consentimiento</li>
                            <li>Utilizar bots, scripts o automatización no autorizada</li>
                            <li>Intentar acceder a cuentas de otros usuarios</li>
                            <li>Publicar contenido con menores de edad</li>
                            <li>Realizar actividades fraudulentas o estafas</li>
                        </ul>
                    </div>
                </section>

                <!-- Suscripciones Premium -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">5. Suscripciones Premium</h2>
                    <div class="space-y-4 text-gray-300">
                        <h3 class="text-xl font-bold text-white">5.1 Planes de Pago</h3>
                        <p class="leading-relaxed">
                            Ofrecemos suscripciones premium que otorgan acceso a funciones adicionales. Los precios y
                            beneficios están claramente indicados en nuestra plataforma.
                        </p>

                        <h3 class="text-xl font-bold text-white mt-6">5.2 Facturación</h3>
                        <p class="leading-relaxed">
                            Las suscripciones se renuevan automáticamente a menos que las canceles antes de la fecha de
                            renovación. Utilizamos procesadores de pago seguros de terceros (Mercado Pago).
                        </p>

                        <h3 class="text-xl font-bold text-white mt-6">5.3 Reembolsos</h3>
                        <p class="leading-relaxed">
                            Los pagos son generalmente no reembolsables, excepto cuando lo exija la ley o en casos de
                            error de facturación comprobado.
                        </p>
                    </div>
                </section>

                <!-- Contenido del Usuario -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">6. Contenido del Usuario</h2>
                    <div class="space-y-4 text-gray-300">
                        <h3 class="text-xl font-bold text-white">6.1 Propiedad</h3>
                        <p class="leading-relaxed">
                            Conservas todos los derechos sobre el contenido que publicas. Sin embargo, nos otorgas una
                            licencia mundial, no exclusiva y libre de regalías para usar, mostrar y distribuir tu
                            contenido en la plataforma.
                        </p>

                        <h3 class="text-xl font-bold text-white mt-6">6.2 Moderación</h3>
                        <p class="leading-relaxed">
                            Nos reservamos el derecho de revisar, moderar o eliminar cualquier contenido que viole estos
                            términos o que consideremos inapropiado.
                        </p>
                    </div>
                </section>

                <!-- Privacidad y Seguridad -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">7. Privacidad y Seguridad</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">
                            Tu privacidad es importante para nosotros. Consulta nuestra
                            <a href="{{ route('legal.privacy') }}"
                                class="text-amber-400 hover:text-amber-300 underline">
                                Política de Privacidad
                            </a>
                            para entender cómo recopilamos, usamos y protegemos tu información.
                        </p>
                        <p class="leading-relaxed">
                            Implementamos medidas de seguridad razonables, pero no podemos garantizar la seguridad
                            absoluta de tus datos. Usas la plataforma bajo tu propio riesgo.
                        </p>
                    </div>
                </section>

                <!-- Limitación de Responsabilidad -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">8. Limitación de Responsabilidad</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">
                            Big-Dad es una plataforma de conexión. <strong class="text-white">No somos responsables
                                de:</strong>
                        </p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Las interacciones entre usuarios fuera de la plataforma</li>
                            <li>La veracidad de la información proporcionada por los usuarios</li>
                            <li>Daños directos, indirectos, incidentales o consecuentes derivados del uso del servicio
                            </li>
                            <li>Pérdida de datos, beneficios o interrupciones del servicio</li>
                        </ul>
                        <p class="leading-relaxed mt-4">
                            El servicio se proporciona "tal cual" sin garantías de ningún tipo.
                        </p>
                    </div>
                </section>

                <!-- Terminación -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">9. Terminación</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">
                            Podemos suspender o terminar tu cuenta en cualquier momento si:
                        </p>
                        <ul class="list-disc list-inside space-y-2 ml-4">
                            <li>Violas estos Términos y Condiciones</li>
                            <li>Participas en actividades fraudulentas o ilegales</li>
                            <li>Recibes múltiples reportes de otros usuarios</li>
                            <li>Lo consideramos necesario para proteger la plataforma o a otros usuarios</li>
                        </ul>
                        <p class="leading-relaxed mt-4">
                            Puedes cancelar tu cuenta en cualquier momento desde la configuración de tu perfil.
                        </p>
                    </div>
                </section>

                <!-- Modificaciones -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">10. Modificaciones</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">
                            Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios
                            significativos serán notificados por email o mediante un aviso en la plataforma. El uso
                            continuado del servicio después de las modificaciones constituye tu aceptación de los nuevos
                            términos.
                        </p>
                    </div>
                </section>

                <!-- Ley Aplicable -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">11. Ley Aplicable</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">
                            Estos términos se rigen por las leyes de Chile. Cualquier disputa se resolverá en los
                            tribunales competentes de Santiago, Chile.
                        </p>
                    </div>
                </section>

                <!-- Contacto -->
                <section>
                    <h2 class="text-3xl font-bold text-amber-400 mb-4">12. Contacto</h2>
                    <div class="space-y-4 text-gray-300">
                        <p class="leading-relaxed">
                            Si tienes preguntas sobre estos Términos y Condiciones, contáctanos:
                        </p>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mt-4">
                            <p class="text-white font-bold mb-2">Big-Dad</p>
                            <p>Email: <a href="mailto:legal@big-dad.com"
                                    class="text-amber-400 hover:text-amber-300">legal@big-dad.com</a></p>
                            <p>Soporte: <a href="mailto:soporte@big-dad.com"
                                    class="text-amber-400 hover:text-amber-300">soporte@big-dad.com</a></p>
                        </div>
                    </div>
                </section>

                <!-- Aceptación -->
                <section class="border-t border-white/10 pt-8 mt-8">
                    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-6">
                        <p class="text-amber-400 font-bold mb-2">⚠️ Importante</p>
                        <p class="text-gray-300 leading-relaxed">
                            Al usar Big-Dad, confirmas que has leído, entendido y aceptado estos Términos y Condiciones
                            en su totalidad. Si no estás de acuerdo, debes dejar de usar la plataforma inmediatamente.
                        </p>
                    </div>
                </section>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 mt-24">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="text-center space-y-4">
                <div class="flex items-center justify-center space-x-6 text-sm text-gray-400">
                    <a href="{{ route('legal.terms') }}" class="hover:text-amber-400 transition-colors">
                        Términos y Condiciones
                    </a>
                    <span>•</span>
                    <a href="{{ route('legal.privacy') }}" class="hover:text-amber-400 transition-colors">
                        Política de Privacidad
                    </a>
                    <span>•</span>
                    <a href="{{ route('landing') }}" class="hover:text-amber-400 transition-colors">
                        Inicio
                    </a>
                </div>
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} Big-Dad. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>