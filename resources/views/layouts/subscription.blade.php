<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Big-dad') }} - Planes Premium</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900|inter:400,500,600,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 to-slate-950">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-black bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent"
                        style="font-family: 'Outfit', sans-serif;">
                        Big-Dad
                    </h1>
                </div>
            </div>

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
        </div>

        <!-- Main Content -->
        <div class="md:pl-64">
            <!-- Top Bar -->
            <div
                class="sticky top-0 z-30 flex h-16 items-center gap-x-4 border-b border-purple-500/20 bg-purple-950/50 px-6 backdrop-blur-sm">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold text-white">@yield('page-title', 'Planes Premium')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-purple-300">← Volver</a>
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