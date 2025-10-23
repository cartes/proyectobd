<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Big-dad') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400|inter:400,500,600,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out md:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">

            <!-- Logo -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent"
                    style="font-family: 'Playfair Display', cursive;">
                    Big-dad
                </h1>
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

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 md:hidden"
            @click="sidebarOpen = false"></div>

        <!-- Main Content -->
        <div class="md:pl-64">
            <!-- Top Bar -->
            <div
                class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <!-- Mobile sidebar toggle -->
                <button type="button" class="md:hidden -m-2.5 p-2.5 text-gray-700 hover:text-gray-900"
                    @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Page Title -->
                <div class="flex-1">
                    <h1 class="text-xl font-semibold leading-6 text-gray-900">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>

                <!-- Top Actions -->
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <!-- Notifications -->
                    <button type="button"
                        class="relative rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <span class="sr-only">Ver notificaciones</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute -top-1 -right-1 h-2 w-2 rounded-full bg-red-500"></span>
                    </button>

                    <!-- Premium Badge -->
                    @if (!Auth::user()->isPremium())
                        <a href="#"
                            class="text-xs bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-1 rounded-full font-medium hover:from-amber-500 hover:to-amber-600 transition-colors">
                            ⭐ Upgrade Premium
                        </a>
                    @else
                        <span
                            class="text-xs bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-1 rounded-full font-medium">
                            👑 Premium
                        </span>
                    @endif
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-6">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')

</body>

</html>
