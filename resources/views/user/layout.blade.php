<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameLink - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#0d1520',
                        surface:    '#1a2433',
                        primary:    '#009194',
                        'primary-hover': '#007a7c',
                        accent:     '#3bb1a5',
                        'text-main':  '#ffffff',
                        'text-muted': '#9ba4b0',
                        border:     '#2a3544',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-text-main min-h-screen flex">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-56 bg-surface border-r border-border flex flex-col shrink-0 min-h-screen sticky top-0">

        {{-- Logo (igual que layout.blade.php) --}}
        <div class="px-4 py-4 border-b border-border">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white shadow-lg shadow-primary/20 group-hover:bg-primary-hover transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="6" y1="12" x2="10" y2="12"/>
                        <line x1="8" y1="10" x2="8" y2="14"/>
                        <line x1="15" y1="13" x2="15.01" y2="13"/>
                        <line x1="18" y1="11" x2="18.01" y2="11"/>
                        <rect x="2" y="6" width="20" height="12" rx="2"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-text-main tracking-tight">GameLink</span>
            </a>
            <p class="text-[10px] text-text-muted mt-1 ml-10 tracking-widest uppercase">User Panel</p>
        </div>

        {{-- Navegación (estilo admin, pero con rutas de usuario) --}}
        <nav class="flex-1 px-3 py-4 space-y-1">
            <p class="text-[10px] text-text-muted uppercase tracking-widest px-3 mb-2">General</p>

            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-muted hover:bg-background hover:text-text-main transition-all text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                </svg>
                Mi Perfil
            </a>

            <a href="{{ route('games.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary font-semibold text-sm border-l-2 border-primary">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M5 7v10a1 1 0 001 1h12a1 1 0 001-1V7"/>
                </svg>
                Mis Anuncios
            </a>

            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-muted hover:bg-background hover:text-text-main transition-all text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v4H3zM3 7v13a2 2 0 002 2h14a2 2 0 002-2V7"/>
                </svg>
                Mis Pedidos
            </a>

        </nav>

        {{-- Usuario logueado --}}
        <div class="px-3 py-4 border-t border-border">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-background transition-all">
                 <img src="{{ auth()->user()->avatar_url }}"
                     class="w-8 h-8 rounded-full border border-border" alt="Avatar">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-text-main truncate">{{ auth()->user()->name ?? 'Usuario' }}</p>
                    <p class="text-xs text-text-muted truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </a>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Topbar (copiado de layout.blade.php: breadcrumb, buscador y acciones) --}}
        <header class="h-16 bg-surface border-b border-border flex items-center justify-between px-6 sticky top-0 z-40">
            {{-- Breadcrumb / título --}}
            <div class="flex items-center gap-2 text-sm text-text-muted">
                <a href="{{ route('profile.index') }}" class="hover:text-text-main transition-colors">User</a>
                <span>›</span>
                @php
                    $currentBreadcrumbRoute = match (true) {
                        request()->routeIs('profile.*') => route('profile.index'),
                        request()->routeIs('games.*') => route('games.index'),
                        request()->routeIs('orders.*') => route('orders.index'),
                        default => url()->current(),
                    };
                @endphp
                <a href="{{ $currentBreadcrumbRoute }}" class="text-text-main font-medium hover:text-primary transition-colors">@yield('title', 'Panel')</a>
            </div>

            {{-- Buscador funcional (redirige a games.index con query) --}}
            <div class="hidden md:flex items-center bg-background border border-border rounded-lg px-3 py-2 gap-2 w-72">
                <svg class="w-4 h-4 text-text-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <form method="GET" action="{{ route('games.index') }}" class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar juegos, consolas..."
                        class="bg-transparent text-sm text-text-main placeholder-text-muted outline-none w-full">
                </form>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center gap-3">
                <button class="relative p-2 text-text-muted hover:text-text-main hover:bg-background rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-surface"></span>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium">{{ auth()->user()->name ?? '' }}</p>
                            <p class="text-xs text-text-muted">{{ auth()->user()->email ?? '' }}</p>
                        </div>

                            <img src="{{ auth()->user()->avatar_url }}"
                             class="w-8 h-8 rounded-full border-2 border-border hover:border-primary transition-all" alt="Avatar">
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

                        <div class="px-4 py-3 border-b border-border">
                            <p class="text-sm font-semibold text-text-main truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-text-muted truncate">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors">
                            Mi perfil
                        </a>
                        <a href="{{ route('games.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors">
                            Mis anuncios
                        </a>
                        <a href="{{ route('orders.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors">
                            Mis pedidos
                        </a>
                        <a href="{{ route('cart.index') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-text-muted hover:text-text-main hover:bg-background transition-colors">
                            Carrito
                        </a>

                        <div class="border-t border-border my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-400 hover:bg-background transition-colors">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Contenido --}}
        <main class="flex-1 p-6 bg-background overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>