<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GameLink - @yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ICONOS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

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

<body class="bg-background text-text-main min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-56 bg-surface border-r border-border flex flex-col min-h-screen">

        <div class="px-4 py-4 border-b border-border">
            <span class="text-lg font-bold">🎮 GameLink</span>
            <p class="text-xs text-text-muted">User Panel</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-2">

            <a href="{{ route('profile.index') }}"
               class="block px-3 py-2 rounded-lg hover:bg-background">
                Mi Perfil
            </a>

            <a href="{{ route('games.index') }}"
               class="block px-3 py-2 rounded-lg bg-primary/10 text-primary font-semibold border-l-2 border-primary">
                Mis Anuncios
            </a>

            <a href="{{ route('orders.index') }}"
               class="block px-3 py-2 rounded-lg hover:bg-background">
                Mis Pedidos
            </a>

        </nav>

        <div class="px-3 py-4 border-t border-border">
            <p class="text-sm">{{ auth()->user()->name ?? 'Usuario' }}</p>
            <p class="text-xs text-text-muted">{{ auth()->user()->email ?? '' }}</p>
        </div>

    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">

        <!-- HEADER NUEVO -->
        <header class="h-16 bg-surface border-b border-border flex items-center justify-between px-6">

            <!-- IZQUIERDA -->
            <div class="flex items-center gap-4 w-1/2">

                <span class="font-semibold text-lg">GameLink</span>

                <!-- 🔍 BUSCADOR FUNCIONAL -->
                <form method="GET" action="{{ route('games.index') }}" class="w-full">
                    <input 
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar juegos, consolas..."
                        class="w-full bg-background border border-border px-4 py-2 rounded-lg text-sm focus:outline-none focus:border-primary"
                    >
                </form>

            </div>

            <!-- DERECHA -->
            <div class="flex items-center gap-4">

                <i class="bi bi-bell text-text-muted"></i>

                <!-- 👤 USUARIO -->
                <div class="flex items-center gap-2">

                    <div class="text-right">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-text-muted">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Avatar -->
                    <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                </div>

            </div>

        </header>

        <!-- CONTENT -->
        <main class="flex-1 p-6 bg-background">
            @yield('content')
        </main>

    </div>

</body>
</html>