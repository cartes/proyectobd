<div class="space-y-2 p-4">
    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
        class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600' }}">
        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
        </svg>
        Dashboard
    </a>

    <!-- My Profile -->
    <a href="{{ route('profile.show') }}"
        class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('profile.show') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600' }}">
        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Mi Perfil
    </a>

    <!-- My Photos -->
    <a href="{{ route('profile.photos.index') }}"
        class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('profile.photos.index') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600' }}">
        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Mis Fotos
    </a>

    <!-- Discover Daddies - DESTACADO CON BADGE -->
    <a href="{{ route('discover.index') }}"
        class="group relative flex items-center px-4 py-4 text-sm font-black rounded-2xl transition-all duration-300 overflow-visible animate-pulse-slow
        {{ request()->routeIs('discover.index')
    ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-xl shadow-purple-500/30 scale-105'
    : 'bg-gradient-to-r from-purple-500/10 to-indigo-500/10 text-purple-600 hover:from-purple-500/20 hover:to-indigo-500/20 hover:scale-105 hover:shadow-lg hover:shadow-purple-500/20 border border-purple-500/20' }}">

        <!-- Animated background -->
        <div
            class="absolute inset-0 bg-gradient-to-r from-purple-400/0 via-white/10 to-purple-400/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000">
        </div>

        <!-- Pulsing badge -->
        <div class="absolute -top-1 -right-1 z-20">
            <span class="relative flex h-3 w-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-purple-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-purple-500"></span>
            </span>
        </div>

        <svg class="mr-3 h-6 w-6 transition-transform group-hover:scale-110 group-hover:rotate-12 relative z-10"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z">
            </path>
        </svg>
        <div class="flex flex-col relative z-10">
            <span>Descubrir Daddies</span>
            @php
                // Contar solo perfiles públicos y activos, excluyendo al usuario actual
                $availableCount = \App\Models\User::where('user_type', 'sugar_daddy')
                    ->where('is_active', true)
                    ->where('id', '!=', auth()->id())
                    ->whereHas('profileDetail', function($q) {
                        $q->where('is_private', false);
                    })
                    ->count();
            @endphp
            <span class="text-[9px] font-bold opacity-80">{{ $availableCount }} disponibles ahora</span>
        </div>
        <span class="ml-auto text-xl relative z-10">👑</span>
    </a>

    <!-- Mensajes -->
    <a href="{{ route('chat.index') }}"
        class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('chat.*') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600' }}">
        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span>Mensajes</span>

        @php
            $unreadCount = auth()->user()->receivedMessages()->where('is_read', false)->count();
        @endphp

        @if ($unreadCount > 0)
            <span
                class="ml-auto inline-flex items-center justify-center px-2 py-1 rounded-full text-[10px] font-black leading-none bg-gradient-to-r from-pink-500 to-orange-500 text-white shadow-sm">
                {{ $unreadCount }}
            </span>
        @endif
    </a>

    <!-- Wishlist -->
    <a href="{{ route('discover.favorites') }}"
        class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('discover.favorites') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600' }}">
        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
        </svg>
        Favoritos
    </a>

    <div class="pt-4 pb-2 px-4">
        <div class="h-px bg-gray-100 w-full"></div>
    </div>

    <!-- Profile Enhancement -->
    <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Perfil</p>

    <a href="{{ route('profile.edit') }}"
        class="group flex items-center px-4 py-3 text-sm font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('profile.edit') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-500 hover:bg-pink-50 hover:text-pink-600' }}">
        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
        </svg>
        Configuración
    </a>
</div>