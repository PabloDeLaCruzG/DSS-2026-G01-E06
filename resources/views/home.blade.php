@extends('layouts.app')

@section('title', 'GameLink - Marketplace de Videojuegos')

@section('content')

    {{-- Aviso entrega: funciones no planificadas --}}
    <div id="delivery-warning" class="flex items-start gap-3 p-4 rounded-lg text-sm mb-6 mt-4" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.35);color:#fde047;">
        <svg class="w-5 h-5 mt-0.5 shrink-0" style="color:#facc15;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div class="flex-1">
            <span class="font-semibold">Aviso de entrega:</span>
            algunos botones y funciones de esta página están sin acción o pueden comportarse de forma incorrecta, ya que no estaban planificados para la entrega actual.
        </div>
        <button onclick="document.getElementById('delivery-warning').remove()" style="color:#facc15;" aria-label="Cerrar aviso">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ===== HERO BANNER ===== --}}
    <section class="relative bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-xl overflow-hidden mb-10 mt-4 p-10">
        <div class="max-w-lg">
            <span class="text-xs bg-[#009194] text-white px-2 py-1 rounded font-semibold uppercase tracking-wide">
                🟣 Nuevo Plaza Live
            </span>
            <h1 class="text-4xl font-bold text-white mt-4 mb-3 leading-tight">
                Mejora tu Colección
            </h1>
            <p class="text-gray-400 text-sm mb-6">
                Compra, vende e intercambia los últimos lanzamientos y rarezas retro en el marketplace líder de videojuegos.
            </p>
            <div class="flex gap-3">
                <a href="{{ route('games.sell') }}" class="bg-[#009194] hover:bg-[#007a7c] text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    Empieza a Vender
                </a>
            </div>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-[#009194]/30 to-transparent pointer-events-none"></div>
    </section>

    {{-- ===== BARRA DE BÚSQUEDA ===== --}}
    <section class="mb-8">
        <form action="{{ route('home') }}" method="GET">
            @if(request('platform'))
                <input type="hidden" name="platform" value="{{ request('platform') }}">
            @endif
            <div class="flex items-center gap-3 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 mb-4">
                <button type="submit" class="text-gray-500 hover:text-[#009194] transition-colors text-sm">🔍</button>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Busca juegos, consolas o accesorios..."
                    class="bg-transparent text-sm text-gray-300 placeholder-gray-500 outline-none flex-1"
                />
            </div>
        </form>

        {{-- Filtros de plataforma --}}
    <div class="flex gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="text-xs px-3 py-1.5 rounded-full {{ !request('platform') ? 'bg-[#009194] border border-[#009194] text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Todas las Plataformas</a>
            <a href="{{ route('home', ['platform' => 'PS5']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'PS5' ? 'bg-[#009194] border border-[#009194] text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">PlayStation 5</a> 
            <a href="{{ route('home', ['platform' => 'XBOX']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'XBOX' ? 'bg-[#009194] border border-[#009194] text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Xbox Series X</a> 
            <a href="{{ route('home', ['platform' => 'SWITCH']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'SWITCH' ? 'bg-[#009194] border border-[#009194] text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Nintendo Switch</a> 
            <a href="{{ route('home', ['platform' => 'PC']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'PC' ? 'bg-[#009194] border border-[#009194] text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">PC Gaming</a> 
            <a href="{{ route('home', ['platform' => 'RETRO']) }}" class="text-xs px-3 py-1.5 rounded-full {{ request('platform') == 'RETRO' ? 'bg-[#009194] border border-[#009194] text-white' : 'border border-gray-600 text-gray-400 hover:text-white transition-colors' }}">Retro</a> 
    </div>
    </section>

    {{-- ===== TRENDING NOW ===== --}}
    <section class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-white font-bold text-lg flex items-center gap-2">⚡ Lo más popular</h2>
            <a href="{{ route('home', array_filter(['platform' => request('platform'), 'rating' => '4.5'])) }}" class="text-xs text-[#3bb1a5] hover:text-[#009194] transition-colors">Ver todo →</a>
        </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @forelse($games as $game)
<a href="{{ route('games.show', $game->id) }}"
   class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-[#009194] transition-colors group block">

    {{-- Imagen --}}
    <div class="relative h-36 bg-gray-700 flex items-center justify-center">
        @if($game->cover_image)
            <img src="{{ $game->cover_image }}"
                 alt="{{ $game->title }}"
                 class="w-full h-full object-cover">
        @else
            <span class="text-4xl">🎮</span>
        @endif

        <span class="absolute top-2 left-2 text-xs bg-gray-900/80 text-gray-300 px-2 py-0.5 rounded font-medium">
            {{ $game->platform }}
        </span>

        <button class="absolute top-2 right-2 text-gray-400 hover:text-red-400 transition-colors"onclick="event.stopPropagation(); event.preventDefault();">♡</button>
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
        </div>
    </div>
</a>

            @empty
                {{-- Sin juegos --}}
                <div class="col-span-4 text-center text-gray-500 py-16">
                    <p class="text-5xl mb-4">🎮</p>
                    <p class="text-sm">No hay juegos disponibles de momento.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- links de paginación --}}
    {{ $games->links() }}

@endsection