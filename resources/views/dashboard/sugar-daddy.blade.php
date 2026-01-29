@php
    $user = Auth::user();
    $profileViews = $data['profileViews'] ?? 0;
    $newMatches = $data['newMatches'] ?? 0;
    $messageCount = $data['messageCount'] ?? 0;
    $unreadCount = $data['unreadCount'] ?? 0;
    $suggestedBabies = $data['suggestedBabies'] ?? [];
    $recentMessages = $data['recentMessages'] ?? [];
    $viewsGrowth = $data['viewsGrowth'] ?? 0;
@endphp

<div class="py-8 min-h-screen theme-sd-content"
    style="background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.15), transparent), radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.1), transparent);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="rounded-3xl p-8 text-white mb-8 shadow-2xl relative overflow-hidden group border border-white/10"
            style="background: var(--theme-gradient);">
            <div class="absolute inset-0 bg-black/20 opacity-40"></div>
            <div
                class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700">
            </div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight flex items-center gap-3">
                        ¡Hola, {{ $user->name }}!
                        <svg class="w-8 h-8 text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.4)]" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.55 18.55 20 18 20H6C5.45 20 5 19.55 5 19V18H19V19Z"/></svg>
                    </h2>
                    <p class="text-indigo-100 mt-2 text-lg font-medium opacity-90">Descubre conexiones auténticas en Big-dad</p>
                </div>
                <div class="text-right">
                    @if (!$user->isPremium())
                        <a href="{{ route('subscription.plans') }}"
                            class="bg-white/10 backdrop-blur-md hover:bg-white/20 text-white px-6 py-3 rounded-2xl text-sm font-bold transition-all border border-white/20 shadow-xl">
                            ⭐ Upgrade Premium
                        </a>
                    @else
                        <span
                            class="bg-indigo-500/80 backdrop-blur-md text-white px-5 py-2 rounded-full text-sm font-black border border-indigo-400/50 shadow-lg flex items-center gap-2 uppercase tracking-widest">
                            <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.55 18.55 20 18 20H6C5.45 20 5 19.55 5 19V18H19V19Z"/></svg>
                            Premium
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Profile Views -->
            <div class="glass-card rounded-3xl p-7 transition-all hover:translate-y-[-4px] border border-gray-700/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-indigo-400 uppercase tracking-widest mb-1">Vistas de Perfil
                        </p>
                        <p class="text-3xl font-black text-white">{{ $profileViews }}</p>
                        <p class="text-xs font-bold {{ $viewsGrowth >= 0 ? 'text-emerald-400' : 'text-amber-400' }} mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="{{ $viewsGrowth >= 0 ? 'M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z' : 'M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 13.586 4.414 10H7a1 1 0 100-2H2a1 1 0 00-1 1v5a1 1 0 102 0v-2.586l4.293 4.293a1 1 0 001.414 0L11 9.414 14.586 13H12z' }}"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $viewsGrowth >= 0 ? '+' : '' }}{{ $viewsGrowth }}% esta semana
                        </p>
                    </div>
                    <div
                        class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center border border-indigo-500/20">
                        <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- New Matches -->
            <div class="glass-card rounded-3xl p-7 transition-all hover:translate-y-[-4px] border border-gray-700/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-purple-400 uppercase tracking-widest mb-1">Nuevos Matches</p>
                        <p class="text-3xl font-black text-white">{{ $newMatches }}</p>
                        <p class="text-xs font-bold text-purple-400 mt-2 uppercase">Últimos 7 días</p>
                    </div>
                    <div
                        class="w-16 h-16 bg-purple-500/10 rounded-2xl flex items-center justify-center border border-purple-500/20">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="glass-card rounded-3xl p-7 transition-all hover:translate-y-[-4px] border border-gray-700/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-1">Mensajes</p>
                        <p class="text-3xl font-black text-white">{{ $messageCount }}</p>
                        <p class="text-xs font-bold text-blue-400 mt-2 uppercase tracking-tighter">{{ $unreadCount }} no
                            leídos</p>
                    </div>
                    <div
                        class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center border border-blue-500/20">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Purchase Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Boost Card -->
            <div class="glass-card rounded-2xl p-6 hover:bg-indigo-600/20 border border-indigo-500/30 transition-all cursor-pointer group text-center"
                onclick="openModal('boostModal')">
                <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform">🚀</div>
                <h4 class="text-white font-black uppercase tracking-tight">Boost</h4>
                <p class="text-sm text-indigo-300 mt-1">$2.99</p>
            </div>

            <!-- Super Likes Card -->
            <div class="glass-card rounded-2xl p-6 hover:bg-pink-600/20 border border-pink-500/30 transition-all cursor-pointer group text-center"
                onclick="openModal('superLikesModal')">
                <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform">⭐</div>
                <h4 class="text-white font-black uppercase tracking-tight">Super Likes</h4>
                <p class="text-sm text-pink-300 mt-1">$2.50</p>
            </div>

            <!-- Verification Card -->
            <div class="glass-card rounded-2xl p-6 hover:bg-blue-600/20 border border-blue-500/30 transition-all cursor-pointer group text-center"
                onclick="openModal('verificationModal')">
                <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform">✓</div>
                <h4 class="text-white font-black uppercase tracking-tight">Verificación</h4>
                <p class="text-sm text-blue-300 mt-1">$4.99</p>
            </div>

            <!-- Premium Button -->
            <a href="{{ route('subscription.plans') }}"
                class="glass-card rounded-2xl p-6 hover:bg-indigo-600/30 border border-indigo-400/40 transition-all group flex flex-col justify-center text-center relative overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity">
                </div>
                <div class="relative">
                    <div class="text-4xl mb-3 transform group-hover:rotate-12 transition-transform text-amber-400">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.55 18.55 20 18 20H6C5.45 20 5 19.55 5 19V18H19V19Z"/></svg>
                    </div>
                    <h4 class="text-white font-black uppercase tracking-tight">Premium</h4>
                    <p class="text-sm text-indigo-200 mt-1">{{ $user->isPremium() ? 'Mi Suscripción' : 'Ver planes' }}
                    </p>
                </div>
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Suggested Matches -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl border border-gray-700/30">
                <div class="px-8 py-6 border-b border-gray-700/50 flex items-center justify-between bg-white/5">
                    <h3 class="text-xl font-black text-white tracking-tight">Matches Sugeridos</h3>
                    <a href="{{ route('discover.index') }}"
                        class="text-indigo-400 text-sm font-black hover:text-indigo-300 transition-colors uppercase tracking-widest">Ver
                        todos</a>
                </div>
                <div class="p-8">
                    <div class="space-y-6">
                        @forelse($suggestedBabies as $baby)
                            <div
                                class="flex items-center space-x-5 p-4 rounded-2xl bg-white/5 border border-transparent hover:border-indigo-500/30 hover:bg-white/10 transition-all cursor-pointer group">
                                <div class="flex-shrink-0">
                                    <div class="p-0.5 rounded-full bg-indigo-500 shadow-[0_0_15px_rgba(79,70,229,0.4)]">
                                        <div class="bg-gray-900 rounded-full p-0.5">
                                            <x-user-avatar :user="$baby" size="md" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors truncate">
                                        {{ $baby->name }}</h4>
                                    <p class="text-xs font-bold text-gray-400 uppercase">{{ $baby->age }} años •
                                        {{ $baby->city }}</p>
                                    <p
                                        class="text-sm text-gray-300 mt-2 line-clamp-1 italic font-medium opacity-80 group-hover:opacity-100 transition-opacity leading-relaxed">
                                        {{ $baby->bio }}</p>
                                </div>
                                <div class="flex space-x-3">
                                    <button
                                        class="w-10 h-10 bg-gray-800/80 hover:bg-red-500/20 text-red-500 rounded-xl flex items-center justify-center transition-all border border-gray-700 hover:border-red-500/50">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    <button
                                        class="w-10 h-10 bg-indigo-600/80 hover:bg-indigo-600 text-white rounded-xl flex items-center justify-center transition-all shadow-lg hover:shadow-indigo-500/40">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8 font-bold italic">No hay sugerencias disponibles</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Messages -->
            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl border border-gray-700/30">
                <div class="px-8 py-6 border-b border-gray-700/50 flex items-center justify-between bg-white/5">
                    <h3 class="text-xl font-black text-white tracking-tight">Mensajes Recientes</h3>
                    <a href="{{ route('chat.index') }}"
                        class="text-indigo-400 text-sm font-black hover:text-indigo-300 transition-colors uppercase tracking-widest">Ver
                        todos</a>
                </div>
                <div class="p-8">
                    <div class="space-y-6">
                        @forelse($recentMessages as $message)
                            @php
                                $sender = $message->sender;
                            @endphp
                            <a href="{{ route('chat.show', $message->conversation) }}"
                                class="flex items-start space-x-5 p-4 rounded-2xl hover:bg-white/5 border border-transparent hover:border-gray-700 transition-all group">
                                <div class="flex-shrink-0">
                                    <div class="p-0.5 rounded-full bg-gray-700 group-hover:bg-indigo-500 transition-colors">
                                        <div class="bg-gray-900 rounded-full p-0.5">
                                            <x-user-avatar :user="$sender" size="md" />
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4
                                            class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">
                                            {{ $sender->name }}</h4>
                                        <span
                                            class="text-[10px] font-black text-gray-500 uppercase tracking-tighter">{{ $message->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p
                                        class="text-sm text-gray-400 truncate font-medium group-hover:text-gray-300 transition-colors italic leading-relaxed">
                                        {{ $message->content }}</p>
                                    @if (!$message->is_read && $message->receiver_id === $user->id)
                                        <div
                                            class="w-2.5 h-2.5 bg-indigo-500 rounded-full mt-2 shadow-[0_0_10px_rgba(79,70,229,0.8)]">
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-500 text-center py-8 font-bold italic">No hay mensajes aún</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>