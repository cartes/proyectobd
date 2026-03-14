<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', 'Big-dad | Sugar Dating en Latinoamérica')</title>
    <meta name="description"
        content="@yield('meta_description', 'Big-dad, la plataforma líder de Sugar Dating en Latinoamérica.')">
    <meta name="keywords"
        content="@yield('meta_keywords', 'big-dad, sugar dating, sugar daddy, sugar baby, citas exclusivas')">
    <meta name="author" content="@yield('meta_author', 'Big-dad')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', trim(strip_tags($__env->yieldContent('meta_title', 'Big-dad'))))">
    <meta property="og:description"
        content="@yield('og_description', trim(strip_tags($__env->yieldContent('meta_description', 'Big-dad, la plataforma líder de Sugar Dating en Latinoamérica.'))))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:locale" content="@yield('og_locale', 'es_CL')">
    <meta property="og:site_name" content="Big-dad">

    <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
    <meta name="twitter:title"
        content="@yield('twitter_title', trim(strip_tags($__env->yieldContent('meta_title', 'Big-dad'))))">
    <meta name="twitter:description"
        content="@yield('twitter_description', trim(strip_tags($__env->yieldContent('meta_description', 'Big-dad, la plataforma líder de Sugar Dating en Latinoamérica.'))))">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-image.jpg'))">
    @yield('head_meta')
    @yield('structured_data')

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900|montserrat:300,400,500,600,700,800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

<body class="bg-gray-50 font-sans antialiased">
    <nav class="fixed top-0 left-0 z-50 w-full bg-slate-900/90 py-4 shadow-lg backdrop-blur-md transition-all duration-300">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6">
            <a href="{{ route('welcome') }}" class="text-2xl font-black tracking-tighter transition-transform hover:scale-105">
                BIG-<span class="text-pink-500">DAD</span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-bold uppercase tracking-widest text-slate-300 md:flex">
                <a href="{{ route('plans.public') }}"
                    class="{{ request()->routeIs('plans.public', 'subscription.plans') ? 'border-b-2 border-pink-500 text-white' : 'hover:text-pink-400' }} transition-colors">Planes</a>
                <a href="{{ route('blog.index') }}"
                    class="{{ request()->routeIs('blog.*') ? 'border-b-2 border-pink-500 text-white' : 'hover:text-pink-400' }} transition-colors">Blog</a>
                <a href="{{ route('como-funciona') }}"
                    class="{{ request()->routeIs('como-funciona') ? 'border-b-2 border-pink-500 text-white' : 'hover:text-pink-400' }} transition-colors">Cómo Funciona</a>
                <a href="{{ route('about.index') }}"
                    class="{{ request()->routeIs('about.index') ? 'border-b-2 border-pink-500 text-white' : 'hover:text-pink-400' }} transition-colors">Quiénes Somos</a>
                <a href="{{ route('welcome') }}#beneficios" class="transition-colors hover:text-pink-400">Beneficios</a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-pink-500/30 transition-all hover:shadow-pink-500/50">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="rounded-full border-2 border-pink-500 px-5 py-2.5 text-sm font-bold text-white transition-all hover:bg-pink-500/10">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-gradient-to-r from-pink-500 to-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-pink-500/30 transition-all hover:shadow-pink-500/50">
                        Únete Ahora
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="h-20"></div>

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>
