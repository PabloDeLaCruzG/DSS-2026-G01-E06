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
                    <th class="text-left px-4 py-3">Juego</th>
                    <th class="text-left px-3 py-3">Año</th>
                    <th class="text-left px-3 py-3">Plataformas</th>
                    <th class="text-left px-3 py-3">Rating</th>
                    <th class="text-left px-3 py-3">Anuncios</th>
                    <th class="text-left px-3 py-3">Estado</th>
                    <th class="text-right px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($games as $game)
                <tr class="hover:bg-background/50 transition">
                    {{-- Juego --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-11 rounded bg-background border border-border overflow-hidden flex-shrink-0">
                                @if($game->cover_image)
                                    <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-text-muted">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-text-main truncate max-w-[160px]">{{ $game->title }}</p>
                                @if($game->genres)
                                    <p class="text-xs text-text-muted mt-0.5 truncate max-w-[160px]">{{ implode(', ', array_slice($game->genres, 0, 2)) }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Año --}}
                    <td class="px-3 py-3 text-text-muted text-xs">{{ $game->year ?? '—' }}</td>

                    {{-- Plataformas --}}
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-1">
                            @if($game->platforms)
                                @foreach(array_slice($game->platforms, 0, 2) as $p)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] bg-border text-text-muted">{{ $p }}</span>
                                @endforeach
                                @if(count($game->platforms) > 2)
                                    <span class="text-[10px] text-text-muted">+{{ count($game->platforms) - 2 }}</span>
                                @endif
                            @else
                                <span class="text-text-muted">—</span>
                            @endif
                        </div>
                    </td>

                    {{-- Rating --}}
                    <td class="px-3 py-3 text-xs">
                        <span class="text-text-main font-medium">{{ number_format($game->rating, 1) }}</span>
                        <span class="text-text-muted">/10</span>
                    </td>

                    {{-- Anuncios --}}
                    <td class="px-3 py-3 text-text-muted text-xs text-center">{{ $game->game_ads_count }}</td>

                    {{-- Estado --}}
                    <td class="px-3 py-3">
                        @if($game->is_published)
                            <span class="flex items-center gap-1 text-xs text-accent">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span>Pub.
                            </span>
                        @else
                            <span class="flex items-center gap-1 text-xs text-text-muted">
                                <span class="w-1.5 h-1.5 rounded-full bg-text-muted shrink-0"></span>Oculto
                            </span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            {{-- Toggle publicar/ocultar --}}
                            <form method="POST" action="{{ route('admin.games.toggle-publish', $game) }}">
                                @csrf
                                <button type="submit" title="{{ $game->is_published ? 'Ocultar' : 'Publicar' }}"
                                        class="p-1.5 rounded hover:bg-background transition-colors {{ $game->is_published ? 'text-text-muted hover:text-yellow-400' : 'text-text-muted hover:text-accent' }}">
                                    @if($game->is_published)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    @endif
                                </button>
                            </form>

                            {{-- Editar --}}
                            <a href="{{ route('admin.games.edit', $game) }}" title="Editar"
                               class="p-1.5 rounded text-text-muted hover:text-text-main hover:bg-background transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>

                            {{-- Eliminar --}}
                            <form method="POST" action="{{ route('admin.games.destroy', $game) }}"
                                  data-confirm="¿Eliminar el juego &quot;{{ addslashes($game->title) }}&quot;? Se eliminarán también todos sus anuncios.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Eliminar"
                                        class="p-1.5 rounded text-text-muted hover:text-red-400 hover:bg-background transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-10 text-center text-text-muted text-sm">No se encontraron juegos.</td>
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
