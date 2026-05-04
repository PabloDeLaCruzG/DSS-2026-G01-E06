@extends('user.layout')

@section('title', 'Mis Anuncios')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold">Mis Anuncios</h1>
        <p class="text-text-muted text-sm">Gestiona tus juegos en venta y revisa su estado</p>
    </div>

    <a href="{{ route('games.create') }}"
       class="bg-primary hover:bg-primary-hover px-4 py-2 rounded-full text-sm">
        Publicar Nuevo Juego
    </a>
</div>

<!-- MENSAJES -->
@if(session('success'))
    <div class="mb-4 bg-green-500/20 text-green-400 p-3 rounded">
        {{ session('success') }}
    </div>
@endif

<!-- CARDS -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">

    <div class="bg-surface p-4 rounded-xl">
        <p class="text-text-muted text-sm">Pendiente</p>
        <h2 class="text-xl font-bold text-yellow-400">{{ number_format($pendiente, 2) }}€</h2>
    </div>

    <div class="bg-surface p-4 rounded-xl">
        <p class="text-text-muted text-sm">Total Vendido</p>
        <h2 class="text-xl font-bold text-accent">{{ number_format($totalVendido, 2) }}€</h2>
    </div>

    <div class="bg-surface p-4 rounded-xl">
        <p class="text-text-muted text-sm">Anuncios Activos</p>
        <h2 class="text-xl font-bold">{{ $activos }}</h2>
    </div>

    <div class="bg-surface p-4 rounded-xl">
        <p class="text-text-muted text-sm">Total Anuncios</p>
        <h2 class="text-xl font-bold">{{ $ads->total() }}</h2>
    </div>

</div>

<!-- TABLA -->
<div class="bg-surface p-6 rounded-xl">

<table class="w-full text-sm">

    <thead class="text-text-muted">
        <tr>
            <th class="text-left pb-3">TÍTULO</th>
            <th class="text-left pb-3">FORMATO</th>
            <th class="text-left pb-3">PRECIO</th>
            <th class="text-left pb-3">ESTADO</th>
            <th class="text-left pb-3">ACCIONES</th>
        </tr>
    </thead>

    <tbody>
    @forelse($ads as $ad)
        <tr class="border-t border-border hover:bg-white/5 transition">

            <!-- TITULO + IMAGEN -->
            <td class="py-3 flex items-center gap-3">
                <img src="{{ $ad->game && $ad->game->cover_image
                    ? $ad->game->cover_image
                    : 'https://placehold.co/600x800?text=Sin+imagen' }}"
                     class="w-10 h-10 rounded object-cover">

                <span>{{ $ad->game->title ?? 'Juego eliminado' }}</span>
            </td>

            <!-- FORMATO -->
            <td>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $ad->format == 'PHYSICAL' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                    {{ $ad->format == 'PHYSICAL' ? 'Físico' : 'Digital' }}
                </span>
            </td>

            <!-- PRECIO -->
            <td>{{ number_format($ad->price, 2) }} €</td>

            <!-- ESTADO -->
            <td>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $ad->status == 'ACTIVE' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400' }}">
                    {{ $ad->status == 'ACTIVE' ? 'Activo' : 'Vendido' }}
                </span>
            </td>

            <!-- ACCIONES -->
            <td class="flex gap-3 items-center">

                <!-- EDITAR -->
                <a href="{{ route('games.edit', $ad->id) }}"
                   class="text-text-muted hover:text-white">
                    <i class="bi bi-pencil"></i>
                </a>

                <!-- ELIMINAR -->
                <form method="POST" action="{{ route('games.destroy', $ad->id) }}"
                      data-confirm="¿Seguro que quieres eliminar este anuncio? Esta acción no se puede deshacer.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-text-muted hover:text-red-400">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </td>

        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center py-6 text-text-muted">
                No tienes anuncios todavía
            </td>
        </tr>
    @endforelse
    </tbody>

</table>

<!-- PAGINACIÓN -->
<div class="mt-4 flex justify-between items-center">

    <p class="text-xs text-text-muted">
        Mostrando {{ $ads->firstItem() ?? 0 }}-{{ $ads->lastItem() ?? 0 }} de {{ $ads->total() }} anuncios
    </p>

    {{ $ads->appends(['search' => request('search')])->links('user.pagination') }}

</div>

</div>

@endsection