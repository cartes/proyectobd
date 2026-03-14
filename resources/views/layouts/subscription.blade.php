<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Big-dad') }} - Planes Premium</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900|montserrat:300,400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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


<body class="font-sans antialiased">
    @php
        $sidebarHidden = $hideSidebar ?? false;
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-slate-950">
        @if (!$sidebarHidden)
            <!-- Sidebar -->
            <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                    <a href="{{ route('welcome') }}"
                        class="text-2xl font-black tracking-tighter text-slate-950 drop-shadow-sm transition-transform hover:scale-105">
                        BIG-<span class="text-pink-600">DAD</span>
                    </a>
                </div>

                @auth
                    <!-- User Info -->
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r {{ Auth::user()->isAdmin() ? 'from-red-500 to-red-600' : (Auth::user()->isSugarDaddy() ? 'from-purple-500 to-purple-600' : 'from-pink-500 to-pink-600') }} rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ Auth::user()->name }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @if (Auth::user()->isAdmin())
                                        🛡️ Administrador
                                    @elseif(Auth::user()->isSugarDaddy())
                                        👑 Sugar Daddy
                                    @else
                                        💎 Sugar Baby
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                        @if (Auth::user()->isAdmin())
                            @include('components.navigation.admin')
                        @elseif(Auth::user()->isSugarDaddy())
                            @include('components.navigation.sugar-daddy')
                        @else
                            @include('components.navigation.sugar-baby')
                        @endif
                    </nav>

                    <!-- Logout -->
                    <div class="absolute bottom-0 w-full p-4 border-t border-gray-200 bg-white">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m0 0v3H7v2h10v3z"></path>
                                </svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Guest Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bienvenido</p>
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-3 text-gray-600 hover:text-purple-600 font-bold transition-all p-2 rounded-xl hover:bg-purple-50">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">👤</span>
                            Inicia Sesión
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex items-center gap-3 text-gray-600 hover:text-purple-600 font-bold transition-all p-2 rounded-xl hover:bg-purple-50">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">✨</span>
                            Regístrate ahora
                        </a>
                        <a href="{{ route('about.index') }}"
                            class="flex items-center gap-3 text-gray-600 hover:text-purple-600 font-bold transition-all p-2 rounded-xl hover:bg-purple-50">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">ℹ️</span>
                            Quiénes Somos
                        </a>
                    </nav>
                @endauth
            </div>
        @endif

        <!-- Main Content -->
        <div class="{{ $sidebarHidden ? '' : 'md:pl-64' }}">
            <!-- Top Bar -->
            <div
                class="sticky top-0 z-30 flex h-16 items-center gap-x-4 border-b border-purple-500/20 bg-purple-950/50 px-6 backdrop-blur-sm">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-white">@yield('page-title', 'Planes Premium')</h1>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-purple-300">← Volver</a>
                    @else
                        <a href="{{ route('welcome') }}" class="text-white hover:text-purple-300">← Inicio</a>
                    @endauth
                </div>
            </div>

            <!-- Page Content -->
            <main class="min-h-[calc(100vh-64px)]">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
