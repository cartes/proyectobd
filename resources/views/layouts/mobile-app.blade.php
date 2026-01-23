<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Big-Dad') - {{ config('app.name') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&family=Outfit:wght@400;700;900&display=swap"
        rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $user = auth()->user();
    $themeClass = $user->user_type === 'sugar_daddy' ? 'theme-sd' : 'theme-sb';
@endphp

<body class="antialiased overflow-x-hidden font-sans {{ $themeClass }}" style="background: var(--theme-bg);">

    {{-- Layout Container --}}
    <div class="flex min-h-screen">

        {{-- SIDEBAR - Solo Desktop (≥768px) --}}
        <aside
            class="hidden md:flex md:flex-col md:w-64 lg:w-72 fixed h-screen z-40 border-r border-white/5 shadow-2xl transition-colors duration-500"
            style="background: var(--theme-sidebar);">

            {{-- Logo / Branding Premium --}}
            <div class="p-8 border-b border-white/5">
                <h1 class="text-3xl font-black uppercase tracking-tighter text-theme-title">
                    Big-Dad
                </h1>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500 mt-2">
                    @if ($user->user_type === 'sugar_daddy') Sugar Daddy @else Sugar Baby @endif
                </p>
            </div>

            {{-- Perfil User Premium --}}
            <div class="p-6">
                <a href="{{ route('profile.show') }}"
                    class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 transition-all group border border-transparent hover:border-white/10">
                    @if ($user->primaryPhoto)
                        <img src="{{ $user->primaryPhoto->url }}" alt="{{ $user->name }}"
                            class="w-12 h-12 rounded-xl object-cover ring-2 ring-white/10 group-hover:ring-teal-400 transition-all">
                    @else
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg"
                            style="background: var(--theme-gradient);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-gray-900 theme-sd:text-white truncate text-sm">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Mi Perfil</p>
                    </div>
                </a>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex-1 px-6 space-y-2 overflow-y-auto">
                {{-- Explorar --}}
                <a href="{{ route('discover.index') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all
                          {{ request()->routeIs('discover.index')
    ? 'bg-white/10 text-white shadow-xl border border-white/20'
    : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Explorar</span>
                </a>

                {{-- Mis Favoritos --}}
                <a href="{{ route('discover.favorites') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all
                          {{ request()->routeIs('discover.favorites')
    ? 'bg-white/10 text-white shadow-xl border border-white/20'
    : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Favoritos</span>
                </a>

                {{-- Matches --}}
                <a href="{{ route('matches.index') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all
                          {{ request()->routeIs('matches.*')
    ? 'bg-white/10 text-white shadow-xl border border-white/20'
    : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <span>Matches</span>
                </a>

                {{-- Mensajes --}}
                <a href="{{ route('chat.index') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all
                          {{ request()->routeIs('chat.*')
    ? 'bg-white/10 text-white shadow-xl border border-white/20'
    : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <span>Mensajes</span>
                </a>

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all
                          {{ request()->routeIs('dashboard')
    ? 'bg-white/10 text-white shadow-xl border border-white/20'
    : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </nav>

            {{-- Logout Button Premium --}}
            <div class="p-8 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-red-400 hover:bg-red-500/10 transition-all border border-transparent hover:border-red-500/20 group">
                        <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Salir</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <div class="flex-1 md:ml-64 lg:ml-72 transition-all duration-500">
            <main class="pb-20 md:pb-0 min-h-screen">
                <div class="md:max-w-none">
                    @yield('content')
                </div>
            </main>

            {{-- BOTTOM NAVIGATION - Solo Mobile (<768px) --}} <nav
                class="md:hidden fixed bottom-0 left-0 right-0 backdrop-blur-xl border-t border-white/10 shadow-[0_-10px_40px_rgba(0,0,0,0.3)] z-50 transition-colors duration-500"
                style="background: rgba(var(--primary-rgb, 79, 70, 229), 0.1);">
                <div class="flex items-center justify-around h-20 px-4">

                    {{-- Link helper --}}
                    @php
                        $navItemClass = "flex flex-col items-center justify-center flex-1 h-full transition-all duration-300 gap-1";
                        $activeClass = "text-white scale-110";
                        $inactiveClass = "text-gray-500 hover:text-gray-300";
                    @endphp

                    <a href="{{ route('discover.index') }}"
                        class="{{ $navItemClass }} {{ request()->routeIs('discover.index') ? $activeClass : $inactiveClass }}">
                        <div
                            class="p-2 rounded-xl {{ request()->routeIs('discover.index') ? 'bg-white/10 shadow-lg' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Explorar</span>
                    </a>

                    <a href="{{ route('discover.favorites') }}"
                        class="{{ $navItemClass }} {{ request()->routeIs('discover.favorites') ? $activeClass : $inactiveClass }}">
                        <div
                            class="p-2 rounded-xl {{ request()->routeIs('discover.favorites') ? 'bg-white/10 shadow-lg' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Favoritos</span>
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="flex flex-col items-center justify-center flex-1 h-full -top-6 relative">
                        <div class="w-16 h-16 rounded-full shadow-[0_10px_25px_rgba(0,0,0,0.4)] flex items-center justify-center border-4 border-white/10 transform transition-transform hover:scale-110 active:scale-95"
                            style="background: var(--theme-gradient);">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('chat.index') }}"
                        class="{{ $navItemClass }} {{ request()->routeIs('chat.*') ? $activeClass : $inactiveClass }}">
                        <div class="p-2 rounded-xl {{ request()->routeIs('chat.*') ? 'bg-white/10 shadow-lg' : '' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Chat</span>
                    </a>

                    <a href="{{ route('profile.show') }}"
                        class="{{ $navItemClass }} {{ request()->routeIs('profile.*') ? $activeClass : $inactiveClass }}">
                        <div
                            class="p-2 rounded-xl {{ request()->routeIs('profile.*') ? 'bg-white/10 shadow-lg' : '' }}">
                            @if ($user->primaryPhoto)
                                <img src="{{ $user->primaryPhoto->url }}" class="w-6 h-6 rounded-lg object-cover">
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            @endif
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-tighter">Perfil</span>
                    </a>

                </div>
                </nav>
        </div>
    </div>

    @stack('scripts')
</body>

</html>