<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Big-dad - Conexiones Premium</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&family=Permanent+Marker&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-hero text-white antialiased overflow-x-hidden" x-data="homePage()">

    <!-- Hero Section -->
    <section class="relative h-screen flex flex-col items-center justify-center overflow-hidden">

        <!-- Floating Hearts Background -->
        <div class="absolute inset-0 z-0">
            <template x-for="i in 20">
                <div class="heart-particle text-white/10"
                    :style="`left: ${Math.random()*100}%; animation-duration: ${10 + Math.random()*20}s; animation-delay: -${Math.random()*20}s; font-size: ${20 + Math.random()*40}px; filter: blur(${Math.random()*3}px);`"
                    x-text="Math.random() > 0.5 ? '❤️' : '💖'">
                </div>
            </template>
        </div>

        <!-- Subtle Glows -->
        <div class="absolute top-1/4 -left-20 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-pink-600/20 rounded-full blur-[120px]"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
            <h1 class="logo-graffiti animate-pulse-glow mb-2">BIG-DAD</h1>

            <div class="hero-subtitle mb-12 flex flex-col items-center justify-center">
                <div class="text-2xl md:text-3xl font-light uppercase tracking-[0.3em] overflow-hidden">
                    <span x-text="phrases[currentPhraseIndex]"
                        class="inline-block transition-all duration-700 transform"
                        :class="animating ? 'opacity-0 translate-y-4' : 'opacity-100 translate-y-0'">
                    </span>
                </div>
                <div class="w-12 h-[2px] bg-gradient-to-r from-pink-500 to-purple-600 mt-4"></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="{{ route('register') }}"
                    class="group relative px-10 py-5 bg-gradient-to-r from-pink-500 to-rose-600 rounded-2xl font-black uppercase tracking-widest text-sm shadow-[0_0_30px_rgba(255,51,102,0.3)] hover:scale-105 transition-all duration-300">
                    <span class="relative z-10 text-white">Únete Ahora</span>
                </a>

                <a href="{{ route('login') }}"
                    class="group relative px-10 py-5 bg-purple-600/30 backdrop-blur-lg border-2 border-purple-500/50 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-purple-600/50 transition-all duration-300 text-white shadow-xl">
                    <span>Ingresar</span>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-32 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7" />
            </svg>
        </div>
    </section>

    <!-- Sticky Navigation Anchor -->
    <div id="sticky-anchor" class="w-full h-px absolute pointer-events-none" style="bottom: 100px;"></div>

    <nav x-ref="navbar" class="nav-sticky-wrapper nav-sticky"
        :class="isSticky ? 'fixed top-0 left-0 is-pinned' : 'relative'">
        <div class="max-w-7xl mx-auto px-8 py-6 flex items-center justify-between">
            <div class="text-2xl font-black tracking-tighter"
                :class="isSticky ? 'opacity-100' : 'opacity-0 transition-opacity'">
                BIG-<span class="text-pink-500">DAD</span>
            </div>

            <div class="hidden md:flex items-center gap-12 text-sm font-bold uppercase tracking-widest text-gray-200">
                <a href="#" class="hover:text-pink-400 transition-colors">Sobre Nosotros</a>
                <a href="#" class="hover:text-pink-400 transition-colors">Servicios</a>
                <a href="#" class="hover:text-pink-400 transition-colors">Membresía</a>
                <a href="#" class="hover:text-pink-400 transition-colors">Blog</a>
                <a href="#" class="hover:text-pink-400 transition-colors">Contacto</a>
            </div>

            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-sm font-bold text-pink-500 border-b-2 border-pink-500/0 hover:border-pink-500 transition-all pb-1">Mi
                        Panel</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold hover:text-pink-500 transition-colors">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 bg-pink-500 rounded-xl text-sm font-bold hover:bg-pink-600 transition-all">Empieza
                        Gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Landing Body (Placeholder for long scrolling) -->
    <main class="relative bg-hero py-24 min-h-screen">
        <div class="max-w-5xl mx-auto px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10 hover:border-pink-500/30 transition-all">
                    <div class="text-4xl mb-6">🥂</div>
                    <h3 class="text-xl font-bold mb-4">Experiencias Luxury</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Disfruta de citas exclusivas en los lugares más
                        prestigiosos del mundo.</p>
                </div>
                <div
                    class="bg-white/5 p-8 rounded-3xl border border-white/10 hover:border-purple-500/30 transition-all">
                    <div class="text-4xl mb-6">💎</div>
                    <h3 class="text-xl font-bold mb-4">Elite Networking</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Conecta con personas que comparten tu estilo de
                        vida y ambiciones.</p>
                </div>
                <div class="bg-white/5 p-8 rounded-3xl border border-white/10 hover:border-amber-500/30 transition-all">
                    <div class="text-4xl mb-6">🗝️</div>
                    <h3 class="text-xl font-bold mb-4">Privacidad Total</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Seguridad y discreción garantizada para todos
                        nuestros miembros.</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        function homePage() {
            return {
                phrases: [
                    "Conexiones Premium",
                    "Experiencias Exclusivas",
                    "El Match Perfecto",
                    "Donde el Estilo nace"
                ],
                currentPhraseIndex: 0,
                animating: false,
                isSticky: false,

                init() {
                    // Phrase Rotation
                    setInterval(() => {
                        this.animating = true;
                        setTimeout(() => {
                            this.currentPhraseIndex = (this.currentPhraseIndex + 1) % this.phrases.length;
                            this.animating = false;
                        }, 700);
                    }, 4000);

                    // Sticky Nav Observer
                    const observer = new IntersectionObserver((entries) => {
                        this.isSticky = !entries[0].isIntersecting;
                    }, { threshold: [1] });

                    observer.observe(document.getElementById('sticky-anchor'));

                    // Cursor Glow Interaction
                    window.addEventListener('mousemove', (e) => {
                        document.body.style.setProperty('--x', e.clientX + 'px');
                        document.body.style.setProperty('--y', e.clientY + 'px');
                    });
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Cursor Glow (Optional high-end touch) */
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