<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GameLink')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        /* Neutralizar estilos de Bootstrap que rompen Tailwind */
        :root { --bs-primary: #009194; --bs-primary-rgb: 0,145,148; --bs-link-color: #009194; }
        a { text-decoration: none !important; color: inherit !important; }
        a:hover { color: inherit !important; }
        *, *::before, *::after { box-sizing: border-box; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <nav class="bg-surface border-b border-border sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="container mx-auto px-4 h-16 flex items-center justify-between gap-4">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group flex-shrink-0 no-underline">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="6" y1="12" x2="10" y2="12"/>
                        <line x1="8" y1="10" x2="8" y2="14"/>
                        <line x1="15" y1="13" x2="15.01" y2="13"/>
                        <line x1="18" y1="11" x2="18.01" y2="11"/>
                        <rect x="2" y="6" width="20" height="12" rx="2"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-text-main">GameLink</span>
            </a>

            <!-- Derecha: Avatar / Auth + hamburger para invitados -->
            <div class="flex items-center gap-3">
                @auth
                    <!-- Dropdown de usuario -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none">
                            <img src="{{ auth()->user()->avatar_url }}"
                                class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-all object-cover"
                                alt="Avatar">
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-52 bg-surface border border-border rounded-xl shadow-lg py-1 z-50"
                             style="display: none;">

                            <!-- Info del usuario -->
                            <div class="px-4 py-3 border-b border-border">
                                <p class="text-sm font-semibold text-text-main truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-text-muted truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <!-- Panel Admin (solo admins) -->
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.users.index') }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-primary hover:bg-background transition-colors no-underline">
                                    ⚙️ Panel Admin
                                </a>
                                <div class="border-t border-border my-1"></div>
                            @endif

                            <!-- Opciones de usuario -->
                            <a href="{{ route('users.show', auth()->user()) }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors no-underline">
                                👤 Mi perfil
                            </a>
                            <a href="{{ route('games.index') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors no-underline">
                                🎮 Mis anuncios
                            </a>
                            <a href="{{ route('orders.index') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors no-underline">
                                📦 Mis pedidos
                            </a>
                            <a href="{{ route('cart.index') }}"
                               class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors no-underline">
                                🛒 Carrito
                            </a>

                            <!-- Logout -->
                            <div class="border-t border-border my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:bg-background transition-colors">
                                    🚪 Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Guest desktop: links de acceso -->
                    <div class="hidden md:flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            class="text-sm text-text-muted hover:text-text-main transition-colors font-medium">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}"
                            class="text-sm bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg px-4 py-1.5 transition-colors">
                            Registrarse
                        </a>
                    </div>
                    <!-- Guest mobile: hamburger (Alpine.js) -->
                    <button @click="mobileOpen = !mobileOpen"
                            class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg hover:bg-background transition-colors"
                            type="button"
                            style="color: #9ba4b0; border: none; background: none;">
                        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                @endauth
            </div>

        </div>

        <!-- Mobile guest nav (Alpine.js) -->
        @guest
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="border-t border-border px-4 py-3 flex flex-col gap-2 md:hidden"
             style="display:none; background-color: #1a2433;">
            <a href="{{ route('login') }}"
               class="text-sm text-text-muted font-medium py-2 hover:text-text-main transition-colors">
                Iniciar sesión
            </a>
            <a href="{{ route('register') }}"
               class="text-sm bg-primary text-white font-semibold rounded-lg px-4 py-2 text-center transition-colors">
                Registrarse
            </a>
        </div>
        @endguest
    </nav>

    <!-- CONTENIDO -->
    <main class="container mx-auto px-4 py-6 flex-1">
        @yield('content')
    </main>

   <!-- ==== FOOTER ==== -->
<footer class="bg-surface border-t border-border mt-auto">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">

            {{-- Columna 1: GameLink --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="6" y1="12" x2="10" y2="12"/>
                            <line x1="8" y1="10" x2="8" y2="14"/>
                            <line x1="15" y1="13" x2="15.01" y2="13"/>
                            <line x1="18" y1="11" x2="18.01" y2="11"/>
                            <rect x="2" y="6" width="20" height="12" rx="2"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-text-main">GameLink</span>
                </div>
                <p class="text-sm text-text-muted leading-relaxed">
                    La plataforma líder para conectar a jugadores de todo el mundo. Compra, vende e intercambia con total seguridad.
                </p>

                <!-- ICONOS (NO LINKS, solo elementos decorativos) -->
                <div class="flex gap-3">
                    <div class="w-9 h-9 rounded-full bg-background flex items-center justify-center text-text-muted border border-border"
                         role="img" aria-label="icono web">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
                    </div>

                    <div class="w-9 h-9 rounded-full bg-background flex items-center justify-center text-text-muted border border-border"
                         role="img" aria-label="icono correo">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2 6v12h20V6l-10 6L2 6z"/></svg>
                    </div>

                    <div class="w-9 h-9 rounded-full bg-background flex items-center justify-center text-text-muted border border-border"
                         role="img" aria-label="icono juego">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M6 10v4a2 2 0 002 2h1m5 0h1a2 2 0 002-2v-4M8 12h.01M10 10h4"/></svg>
                    </div>
                </div>
            </div>

            {{-- Columna 2: Marketplace --}}
            <div class="space-y-4">
                <h4 class="text-text-main font-semibold">Marketplace</h4>
                <p class="text-sm text-text-muted leading-relaxed">
                    Busca y encuentra las mejores ofertas de tus juegos favoritos en cualquier momento.
                </p>
            </div>

            {{-- Columna 3: Soporte --}}
            <div class="space-y-4">
                <h4 class="text-text-main font-semibold">Soporte</h4>
                <p class="text-sm text-text-muted">Contáctanos para cualquier incidencia.</p>
            </div>
        </div>

        {{-- Pie inferior --}}
        <div class="border-t border-border mt-12 pt-8 flex flex-col items-center gap-2">
            <p class="text-xs text-text-muted">© GameLink 2026. Todos los derechos reservados.</p>
            <div class="flex gap-4 text-xs font-medium">
                <span class="text-text-muted">Español (ES)</span>
                <span class="text-text-muted">Privacidad</span>
                <span class="text-text-muted">Cookies</span>
            </div>
        </div>
    </div>
</footer>

@include('partials._confirm-modal')
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>