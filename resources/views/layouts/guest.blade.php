<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Big-dad') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:400|inter:400,500,600,700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen bg-gradient-to-br from-purple-900 via-purple-800 to-pink-800 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
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
                    <h1 class="text-5xl font-bold bg-gradient-to-r from-purple-600 via-pink-600 to-purple-800 bg-clip-text text-transparent px-10"
                        style="font-family: 'Playfair Display', cursive; font-style: italic; line-height: 4rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        Big-dad
                    </h1>
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
