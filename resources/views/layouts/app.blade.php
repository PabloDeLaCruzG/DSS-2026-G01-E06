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

                <ul class="hidden md:flex items-center gap-6 text-sm font-medium text-text-muted">
                    <li><a href="{{ route('home') }}"
                            class="hover:text-text-main transition-colors {{ request()->routeIs('home') ? 'text-text-main' : '' }}">Marketplace</a>
                    </li>
                    <a href="{{ route('admin.users.index') }}" class="hover:text-text-main transition-colors">Panel Admin</a>
                    <li><a href="#" class="hover:text-text-main transition-colors">Comunidad</a></li>
                </ul>
            </div>

            <!-- Centro: Barra de búsqueda -->
            <div class="hidden md:flex flex-1 max-w-md">
                <div class="flex items-center w-full bg-background border border-border rounded-lg px-3 py-2 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-text-muted flex-shrink-0" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" placeholder="Busca juegos, claves, consolas..."
                        class="bg-transparent text-sm text-text-main placeholder-text-muted outline-none flex-1">
                </div>
            </div>

            <!-- Derecha: Carrito + Avatar -->
            <div class="flex items-center gap-3">
                <!-- Carrito -->
                <a href="{{ route('cart.index') }}">
                    🛒
                </a>

                <!-- Avatar -->
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

    <!-- ===== FOOTER ===== -->
    <footer class="border-t border-border mt-auto bg-surface">
        <div class="container mx-auto px-4 py-10">
            {{-- Columnas principales --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">

                {{-- GameLink --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-6 h-6 bg-primary rounded flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="6" y1="12" x2="10" y2="12"></line>
                                <line x1="8" y1="10" x2="8" y2="14"></line>
                                <line x1="15" y1="13" x2="15.01" y2="13"></line>
                                <line x1="18" y1="11" x2="18.01" y2="11"></line>
                                <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                            </svg>
                        </div>
                        <span class="font-bold text-text-main">GameLink</span>
                    </div>
                    <p class="text-xs text-text-muted leading-relaxed mb-4">
                        La plataforma líder en el mercado de videojuegos digitales y físicos. Encuentra las mejores
                        ofertas y conecta con la comunidad.
                    </p>
                    <div class="flex gap-3 text-text-muted">
                        <a href="#" class="hover:text-text-main transition-colors">𝕏</a>
                        <a href="#" class="hover:text-text-main transition-colors">@</a>
                        <a href="#" class="hover:text-text-main transition-colors">▶</a>
                    </div>
                </div>

                {{-- Comprar --}}
                <div>
                    <h4 class="font-bold text-text-main text-sm mb-3">Comprar</h4>
                    <ul class="space-y-2 text-xs text-text-muted">
                        <li><a href="#" class="hover:text-text-main transition-colors">Claves Digitales</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Juegos Físicos</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Consolas</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Hardware</a></li>
                    </ul>
                </div>

                {{-- Vender --}}
                <div>
                    <h4 class="font-bold text-text-main text-sm mb-3">Vender</h4>
                    <ul class="space-y-2 text-xs text-text-muted">
                        <li><a href="#" class="hover:text-text-main transition-colors">Crear Cuenta</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Centro de Vendedores</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Comisiones y Tarifas</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Políticas</a></li>
                    </ul>
                </div>

                {{-- Soporte --}}
                <div>
                    <h4 class="font-bold text-text-main text-sm mb-3">Soporte</h4>
                    <ul class="space-y-2 text-xs text-text-muted">
                        <li><a href="#" class="hover:text-text-main transition-colors">Centro de Ayuda</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Confianza y Seguridad</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Contactar Soporte</a></li>
                        <li><a href="#" class="hover:text-text-main transition-colors">Comentarios</a></li>
                    </ul>
                </div>
            </div>

            {{-- Barra inferior --}}
            <div
                class="border-t border-border pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-text-muted gap-3">
                <p>&copy; 2024 GameLink. Todos los derechos reservados.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-text-main transition-colors">Política de Privacidad</a>
                    <a href="#" class="hover:text-text-main transition-colors">Términos de Servicio</a>
                    <a href="#" class="hover:text-text-main transition-colors">Configuración de Cookies</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>