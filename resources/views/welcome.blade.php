<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Big-dad - Conexiones Premium</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-purple-50 to-pink-50">
    <div class="min-h-screen flex flex-col justify-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-purple-600 mb-4">Big-dad</h1>
                <p class="text-xl text-gray-600 mb-8">La plataforma premium para conexiones auténticas</p>
                
                <div class="space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</body>
</html>
