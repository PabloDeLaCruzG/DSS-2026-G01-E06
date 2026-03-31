<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GameLink')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#0d1520',
                        surface: '#1a2433',
                        primary: '#009194',
                        'primary-hover': '#007a7c',
                        accent: '#3bb1a5',
                        'text-main': '#ffffff',
                        'text-muted': '#9ba4b0',
                        border: '#2a3544',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-background text-text-main min-h-screen flex flex-col">

<!-- ===== NAVBAR ===== -->
<nav class="bg-surface border-b border-border sticky top-0 z-50">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between gap-4">

        <!-- Izquierda -->
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white">
                    🎮
                </div>
                <span class="text-xl font-bold">GameLink</span>
            </a>

            <ul class="hidden md:flex items-center gap-6 text-sm font-medium text-text-muted">

                <li>
                    <a href="{{ route('home') }}"
                       class="hover:text-text-main {{ request()->routeIs('home') ? 'text-text-main' : '' }}">
                        Marketplace
                    </a>
                </li>

                @auth
                <li>
                    <a href="{{ route('profile.index') }}"
                       class="hover:text-text-main {{ request()->routeIs('profile.*') ? 'text-text-main' : '' }}">
                        Panel Usuario
                    </a>
                </li>
                @endauth

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="hover:text-text-main">
                        Panel Admin
                    </a>
                </li>

                <li>
                    <a href="#" class="hover:text-text-main">Community</a>
                </li>

            </ul>
        </div>

        <!-- Buscador -->
        <div class="hidden md:flex flex-1 max-w-md">
            <div class="flex items-center w-full bg-background border border-border rounded-lg px-3 py-2 gap-2">
                🔍
                <input type="text"
                    placeholder="Buscar juegos..."
                    class="bg-transparent text-sm outline-none flex-1">
            </div>
        </div>

        <!-- Derecha -->
        <div class="flex items-center gap-3">

            <!-- Carrito -->
            <a href="{{ route('cart.index') }}"
               class="p-2 hover:bg-background rounded-full">
                🛒
            </a>

            <!-- Usuario -->
            @auth
                <div class="flex items-center gap-2">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}"
                        class="w-8 h-8 rounded-full">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                </div>
            @else
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs">
                    U
                </div>
            @endauth

        </div>

    </div>
</nav>

<!-- CONTENIDO -->
<main class="container mx-auto px-4 py-6 flex-1">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="border-t border-border mt-auto bg-surface">
    <div class="container mx-auto px-4 py-6 text-xs text-text-muted text-center">
        © 2024 GameLink
    </div>
</footer>

</body>
</html>