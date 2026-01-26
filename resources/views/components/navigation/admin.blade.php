<div class="space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
        </svg>
        Panel de Control
    </a>

    <!-- Users Management -->
    <div class="px-2 py-1 mt-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gestión de Usuarios</p>
    </div>

    <a href="{{ route('admin.moderation.users') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->fullUrlIs(route('admin.moderation.users')) ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
            </path>
        </svg>
        Todos los Usuarios
        <span
            class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\User::count() }}</span>
    </a>

    <a href="{{ route('admin.moderation.users', ['user_type' => 'sugar_daddy']) }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request('user_type') === 'sugar_daddy' ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
            </path>
        </svg>
        Sugar Daddies
        <span
            class="ml-auto bg-purple-100 text-purple-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\User::where('user_type', 'sugar_daddy')->count() }}</span>
    </a>

    <a href="{{ route('admin.moderation.users', ['user_type' => 'sugar_baby']) }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request('user_type') === 'sugar_baby' ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
        </svg>
        Sugar Babies
        <span
            class="ml-auto bg-pink-100 text-pink-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\User::where('user_type', 'sugar_baby')->count() }}</span>
    </a>

    <a href="{{ route('admin.moderation.users', ['status' => 'pending_verification']) }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request('status') === 'pending_verification' ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
            </path>
        </svg>
        Verificaciones Pendientes
        <span
            class="ml-auto bg-yellow-100 text-yellow-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\User::where('is_verified', false)->count() }}</span>
    </a>

    <!-- Content Management -->
    <div class="px-2 py-1 mt-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Contenido y Moderación</p>
    </div>

    @if (auth()->user()?->isAdmin())
        <a href="{{ route('admin.moderation.dashboard') }}"
            class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.*') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            🛡️ Panel de Moderación
            <span
                class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ App\Models\Report::pending()->count() }}</span>
        </a>
    @endif

    <a href="{{ route('admin.moderation.reports') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.moderation.reports') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
            </path>
        </svg>
        Reportes y Quejas
        <span
            class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\Report::pending()->count() }}</span>
    </a>

    <a href="{{ route('admin.moderation.photos.index') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.moderation.photos*') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
            </path>
        </svg>
        Moderación de Fotos
        <span
            class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\ProfilePhoto::where('moderation_status', 'pending')->count() }}</span>
    </a>

    <a href="{{ route('admin.moderation.users', ['status' => 'suspended']) }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request('status') === 'suspended' ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
            </path>
        </svg>
        Usuarios Suspendidos
        <span
            class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\UserAction::suspensions()->active()->count() }}</span>
    </a>

    <!-- Financial Management -->
    <div class="px-2 py-1 mt-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Finanzas</p>
    </div>

    <a href="{{ route('admin.finance.transactions') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.finance.transactions') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
            </path>
        </svg>
        Suscripciones Premium
        <span
            class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded-full">{{ \App\Models\Subscription::where('status', 'active')->count() }}</span>
    </a>

    <a href="{{ route('admin.finance.transactions') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.finance.reports') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
        </svg>
        Reportes Financieros
    </a>

    <a href="{{ route('admin.plans.index') }}"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.plans*') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        Métodos de Pago & Planes
    </a>

    <!-- System Management -->
    <div class="px-2 py-1 mt-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Sistema</p>
    </div>

    <a href="#"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
        </svg>
        Configuración Global
    </a>

    <a href="#"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
        </svg>
        Logs del Sistema
    </a>

    <a href="#"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
            </path>
        </svg>
        Estadísticas Generales
    </a>

    <!-- Marketing -->
    <div class="px-2 py-1 mt-4">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Marketing</p>
    </div>

    <a href="#"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
            </path>
        </svg>
        Promociones
    </a>

    <a href="#"
        class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
            </path>
        </svg>
        Notificaciones Push
    </a>
</div>