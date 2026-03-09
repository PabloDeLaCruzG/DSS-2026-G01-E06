<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'GameLink')</title>
    <!-- CSS / Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script> 
</head>
<body class="bg-gray-900 text-white">
    
    <!-- Navbar GameLink -->
<nav class="bg-primary border-b border-gray-800 sticky top-0 z-50">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">
        
        <!-- Izquierda: Logo -->
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white shadow-lg shadow-blue-500/20 group-hover:bg-blue-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"></line><line x1="8" y1="10" x2="8" y2="14"></line><line x1="15" y1="13" x2="15.01" y2="13"></line><line x1="18" y1="11" x2="18.01" y2="11"></line><rect x="2" y="6" width="20" height="12" rx="2"></rect></svg>
                </div>
                <span class="text-xl font-bold text-white tracking-tight">GameLink</span>
            </a>
        </div>

        <!-- Centro: Menú de Navegación -->
        <ul class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-400">
            <li><a href="{{ route('home') }}" class="hover:text-white transition-colors {{ request()->routeIs('home') ? 'text-white' : '' }}">Buy</a></li>
            <li><a href="{{ route('games.sell') }}" class="hover:text-white transition-colors">Sell</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Trade</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Auctions</a></li>
            <li><a href="#" class="hover:text-white transition-colors">Community</a></li>
        </ul>

        <!-- Derecha: Acciones de Usuario -->
        <div class="flex items-center gap-4">
            <!-- Carrito -->
            <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-full transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>

            <!-- Notificaciones -->
            <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-full transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            <!-- Avatar de Usuario -->
            <div class="ml-2">
                @auth
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random" class="w-8 h-8 rounded-full border-2 border-gray-700 hover:border-blue-500 cursor-pointer transition-all" alt="Avatar">
                @else
                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-300 border border-gray-600">
                        U
                    </div>
                @endauth
            </div>
        </div>

    </div>
</nav>

    <!-- Contenido Principal -->
    <main class="container mx-auto p-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="p-4 text-center text-gray-500">
        &copy; 2026 GameLink
    </footer>
</body>
</html>