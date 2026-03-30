@extends('layouts.app')

@section('title', 'GameLink - Marketplace de Videojuegos')

@section('content')

    {{-- ===== HERO BANNER ===== --}}
    <section class="relative bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-xl overflow-hidden mb-10 mt-4 p-10">
        <div class="max-w-lg">
            <span class="text-xs bg-violet-600 text-white px-2 py-1 rounded font-semibold uppercase tracking-wide">
                🟣 Nuevo Plaza Live
            </span>
            <h1 class="text-4xl font-bold text-white mt-4 mb-3 leading-tight">
                Level Up Your Collection
            </h1>
            <p class="text-gray-400 text-sm mb-6">
                Buy, sell and trade the latest releases and rare retro finds on the world's premier gaming marketplace.
            </p>
            <div class="flex gap-3">
                <a href="#" class="bg-gradient-to-r from-violet-600 to-purple-500 hover:opacity-90 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-opacity">
                    Start Selling
                </a>
                <a href="#" class="border border-gray-600 hover:border-gray-400 text-gray-300 hover:text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Browse Games
                </a>
            </div>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-violet-900/30 to-transparent pointer-events-none"></div>
    </section>

    {{-- ===== BARRA DE BÚSQUEDA ===== --}}
    <section class="mb-8">
        <div class="flex items-center gap-3 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 mb-4">
            <span class="text-gray-500 text-sm">🔍</span>
            <input
                type="text"
                placeholder="Search games, consoles, or accessories..."
                class="bg-transparent text-sm text-gray-300 placeholder-gray-500 outline-none flex-1"
            />
        </div>

        {{-- Filtros de plataforma --}}
<div class="flex gap-2 flex-wrap">
    <a href="{{ route('home') }}" class="text-xs px-3 py-1.5 rounded-full {{ !request('platform') ? 'bg-violet-600 border border-violet-600 text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">All Platforms</a>
    <a href="{{ route('home', ['platform' => 'PS5']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'PS5' ? 'bg-violet-600 border border-violet-600 text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">PlayStation 5</a>
    <a href="{{ route('home', ['platform' => 'XBOX']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'XBOX' ? 'bg-violet-600 border border-violet-600 text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Xbox Series X</a>
    <a href="{{ route('home', ['platform' => 'SWITCH']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platfor') == 'SWITCH' ? 'bg-violet-600 border border-violet-600 text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Nintendo Switch</a>
    <a href="{{ route('home', ['platform' => 'PC']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'PC' ? 'bg-violet-600 border border-violet-600 text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">PC Gaming</a>
    <a href="{{ route('home', ['platform' => 'RETRO']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'RETRO' ? 'bg-violet-600 border border-violet-600 text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Retro</a>
</div>
    </section>

    {{-- ===== TRENDING NOW ===== --}}
    <section class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-white font-bold text-lg flex items-center gap-2">⚡ Trending Now</h2>
            <a href="#" class="text-xs text-violet-400 hover:text-violet-300 transition-colors">View all →</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($games as $game)
                <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-violet-500 transition-colors group">

                    {{-- Imagen --}}
                    <div class="relative h-36 bg-gray-700 flex items-center justify-center">
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image }}"
                                 alt="{{ $game->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">🎮</span>
                        @endif

                        {{-- Plataforma --}}
                        <span class="absolute top-2 left-2 text-xs bg-gray-900/80 text-gray-300 px-2 py-0.5 rounded font-medium">
                            {{ $game->platform }}
                        </span>

                        {{-- Favorito --}}
                        <button class="absolute top-2 right-2 text-gray-400 hover:text-red-400 transition-colors">♡</button>
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                        <p class="text-gray-500 text-xs mb-0.5">{{ $game->genre }}</p>
                        <h3 class="text-white text-sm font-semibold mb-2 truncate" title="{{ $game->title }}">
                            {{ $game->title }}
                        </h3>

                        <div class="flex items-center justify-between">
                            <span class="text-white font-bold text-sm">
                                @if($game->getLowestPrice())
                                    {{ number_format($game->getLowestPrice(), 2) }}€
                                    <span class="text-gray-500 font-normal text-xs ml-1">
                                        {{ $game->game_ads_count }} {{ $game->game_ads_count == 1 ? 'oferta' : 'ofertas' }}
                                    </span>
                                @else
                                    <span class="text-gray-500 font-normal">Sin stock</span>
                                @endif
                            </span>

                            <a href="{{ route('games.show', $game->id) }}"
                               class="w-7 h-7 bg-violet-600 hover:bg-violet-500 rounded-lg flex items-center justify-center text-white text-xs transition-colors">
                                →
                            </a>
                        </div>
                    </div>
                </div>

            @empty
                {{-- Sin juegos --}}
                <div class="col-span-4 text-center text-gray-500 py-16">
                    <p class="text-5xl mb-4">🎮</p>
                    <p class="text-sm">No hay juegos disponibles de momento.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===== CATEGORÍAS ===== --}}
    <section class="mb-10">
        <h2 class="text-white font-bold text-lg mb-4">🗂️ Categorías</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['label' => 'Accessories', 'emoji' => '🎧'],
                ['label' => 'Hardware',    'emoji' => '🖥️'],
                ['label' => 'Merch',       'emoji' => '👕'],
                ['label' => 'VR',          'emoji' => '🥽'],
            ] as $cat)
                <div class="bg-gray-800 border border-gray-700 rounded-xl h-28 flex flex-col items-center justify-center gap-2 hover:border-violet-500 transition-colors cursor-pointer">
                    <span class="text-3xl">{{ $cat['emoji'] }}</span>
                    <span class="text-white text-sm font-semibold">{{ $cat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

@endsection