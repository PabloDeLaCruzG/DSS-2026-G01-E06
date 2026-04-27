@extends('user.layout')

@section('title', 'Mis Anuncios')

@section('content')
<h1 class="text-2xl font-bold mb-6">Mis anuncios</h1>

@if (session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-lg px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('games.create') }}" class="bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg px-4 py-2 text-sm transition-colors inline-block">
        Nuevo anuncio
    </a>
</div>

<div class="bg-surface border border-border rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-background text-text-muted">
            <tr>
                <th class="text-left px-4 py-3">Juego</th>
                <th class="text-left px-4 py-3">Precio</th>
                <th class="text-left px-4 py-3">Estado</th>
                <th class="text-left px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ads as $ad)
                <tr class="border-t border-border">
                    <td class="px-4 py-3">{{ $ad->game?->title ?? 'Sin juego' }}</td>
                    <td class="px-4 py-3">{{ number_format($ad->price, 2) }} €</td>
                    <td class="px-4 py-3">{{ $ad->status }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('games.edit', $ad->id) }}" class="px-3 py-1.5 rounded-lg bg-background border border-border text-text-main">Editar</a>
                            <form method="POST" action="{{ route('games.destroy', $ad->id) }}" onsubmit="return confirm('¿Eliminar este anuncio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 rounded-lg border border-red-500/40 text-red-400">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-text-muted">No tienes anuncios todavía.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $ads->links() }}
</div>
@endsection
