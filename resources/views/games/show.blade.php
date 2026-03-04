@extends('layouts.app')

@section('title', $game->title . ' - GameLink')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-gray-300">

    <!-- COLUMNA IZQUIERDA: PORTADA E INFO -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Portada -->
        <div class="relative group rounded-xl overflow-hidden shadow-2xl border border-gray-700">
            <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full object-cover transition-transform duration-500 group-hover:scale-105">
            @if($game->rating >= 4.5)
            <span class="absolute top-4 left-4 bg-purple-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                Más Vendido
            </span>
            @endif
        </div>

        <!-- Título y Datos -->
        <div>
            <h1 class="text-3xl font-extrabold text-white mb-2">{{ $game->title }}</h1>
            <p class="text-sm text-gray-400 mb-4">FromSoftware Inc. • Bandai Namco</p>

            <!-- Badges -->
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach($game->platforms as $platform)
                <span class="bg-gray-800 border border-gray-600 px-3 py-1 rounded text-xs font-semibold text-gray-200">{{ $platform }}</span>
                @endforeach
                @foreach($game->genres as $genre)
                <span class="bg-gray-800 border border-gray-600 px-3 py-1 rounded text-xs font-semibold text-gray-200">{{ $genre }}</span>
                @endforeach
            </div>

            <!-- Metadata -->
            <div class="space-y-3 text-sm border-t border-gray-700 pt-4">
                <div class="flex justify-between">
                    <span>Lanzamiento</span>
                    <span class="text-white">{{ $game->year }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Valoración</span>
                    <div class="flex items-center text-yellow-400">
                        <span class="mr-1">★</span>
                        <span class="font-bold text-white">{{ $game->rating }}</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span>Metacritic</span>
                    <span class="bg-green-600 text-white px-2 rounded font-bold text-xs">96</span>
                </div>
            </div>

            <!-- Botones Acción -->
            <div class="grid grid-cols-2 gap-4 mt-6">
                <button class="flex items-center justify-center gap-2 border border-gray-600 py-2 rounded-lg hover:bg-gray-800 transition">
                    ❤️ Desear
                </button>
                <button class="flex items-center justify-center gap-2 border border-gray-600 py-2 rounded-lg hover:bg-gray-800 transition">
                    🔗 Compartir
                </button>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: OFERTAS -->
    <div class="lg:col-span-2 space-y-6">

        <!-- 1. CAJA DESTACADA (MEJOR PRECIO) -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-purple-900 flex items-center justify-center text-purple-300">
                    <span class="text-xl">🛡️</span>
                </div>
                <div>
                    <p class="text-xs text-purple-400 font-bold uppercase tracking-wider">Precio Oficial GameLink</p>
                    <p class="text-white font-bold text-lg">Mejor oferta disponible</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold text-white">{{ $game->getLowestPrice() }}€</p>
            </div>
            <button class="bg-purple-600 hover:bg-purple-500 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-purple-900/50 transition">
                Añadir al Carrito 🛒
            </button>
        </div>

        <!-- 2. COMPARADOR DE VENDEDORES (LISTA) -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
            <!-- Header del Comparador -->
            <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-800/50">
                <h3 class="font-bold text-white flex items-center gap-2">
                    🏪 Comparador de Vendedores
                </h3>
                <div class="flex gap-2 text-xs">
                    <button class="px-3 py-1 bg-gray-700 rounded-full text-white">Todos</button>
                    <button class="px-3 py-1 hover:bg-gray-700 rounded-full text-gray-400">Nuevos</button>
                    <button class="px-3 py-1 hover:bg-gray-700 rounded-full text-gray-400">Usados</button>
                </div>
            </div>

            <!-- Cabecera Tabla -->
            <div class="grid grid-cols-12 gap-4 p-3 text-xs font-bold text-gray-500 uppercase bg-gray-900/30">
                <div class="col-span-5 md:col-span-4">Vendedor</div>
                <div class="hidden md:block md:col-span-2 text-center">Estado</div>
                <div class="hidden md:block md:col-span-2 text-center">Formato</div>
                <div class="col-span-4 md:col-span-2 text-right">Precio</div>
                <div class="col-span-3 md:col-span-2 text-right">Acción</div>
            </div>

            <!-- LISTADO DE OFERTAS (MEZCLAMOS PRO Y USER) -->
            <div class="divide-y divide-gray-700">
                @foreach($proAds->merge($userAds) as $ad)
                <x-ad-row :ad="$ad" />
                @endforeach
            </div>

            <div class="p-3 text-center border-t border-gray-700">
                <a href="#" class="text-sm text-purple-400 hover:text-purple-300 font-semibold">
                    Ver más vendedores ↓
                </a>
            </div>
        </div>

        <!-- 3. INFO PROTECCIÓN -->
        <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4 flex gap-4 items-start">
            <div class="text-2xl">ℹ️</div>
            <div>
                <h4 class="font-bold text-white text-sm">Protección al Comprador GameLink</h4>
                <p class="text-xs text-gray-400 mt-1">
                    Todas las compras a través de GameLink están cubiertas por nuestra garantía de devolución de dinero de 30 días.
                    Verifica siempre que el vendedor tenga el distintivo "Socio Verificado" para mayor seguridad.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection