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

<div class="bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg p-6 text-white mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">¡Hola, {{ $user->name }}! 💎</h2>
                    <p class="text-pink-100 mt-1">Tu espacio personal en Big-dad</p>
                </div>
                <div class="text-right">
                    {{ $user->isPremium() }}
                    @if (!$user->isPremium())
                        <a href="{{ route('subscription.plans') }}"
                            class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            ✨ Upgrade Premium
                        </a>
                    @else
                        @if ($activeSubscription)
                            <a href="{{ route('subscription.show', $activeSubscription->id) }}"
                                class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-medium">
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
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Perfil Completado</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $profileCompletion }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-pink-500 h-2 rounded-full" style="width: {{ $profileCompletion }}%"></div>
                </div>
                <p class="text-xs text-pink-600 mt-2">¡Agrega más fotos!</p>
            </div>

            <!-- Profile Views -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Vistas Hoy</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todayViews }}</p>
                        <p class="text-xs text-green-600 mt-1">+12 vs ayer</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- New Likes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Nuevos Likes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $newLikes }}</p>
                        <p class="text-xs text-purple-600 mt-1">Esta semana</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Mensajes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $messageCount }}</p>
                        <p class="text-xs text-amber-600 mt-1">{{ $unreadCount }} no leídos</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Enhancement -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Mejora tu Perfil</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Add Photos -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Agregar Fotos</p>
                                    <p class="text-xs text-gray-500">{{ $user->photos()->count() }} de 8 fotos</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-pink-500 text-sm font-medium">+15%</span>
                                </div>
                            </a>

                            <!-- Complete Bio -->
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Completar Biografía</p>
                                    <p class="text-xs text-gray-500">Cuéntales sobre ti</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-blue-500 text-sm font-medium">+25%</span>
                                </div>
                            </a>

                            <!-- Verify Profile -->
                            @if (!$user->is_verified)
                                <div
                                    class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Verificar Perfil</p>
                                        <p class="text-xs text-gray-500">Obtén el check azul</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="text-green-500 text-sm font-medium">+40%</span>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="flex items-center space-x-3 p-3 border border-green-200 rounded-lg bg-green-50">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-green-900">✅ Verificado</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Premium Features -->
                @if (!$user->isPremium())
                    <div class="bg-gradient-to-r from-amber-400 to-amber-500 rounded-lg p-6 text-white">
                        <div class="text-center">
                            <div class="text-3xl mb-2">✨</div>
                            <h3 class="font-bold text-lg mb-2">¡Hazte Premium!</h3>
                            <p class="text-sm text-amber-100 mb-4">Accede a funciones exclusivas</p>
                            <ul class="text-xs text-amber-100 space-y-1 mb-4 text-left">
                                <li>• Ver quién te dio like</li>
                                <li>• Likes ilimitados</li>
                                <li>• Perfil destacado</li>
                            </ul>
                            <button
                                class="bg-white text-amber-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-50 w-full">
                                Upgrade
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Feed -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Featured Daddies -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Daddies Destacados 👑</h3>
                        <a href="{{ route('discover.index') }}"
                            class="text-pink-600 text-sm font-medium hover:text-pink-800">Ver más</a>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($featuredDaddies as $daddy)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-user-avatar :user="$daddy" size="lg" />
                                            <div class="flex justify-center mt-1">
                                                <span
                                                    class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">👑
                                                    Premium</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $daddy->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $daddy->age }} años •
                                                {{ $daddy->city }}</p>
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $daddy->bio }}</p>
                                            @if ($daddy->is_verified)
                                                <span
                                                    class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full inline-block mt-2">✅
                                                    Verificado</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex space-x-2 mt-4">
                                        <a href="{{ route('profile.show', $daddy) }}"
                                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-md text-sm font-medium transition-colors">
                                            Ver Perfil
                                        </a>
                                        <button
                                            class="flex-1 bg-pink-500 hover:bg-pink-600 text-white py-2 px-3 rounded-md text-sm font-medium transition-colors">
                                            💕
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4 col-span-2">No hay daddies disponibles</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Conversaciones 💬</h3>
                        <a href="{{ route('chat.index') }}"
                            class="text-pink-600 text-sm font-medium hover:text-pink-800">Ver todas</a>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentMessages as $message)
                                @php
                                    $sender = $message->sender;
                                @endphp
                                <a href="{{ route('chat.show', $message->conversation) }}"
                                    class="flex items-start space-x-4 p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition-colors">
                                    <div class="flex-shrink-0 relative">
                                        <x-user-avatar :user="$sender" size="md" />
                                        @if ($sender->is_active)
                                            <div
                                                class="w-3 h-3 bg-green-400 border-2 border-white rounded-full absolute -mt-3 ml-9">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $sender->name }}</h4>
                                            <span
                                                class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate">{{ $message->content }}</p>
                                        @if (!$message->is_read && $message->receiver_id === $user->id)
                                            <span class="w-2 h-2 bg-pink-500 rounded-full inline-block mt-1"></span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <p class="text-gray-500 text-center py-4">Sin conversaciones aún</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
