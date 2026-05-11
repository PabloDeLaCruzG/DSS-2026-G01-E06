@extends('admin.layouts.layout')
@section('title', 'Catálogo de Juegos')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-main">Catálogo de Juegos</h1>
            <p class="text-sm text-text-muted mt-1">Gestiona los juegos disponibles en el marketplace.</p>
        </div>
        <a href="{{ route('admin.games.create') }}"
           class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-hover rounded-lg text-sm font-medium text-white transition shadow-lg shadow-primary/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nuevo Juego
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-accent/10 border border-accent/30 text-accent rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-surface rounded-xl border border-border p-5">
            <p class="text-xs text-text-muted uppercase tracking-wide mb-1">Total Juegos</p>
            <p class="text-2xl font-bold text-text-main">{{ $totalGames }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-5">
            <p class="text-xs text-text-muted uppercase tracking-wide mb-1">Publicados</p>
            <p class="text-2xl font-bold text-accent">{{ $published }}</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-5">
            <p class="text-xs text-text-muted uppercase tracking-wide mb-1">Ocultos</p>
            <p class="text-2xl font-bold text-text-muted">{{ $hidden }}</p>
        </div>
    </div>

    {{-- Filtros + Búsqueda --}}
    <div class="space-y-3">
        <div class="relative max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
            <form method="GET">
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por título..."
                    class="w-full bg-surface border border-border rounded-lg pl-9 pr-4 py-2 text-sm text-text-main placeholder-text-muted focus:outline-none focus:border-primary transition">
            </form>
        </div>

        <div class="flex gap-2">
            @foreach(['' => 'Todos', 'published' => 'Publicados', 'hidden' => 'Ocultos'] as $value => $label)
                <a href="{{ route('admin.games.index', array_merge(request()->except('filter','page'), $value ? ['filter' => $value] : [])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition
                   {{ request('filter','') === $value
                       ? 'bg-primary text-white shadow-sm shadow-primary/30'
                       : 'bg-surface text-text-muted hover:text-text-main border border-border' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border text-xs text-text-muted uppercase tracking-wide">
                    <th class="text-left px-5 py-3">Juego</th>
                    <th class="text-left px-5 py-3">Año</th>
                    <th class="text-left px-5 py-3">Plataformas</th>
                    <th class="text-left px-5 py-3">Rating</th>
                    <th class="text-left px-5 py-3">Anuncios</th>
                    <th class="text-left px-5 py-3">Estado</th>
                    <th class="text-right px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($games as $game)
                <tr class="hover:bg-background/50 transition">
                    {{-- Juego --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-14 rounded bg-background border border-border overflow-hidden flex-shrink-0">
                                @if($game->cover_image)
                                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-text-muted">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-text-main">{{ $game->title }}</p>
                                @if($game->genres)
                                    <p class="text-xs text-text-muted mt-0.5">{{ implode(', ', array_slice($game->genres, 0, 3)) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Año --}}
                    <td class="px-5 py-4 text-text-muted">{{ $game->year ?? '—' }}</td>

                    {{-- Plataformas --}}
                    <td class="px-5 py-4">
                        <div class="flex flex-wrap gap-1">
                            @if($game->platforms)
                                @foreach(array_slice($game->platforms, 0, 3) as $p)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-border text-text-muted">{{ $p }}</span>
                                @endforeach
                                @if(count($game->platforms) > 3)
                                    <span class="text-[10px] text-text-muted">+{{ count($game->platforms) - 3 }}</span>
                                @endif
                            @else
                                <span class="text-text-muted">—</span>
                            @endif
                        </div>
                    </td>

                    {{-- Rating --}}
                    <td class="px-5 py-4">
                        <span class="text-text-main font-medium">{{ number_format($game->rating, 1) }}</span>
                        <span class="text-text-muted text-xs">/10</span>
                    </td>

                    {{-- Anuncios --}}
                    <td class="px-5 py-4 text-text-muted">{{ $game->game_ads_count }}</td>

                    {{-- Estado --}}
                    <td class="px-5 py-4">
                        @if($game->is_published)
                            <span class="flex items-center gap-1.5 text-xs text-accent">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>Publicado
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-xs text-text-muted">
                                <span class="w-1.5 h-1.5 rounded-full bg-text-muted"></span>Oculto
                            </span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Publicar / Ocultar --}}
                            <form method="POST" action="{{ route('admin.games.toggle-publish', $game) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 text-xs rounded border transition
                                        {{ $game->is_published
                                            ? 'bg-surface text-text-muted border-border hover:text-yellow-400 hover:border-yellow-500/30'
                                            : 'bg-accent/10 text-accent border-accent/20 hover:bg-accent/20' }}">
                                    {{ $game->is_published ? 'Ocultar' : 'Publicar' }}
                                </button>
                            </form>

                            {{-- Editar --}}
                            <a href="{{ route('admin.games.edit', $game) }}"
                               class="px-3 py-1 text-xs rounded bg-surface text-text-muted border border-border hover:text-text-main transition">
                                Editar
                            </a>

                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('admin.games.destroy', $game) }}"
                                  data-confirm="¿Eliminar el juego &quot;{{ addslashes($game->title) }}&quot;? Se eliminarán también todos sus anuncios.">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 text-xs rounded bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-text-muted text-sm">No se encontraron juegos.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if($games->hasPages())
        <div class="px-5 py-3 border-t border-border flex items-center justify-between">
            <p class="text-xs text-text-muted">
                Mostrando {{ $games->firstItem() }}–{{ $games->lastItem() }} de {{ $games->total() }} juegos
            </p>
            {{ $games->onEachSide(1)->links('admin.layouts.pagination') }}
        </div>
        @endif
    </div>

</div>
@endsection
