<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Big-dad') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900|inter:400,500,600,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen bg-gradient-to-br from-purple-900 via-purple-800 to-pink-800 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">

        <!-- Floating Home Button -->
        <a href="/"
            class="fixed top-6 left-6 z-50 flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-white font-medium shadow-lg hover:bg-white/20 hover:scale-105 transition-all duration-300 group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Volver al inicio</span>
        </a>

        <!-- Decorative elements -->
        <div class="absolute inset-0 bg-black/20"></div>
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-amber-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
        </div>

        <div
            class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-4 bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20">
            <!-- Logo/Brand -->
            <div class="flex justify-center mb-6">
                <div class="text-center">
                    <a href="/"
                        class="flex items-center justify-center space-x-3 mb-2 hover:opacity-90 transition-opacity">
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" />
                            </svg>
                        </div>
                        <h1 class="text-4xl font-black bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent"
                            style="font-family: 'Outfit', sans-serif;">
                            Big-Dad
                        </h1>
                    </a>
                    <p class="text-sm text-gray-600 mt-1 font-medium">Conexiones Premium</p>
                </div>
            </div>
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="relative z-10 mt-8 text-center text-white/80 text-sm pb-10">
            <p>&copy; 2025 Big-dad. Conexiones auténticas y discretas.</p>
            <div class="mt-2 space-x-4">
                <a href="#" class="hover:text-white transition-colors">Términos</a>
                <a href="#" class="hover:text-white transition-colors">Privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Soporte</a>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Tipografía elegante para la marca */
        .brand-logo {
            font-family: 'Playfair Display', cursive;
            letter-spacing: 2px;
        }

        /* Efecto de brillo sutil */
        .brand-logo:hover {
            filter: drop-shadow(0 0 8px rgba(147, 51, 234, 0.3));
            transition: filter 0.3s ease;
        }
    </style>
</body>

</html>