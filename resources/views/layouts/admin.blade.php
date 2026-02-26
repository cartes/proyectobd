<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Big-Dad Admin') }} - Admin Panel</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* Custom scrollbar for admin */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .sidebar-link-active {
            background: rgba(236, 72, 153, 0.1);
            border-left: 4px solid #ec4899;
            color: #ec4899;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#05070a] text-gray-200 antialiased min-h-screen lg:flex" x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Header -->
    <div
        class="lg:hidden flex items-center justify-between px-6 py-4 bg-[#0c111d] border-b border-white/5 sticky top-0 z-50">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <div
                class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center font-black text-white text-base">
                B</div>
            <div class="font-outfit text-lg font-bold">Admin</div>
        </a>
        <button @click="mobileMenuOpen = !mobileMenuOpen"
            class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Overlay (Mobile) -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="w-72 bg-[#0c111d] border-r border-white/5 flex flex-col fixed h-full z-50 transition-transform duration-300 ease-in-out">
        <!-- Logo -->
        <div class="px-8 py-10">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-pink-500 rounded-xl flex items-center justify-center font-black text-white text-xl shadow-lg shadow-pink-500/20">
                    B</div>
                <div class="font-outfit text-xl font-bold tracking-tight">
                    BIG-<span class="text-pink-500">ADMIN</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-4 mt-2">Principal</div>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-4 mt-8">Gestión</div>

            <a href="{{ route('admin.moderation.users') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->fullUrlIs(route('admin.moderation.users')) ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Usuarios</span>
                    <span
                        class="text-[10px] bg-white/5 px-2 py-0.5 rounded-full text-gray-500">{{ \App\Models\User::count() }}</span>
                </div>
            </a>

            <a href="{{ route('admin.moderation.reports') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.moderation.reports*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Reportes</span>
                    <span
                        class="text-[10px] bg-rose-500/10 px-2 py-0.5 rounded-full text-rose-500">{{ \App\Models\Report::pending()->count() }}</span>
                </div>
            </a>

            <a href="{{ route('admin.moderation.photos.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.moderation.photos*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Fotos</span>
                    <span
                        class="text-[10px] bg-amber-500/10 px-2 py-0.5 rounded-full text-amber-500">{{ $adminStats['pending_photos_count'] ?? 0 }}</span>
                </div>
            </a>

            <a href="{{ route('admin.moderation.proposals.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.moderation.proposals*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="font-medium">Propuestas</span>
            </a>

            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-4 mt-8">Negocio</div>

            <a href="{{ route('admin.finance.transactions') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.finance.transactions') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Transacciones</span>
            </a>

            <a href="{{ route('admin.finance.reports') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.finance.reports') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                <span class="font-medium">Reportes Financieros</span>
            </a>

            <a href="{{ route('admin.plans.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.plans*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="font-medium">Planes y Precios</span>
            </a>

            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-4 mt-8">Sistema</div>

            <a href="{{ route('admin.system.config') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.system.config') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Configuración</span>
            </a>

            <a href="{{ route('admin.system.logs') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.system.logs') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span class="font-medium">Logs</span>
            </a>

            <a href="{{ route('admin.system.stats') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.system.stats') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
                <span class="font-medium">Estadísticas</span>
            </a>

            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-4 mt-8">Marketing</div>

            <a href="{{ route('admin.marketing.promotions') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.marketing.promotions') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                <span class="font-medium">Promociones</span>
            </a>

            <a href="{{ route('admin.marketing.notifications') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.marketing.notifications') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                    </path>
                </svg>
                <span class="font-medium">Notificaciones Push</span>
            </a>

            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-4 mt-8">Contenido</div>

            <a href="{{ route('admin.blog.posts.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 transition-all {{ request()->routeIs('admin.blog.*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Blog</span>
                    <span class="text-[10px] bg-white/5 px-2 py-0.5 rounded-full text-gray-500">
                        {{ \App\Models\BlogPost::count() }}
                    </span>
                </div>
            </a>
        </nav>

        <!-- User Profile (Admin) -->
        <div class="p-6 border-t border-white/5">
            <div class="flex items-center gap-3 px-4 py-3">
                <div
                    class="w-10 h-10 rounded-full bg-pink-500/20 flex items-center justify-center text-pink-500 font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">Super Admin</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 lg:ml-72 min-h-screen flex flex-col">
        <!-- Top Header (Desktop) -->
        <header
            class="hidden lg:flex h-20 border-b border-white/5 px-10 items-center justify-between sticky top-0 bg-[#05070a]/80 backdrop-blur-xl z-40">
            <div>
                <h1 class="text-2xl font-outfit font-bold tracking-tight">@yield('title', 'Admin Panel')</h1>
            </div>


            <div class="flex items-center gap-4">
                {{-- Campana de Notificaciones --}}
                @php $unreadNotifications = auth()->user()->unreadNotifications->take(10); @endphp
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center hover:bg-white/10 transition-colors relative"
                        aria-label="Notificaciones">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($unreadNotifications->count() > 0)
                            <span
                                class="absolute top-2 right-2 min-w-[16px] h-4 bg-pink-500 rounded-full text-[9px] font-bold text-white flex items-center justify-center px-0.5">
                                {{ $unreadNotifications->count() > 9 ? '9+' : $unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-96 bg-[#0c111d] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">

                        {{-- Header --}}
                        <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                            <div>
                                <h3 class="text-sm font-bold text-white">Notificaciones</h3>
                                @if ($unreadNotifications->count() > 0)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $unreadNotifications->count() }} sin
                                        leer</p>
                                @endif
                            </div>
                            @if ($unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs text-pink-400 hover:text-pink-300 transition-colors font-medium">
                                        Marcar todas leídas
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Lista --}}
                        <div class="max-h-80 overflow-y-auto divide-y divide-white/5">
                            @forelse ($unreadNotifications as $notification)
                                @php $data = $notification->data; @endphp
                                <div class="flex items-start gap-3 px-5 py-3.5 hover:bg-white/5 transition-colors">
                                    {{-- Avatar inicial --}}
                                    <div
                                        class="w-9 h-9 rounded-full bg-pink-500/20 text-pink-400 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ mb_substr($data['user_name'] ?? '?', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs text-white font-semibold truncate">
                                            {{ $data['user_name'] ?? 'Nuevo usuario' }}
                                            <span class="text-gray-500 font-normal ml-1">se registró</span>
                                        </p>
                                        <p class="text-[11px] text-gray-400 mt-0.5 truncate">
                                            {{ $data['user_type'] === 'sugar_daddy' ? '💰 Sugar Daddy' : '🍬 Sugar Baby' }}
                                            · {{ $data['user_email'] ?? '' }}
                                        </p>
                                        <p class="text-[10px] text-gray-600 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 mt-1">
                                        @if (isset($data['link']))
                                            <a href="{{ $data['link'] }}"
                                                class="text-[11px] text-pink-400 hover:text-pink-300 font-medium transition-colors">Ver</a>
                                        @endif
                                        <form method="POST"
                                            action="{{ route('admin.notifications.mark-read', $notification->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="text-gray-600 hover:text-gray-400 transition-colors"
                                                title="Marcar como leída">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="px-5 py-8 text-center">
                                    <p class="text-2xl mb-2">🎉</p>
                                    <p class="text-sm text-gray-500">Todo al día, sin notificaciones nuevas.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-white/10 mx-2"></div>
                <a href="{{ url('/') }}"
                    class="px-4 py-2 bg-white/5 rounded-xl text-sm font-semibold hover:bg-white/10 transition-colors">Volver
                    al Sitio</a>
            </div>
        </header>

        <!-- Content -->
        <div class="p-6 lg:p-10 flex-1">
            <div class="lg:hidden mb-10">
                <h2 class="text-3xl font-outfit font-bold tracking-tight">@yield('title', 'Admin Panel')</h2>
                <div class="h-1 w-12 bg-pink-500 rounded-full mt-2"></div>
            </div>
            @if (session('success'))
                <div
                    class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>

</html>
