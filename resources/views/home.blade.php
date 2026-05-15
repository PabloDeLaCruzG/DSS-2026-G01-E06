@extends('layouts.app')

@section('title', 'GameLink - Marketplace de Videojuegos')

@section('content')

<!-- LOCAL OVERRIDES (manteniendo @theme): colores y paginación para que se vea como en el diseño -->
<style>
/* Contenedor de paginación: alineado a la derecha y sin márgenes extra */
.custom-pagination-wrapper { margin-top: 1rem; display: flex; justify-content: flex-end; align-items: center; }

/* Estilos para el markup estándar de Laravel pagination (.pagination .page-item .page-link) */
.custom-pagination-wrapper .pagination { margin: 0; padding: 0; display: inline-flex; gap: 0.5rem; list-style: none; align-items: center; }

/* Link base */
.custom-pagination-wrapper .pagination .page-link {
  background: var(--color-surface, #1a2433);
  color: var(--color-text-muted, #9ba4b0);
  border: 1px solid var(--color-border, #2a3544);
  padding: .35rem .7rem;
  border-radius: 10px;
  min-width: 36px;
  height: 36px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: none;
  transition: all .12s ease;
}

/* Hover / focus */
.custom-pagination-wrapper .pagination .page-link:hover,
.custom-pagination-wrapper .pagination .page-link:focus {
  background: rgba(0,145,148,0.06);
  color: var(--color-text-main, #ffffff);
  border-color: rgba(0,145,148,0.18);
  text-decoration: none;
}

/* Página activa (pill azul) */
.custom-pagination-wrapper .pagination .page-item.active .page-link {
  background: var(--color-primary, #009194);
  color: var(--color-text-main, #ffffff);
  border-color: var(--color-primary, #009194);
  box-shadow: none;
}

/* Flechas / prev-next: hacerlas más discretas */
.custom-pagination-wrapper .pagination .page-item:first-child .page-link,
.custom-pagination-wrapper .pagination .page-item:last-child .page-link {
  background: var(--color-surface, #1a2433);
}

/* Disabled */
.custom-pagination-wrapper .pagination .page-item.disabled .page-link {
  opacity: .45;
  cursor: not-allowed;
  pointer-events: none;
}

/* Botones principales (buscar, etc.) */
.btn-primary {
  background: var(--color-primary, #009194) !important;
  border-color: var(--color-primary, #009194) !important;
  color: var(--color-text-main, #ffffff) !important;
  box-shadow: none;
}
.btn-primary:hover, .btn-primary:focus {
  background: var(--color-primary-hover, #007a7c) !important;
  border-color: var(--color-primary-hover, #007a7c) !important;
}

/* Outline secundario (botón filtros) */
.btn-outline-secondary {
  color: var(--color-text-main, #ffffff) !important;
  background: transparent !important;
  border: 1px solid var(--color-border, #2a3544) !important;
}
.btn-outline-secondary:focus, .btn-outline-secondary:hover {
  background: rgba(0,145,148,0.06) !important;
  color: var(--color-accent, #3bb1a5) !important;
}

/* Tarjetas */
.card {
  background-color: var(--color-surface, #1a2433) !important;
  border: 1px solid var(--color-border, #2a3544) !important;
  color: var(--color-text-main, #ffffff) !important;
  border-radius: 12px;
}

/* Texto secundario y small */
.text-muted, .small, .form-text {
  color: var(--color-text-muted, #9ba4b0) !important;
}

/* Iconos de filtros heredan color correcto */
.filter-link svg { color: var(--color-text-muted, #9ba4b0); fill: currentColor; }
.filter-link.active svg { color: var(--color-accent, #3bb1a5); }

/* Favorito (corazón) mejor contraste */
.favorite-btn { box-shadow: none; }

/* Ajustes responsive: paginación más compacta en móviles */
@media (max-width: 576px) {
  .custom-pagination-wrapper .pagination .page-link {
    min-width: 30px;
    height: 30px;
    padding: .2rem .45rem;
    border-radius: 8px;
    font-size: .875rem;
  }

  /* Asegurar separación inferior en grid móvil */
  #game-grid { margin-bottom: 0.5rem; }
}
</style>

    {{-- ==== HERO BANNER ==== --}}
    <section class="relative rounded-xl overflow-hidden mb-10 mt-4" style="background: linear-gradient(135deg, var(--color-background) 0%, var(--color-surface) 50%, var(--color-background) 100%); min-height: 320px;">
        <div class="absolute inset-0 opacity-50" style="background: url('https://plus.unsplash.com/premium_vector-1725298133648-3ab96f885592?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover no-repeat;"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(13,21,32,0.95) 10%, rgba(13,21,32,0.3) 100%);"></div>

        <div class="relative z-10 flex flex-col items-center justify-center text-center px-4 py-16">
            <h1 class="text-5xl font-bold mb-4 leading-tight" style="color: var(--color-text-main);">
                Encuentra tu próximo juego<br>al mejor precio
            </h1>
            <p class="mb-8 max-w-xl" style="color: var(--color-text-muted);">
                La mayor comunidad de compra y venta de videojuegos. Únete a miles de jugadores y ahorra en tus títulos favoritos.
            </p>

            {{-- Barra de búsqueda hero --}}
            <form action="{{ route('home') }}" method="GET" class="w-full max-w-2xl px-3">
                @if(request('platform'))
                    <input type="hidden" name="platform" value="{{ request('platform') }}">
                @endif
                <div class="d-flex align-items-center rounded-xl px-3 py-2 gap-2" style="background: rgba(17,17,17,0.55); border: 1px solid var(--color-border);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color: var(--color-text-muted);">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                    id="hero-search-input"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="¿Qué quieres jugar hoy?"
                    class="form-control bg-transparent border-0 p-0"
                    style="color: var(--color-text-main); box-shadow: none;"
                    autocomplete="off"
                    />
                    <button type="submit" class="btn btn-primary ms-2" style="background: var(--color-primary); border-color: var(--color-primary); color: var(--color-text-main);">
                        Buscar
                    </button>
                </div>
            </form>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('hero-search-input');
                const gameGrid = document.getElementById('game-grid');
                if (!searchInput || !gameGrid) return;
                const allGames = Array.from(gameGrid.querySelectorAll('.col'));
                searchInput.addEventListener('input', function() {
                    const value = this.value.trim().toLowerCase();
                    if (value === '') {
                    allGames.forEach(el => el.style.display = '');
                    return;
                    }
                    allGames.forEach(el => {
                    const title = el.querySelector('h3')?.textContent?.toLowerCase() || '';
                    el.style.display = title.includes(value) ? '' : 'none';
                    });
                });
            });
            </script>
        </div>
    </section>

    {{-- ==== LAYOUT PRINCIPAL: SIDEBAR + CONTENIDO ==== --}}
    <div class="d-flex gap-3 flex-column flex-md-row">

        {{-- BOTÓN FILTROS (MÓVIL) --}}
        <div class="d-md-none w-100 px-3">
            <button class="btn btn-outline-secondary w-100 mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarFilters" aria-controls="sidebarFilters" style="border-color: var(--color-border); color: var(--color-text-main);">
                🔍 Filtros
            </button>

            <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarFilters" aria-labelledby="sidebarFiltersLabel">
                <div class="offcanvas-header" style="border-bottom: 1px solid var(--color-border);">
                    <h5 class="offcanvas-title" id="sidebarFiltersLabel" style="color: var(--color-text-main);">Filtros</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    @include('partials.filters', ['mode' => 'mobile'])
                </div>
            </div>
        </div>

        {{-- SIDEBAR DESKTOP (include partial) --}}
        <aside class="d-none d-md-block flex-shrink-0 px-3" style="width: 220px;">
            @include('partials.filters', ['mode' => 'desktop'])
        </aside>

        {{-- ==== CONTENIDO PRINCIPAL ==== --}}
        <div id="game-grid-wrapper" class="flex-1 min-w-0 px-3">

            {{-- Cabecera sección --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="fw-bold fs-5 mb-0" style="color: var(--color-text-main);">Explorar Juegos</h2>

                <form id="sort-form" action="{{ route('home') }}" method="GET" class="d-flex align-items-center gap-2">
                    {{-- Mantener los filtros actuales como inputs ocultos --}}
                    @foreach(request()->except('sort') as $k => $v)
                    @if(is_array($v))
                    @foreach($v as $vv)
                    <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                    @endforeach
                    @else
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endif
                    @endforeach

                    <label class="small me-2" style="color: var(--color-text-muted);">Ordenar por:</label>
                    <select name="sort" onchange="document.getElementById('sort-form').submit()"
                    class="form-select form-select-sm w-auto" style="background: transparent; border: 1px solid var(--color-border); color: var(--color-text-main);">
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Más populares</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Más barato</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Más caro</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
                    </select>
                </form>
            </div>

            {{-- Grid de juegos (Bootstrap) --}}
            <div id="game-grid" class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 g-3">
                @forelse($games as $game)
                    <div class="col">
                    <div class="card h-100 position-relative" style="background-color: var(--color-surface); border: 1px solid var(--color-border); border-radius: 12px; overflow: hidden;">
                    <a href="{{ route('games.show', $game->id) }}" class="text-decoration-none text-reset d-block h-100">
                    {{-- Imagen --}}
                    <div class="d-flex align-items-center justify-content-center position-relative" style="height: 300px; background-color: rgba(0,0,0,0.06);">
                    @if($game->cover_image)
                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                    <span class="fs-1">🎮</span>
                    @endif

                    {{-- Badge plataforma --}}
                    @if(!empty($game->platforms))
                    @php $platforms = is_array($game->platforms) ? $game->platforms : [$game->platforms]; @endphp
                    <div class="position-absolute top-2 start-2 d-flex flex-wrap gap-1 pe-5">
                    @foreach($platforms as $platform)
                    <span class="small text-white px-2 py-0.5 rounded" style="background-color: {{ ['PS5' => '#1a4fc4', 'Xbox' => '#107c10', 'Nintendo Switch' => '#e4000f', 'PC' => 'var(--color-primary)', 'Retro' => '#b45309'][$platform] ?? 'var(--color-primary)'}};">
                    {{ $platform }}
                    </span>
                    @endforeach
                    </div>
                    @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-3">
                    <p class="mb-1 small" style="color: var(--color-text-muted);">{{ $game->genre }}</p>
                    <h3 class="mb-2 small fw-semibold text-truncate" title="{{ $game->title }}" style="color: var(--color-text-main);">{{ $game->title }}</h3>

                    @if($game->rating)
                    <div class="d-flex align-items-center gap-1 mb-2">
                    <span class="text-warning small">★</span>
                    <span class="small" style="color: var(--color-text-muted);">{{ number_format($game->rating, 1) }}</span>
                    </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-bold small" style="color: var(--color-text-main);">
                    @if($game->getLowestPrice())
                    {{ number_format($game->getLowestPrice(), 2) }}€
                    <span class="small ms-1" style="color: var(--color-text-muted);">
                    {{ $game->game_ads_count }} {{ $game->game_ads_count == 1 ? 'oferta' : 'ofertas' }}
                    </span>
                    @else
                    <span class="small" style="color: var(--color-text-muted);">Sin stock</span>
                    @endif
                    </span>
                    @if($game->getLowestPrice())
                    <span class="small px-2 py-1 rounded" style="background: rgba(0,145,148,0.12); color: var(--color-accent);">Ver Ofertas</span>
                    @endif
                    </div>
                    </div>
                    </a>

                    {{-- Corazón --}}
                    @auth
                    <button
                    class="favorite-btn position-absolute" style="top:12px; right:12px; width:34px; height:34px; border-radius:50%; background: rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; color: {{ in_array($game->id, $favoriteIds) ? '#f87171' : '#d1d5db' }};"
                    data-game-id="{{ $game->id }}"
                    data-favorited="{{ in_array($game->id, $favoriteIds) ? 'true' : 'false' }}"
                    onclick="toggleFavorite(this)">
                    {{ in_array($game->id, $favoriteIds) ? '♥' : '♡' }}
                    </button>
                    @else
                    <a href="{{ route('login') }}"
                    class="position-absolute" style="top:12px; right:12px; width:34px; height:34px; border-radius:50%; background: rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; color: var(--color-text-muted); text-decoration:none;">
                    ♡
                    </a>
                    @endauth

                    </div>
                    </div>
                @empty
                    <div class="col-12 text-center" style="color: var(--color-text-muted); padding: 4rem 0;">
                    <p class="fs-1 mb-3">🎮</p>
                    <p class="small">No hay juegos disponibles de momento.</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginación --}}
<div class="mt-4">
    <nav class="d-flex justify-content-end align-items-center custom-pagination-wrapper" aria-label="Paginación">
        {{ $games->links() }}
    </nav>
</div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
function toggleFavorite(btn) {
    event.preventDefault();
    event.stopPropagation();

    const gameId = btn.getAttribute('data-game-id');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/favorites/${gameId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) alert('Debes iniciar sesión');
            throw new Error('Error en la petición');
        }
        return response.json();
    })
    .then(data => {
        if (data.favorited) {
            btn.textContent = '♥';
            btn.style.color = '#f87171';
        } else {
            btn.textContent = '♡';
            btn.style.color = '#d1d5db';
        }
    })
    .catch(error => console.error('Error:', error));
}

(function () {
    const ROUTE_HOME = '{{ route('home') }}';
    let debounceTimer = null;

    function debounce(fn, delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fn, delay);
    }

    async function loadFiltered(params) {
        const url = ROUTE_HOME + (params.toString() ? '?' + params.toString() : '');
        const wrapper = document.getElementById('game-grid-wrapper');
        if (!wrapper) return;

        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) return;
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newWrapper = doc.getElementById('game-grid-wrapper');
            if (newWrapper) {
                wrapper.innerHTML = newWrapper.innerHTML;
            }
            history.pushState({}, '', url);
            updateSidebarActive(params);
        } catch (e) {
            console.error('Filter error:', e);
        } finally {
            wrapper.style.opacity = '';
            wrapper.style.pointerEvents = '';
        }
    }

    function updateSidebarActive(params) {
        const platform = params.get('platform') ?? '';
        const genre = params.get('genre') ?? '';

        document.querySelectorAll('[data-platform]').forEach(el => {
            const isActive = el.dataset.platform === platform;
            el.classList.toggle('active', isActive);
            el.classList.toggle('text-muted', !isActive);
        });

        document.querySelectorAll('[data-genre]').forEach(el => {
            const isActive = el.dataset.genre === genre;
            el.classList.toggle('active', isActive);
            el.classList.toggle('text-muted', !isActive);
        });
    }

    document.addEventListener('click', function (e) {
        const filterLink = e.target.closest('.filter-link');
        if (filterLink) {
            e.preventDefault();
            const params = new URLSearchParams(window.location.search);
            params.delete('page');

            if ('platform' in filterLink.dataset) {
                if (filterLink.dataset.platform === '') {
                    params.delete('platform');
                } else {
                    params.set('platform', filterLink.dataset.platform);
                }
            }
            if ('genre' in filterLink.dataset) {
                if (filterLink.dataset.genre === '') {
                    params.delete('genre');
                } else {
                    params.set('genre', filterLink.dataset.genre);
                }
            }

            loadFiltered(params);
            return;
        }

        const wrapper = document.getElementById('game-grid-wrapper');
        if (wrapper) {
            const paginationLink = e.target.closest('a[href]');
            if (paginationLink && wrapper.contains(paginationLink)) {
                const linkUrl = new URL(paginationLink.href, window.location.origin);
                if (linkUrl.searchParams.has('page')) {
                    e.preventDefault();
                    loadFiltered(linkUrl.searchParams);
                }
            }
        }
    });

    // Price & rating handlers (sync mobile <-> desktop)
    const priceRangeMobile = document.getElementById('price-range');
    const priceValueMobile = document.getElementById('price-value');
    const priceRangeDesktop = document.getElementById('price-range-desktop');
    const priceValueDesktop = document.getElementById('price-value-desktop');

    function handlePriceInput(elem, valueElem) {
        if (!elem || !valueElem) return;
        elem.addEventListener('input', function () {
            valueElem.textContent = '$' + this.value + (this.value == 200 ? '+' : '');
            if (elem === priceRangeMobile && priceRangeDesktop) priceRangeDesktop.value = this.value;
            if (elem === priceRangeDesktop && priceRangeMobile) priceRangeMobile.value = this.value;

            const params = new URLSearchParams(window.location.search);
            params.delete('page');
            if (this.value == 200) {
                params.delete('max_price');
            } else {
                params.set('max_price', this.value);
            }
            debounce(() => loadFiltered(params), 500);
        });
    }

    handlePriceInput(priceRangeMobile, priceValueMobile);
    handlePriceInput(priceRangeDesktop, priceValueDesktop);

    const ratingRangeMobile = document.getElementById('rating-range');
    const ratingValueMobile = document.getElementById('rating-value');
    const ratingRangeDesktop = document.getElementById('rating-range-desktop');
    const ratingValueDesktop = document.getElementById('rating-value-desktop');

    function handleRatingInput(elem, valueElem) {
        if (!elem || !valueElem) return;
        elem.addEventListener('input', function () {
            valueElem.textContent = '★ ' + parseFloat(this.value).toFixed(1);
            if (elem === ratingRangeMobile && ratingRangeDesktop) ratingRangeDesktop.value = this.value;
            if (elem === ratingRangeDesktop && ratingRangeMobile) ratingRangeMobile.value = this.value;

            const params = new URLSearchParams(window.location.search);
            params.delete('page');
            if (parseFloat(this.value) === 0) {
                params.delete('rating');
            } else {
                params.set('rating', this.value);
            }
            debounce(() => loadFiltered(params), 500);
        });
    }

    handleRatingInput(ratingRangeMobile, ratingValueMobile);
    handleRatingInput(ratingRangeDesktop, ratingValueDesktop);

    window.addEventListener('popstate', function () {
        const params = new URLSearchParams(window.location.search);
        if (priceRangeMobile) priceRangeMobile.value = params.get('max_price') ?? 200;
        if (priceRangeDesktop) priceRangeDesktop.value = params.get('max_price') ?? 200;

        if (ratingRangeMobile) ratingRangeMobile.value = params.get('rating') ?? 0;
        if (ratingRangeDesktop) ratingRangeDesktop.value = params.get('rating') ?? 0;

        if (priceValueMobile) priceValueMobile.textContent = '$' + (params.get('max_price') ?? '200') + (!params.get('max_price') ? '+' : '');
        if (priceValueDesktop) priceValueDesktop.textContent = '$' + (params.get('max_price') ?? '200') + (!params.get('max_price') ? '+' : '');

        if (ratingValueMobile) ratingValueMobile.textContent = '★ ' + parseFloat(params.get('rating') ?? 0).toFixed(1);
        if (ratingValueDesktop) ratingValueDesktop.textContent = '★ ' + parseFloat(params.get('rating') ?? 0).toFixed(1);

        updateSidebarActive(params);
        loadFiltered(params);
    });
})();
</script>
@endpush