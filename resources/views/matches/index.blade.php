@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent" 
                style="font-family: 'Playfair Display', serif;">
                Tus Matches
            </h1>
            <p class="text-gray-600 mt-2">{{ auth()->user()->matchesCount() }} personas con las que has conectado</p>
        </div>

        <!-- Grid de Matches -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse(auth()->user()->matches()->get() as $matchedUser)
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
                    <!-- Imagen -->
                    <div class="h-64 bg-gradient-to-br from-purple-200 to-pink-200 relative">
                        <img src="{{ $matchedUser->primary_photo_url ?? '/images/default-avatar.png' }}" 
                             alt="{{ $matchedUser->name }}"
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
                            <span class="text-sm font-semibold capitalize">
                                {{ $matchedUser->user_type === 'sugar_daddy' ? 'Sugar Daddy' : 'Sugar Baby' }}
                            </span>
                        </div>
                        @if($matchedUser->is_verified)
                            <div class="absolute top-4 left-4 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                Verificado
                            </div>
                        @endif
                    </div>
                    
                    <!-- Info -->
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $matchedUser->name }}</h3>
                        <p class="text-gray-600">{{ $matchedUser->age }} años • {{ $matchedUser->city }}</p>
                        <p class="text-gray-700 mt-3 line-clamp-3">{{ $matchedUser->bio }}</p>
                        
                        <!-- Botón Enviar Mensaje -->
                        <a href="{{ route('chat.create', $matchedUser) }}" 
                           class="mt-4 w-full flex items-center justify-center space-x-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full hover:shadow-xl transition-all transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span>Enviar mensaje</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl shadow-xl p-16 text-center max-h-[500px]">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Aún no tienes matches</h3>
                    <p class="text-gray-500 mb-6">¡Sigue explorando perfiles y dando likes!</p>
                    <a href="{{ route('discover.index') }}" 
                       class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-full hover:shadow-lg transition-all">
                        Explorar perfiles
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
