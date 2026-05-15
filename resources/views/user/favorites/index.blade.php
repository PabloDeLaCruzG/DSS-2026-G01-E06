@extends('user.layout')

@section('title', 'Mis Favoritos')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Mis Favoritos</h1>
    <a href="{{ route('home') }}" class="text-sm text-primary hover:underline">← Explorar juegos</a>
</div>

@if($favorites->isEmpty())
    <div class="bg-surface border border-border rounded-xl p-12 text-center">
        <p class="text-5xl mb-4">♡</p>
        <p class="text-text-muted text-sm">Aún no tienes juegos favoritos.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block bg-primary text-white px-5 py-2 rounded-lg text-sm hover:bg-primary-hover transition-colors">
            Explorar juegos
        </a>
    </div>
@else
    <div class="bg-surface border border-border rounded-xl overflow-hidden">

        {{-- Cabecera tabla (solo desktop) --}}
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 border-b border-border text-xs text-text-muted uppercase tracking-wider">
            <div class="col-span-6">Juego</div>
            <div class="col-span-2 text-center">Plataforma</div>
            <div class="col-span-2 text-center">Ofertas</div>
            <div class="col-span-2 text-center">Acciones</div>
        </div>

        {{-- Filas --}}
        @foreach($favorites as $game)
            <div data-fav-row class="flex flex-wrap items-center gap-3 px-4 py-4 border-b border-border/50 hover:bg-background/50 transition-colors md:grid md:grid-cols-12 md:gap-4 md:px-6">

                {{-- Imagen + título --}}
                <div class="w-full md:col-span-6 flex items-center gap-4">
                    <div class="w-12 h-16 rounded-lg overflow-hidden bg-background flex-shrink-0">
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl">🎮</div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <a href="{{ route('games.show', $game->id) }}" class="text-sm font-semibold text-text-main hover:text-primary transition-colors truncate block">
                            {{ $game->title }}
                        </a>
                        <p class="text-xs text-text-muted mt-0.5">{{ $game->genre }}</p>
                        @if($game->rating)
                            <p class="text-xs text-yellow-400 mt-0.5">★ {{ number_format($game->rating, 1) }}</p>
                        @endif
                        {{-- Precio visible solo en móvil --}}
                        @if($game->getLowestPrice())
                            <p class="text-sm font-bold text-text-main mt-1 md:hidden">
                                {{ number_format($game->getLowestPrice(), 2) }}€
                                <span class="text-xs font-normal text-text-muted ml-1">{{ $game->game_ads_count }} {{ $game->game_ads_count == 1 ? 'oferta' : 'ofertas' }}</span>
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Plataforma (solo desktop) --}}
                <div class="hidden md:block md:col-span-2 text-center">
                    @if(!empty($game->platforms))
                        @php $platform = is_array($game->platforms) ? $game->platforms[0] : $game->platforms; @endphp
                        <span class="text-xs text-white px-2 py-0.5 rounded font-medium"
                              style="background-color: {{ ['PS5' => '#1a4fc4', 'Xbox' => '#107c10', 'Nintendo Switch' => '#e4000f', 'PC' => '#374151', 'Retro' => '#b45309'][$platform] ?? '#009194' }}">
                            {{ $platform }}
                        </span>
                    @else
                        <span class="text-xs text-text-muted">—</span>
                    @endif
                </div>

                {{-- Ofertas (solo desktop) --}}
                <div class="hidden md:block md:col-span-2 text-center">
                    @if($game->getLowestPrice())
                        <p class="text-sm font-bold text-text-main">{{ number_format($game->getLowestPrice(), 2) }}€</p>
                        <p class="text-xs text-text-muted">{{ $game->game_ads_count }} {{ $game->game_ads_count == 1 ? 'oferta' : 'ofertas' }}</p>
                    @else
                        <span class="text-xs text-text-muted">Sin stock</span>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="w-full md:col-span-2 flex items-center gap-3 md:justify-center">
                    <a href="{{ route('games.show', $game->id) }}"
                       class="flex-1 md:flex-none text-center text-xs bg-primary/10 text-primary hover:bg-primary/20 px-3 py-2 rounded-lg transition-colors">
                        Ver ofertas
                    </a>
                    <button onclick="removeFavorite(this)"
                            data-game-id="{{ $game->id }}"
                            class="text-text-muted hover:text-red-400 transition-colors shrink-0"
                            title="Quitar de favoritos">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>

            </div>
        @endforeach

        {{-- Footer con total --}}
        <div class="px-6 py-3 text-xs text-text-muted">
            Mostrando {{ $favorites->firstItem() }}-{{ $favorites->lastItem() }} de {{ $favorites->total() }} favoritos
        </div>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $favorites->links('user.pagination') }}
    </div>
@endif

<script>
function removeFavorite(btn) {
    const gameId = btn.getAttribute('data-game-id');
    fetch(`/favorites/${gameId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.favorited) {
            btn.closest('[data-fav-row]').remove();
        }
    });
}
</script>

@endsection