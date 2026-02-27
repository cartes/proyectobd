<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- SEO Meta Tags -->
    <title>Big-dad: #1 Sugar Dating en Latinoamérica | Citas Exclusivas y Lujos</title>
    <meta name="description"
        content="Únete a Big-dad, la comunidad de élite para Sugar Daddies y Sugar Babies en Latinoamérica. Encuentra tu compañero de lujo para viajes, cenas y conexiones exclusivas. Registro GRATIS para una vida de alto nivel." />
    <meta name="keywords"
        content="sugar daddy latinoamerica, sugar baby, citas exclusivas, dating de lujo, relaciones mutuamente beneficiosas, buscar pareja con dinero, sugar dating internacional" />
    <meta name="author" content="Big-dad Elite Dating" />
    <meta name="robots" content="index, follow" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:title" content="Big-dad: El Club de Citas para Gente Exitosa en Latinoamérica" />
    <meta property="og:description"
        content="¿Buscas un estilo de vida premium? Conecta con personas que comparten tus mismos gustos y ambiciones. La red social exclusiva para Sugar Dating en Latinoamérica." />
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url('/') }}" />
    <meta property="twitter:title" content="Big-dad: Citas de Lujo y Luxury Lifestyle" />
    <meta property="twitter:description"
        content="Descubre el Sugar Dating en Latinoamérica de forma segura y exclusiva. Únete a la comunidad Big-dad hoy." />
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&family=Playfair+Display:ital,wght@0,600;1,600&display=swap"
        rel="stylesheet">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebSite",
            "name": "Big-dad Latinoamérica",
            "url": "{{ url('/') }}",
            "potentialAction": {
                "@@type": "SearchAction",
                "target": "{{ url('/') }}/search?q={search_term_string}",
                "query-input": "required name=search_term_string"
            },
            "description": "Plataforma líder de Sugar Dating y citas exclusivas en Latinoamérica."
        }
    </script>

    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-G035SGF3GT"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-G035SGF3GT');
    </script>
</head>


<body class="bg-slate-900 text-white antialiased overflow-x-hidden font-outfit" x-data="homePage()">

    <!-- Modal de Verificación de Edad (Age Gate) -->
    <div x-cloak x-show="showAgeGate" x-transition.opacity.duration.500ms
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/80 backdrop-blur-md">

        <div class="relative w-full max-w-md p-8 mx-4 text-center border shadow-2xl bg-slate-800 border-slate-700 rounded-3xl overflow-hidden"
            @click.outside.prevent>
            <!-- Resplandor decorativo de fondo -->
            <div class="absolute w-full h-full rounded-full -top-1/2 -left-1/2 bg-pink-500/10 blur-[80px]"></div>

            <div class="relative z-10">
                <!-- Logo -->
                <div class="mb-6">
                    <span class="text-3xl font-black tracking-tighter">
                        BIG-<span class="text-pink-500">DAD</span>
                    </span>
                </div>

                <!-- Título -->
                <h2 class="mb-4 text-2xl font-bold text-white">Sitio exclusivo para mayores de 18 años</h2>

                <!-- Texto Legal -->
                <p class="mb-8 text-sm leading-relaxed text-white">
                    Esta plataforma contiene contenido para adultos y está estrictamente restringida a personas mayores
                    de 18 años o la mayoría de edad legal en su jurisdicción. Al ingresar, confirmas bajo tu
                    responsabilidad que cumples con este requisito.
                </p>

                <!-- Botones -->
                <div class="flex flex-col gap-4">
                    <button @click="acceptAge"
                        class="w-full px-6 py-3 font-bold text-white transition-all bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl hover:shadow-lg hover:shadow-pink-500/30 hover:scale-[1.02]">
                        Sí, soy mayor de 18 años y acepto los términos
                    </button>

                    <a href="https://www.google.com"
                        class="w-full px-6 py-3 font-bold transition-all border text-slate-300 border-slate-600 rounded-xl hover:bg-slate-700 hover:text-white">
                        No, salir del sitio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Navigation -->
    <nav x-ref="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300"
        :class="isScrolled ? 'bg-slate-900/90 backdrop-blur-md shadow-lg py-4' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <a href="/" class="text-2xl font-black tracking-tighter hover:scale-105 transition-transform">
                BIG-<span class="text-pink-500">DAD</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-300">
                <a href="{{ route('plans.public') }}" class="hover:text-pink-400 transition-colors">Planes</a>
                <a href="{{ route('blog.index') }}" class="hover:text-pink-400 transition-colors">Blog</a>
                <a href="#como-funciona" class="hover:text-pink-400 transition-colors">Cómo Funciona</a>
                <a href="#beneficios" class="hover:text-pink-400 transition-colors">Beneficios</a>
            </div>

            <div class="flex items-center gap-4">
                @if (auth()->check())
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full text-sm font-bold shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 transition-all">
                        Mi Panel VIP
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold hover:text-pink-400 transition-colors">Ingresar</a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 bg-white text-pink-600 rounded-full text-sm font-bold hover:bg-gray-100 transition-all shadow-lg">
                        Únete Gratis
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative min-h-[90vh] flex flex-col items-center justify-center overflow-hidden pt-20">
        <!-- Floating Hearts Background -->
        <div class="absolute inset-0 z-0">
            <template x-for="i in 20">
                <div class="heart-particle text-white/10"
                    :style="`left: ${Math.random()*100}%; animation-duration: ${10 + Math.random()*20}s; animation-delay: -${Math.random()*20}s; font-size: ${20 + Math.random()*40}px; filter: blur(${Math.random()*3}px);`"
                    x-text="Math.random() > 0.5 ? '❤️' : '💖'">
                </div>
            </template>
        </div>

        <!-- Background Elements -->
        <div class="absolute inset-0 bg-hero-pattern opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/50 via-slate-900/20 to-slate-900"></div>

        <!-- Animated Blobs -->
        <div
            class="absolute top-1/4 -left-20 w-[500px] h-[500px] bg-purple-600/30 rounded-full blur-[120px] animate-blob">
        </div>
        <div
            class="absolute bottom-1/4 -right-20 w-[500px] h-[500px] bg-pink-600/30 rounded-full blur-[120px] animate-blob animation-delay-2000">
        </div>

        <div class="relative z-10 container mx-auto px-6 text-center">
            <div class="relative inline-block animate-fade-in-up">
                <!-- Badge +18 -->
                <span
                    class="inline-block px-4 py-1 mb-4 text-sm font-bold tracking-wider text-red-500 uppercase border rounded-full bg-red-500/10 border-red-500/30">
                    🔞 +18 PLATAFORMA EXCLUSIVA PARA ADULTOS
                </span>
                <br>
                <span
                    class="inline-block py-1 px-3 rounded-full bg-pink-500/10 border border-pink-500/30 text-pink-400 text-xs font-bold uppercase tracking-widest mb-6">
                    ✨ La comunidad #1 de Lifestyle en Latinoamérica
                </span>

                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black mb-6 leading-tight animation-delay-300">
                    La <span
                        class="bg-clip-text text-transparent bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400">Buena
                        Vida</span><br>
                    es Mejor Compartida
                </h1>
            </div>

            <p
                class="text-xl md:text-2xl text-slate-300 mb-10 max-w-2xl mx-auto font-light leading-relaxed animate-fade-in-up animation-delay-500">
                Descubre conexiones genuinas con personas exitosas y atractivas. Sin juegos, sin dramas, solo
                experiencias de alto nivel.
            </p>

            <!-- Role Selector / CTA -->
            <div
                class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up animation-delay-700">
                <a href="{{ route('register', ['role' => 'sugar_baby']) }}" class="group relative w-full sm:w-auto">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl blur opacity-75 group-hover:opacity-100 transition duration-1000 group-hover:duration-200 animate-tilt">
                    </div>
                    <button
                        class="relative w-full px-8 py-5 bg-slate-900 rounded-2xl leading-none flex items-center justify-center space-x-4">
                        <span class="text-2xl">💅</span>
                        <div class="text-left">
                            <span class="block text-xs text-pink-400 uppercase tracking-wider font-bold">Soy Sugar
                                Baby</span>
                            <span class="font-bold text-white">Busco Mimarme</span>
                        </div>
                    </button>
                </a>

                <a href="{{ route('register', ['role' => 'sugar_daddy']) }}" class="group relative w-full sm:w-auto">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl blur opacity-50 group-hover:opacity-100 transition duration-1000 group-hover:duration-200">
                    </div>
                    <button
                        class="relative w-full px-8 py-5 bg-slate-900 rounded-2xl leading-none flex items-center justify-center space-x-4">
                        <span class="text-2xl">🎩</span>
                        <div class="text-left">
                            <span class="block text-xs text-indigo-400 uppercase tracking-wider font-bold">Soy Sugar
                                Daddy</span>
                            <span class="font-bold text-white">Busco Compañía</span>
                        </div>
                    </button>
                </a>
            </div>

            <p class="mt-6 text-sm text-slate-400 animate-fade-in-up animation-delay-1000">
                🔒 Registro Rápido, Discreto y Seguro.
            </p>
        </div>
    </header>

    <!-- How It Works Section -->
    <section id="como-funciona" class="py-24 bg-slate-900 relative">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-black mb-4"><span class="text-pink-500">Desbloquea</span> Tu
                    Nuevo
                    Estilo de Vida</h2>
                <p class="text-slate-400 max-w-2xl mx-auto">En Big-dad, simplificamos las reglas del juego. Conecta con
                    personas de élite en toda Latinoamérica.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div
                    class="bg-slate-800/50 p-8 rounded-3xl border border-slate-700 hover:border-pink-500/50 transition-all group">
                    <div
                        class="w-16 h-16 bg-pink-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                        📝</div>
                    <h3 class="text-xl font-bold mb-3">1. Crea tu Perfil VIP</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Sube tus mejores fotos (¡o mantenlas privadas!) y
                        define qué buscas con claridad. Sé directo y auténtico.</p>
                </div>

                <!-- Step 2 -->
                <div
                    class="bg-slate-800/50 p-8 rounded-3xl border border-slate-700 hover:border-purple-500/50 transition-all group">
                    <div
                        class="w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                        🔍</div>
                    <h3 class="text-xl font-bold mb-3">2. Descubre la Elite</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Usa nuestros filtros premium para encontrar
                        personas exitosas en tu ciudad o en toda Latinoamérica.</p>
                </div>

                <!-- Step 3 -->
                <div
                    class="bg-slate-800/50 p-8 rounded-3xl border border-slate-700 hover:border-indigo-500/50 transition-all group">
                    <div
                        class="w-16 h-16 bg-indigo-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                        🥂</div>
                    <h3 class="text-xl font-bold mb-3">3. Vive la Experiencia</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Conecta, chatea y acuerda esa primera cita
                        soñada.
                        Cenas, viajes o simplemente buena compañía.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="beneficios" class="py-24 bg-gradient-to-b from-slate-900 to-slate-950">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="relative">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-pink-500 to-purple-600 rounded-[2rem] blur-lg opacity-30">
                        </div>
                        <img src="https://images.unsplash.com/photo-1544911845-1f34a3eb46b1?q=80&w=2070&auto=format&fit=crop"
                            alt="Luxury Dating Lifestyle"
                            class="relative rounded-[2rem] shadow-2xl w-full object-cover h-[500px]">

                        <!-- Floating Badge -->
                        <div
                            class="absolute -bottom-8 -right-8 bg-white text-slate-900 p-6 rounded-2xl shadow-xl max-w-xs hidden md:block">
                            <p class="font-serif italic text-lg mb-2">"La vida es demasiado corta para citas
                                aburridas."
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="text-pink-500">★★★★★</span>
                                <span class="text-xs font-bold text-slate-500">Verificado</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/2">
                    <span class="text-pink-500 font-bold tracking-widest uppercase text-sm mb-2 block">¿Por qué
                        Big-dad?</span>
                    <h2 class="text-4xl md:text-5xl font-black mb-8 leading-tight">Más que Citas,<br>Un <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-indigo-400">Estilo
                            de
                            Vida</span>.</h2>

                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-pink-500/20 flex items-center justify-center text-pink-500 mt-1">
                                ✓</div>
                            <div>
                                <h4 class="text-xl font-bold text-white">Cero Drama, Todo Clarity</h4>
                                <p class="text-slate-400 mt-1">Aquí todos saben lo que buscan. Relaciones directas y
                                    transparentes desde el "Hola".</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center text-purple-500 mt-1">
                                ✓</div>
                            <div>
                                <h4 class="text-xl font-bold text-white">Privacidad Garantizada</h4>
                                <p class="text-slate-400 mt-1">Tú controlas quién ve tus fotos y tu información. Tu
                                    discreción es nuestra prioridad.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-500 mt-1">
                                ✓</div>
                            <div>
                                <h4 class="text-xl font-bold text-white">Calidad sobre Cantidad</h4>
                                <p class="text-slate-400 mt-1">Verificamos perfiles y moderamos 24/7 para asegurar que
                                    solo gente real y de nivel esté aquí.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-10">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 text-pink-400 font-bold hover:gap-4 transition-all">
                            Empieza tu historia hoy <span class="text-xl">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership / CTA Section -->
    <section class="py-24 bg-slate-900 border-t border-white/5">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-5xl font-black mb-12">Elige tu Camino</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Card Sugar Baby -->
                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 rounded-3xl border border-white/5 hover:border-pink-500/50 transition-all hover:-translate-y-2">
                    <div class="text-5xl mb-6">💃</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Para Sugar Babies</h3>
                    <p class="text-slate-400 mb-8 h-12">¿Buscas mentoría, regalos y viajes? Encuentra a alguien que
                        disfrute consentirte.</p>
                    <ul class="text-left space-y-3 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-2"><span class="text-pink-500">●</span> Registro 100%
                            Gratuito
                        </li>
                        <li class="flex items-center gap-2"><span class="text-pink-500">●</span> Perfil destacado
                            disponible</li>
                        <li class="flex items-center gap-2"><span class="text-pink-500">●</span> Filtros de búsqueda
                            avanzados</li>
                    </ul>
                    <a href="{{ route('register', ['role' => 'sugar_baby']) }}"
                        class="block w-full py-4 bg-pink-600 rounded-xl font-bold hover:bg-pink-500 transition-colors">
                        Soy Sugar Baby
                    </a>
                </div>

                <!-- Card Sugar Daddy -->
                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 rounded-3xl border border-white/5 hover:border-indigo-500/50 transition-all hover:-translate-y-2">
                    <div class="text-5xl mb-6">🥂</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Para Sugar Daddies</h3>
                    <p class="text-slate-400 mb-8 h-12">¿Buscas compañía atractiva para tus eventos y viajes? Comparte
                        tu éxito.</p>
                    <ul class="text-left space-y-3 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-2"><span class="text-indigo-500">●</span> Privacidad y
                            anonimato</li>
                        <li class="flex items-center gap-2"><span class="text-indigo-500">●</span> Sin pérdidas de
                            tiempo</li>
                        <li class="flex items-center gap-2"><span class="text-indigo-500">●</span> Match con gente
                            verificada</li>
                    </ul>
                    <a href="{{ route('register', ['role' => 'sugar_daddy']) }}"
                        class="block w-full py-4 bg-indigo-600 rounded-xl font-bold hover:bg-indigo-500 transition-colors">
                        Soy Sugar Daddy
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer SEO -->
    <footer class="bg-slate-950 py-16 border-t border-white/5 text-sm text-slate-400">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div>
                <a href="/" class="text-2xl font-black text-white block mb-6">
                    BIG-<span class="text-pink-500">DAD</span>
                </a>
                <p class="leading-relaxed mb-6">
                    La plataforma líder de Sugar Dating en Latinoamérica. Conectando ambición con éxito desde 2024.
                </p>
                <div class="flex gap-4">
                    <a href="#"
                        class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all">XT</a>
                    <a href="#"
                        class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all">IG</a>
                    <a href="#"
                        class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all">FB</a>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Descubrir</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Sugar Babies Premium</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Sugar Daddies Verificados</a>
                    </li>
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Elite Dating Internacional</a>
                    </li>
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Blog de Estilo de Vida</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('legal.terms') }}" class="hover:text-pink-500 transition-colors">Términos y
                            Condiciones</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="hover:text-pink-500 transition-colors">Política
                            de
                            Privacidad</a></li>
                    <li><a href="{{ route('legal.rules') }}" class="hover:text-pink-500 transition-colors">Reglas de
                            la
                            Comunidad</a></li>
                    <li><a href="{{ route('legal.safety') }}"
                            class="hover:text-pink-500 transition-colors">Seguridad</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Ayuda</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Soporte 24/7</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Contacto</a></li>
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Preguntas Frecuentes</a></li>
                </ul>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-16 pt-8 border-t border-white/5 text-center text-xs">
            <p>&copy; {{ date('Y') }} Big-dad Latinoamérica. Todos los derechos reservados. Hecho con ❤️ para toda
                LATAM.</p>
        </div>
    </footer>

    <script>
        function homePage() {
            return {
                isScrolled: false,
                showAgeGate: false,

                init() {
                    // Lógica del Modal Age Gate
                    if (!localStorage.getItem('age_verified')) {
                        this.showAgeGate = true;
                        // Bloquear el scroll del body cuando el modal está abierto
                        document.body.style.overflow = 'hidden';
                    }

                    window.addEventListener('scroll', () => {
                        this.isScrolled = window.scrollY > 50;
                    });

                    // Cursor Glow Interaction
                    window.addEventListener('mousemove', (e) => {
                        document.body.style.setProperty('--x', e.clientX + 'px');
                        document.body.style.setProperty('--y', e.clientY + 'px');
                    });
                },

                // Función que se ejecuta al hacer clic en "Sí"
                acceptAge() {
                    localStorage.setItem('age_verified', 'true');
                    this.showAgeGate = false;
                    document.body.style.overflow = ''; // Restaurar el scroll
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        @@keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        /* Cursor Glow Effect */
        body::after {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.05) 0%, rgba(0, 0, 0, 0) 70%);
            top: var(--y, 0);
            left: var(--x, 0);
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 999;
        }
    </style>
</body>

</html>
