@php
    $user = Auth::user();
    $profileCompletion = $data['profileCompletion'] ?? 85;
    $todayViews = $data['todayViews'] ?? 0;
    $newLikes = $data['newLikes'] ?? 0;
    $messageCount = $data['messageCount'] ?? 0;
    $unreadCount = $data['unreadCount'] ?? 0;
    $featuredDaddies = $data['featuredDaddies'] ?? [];
    $recentMessages = $data['recentMessages'] ?? [];
@endphp

<div class="py-8 min-h-screen"
    style="background: radial-gradient(circle at top right, rgba(255, 51, 102, 0.05), transparent), radial-gradient(circle at bottom left, rgba(0, 245, 212, 0.05), transparent);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="rounded-3xl p-8 text-white mb-8 shadow-2xl transform hover:scale-[1.01] transition-all duration-500 overflow-hidden relative group"
            style="background: var(--theme-gradient);">
            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            </div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight">¡Hola, {{ $user->name }}! 💎</h2>
                    <p class="text-white/80 mt-2 text-lg">Tu espacio personal en Big-dad</p>
                </div>
                <div class="text-right">
                    @if (!$user->isPremium())
                        <a href="{{ route('subscription.plans') }}"
                            class="bg-white/20 backdrop-blur-md hover:bg-white/30 text-white px-6 py-3 rounded-2xl text-sm font-bold transition-all border border-white/30 shadow-lg">
                            ✨ Upgrade Premium
                        </a>
                    @else
                        @if ($activeSubscription)
                            <a href="{{ route('subscription.show', $activeSubscription->id) }}"
                                class="bg-amber-400/90 hover:bg-amber-400 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg border border-amber-300/50">
                                💎 Premium Member
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Completion & Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Profile Completion -->
            <div class="glass-card rounded-3xl p-6 transition-all hover:translate-y-[-4px]">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Perfil Completado</p>
                        <p class="text-3xl font-black text-gray-900 mt-1">{{ $profileCompletion }}%</p>
                    </div>
                    <div class="w-14 h-14 bg-pink-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden p-0.5 border border-gray-200">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out"
                        style="width: {{ $profileCompletion }}%; background: var(--theme-gradient);"></div>
                </div>
                <p class="text-xs font-bold text-pink-500 mt-3 uppercase tracking-tighter">¡Agrega más fotos!</p>
            </div>

            <!-- Profile Views -->
            <div class="glass-card rounded-3xl p-6 transition-all hover:translate-y-[-4px]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Vistas Hoy</p>
                        <p class="text-3xl font-black text-gray-900 mt-1">{{ $todayViews }}</p>
                        <p class="text-xs font-bold text-emerald-500 mt-1">▲ +12 vs ayer</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- New Likes -->
            <div class="glass-card rounded-3xl p-6 transition-all hover:translate-y-[-4px]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Nuevos Likes</p>
                        <p class="text-3xl font-black text-gray-900 mt-1">{{ $newLikes }}</p>
                        <p class="text-xs font-bold text-purple-500 mt-1 uppercase tracking-tighter">Esta semana</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="glass-card rounded-3xl p-6 transition-all hover:translate-y-[-4px]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Mensajes</p>
                        <p class="text-3xl font-black text-gray-900 mt-1">{{ $messageCount }}</p>
                        <p class="text-xs font-bold text-amber-500 mt-1 uppercase tracking-tighter">{{ $unreadCount }}
                            no leídos</p>
                    </div>
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Enhancement -->
            <div class="lg:col-span-1">
                <div class="glass-card rounded-3xl mb-8 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Mejora tu Perfil</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Add Photos -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-4 p-4 rounded-2xl border border-transparent hover:border-pink-200 hover:bg-pink-50/50 transition-all group">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-pink-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900">Agregar Fotos</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $user->photos()->count() }} de 8 fotos
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-pink-500 font-black text-sm">+15%</span>
                                </div>
                            </a>

                            <!-- Complete Bio -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-4 p-4 rounded-2xl border border-transparent hover:border-blue-200 hover:bg-blue-50/50 transition-all group">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900">Completar Biografía</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Cuéntales sobre ti</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-blue-500 font-black text-sm">+25%</span>
                                </div>
                            </a>

                            <!-- Verify Profile -->
                            @if (!$user->is_verified)
                                <div
                                    class="flex items-center space-x-4 p-4 rounded-2xl border border-transparent hover:border-emerald-200 hover:bg-emerald-50/50 transition-all group cursor-pointer">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900">Verificar Perfil</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Obtén el check azul</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="text-emerald-500 font-black text-sm">+40%</span>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="flex items-center space-x-4 p-4 rounded-2xl bg-emerald-50/50 border border-emerald-100">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-emerald-900 uppercase tracking-widest">✅ Verificado
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Premium Features -->
                @if (!$user->isPremium())
                    <div class="rounded-3xl p-8 text-white text-center relative overflow-hidden shadow-2xl group transition-all duration-500"
                        style="background: linear-gradient(135deg, #FFB800 0%, #FF8C42 100%);">
                        <div
                            class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative">
                            <div class="text-5xl mb-4 transform group-hover:rotate-12 transition-transform">✨</div>
                            <h3 class="font-black text-xl mb-2 uppercase tracking-tight">¡Hazte Premium!</h3>
                            <p class="text-white/90 text-sm mb-6 font-medium">Accede a funciones exclusivas</p>
                            <ul class="text-xs space-y-3 mb-8 text-left max-w-[160px] mx-auto font-bold opacity-90">
                                <li class="flex items-center space-x-2"><span>💎</span> <span>Ver quién te dio like</span>
                                </li>
                                <li class="flex items-center space-x-2"><span>🔥</span> <span>Likes ilimitados</span></li>
                                <li class="flex items-center space-x-2"><span>🎯</span> <span>Perfil destacado</span></li>
                            </ul>
                            <a href="{{ route('subscription.plans') }}"
                                class="bg-white text-orange-500 px-6 py-3 rounded-2xl text-sm font-black hover:bg-orange-50 transition-all shadow-xl inline-block w-full uppercase tracking-tighter">
                                Upgrade
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Feed -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Featured Daddies -->
                <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Daddies Destacados 👑</h3>
                        <a href="{{ route('discover.index') }}"
                            class="text-pink-500 text-sm font-bold hover:text-pink-600 transition-colors uppercase tracking-widest">Ver
                            más</a>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @forelse($featuredDaddies as $daddy)
                                <div
                                    class="glass-card bg-white/40 border border-gray-100 rounded-2xl p-5 hover:shadow-xl transition-all group overflow-hidden relative">
                                    <div
                                        class="absolute top-0 right-0 p-2 opacity-20 group-hover:opacity-100 transition-opacity">
                                        <span class="text-2xl">👑</span>
                                    </div>
                                    <div class="flex items-start space-x-5 relative">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="relative p-0.5 rounded-full bg-gradient-to-tr from-amber-400 to-pink-500">
                                                <div class="bg-white p-0.5 rounded-full">
                                                    <x-user-avatar :user="$daddy" size="lg" />
                                                </div>
                                            </div>
                                            <div class="flex justify-center mt-2">
                                                <span
                                                    class="bg-amber-400 text-white text-[10px] px-2 py-0.5 rounded-full font-black uppercase shadow-sm">Premium</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-extrabold text-gray-900 text-lg truncate">{{ $daddy->name }}
                                            </h4>
                                            <p class="text-sm font-bold text-gray-500">{{ $daddy->age }} años •
                                                {{ $daddy->city }}
                                            </p>
                                            <p class="text-xs text-gray-600 mt-2 line-clamp-2 leading-relaxed">
                                                {{ $daddy->bio }}</p>
                                            @if ($daddy->is_verified)
                                                <span
                                                    class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full inline-block mt-3 border border-emerald-200 uppercase tracking-tighter">✅
                                                    Verificado</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex space-x-3 mt-6">
                                        <a href="{{ route('profile.show', $daddy) }}"
                                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-center">
                                            Ver Perfil
                                        </a>
                                        <button
                                            class="flex-shrink-0 bg-pink-500 hover:bg-pink-600 text-white w-10 h-10 rounded-xl text-lg font-bold transition-all shadow-lg hover:rotate-6 active:scale-90">
                                            💕
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center py-8 col-span-2 font-medium">No hay daddies disponibles
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                    <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                        <h3 class="text-xl font-black text-gray-900 tracking-tight">Conversaciones 💬</h3>
                        <a href="{{ route('chat.index') }}"
                            class="text-pink-500 text-sm font-bold hover:text-pink-600 transition-colors uppercase tracking-widest">Ver
                            todas</a>
                    </div>
                    <div class="p-8">
                        <div class="space-y-4">
                            @forelse($recentMessages as $message)
                                @php
                                    $sender = $message->sender;
                                @endphp
                                <a href="{{ route('chat.show', $message->conversation) }}"
                                    class="flex items-start space-x-5 p-4 hover:bg-pink-50/30 rounded-2xl cursor-pointer transition-all hover:translate-x-1 group border border-transparent hover:border-pink-100">
                                    <div class="flex-shrink-0 relative">
                                        <div
                                            class="p-0.5 rounded-full {{ $sender->is_active ? 'bg-gradient-to-tr from-emerald-400 to-emerald-500' : 'bg-gray-200' }}">
                                            <div class="bg-white p-0.5 rounded-full">
                                                <x-user-avatar :user="$sender" size="md" />
                                            </div>
                                        </div>
                                        @if ($sender->is_active)
                                            <div
                                                class="w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full absolute bottom-0 right-0 shadow-sm">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4
                                                class="text-sm font-black text-gray-900 group-hover:text-pink-600 transition-colors">
                                                {{ $sender->name }}</h4>
                                            <span
                                                class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium truncate italic leading-relaxed">
                                            {{ $message->content }}</p>
                                        @if (!$message->is_read && $message->receiver_id === $user->id)
                                            <span
                                                class="w-2.5 h-2.5 bg-pink-500 rounded-full inline-block mt-2 shadow-[0_0_10px_rgba(236,72,153,0.5)]"></span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <p class="text-gray-400 text-center py-8 font-medium italic">Sin conversaciones aún</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>