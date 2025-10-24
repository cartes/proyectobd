<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Big-Dad') - {{ config('app.name') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800,900&display=swap"
        rel="stylesheet" />

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- FONDO DE COLOR COMPLETO --}}

<body class="antialiased overflow-x-hidden bg-gradient-to-br from-purple-50 via-pink-50 to-rose-50">

    {{-- Layout Container: Flex en Desktop --}}
    <div class="flex min-h-screen">

        {{-- SIDEBAR - Solo Desktop (≥768px) --}}
        <aside
            class="hidden md:flex md:flex-col md:w-64 lg:w-72 bg-white border-r border-gray-200 shadow-lg fixed h-screen z-40">

            {{-- Logo / Branding --}}
            <div class="p-6 border-b border-gray-200">
                <h1
                    class="text-3xl font-playfair font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    Big-Dad
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if (auth()->user()->user_type === 'sugar_daddy')
                        Sugar Daddy
                    @else
                        Sugar Baby
                    @endif
                </p>
            </div>

            {{-- Perfil User --}}
            <div class="p-4 border-b border-gray-200">
                <a href="{{ route('profile.show') }}"
                    class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-all group">
                    @if (auth()->user()->primaryPhoto)
                        <img src="{{ auth()->user()->primaryPhoto->url }}" alt="{{ auth()->user()->name }}"
                            class="w-12 h-12 rounded-full object-cover ring-2 ring-purple-200 group-hover:ring-purple-400 transition-all">
                    @else
                        <div
                            class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">Ver mi perfil</p>
                    </div>
                </a>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

                {{-- Explorar --}}
                <a href="{{ route('discover.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all
                          {{ request()->routeIs('discover.*')
                              ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg'
                              : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Explorar</span>
                </a>

                {{-- Mis favoritos --}}
                {{-- Mis Favoritos --}}
                <a href="{{ route('discover.favorites') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all
          {{ request()->routeIs('discover.favorites')
              ? 'bg-gradient-to-r from-pink-600 to-rose-600 text-white shadow-lg'
              : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Mis Favoritos</span>
                    @php
                        $favCount = auth()->user()->likes()->count();
                    @endphp
                    @if ($favCount > 0)
                        <span class="ml-auto px-2 py-1 bg-pink-100 text-pink-700 text-xs font-bold rounded-full">
                            {{ $favCount }}
                        </span>
                    @endif
                </a>

                {{-- Matches --}}
                <button onclick="alert('🚀 Matches próximamente!')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-400 hover:bg-gray-50 transition-all text-left">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Matches</span>
                    <span class="ml-auto px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                        SOON
                    </span>
                </button>

                {{-- Mensajes --}}
                <button onclick="alert('💬 Mensajería en desarrollo!')"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-gray-400 hover:bg-gray-50 transition-all text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Mensajes</span>
                    <span class="ml-auto px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">
                        SOON
                    </span>
                </button>

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all
                          {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

            </nav>

            {{-- Logout Button --}}
            <div class="p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-red-600 hover:bg-red-50 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 md:ml-64 lg:ml-72">
            {{-- Contenido Principal --}}
            <main class="pb-20 md:pb-0 min-h-screen">
                <div class="md:max-w-none">
                    @yield('content')
                </div>
            </main>

            {{-- BOTÓN FLOTANTE - Solo Mobile (<768px) --}}
            <a href="{{ route('dashboard') }}"
                class="md:hidden fixed bottom-24 right-6 z-40 w-14 h-14 bg-gradient-to-br from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-full shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </a>

            {{-- BOTTOM NAVIGATION - Solo Mobile (<768px) --}}
            <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-2xl z-50">
                <div class="flex items-center justify-around h-16">

                    {{-- Explorar / Discovery --}}
                    <a href="{{ route('discover.index') }}"
                        class="flex flex-col items-center justify-center flex-1 h-full transition-all duration-200
                              {{ request()->routeIs('discover.*') ? 'text-purple-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="text-xs font-semibold">Explorar</span>
                    </a>

                    {{-- Mis Favoritos --}}
                    <a href="{{ route('discover.favorites') }}"
                        class="flex flex-col items-center justify-center flex-1 h-full transition-all duration-200
                              {{ request()->routeIs('discover.favorites') ? 'text-purple-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="text-xs font-semibold">Favoritos</span>
                    </a>

                    {{-- Matches --}}
                    <button onclick="alert('🚀 Función de Matches próximamente!')"
                        class="flex flex-col items-center justify-center flex-1 h-full transition-all duration-200 text-gray-400">
                        <div class="relative">
                            <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span
                                class="absolute -top-1 -right-2 px-1.5 py-0.5 bg-amber-400 text-white text-[8px] font-bold rounded-full">
                                SOON
                            </span>
                        </div>
                        <span class="text-xs font-semibold">Matches</span>
                    </button>

                    {{-- Mensajes --}}
                    <button onclick="alert('💬 Mensajería en desarrollo!')"
                        class="flex flex-col items-center justify-center flex-1 h-full transition-all duration-200 text-gray-400">
                        <div class="relative">
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span
                                class="absolute -top-1 -right-2 px-1.5 py-0.5 bg-amber-400 text-white text-[8px] font-bold rounded-full">
                                SOON
                            </span>
                        </div>
                        <span class="text-xs font-semibold">Mensajes</span>
                    </button>

                    {{-- Perfil --}}
                    <a href="{{ route('profile.show') }}"
                        class="flex flex-col items-center justify-center flex-1 h-full transition-all duration-200
                              {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        @if (auth()->user()->primaryPhoto)
                            <img src="{{ auth()->user()->primaryPhoto->url }}" alt="Perfil"
                                class="w-6 h-6 rounded-full object-cover mb-1 {{ request()->routeIs('profile.*') ? 'ring-2 ring-indigo-600' : '' }}">
                        @else
                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                        <span class="text-xs font-semibold">Perfil</span>
                    </a>

                </div>
            </nav>
        </div>
    </div>

    {{-- Scripts --}}
    @stack('scripts')
</body>

</html>
