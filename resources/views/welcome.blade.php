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
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:type" content="image/jpeg" />

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
        href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800;900&family=Montserrat:ital,wght@0,400;0,600;0,700;0,800;1,400;1,600&display=swap"
        rel="stylesheet">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebSite",
            "name": "Big-dad Latinoamérica",
            "url": "{{ url('/') }}",
            "inLanguage": "es-CL",
            "description": "Plataforma líder de Sugar Dating y citas exclusivas en Latinoamérica.",
            "keywords": "sugar daddy latinoamerica, sugar baby, citas exclusivas, dating de lujo, relaciones mutuamente beneficiosas, buscar pareja con dinero, sugar dating internacional",
            "potentialAction": {
                "@@type": "SearchAction",
                "target": {
                    "@@type": "EntryPoint",
                    "urlTemplate": "{{ url('/') }}/search?q={search_term_string}"
                },
                "query-input": "required name=search_term_string"
            }
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "Organization",
            "name": "Big-dad",
            "url": "{{ url('/') }}",
            "description": "Plataforma de Sugar Dating exclusiva que conecta Sugar Daddies y Sugar Babies en Latinoamérica.",
            "keywords": "sugar daddy, sugar baby, citas exclusivas, dating de lujo, sugar dating latinoamerica",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('favicon.png') }}",
                "caption": "Big-dad Logo"
            },
            "sameAs": [
                "https://www.instagram.com/big_dad.app/"
            ],
            "contactPoint": {
                "@@type": "ContactPoint",
                "email": "hola@big-dad.com",
                "contactType": "customer support",
                "availableLanguage": "Spanish"
            }
        }
    </script>
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebPage",
            "@@id": "{{ url('/') }}",
            "url": "{{ url('/') }}",
            "name": "Big-dad: #1 Sugar Dating en Latinoamérica | Citas Exclusivas y Lujos",
            "description": "Únete a Big-dad, la comunidad de élite para Sugar Daddies y Sugar Babies en Latinoamérica. Encuentra tu compañero de lujo para viajes, cenas y conexiones exclusivas.",
            "keywords": "sugar daddy latinoamerica, sugar baby, citas exclusivas, dating de lujo, relaciones mutuamente beneficiosas, buscar pareja con dinero, sugar dating internacional",
            "inLanguage": "es-CL",
            "isPartOf": {
                "@@type": "WebSite",
                "name": "Big-dad Latinoamérica",
                "url": "{{ url('/') }}"
            },
            "publisher": {
                "@@type": "Organization",
                "name": "Big-dad",
                "url": "{{ url('/') }}"
            },
            "image": "{{ asset('images/og-image.jpg') }}"
        }
    </script>

    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.161.0/build/three.min.js"></script>
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
                <a href="{{ route('como-funciona') }}" class="hover:text-pink-400 transition-colors">Cómo Funciona</a>
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

    <!-- How It Works Section — Three.js Enhanced -->
    <section id="como-funciona" class="relative overflow-hidden" style="background:#0f172a; min-height:100vh;">

        <!-- Three.js canvas (background) -->
        <canvas id="hiw-canvas" class="absolute inset-0 w-full h-full" style="pointer-events:none;"></canvas>

        <!-- Content -->
        <div class="relative z-10 py-28 container mx-auto px-6">

            <!-- Header -->
            <div class="text-center mb-24 hiw-reveal">
                <span class="inline-block text-pink-500 font-bold tracking-[.25em] uppercase text-xs mb-5 px-4 py-1.5 rounded-full border border-pink-500/30 bg-pink-500/10">
                    El Proceso
                </span>
                <h2 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-purple-400">Desbloquea</span>
                    Tu<br class="hidden md:block"> Nuevo Estilo de Vida
                </h2>
                <p class="text-slate-400 max-w-xl mx-auto text-lg">
                    En Big-dad, simplificamos las reglas del juego. Conecta con personas de élite en toda Latinoamérica.
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto items-start">

                <!-- Step 1 -->
                <div class="hiw-reveal hiw-card group relative" style="--hiw-delay:0ms">
                    <div class="absolute -inset-px rounded-3xl bg-gradient-to-b from-pink-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative h-full bg-slate-900/70 backdrop-blur-xl p-10 rounded-3xl border border-slate-700/60 group-hover:border-pink-500/60 transition-all duration-500">
                        <span class="absolute top-5 right-7 text-8xl font-black text-pink-500/8 select-none leading-none">01</span>
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-pink-500 to-pink-700 flex items-center justify-center text-4xl mb-8 shadow-xl shadow-pink-500/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">📝</div>
                        <h3 class="text-2xl font-bold mb-4">Crea tu Perfil VIP</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Sube tus mejores fotos y define qué buscas con claridad. Sé directo y auténtico — tu perfil es tu carta de presentación al mundo de élite.</p>
                        <div class="mt-8 h-px bg-gradient-to-r from-pink-500 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left rounded-full"></div>
                    </div>
                </div>

                <!-- Step 2 — destacado -->
                <div class="hiw-reveal hiw-card group relative md:-mt-6" style="--hiw-delay:120ms">
                    <div class="absolute -inset-px rounded-3xl bg-gradient-to-b from-purple-500 via-pink-500 to-transparent opacity-40 pointer-events-none"></div>
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 whitespace-nowrap bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold px-5 py-1.5 rounded-full shadow-lg shadow-purple-500/40">
                        ⭐ Paso Clave
                    </div>
                    <div class="relative h-full bg-slate-900/80 backdrop-blur-xl p-10 rounded-3xl border border-purple-500/50">
                        <span class="absolute top-5 right-7 text-8xl font-black text-purple-500/8 select-none leading-none">02</span>
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-4xl mb-8 shadow-xl shadow-purple-500/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">🔍</div>
                        <h3 class="text-2xl font-bold mb-4">Descubre la Elite</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Usa nuestros filtros premium para encontrar personas exitosas en tu ciudad o en toda Latinoamérica. Total discreción garantizada.</p>
                        <div class="mt-8 h-px bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"></div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="hiw-reveal hiw-card group relative" style="--hiw-delay:240ms">
                    <div class="absolute -inset-px rounded-3xl bg-gradient-to-b from-indigo-500/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                    <div class="relative h-full bg-slate-900/70 backdrop-blur-xl p-10 rounded-3xl border border-slate-700/60 group-hover:border-indigo-500/60 transition-all duration-500">
                        <span class="absolute top-5 right-7 text-8xl font-black text-indigo-500/8 select-none leading-none">03</span>
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-700 flex items-center justify-center text-4xl mb-8 shadow-xl shadow-indigo-500/30 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">🥂</div>
                        <h3 class="text-2xl font-bold mb-4">Vive la Experiencia</h3>
                        <p class="text-slate-400 leading-relaxed text-sm">Conecta, chatea y acuerda esa primera cita soñada. Cenas exclusivas, viajes de lujo o simplemente la mejor compañía de tu vida.</p>
                        <div class="mt-8 h-px bg-gradient-to-r from-indigo-500 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center mt-24 hiw-reveal" style="--hiw-delay:360ms">
                <p class="text-slate-400 mb-6 text-base tracking-wide uppercase text-xs font-semibold">¿Listo para comenzar?</p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-3 bg-gradient-to-r from-pink-500 to-purple-600 text-white font-bold py-4 px-12 rounded-full text-lg hover:opacity-90 hover:scale-105 transition-all duration-300 shadow-2xl shadow-pink-500/30">
                    Únete Gratis
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
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
                    <a href="https://www.instagram.com/big_dad.app/" target="_blank" rel="noopener"
                        class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all"
                        aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <a href="mailto:hola@big-dad.com"
                        class="text-gray-400 hover:text-pink-500 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        hola@big-dad.com
                    </a>
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
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Preguntas Frecuentes</a></li>
                </ul>

                <h4 class="text-white font-bold mt-8 mb-6 uppercase tracking-wider">Sitios Amigos</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="https://tecnopatitas.com" target="_blank" rel="noopener"
                            class="hover:text-pink-500 transition-colors flex items-center gap-2">
                            tecnopatitas.com
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="https://bandoleras.cl" target="_blank" rel="noopener"
                            class="hover:text-pink-500 transition-colors flex items-center gap-2">
                            bandoleras.cl
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </li>
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

    <!-- ═══════════════════════════════════════════════
         Three.js — Sección Cómo Funciona
    ═══════════════════════════════════════════════ -->
    <script>
    (function () {
        'use strict';

        /* ── Esperar a que Three.js y el DOM estén listos ── */
        function init() {
            if (typeof THREE === 'undefined') return;

            const section = document.getElementById('como-funciona');
            const canvas  = document.getElementById('hiw-canvas');
            if (!section || !canvas) return;

            /* ── Renderer ── */
            const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: false });
            renderer.setClearColor(0x0f172a, 1);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            /* ── Scene & Camera ── */
            const scene  = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(65, 1, 0.1, 200);
            camera.position.z = 22;

            /* ── Particle field ── */
            const COUNT = 340;
            const pos   = new Float32Array(COUNT * 3);
            const col   = new Float32Array(COUNT * 3);
            const palette = [
                new THREE.Color(0xec4899), // pink-500
                new THREE.Color(0xa855f7), // purple-500
                new THREE.Color(0x6366f1), // indigo-500
                new THREE.Color(0xf59e0b), // gold
                new THREE.Color(0xfb7185), // rose
            ];
            for (let i = 0; i < COUNT; i++) {
                pos[i * 3]     = (Math.random() - 0.5) * 70;
                pos[i * 3 + 1] = (Math.random() - 0.5) * 45;
                pos[i * 3 + 2] = (Math.random() - 0.5) * 25;
                const c = palette[Math.floor(Math.random() * palette.length)];
                col[i * 3] = c.r; col[i * 3 + 1] = c.g; col[i * 3 + 2] = c.b;
            }
            const pGeo = new THREE.BufferGeometry();
            pGeo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
            pGeo.setAttribute('color',    new THREE.BufferAttribute(col, 3));
            const pMat = new THREE.PointsMaterial({ size: 0.13, vertexColors: true, transparent: true, opacity: 0.65, sizeAttenuation: true });
            scene.add(new THREE.Points(pGeo, pMat));

            /* ── Floating wireframe shapes ── */
            const shapeDefs = [
                { geo: new THREE.OctahedronGeometry(1.4, 0), color: 0xec4899, x: -16, y:  6, z: -6,  rs: 0.006 },
                { geo: new THREE.OctahedronGeometry(0.7, 0), color: 0xf59e0b, x:  12, y: -4, z: -4,  rs: 0.008 },
                { geo: new THREE.TorusGeometry(2.2, 0.4, 8, 32),              color: 0xa855f7, x:  14, y:  7, z: -9,  rs: 0.005 },
                { geo: new THREE.OctahedronGeometry(0.5, 0), color: 0x6366f1, x:  -9, y: -6, z: -5,  rs: 0.010 },
                { geo: new THREE.TorusGeometry(1.3, 0.3, 8, 20),              color: 0xec4899, x: -19, y: -2, z: -7,  rs: 0.007 },
                { geo: new THREE.TetrahedronGeometry(1.0),                    color: 0xf59e0b, x:  19, y:  1, z: -5,  rs: 0.006 },
                { geo: new THREE.OctahedronGeometry(0.6, 0), color: 0xfb7185, x:   4, y: -8, z: -3,  rs: 0.009 },
                { geo: new THREE.TorusGeometry(0.9, 0.25, 6, 18),             color: 0x6366f1, x: -5,  y:  8, z: -8,  rs: 0.007 },
            ];
            const shapes = shapeDefs.map(d => {
                const mat  = new THREE.MeshBasicMaterial({ color: d.color, wireframe: true, transparent: true, opacity: 0.28 });
                const mesh = new THREE.Mesh(d.geo, mat);
                mesh.position.set(d.x, d.y, d.z);
                mesh.userData = { rs: d.rs, iy: d.y, fs: 0.4 + Math.random() * 0.6, fa: 0.4 + Math.random() * 0.6 };
                scene.add(mesh);
                return mesh;
            });

            /* ── Soft ambient glow (large semi-transparent sphere) ── */
            const glowMat  = new THREE.MeshBasicMaterial({ color: 0x7c3aed, transparent: true, opacity: 0.04 });
            const glowMesh = new THREE.Mesh(new THREE.SphereGeometry(14, 24, 24), glowMat);
            glowMesh.position.set(0, 0, -10);
            scene.add(glowMesh);

            /* ── Mouse parallax ── */
            let mx = 0, my = 0;
            document.addEventListener('mousemove', e => {
                mx = (e.clientX / window.innerWidth  - 0.5) * 2;
                my = (e.clientY / window.innerHeight - 0.5) * 2;
            });

            /* ── Resize helper ── */
            function resize() {
                const w = section.offsetWidth;
                const h = section.offsetHeight || window.innerHeight;
                renderer.setSize(w, h);
                camera.aspect = w / h;
                camera.updateProjectionMatrix();
            }
            resize();
            const ro = new ResizeObserver(resize);
            ro.observe(section);

            /* ── Animation loop ── */
            let t = 0;
            function tick() {
                requestAnimationFrame(tick);
                t += 0.008;

                /* Particle drift */
                pGeo.attributes.position.array.forEach((_, idx) => {
                    if (idx % 3 === 1) {
                        pGeo.attributes.position.array[idx] += Math.sin(t + idx) * 0.002;
                    }
                });
                pGeo.attributes.position.needsUpdate = true;

                /* Shape rotation + float */
                shapes.forEach(m => {
                    m.rotation.x += m.userData.rs;
                    m.rotation.y += m.userData.rs * 1.6;
                    m.position.y  = m.userData.iy + Math.sin(t * m.userData.fs) * m.userData.fa;
                });

                /* Camera parallax */
                camera.position.x += (mx * 2.5 - camera.position.x) * 0.025;
                camera.position.y += (-my * 1.8 - camera.position.y) * 0.025;
                camera.lookAt(scene.position);

                renderer.render(scene, camera);
            }
            tick();
        }

        /* ── Scroll-reveal for cards ── */
        function initReveal() {
            const els = document.querySelectorAll('.hiw-reveal');
            if (!els.length) return;
            const obs = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const el = entry.target;
                        const delay = el.style.getPropertyValue('--hiw-delay') || '0ms';
                        setTimeout(() => {
                            el.style.opacity    = '1';
                            el.style.transform  = 'translateY(0)';
                        }, parseInt(delay));
                        obs.unobserve(el);
                    }
                });
            }, { threshold: 0.15 });
            els.forEach(el => {
                el.style.opacity   = '0';
                el.style.transform = 'translateY(40px)';
                el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                obs.observe(el);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => { init(); initReveal(); });
        } else {
            init(); initReveal();
        }
    })();
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
