<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('meta_title', config('app.name', 'Big-dad') . ' - Blog')</title>
    <meta name="description" content="@yield('meta_description', 'Descubre artículos, consejos y noticias en nuestro blog.')">
    <meta name="keywords" content="@yield('meta_keywords', 'blog, artículos, noticias')">
    <meta name="author" content="@yield('author', config('app.name'))">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', config('app.name') . ' - Blog')">
    <meta property="og:description" content="@yield('og_description', 'Descubre artículos, consejos y noticias en nuestro blog.')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name') . ' - Blog')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Descubre artículos, consejos y noticias en nuestro blog.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-default.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    {{-- Fonts --}}
    <link
        href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|inter:400,500,600,700,800,900|outfit:400,500,600,700,800,900&display=swap"
        rel="stylesheet" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Analytics --}}
    @php
        $gaId = $blogSettings['google_analytics_id'] ?? null;
        $gtmId = $blogSettings['google_tag_manager_id'] ?? null;
    @endphp

    @if ($gaId)
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif

    @if ($gtmId)
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');
        </script>
    @endif

    {{-- Custom Header Scripts --}}
    @php
        $headerScripts = $blogSettings['header_scripts'] ?? null;
    @endphp
    @if ($headerScripts)
        {!! $headerScripts !!}
    @endif

    @stack('styles')
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


<body class="font-sans antialiased bg-gray-50">
    @if ($gtmId)
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0"
                width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- Sticky Navigation (matching welcome/blog index) --}}
    <nav
        class="fixed top-0 left-0 w-full z-50 bg-slate-900/90 backdrop-blur-md shadow-lg py-4 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <a href="{{ route('welcome') }}"
                class="text-2xl font-black tracking-tighter hover:scale-105 transition-transform">
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
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full text-sm font-bold shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2.5 border-2 border-pink-500 rounded-full text-sm font-bold hover:bg-pink-500/10 transition-all">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full text-sm font-bold shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 transition-all">
                        Únete Ahora
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <button class="md:hidden p-2 rounded-lg text-white hover:bg-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Spacer for fixed nav --}}
    <div class="h-20"></div>

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                {{-- About --}}
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white" style="font-family: 'Outfit', sans-serif;">
                            Big-Dad
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Tu plataforma de conexiones premium. Descubre artículos, consejos y las últimas noticias en
                        nuestro blog.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('blog.index') }}" class="hover:text-amber-500 transition-colors">Blog</a>
                        </li>
                        <li><a href="{{ route('welcome') }}" class="hover:text-amber-500 transition-colors">Inicio</a>
                        </li>
                        @auth
                            <li><a href="{{ route('dashboard') }}"
                                    class="hover:text-amber-500 transition-colors">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-amber-500 transition-colors">Iniciar
                                    Sesión</a></li>
                            <li><a href="{{ route('register') }}"
                                    class="hover:text-amber-500 transition-colors">Registrarse</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Categorías</h3>
                    <ul class="space-y-2">
                        @foreach ($categories->take(5) as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}"
                                    class="hover:text-amber-500 transition-colors">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    {{-- Custom Footer Scripts --}}
    @php
        $footerScripts = $blogSettings['footer_scripts'] ?? null;
    @endphp
    @if ($footerScripts)
        {!! $footerScripts !!}
    @endif

    @stack('scripts')
</body>

</html>
