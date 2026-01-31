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
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,700,900|inter:400,500,600,700,800,900|outfit:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Analytics --}}
    @php
        $gaId = \App\Models\BlogSettings::get('google_analytics_id');
        $gtmId = \App\Models\BlogSettings::get('google_tag_manager_id');
    @endphp

    @if($gaId)
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}');
    </script>
    @endif

    @if($gtmId)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
    @endif

    {{-- Custom Header Scripts --}}
    @php
        $headerScripts = \App\Models\BlogSettings::get('header_scripts');
    @endphp
    @if($headerScripts)
        {!! $headerScripts !!}
    @endif

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50">
    @if($gtmId)
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- Blog Header --}}
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent" style="font-family: 'Outfit', sans-serif;">
                            Big-Dad
                        </div>
                    </a>
                    <span class="ml-4 text-gray-400">|</span>
                    <a href="{{ route('blog.index') }}" class="ml-4 text-lg font-semibold text-gray-700 hover:text-amber-600 transition-colors">
                        Blog
                    </a>
                </div>

                {{-- Navigation --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-amber-600 font-medium transition-colors {{ request()->routeIs('blog.index') ? 'text-amber-600' : '' }}">
                        Inicio
                    </a>
                    
                    @php
                        $categories = \App\Models\BlogCategory::active()->get();
                    @endphp
                    
                    @if($categories->count() > 0)
                        <div class="relative group">
                            <button class="text-gray-600 hover:text-amber-600 font-medium transition-colors flex items-center gap-1">
                                Categorías
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                @foreach($categories as $category)
                                    <a href="{{ route('blog.category', $category->slug) }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-600 first:rounded-t-lg last:rounded-b-lg">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-amber-600 font-medium transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-amber-600 font-medium transition-colors">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-4 py-2 rounded-lg font-semibold hover:from-amber-600 hover:to-orange-700 transition-all shadow-md">
                            Únete
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" x-data @click="$dispatch('toggle-mobile-menu')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </nav>
    </header>

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
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                            </svg>
                        </div>
                        <div class="text-2xl font-black text-white" style="font-family: 'Outfit', sans-serif;">
                            Big-Dad
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Tu plataforma de conexiones premium. Descubre artículos, consejos y las últimas noticias en nuestro blog.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('blog.index') }}" class="hover:text-amber-500 transition-colors">Blog</a></li>
                        <li><a href="{{ route('welcome') }}" class="hover:text-amber-500 transition-colors">Inicio</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-amber-500 transition-colors">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-amber-500 transition-colors">Iniciar Sesión</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-amber-500 transition-colors">Registrarse</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h3 class="text-white font-semibold mb-4">Categorías</h3>
                    <ul class="space-y-2">
                        @foreach($categories->take(5) as $category)
                            <li>
                                <a href="{{ route('blog.category', $category->slug) }}" class="hover:text-amber-500 transition-colors">
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
        $footerScripts = \App\Models\BlogSettings::get('footer_scripts');
    @endphp
    @if($footerScripts)
        {!! $footerScripts !!}
    @endif

    @stack('scripts')
</body>

</html>
