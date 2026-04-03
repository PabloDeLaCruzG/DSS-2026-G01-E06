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

    <!-- NAVBAR -->
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

                <ul class="hidden md:flex items-center gap-6 text-sm text-text-muted">
                    <li><a href="{{ route('home') }}">Marketplace</a></li>
                    <li><a href="{{ route('admin.users.index') }}">Panel Admin</a></li>
                </ul>
            </div>

            <!-- Derecha -->
            <div class="flex items-center gap-3">

                <!-- Carrito -->
                <a href="{{ route('cart.index') }}">
                    🛒
                </a>

                <!-- Avatar -->
                <div>
                    @auth
                        <a href="{{ route('games.index') }}" title="Mis anuncios">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=random"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary cursor-pointer transition-all"
                                alt="Avatar">
                        </a>
                    @else
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-white">
                            U
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main class="container mx-auto px-4 py-6 flex-1">
        @yield('content')
    </main>

</body>
</html>