@extends('layouts.app')

@section('title', 'GameLink - Marketplace de Videojuegos')

@section('content')

    {{-- Aviso entrega --}}
    <div id="delivery-warning" class="flex items-start gap-3 p-4 rounded-lg text-sm mb-6 mt-4" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.35);color:#fde047;">
        <svg class="w-5 h-5 mt-0.5 shrink-0" style="color:#facc15;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <div class="flex-1">
            <span class="font-semibold">Aviso de entrega:</span>
            algunos botones y funciones de esta página están sin acción o pueden comportarse de forma incorrecta, ya que no estaban planificados para la entrega actual.
        </div>
        <button onclick="document.getElementById('delivery-warning').remove()" style="color:#facc15;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ===== HERO BANNER ===== --}}
    <section class="relative rounded-xl overflow-hidden mb-10 mt-4" style="background: linear-gradient(135deg, #0d1520 0%, #1a2433 50%, #0d2030 100%); min-height: 320px;">
        {{-- Imagen de fondo difuminada --}}
        <div class="absolute inset-0 opacity-50" style="background: url('https://plus.unsplash.com/premium_vector-1725298133648-3ab96f885592?fm=jpg&amp;q=60&amp;w=3000&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.1.0&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(13,21,32,0.95) 10%, rgba(13,21,32,0.3) 100%);"></div>
        
        <div class="relative z-10 flex flex-col items-center justify-center text-center px-8 py-20">
            <h1 class="text-5xl font-bold text-white mb-4 leading-tight">
                Encuentra tu próximo juego<br>al mejor precio
            </h1>
            <p class="text-gray-400 text-base mb-8 max-w-xl">
                La mayor comunidad de compra y venta de videojuegos. Únete a miles de jugadores y ahorra en tus títulos favoritos.
            </p>

            {{-- Barra de búsqueda hero --}}
            <form action="{{ route('home') }}" method="GET" class="w-full max-w-2xl">
                @if(request('platform'))
                    <input type="hidden" name="platform" value="{{ request('platform') }}">
                @endif
                <div class="flex items-center bg-gray-800/80 backdrop-blur border border-gray-600 rounded-xl px-4 py-3 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="¿Qué quieres jugar hoy?"
                        class="bg-transparent text-sm text-white placeholder-gray-400 outline-none flex-1"
                    />
                    <button type="submit" class="bg-[#009194] hover:bg-[#007a7c] text-white text-sm font-semibold px-5 py-2 rounded-lg transition-colors flex-shrink-0">
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- ===== LAYOUT PRINCIPAL: SIDEBAR + CONTENIDO ===== --}}
    <div class="flex gap-7">

        {{-- ===== SIDEBAR IZQUIERDA ===== --}}
        <aside class="hidden md:flex flex-col gap-6 w-52 flex-shrink-0 sticky top-4 self-start h-fit max-h-screen overflow-y-auto">

            {{-- Plataforma --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Plataforma</h3>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('home', array_filter(['search' => request('search')])) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ !request('platform') ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span>🎮</span> Todas
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'PS5', 'search' => request('search')])) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'PS5' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span>🎮</span> PS5
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'PC', 'search' => request('search')])) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'PC' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span>🖥️</span> PC
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'XBOX', 'search' => request('search')])) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'XBOX' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span>🎯</span> Xbox Series
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'SWITCH', 'search' => request('search')])) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'SWITCH' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span>🕹️</span> Nintendo Switch
                    </a>
                    <a href="{{ route('home', array_filter(['platform' => 'RETRO', 'search' => request('search')])) }}"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('platform') == 'RETRO' ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        <span>👾</span> Retro
                    </a>
                </div>
            </div>

            {{-- Separador --}}
            <div class="border-t border-gray-700"></div>

            {{-- Géneros --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Géneros</h3>
                <div class="flex flex-col gap-1">
                    @foreach([
                        ['value' => 'ACTION',   'label' => 'Acción',    'emoji' => '⚔️'],
                        ['value' => 'RPG',      'label' => 'RPG',       'emoji' => '🧙'],
                        ['value' => 'SPORTS',   'label' => 'Deportes',  'emoji' => '⚽'],
                        ['value' => 'STRATEGY', 'label' => 'Estrategia','emoji' => '♟️'],
                    ] as $genre)
                        <a href="{{ route('home', array_filter(['platform' => request('platform'), 'genre' => $genre['value'], 'search' => request('search')])) }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors {{ request('genre') == $genre['value'] ? 'bg-[#009194]/20 text-[#3bb1a5] font-medium' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                            <span>{{ $genre['emoji'] }}</span> {{ $genre['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Separador --}}
            <div class="border-t border-gray-700"></div>

            {{-- Rango de precio --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Rango de Precio</h3>
                <input 
                    type="range" 
                    min="0" 
                    max="200" 
                    value="{{ request('max_price', 200) }}"
                    id="price-range"
                    class="w-full accent-[#009194]"
                    oninput="document.getElementById('price-value').textContent = '$' + this.value + (this.value == 200 ? '+' : '')"
                    onchange="window.location='{{ route('home') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), max_price: this.value}).toString()"
                />
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>$0</span>
                    <span id="price-value">${{ request('max_price', 200) }}{{ request('max_price', 200) == 200 ? '+' : '' }}</span>
                </div>
            </div>

            {{-- Separador --}}
            <div class="border-t border-gray-700"></div>

            {{-- Rating --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Valoración mínima</h3>
                <input 
                    type="range" 
                    min="0" 
                    max="5" 
                    step="0.1"
                    value="{{ request('rating', 0) }}"
                    id="rating-range"
                    class="w-full accent-[#009194]"
                    oninput="document.getElementById('rating-value').textContent = '★ ' + parseFloat(this.value).toFixed(1)"
                    onchange="window.location='{{ route('home') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), rating: this.value}).toString()"
                />
                <div class="flex justify-between text-xs text-gray-400 mt-1">
                    <span>★ 0</span>
                    <span id="rating-value">★ {{ number_format(request('rating', 0), 1) }}</span>
                </div>
            </div>

        </aside>

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <div class="flex-1 min-w-0">

            {{-- Cabecera sección --}}
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-white font-bold text-lg">Explorar Juegos</h2>
                <a href="{{ route('home', array_filter(['platform' => request('platform'), 'rating' => '4.5'])) }}"
                   class="text-xs text-[#3bb1a5] hover:text-[#009194] transition-colors">Ver todo →</a>
            </div>

            {{-- Grid de juegos --}}
            <div class="grid grid-cols-3 md:grid-cols-4 gap-4">
                @forelse($games as $game)
                    <a href="{{ route('games.show', $game->id) }}"
                       class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden hover:border-[#009194] transition-colors group block">

                        {{-- Imagen --}}
                        <div class="relative bg-gray-700 flex items-center justify-center" style="height: 300px;">
                            @if($game->cover_image)
                                <img src="{{ $game->cover_image }}"
                                     alt="{{ $game->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl">🎮</span>
                            @endif

                            {{-- Badge plataforma --}}
                            @if(!empty($game->platforms))
                                @php $platform = is_array($game->platforms) ? $game->platforms[0] : $game->platforms; @endphp
                                <span class="absolute top-2 left-2 text-xs text-white px-2 py-0.5 rounded font-medium"
                                    style="background-color: {{ ['PS5' => '#1a4fc4', 'XBOX' => '#107c10', 'SWITCH' => '#e4000f', 'PC' => '#111111', 'RETRO' => '#b45309'][$platform] ?? '#009194' }}">
                                    {{ $platform }}
                                </span>
                            @endif

                            {{-- Corazón --}}
                            <button class="absolute top-2 right-2 text-gray-300 hover:text-red-400 transition-colors bg-gray-900/60 rounded-full p-1 w-4 h-4 flex items-center justify-center"
                                    onclick="event.stopPropagation(); event.preventDefault();">♡</button>
                        </div>

                        {{-- Info --}}
                        <div class="p-3">
                            <p class="text-gray-500 text-xs mb-0.5">{{ $game->genre }}</p>
                            <h3 class="text-white text-sm font-semibold mb-2 truncate" title="{{ $game->title }}">
                                {{ $game->title }}
                            </h3>

                            {{-- Estrellas de rating --}}
                            @if($game->rating)
                                <div class="flex items-center gap-1 mb-2">
                                    <span class="text-yellow-400 text-xs">★</span>
                                    <span class="text-gray-400 text-xs">{{ number_format($game->rating, 1) }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-white font-bold text-sm">
                                    @if($game->getLowestPrice())
                                        {{ number_format($game->getLowestPrice(), 2) }}€
                                        <span class="text-gray-500 font-normal text-xs ml-1">
                                            {{ $game->game_ads_count }} {{ $game->game_ads_count == 1 ? 'oferta' : 'ofertas' }}
                                        </span>
                                    @else
                                        <span class="text-gray-500 font-normal text-xs">Sin stock</span>
                                    @endif
                                </span>
                                @if($game->getLowestPrice())
                                    <span class="text-xs bg-[#009194]/20 text-[#3bb1a5] px-2 py-0.5 rounded font-medium">Ver Ofertas</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-16">
                        <p class="text-5xl mb-4">🎮</p>
                        <p class="text-sm">No hay juegos disponibles de momento.</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="mt-8">
                {{ $games->links() }}
            </div>

        </div>
    </div>

@endsection