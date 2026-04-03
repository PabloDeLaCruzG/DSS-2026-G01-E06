<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameLink Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        {{-- Logo --}}
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
            <p class="text-[10px] text-text-muted mt-1 ml-10 tracking-widest uppercase">Admin Panel</p>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 px-3 py-4 space-y-1">
            <p class="text-[10px] text-text-muted uppercase tracking-widest px-3 mb-2">General</p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-muted hover:bg-background hover:text-text-main transition-all text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Resumen
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-muted hover:bg-background hover:text-text-main transition-all text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Catálogo
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/10 text-primary font-semibold text-sm border-l-2 border-primary">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Usuarios
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-text-muted hover:bg-background hover:text-text-main transition-all text-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                Denuncias
            </a>
        </nav>

        {{-- Usuario logueado --}}
        <div class="px-3 py-4 border-t border-border">
            <div class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-background transition-all cursor-pointer">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=009194&color=fff"
                     class="w-8 h-8 rounded-full border border-border" alt="Avatar">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-text-main truncate">{{ auth()->user()->name ?? 'Root Admin' }}</p>
                    <p class="text-xs text-text-muted truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="h-16 bg-surface border-b border-border flex items-center justify-between px-6 sticky top-0 z-40">
            {{-- título --}}
            <div class="flex items-center gap-2 text-sm text-text-muted">
                <span>Admin</span>
                <span>›</span>
                <span class="text-text-main font-medium">@yield('title', 'Panel')</span>
            </div>

            {{-- Buscador --}}
            <div class="hidden md:flex items-center bg-background border border-border rounded-lg px-3 py-2 gap-2 w-72">
                <svg class="w-4 h-4 text-text-muted shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" placeholder="Buscar usuarios, IDs o correos..."
                    class="bg-transparent text-sm text-text-main placeholder-text-muted outline-none flex-1">
            </div>

            {{-- Acciones --}}
            <div class="flex items-center gap-3">
                <button class="relative p-2 text-text-muted hover:text-text-main hover:bg-background rounded-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-surface"></span>
                </button>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=009194&color=fff"
                     class="w-8 h-8 rounded-full border-2 border-border hover:border-primary cursor-pointer transition-all" alt="Avatar">
            </div>
        </header>

        {{-- Contenido --}}
        <main class="flex-1 p-6 bg-background overflow-y-auto">
            @yield('content')
        </main>
    </div>

</body>
</html>