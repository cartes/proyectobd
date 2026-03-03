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
    <title>Blog Big-dad: Lifestyle, Consejos y Relaciones Exclusivas</title>
    <meta name="description"
        content="Descubre artículos, consejos y noticias sobre relaciones sugar, lifestyle premium y citas exclusivas en Latinoamérica." />
    <meta name="keywords"
        content="sugar dating blog, lifestyle premium, relaciones sugar, sugar daddy latinoamerica, sugar baby consejos" />
    <meta name="author" content="Big-dad" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ route('blog.index') }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('blog.index') }}" />
    <meta property="og:title" content="Blog Big-dad: Lifestyle, Consejos y Relaciones Exclusivas" />
    <meta property="og:description"
        content="Descubre artículos, consejos y noticias sobre relaciones sugar, lifestyle premium y citas exclusivas en Latinoamérica." />
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ route('blog.index') }}" />
    <meta name="twitter:title" content="Blog Big-dad: Lifestyle, Consejos y Relaciones Exclusivas" />
    <meta name="twitter:description"
        content="Descubre artículos, consejos y noticias sobre relaciones sugar, lifestyle premium y citas exclusivas en Latinoamérica." />
    <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}" />

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Blog",
        "name": "Blog Big-dad",
        "description": "Artículos, consejos y noticias sobre relaciones sugar, lifestyle premium y citas exclusivas en Latinoamérica.",
        "url": "{{ route('blog.index') }}",
        "publisher": {
            "@@type": "Organization",
            "name": "Big-dad",
            "url": "{{ url('/') }}",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('favicon.png') }}"
            }
        }
    }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&family=Playfair+Display:ital,wght@0,600;1,600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-slate-900 text-white antialiased overflow-x-hidden font-outfit" x-data="homePage()">

    <!-- Sticky Navigation (Copied from welcome) -->
    <nav x-ref="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300"
        :class="isScrolled ? 'bg-slate-900/90 backdrop-blur-md shadow-lg py-4' : 'bg-slate-900/50 backdrop-blur-sm py-6'">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <a href="/" class="text-2xl font-black tracking-tighter hover:scale-105 transition-transform">
                BIG-<span class="text-pink-500">DAD</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-300">
                <a href="{{ route('plans.public') }}" class="hover:text-pink-400 transition-colors">Planes</a>
                <a href="{{ route('blog.index') }}"
                    class="text-white hover:text-pink-400 transition-colors border-b-2 border-pink-500">Blog</a>
                <a href="/#como-funciona" class="hover:text-pink-400 transition-colors">Cómo Funciona</a>
                <a href="/#beneficios" class="hover:text-pink-400 transition-colors">Beneficios</a>
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

    <!-- Header Section -->
    <div class="relative pt-40 pb-20 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-hero-pattern opacity-10"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/90 to-slate-900"></div>

        <div
            class="absolute top-1/4 -right-20 w-[600px] h-[600px] bg-pink-600/20 rounded-full blur-[120px] animate-blob">
        </div>
        <div
            class="absolute bottom-1/4 -left-20 w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[120px] animate-blob animation-delay-2000">
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <span
                class="inline-block py-1 px-3 rounded-full bg-pink-500/10 border border-pink-500/30 text-pink-400 text-xs font-bold uppercase tracking-widest mb-6">
                📚 Lifestyle & Tips
            </span>
            <h1 class="text-5xl md:text-6xl font-black mb-6 leading-tight">
                El Blog de <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-indigo-400">Big-Dad</span>
            </h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto font-light">
                Descubre artículos exclusivos sobre el estilo de vida Sugar, consejos para citas exitosas y novedades de
                nuestra comunidad.
            </p>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <section class="py-12 bg-slate-900">
        <div class="container mx-auto px-6">
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <article
                            class="group relative rounded-3xl overflow-hidden h-[450px] transition-all hover:-translate-y-2 hover:shadow-2xl hover:shadow-pink-500/20">
                            <!-- Background Image -->
                            <div class="absolute inset-0">
                                @if ($post->featured_image)
                                    <img src="{{ asset('app-media/' . $post->featured_image) }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-700"></div>
                                @endif
                                <!-- Overlay Gradient -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/60 to-transparent opacity-90 group-hover:opacity-80 transition-opacity">
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="relative h-full p-8 flex flex-col justify-end">
                                <!-- Tag -->
                                <div class="mb-4">
                                    @if ($post->category)
                                        <a href="{{ route('blog.category', $post->category->slug) }}"
                                            class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-xs font-bold text-white uppercase tracking-wider hover:bg-pink-500 hover:border-pink-500 transition-colors">
                                            {{ $post->category->name }}
                                        </a>
                                    @endif
                                </div>

                                <h2
                                    class="text-2xl font-bold text-white mb-3 leading-tight group-hover:text-pink-400 transition-colors">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h2>

                                <div
                                    class="flex items-center justify-between text-slate-300 text-sm border-t border-white/10 pt-4 mt-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gradient-to-r from-pink-500 to-purple-600 flex items-center justify-center text-[10px] font-bold text-white">
                                            {{ substr($post->author?->name ?? 'BD', 0, 1) }}
                                        </div>
                                        <span>{{ $post->author?->name ?? 'Big-Dad' }}</span>
                                    </div>
                                    <span>{{ $post->published_at?->format('d M') ?? '' }}</span>
                                </div>
                            </div>

                            <!-- Full Link -->
                            <a href="{{ route('blog.show', $post->slug) }}" class="absolute inset-0 z-10"
                                aria-label="Leer artículo"></a>
                        </article>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-slate-800/30 rounded-3xl border border-white/5">
                    <div class="text-6xl mb-6">📝</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Próximamente</h3>
                    <p class="text-slate-400">Estamos preparando contenido increíble para ti.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer (Copied from welcome) -->
    <footer class="bg-slate-950 py-16 border-t border-white/5 text-sm text-slate-400 mt-20">
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
                    <li><a href="{{ route('blog.index') }}" class="hover:text-pink-500 transition-colors">Blog de
                            Estilo de Vida</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('legal.terms') }}" class="hover:text-pink-500 transition-colors">Términos y
                            Condiciones</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="hover:text-pink-500 transition-colors">Política
                            de Privacidad</a></li>
                    <li><a href="{{ route('legal.rules') }}" class="hover:text-pink-500 transition-colors">Reglas de
                            la Comunidad</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Ayuda</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="hover:text-pink-500 transition-colors">Soporte 24/7</a></li>
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
                init() {
                    window.addEventListener('scroll', () => {
                        this.isScrolled = window.scrollY > 50;
                    });
                }
            }
        }
    </script>

    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        @keyframes blob {
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
    </style>
</body>

</html>
