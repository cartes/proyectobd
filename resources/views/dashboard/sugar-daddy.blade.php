<!-- Welcome Header -->
<div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">¡Hola, {{ Auth::user()->name }}! 👑</h2>
            <p class="text-purple-100 mt-1">Descubre conexiones auténticas en Big-dad</p>
        </div>
        <div class="text-right">
            @if(!Auth::user()->isPremium())
                <a href="#" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ⭐ Upgrade Premium
                </a>
            @else
                <span class="bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    👑 Premium Member
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Profile Views -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Vistas de Perfil</p>
                <p class="text-2xl font-bold text-gray-900">127</p>
                <p class="text-xs text-green-600 mt-1">+23% esta semana</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- New Matches -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Nuevos Matches</p>
                <p class="text-2xl font-bold text-gray-900">8</p>
                <p class="text-xs text-pink-600 mt-1">Últimos 7 días</p>
            </div>
            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Mensajes</p>
                <p class="text-2xl font-bold text-gray-900">15</p>
                <p class="text-xs text-blue-600 mt-1">3 no leídos</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Suggested Matches -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Matches Sugeridos</h3>
            <a href="#" class="text-purple-600 text-sm font-medium hover:text-purple-800">Ver todos</a>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Match 1 -->
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-pink-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold">
                            S
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">Sofia Martinez</h4>
                        <p class="text-xs text-gray-500">24 años • Santiago</p>
                        <p class="text-xs text-gray-600 mt-1">Estudiante de Arte</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="w-8 h-8 bg-red-100 hover:bg-red-200 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <button class="w-8 h-8 bg-green-100 hover:bg-green-200 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Match 2 -->
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-pink-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold">
                            V
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">Valentina Rodriguez</h4>
                        <p class="text-xs text-gray-500">22 años • Valparaíso</p>
                        <p class="text-xs text-gray-600 mt-1">Modelo & Influencer</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="w-8 h-8 bg-red-100 hover:bg-red-200 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <button class="w-8 h-8 bg-green-100 hover:bg-green-200 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Mensajes Recientes</h3>
            <a href="#" class="text-purple-600 text-sm font-medium hover:text-purple-800">Ver todos</a>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Message 1 -->
                <div class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            A
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900">Andrea López</h4>
                            <span class="text-xs text-gray-500">2h</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">¡Hola! Me encantó tu perfil... 💕</p>
                        <div class="w-2 h-2 bg-purple-500 rounded-full mt-1"></div>
                    </div>
                </div>

                <!-- Message 2 -->
                <div class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-pink-400 to-pink-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            M
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900">María Fernanda</h4>
                            <span class="text-xs text-gray-500">1d</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Gracias por el match! 😊</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
