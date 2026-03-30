@extends('user.layout')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[var(--color-text-main)]">
            Mis Anuncios
        </h1>
        <p class="text-[var(--color-text-muted)]">
            Gestiona tus juegos en venta y revisa su estado
        </p>
    </div>

    <a href="#" 
       class="bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] px-4 py-2 rounded-lg">
        Publicar Nuevo Juego
    </a>
</div>

<!-- CARDS -->
<div class="grid grid-cols-3 gap-4 mb-6">

    <div class="bg-[var(--color-surface)] p-4 rounded-xl">
        <p class="text-[var(--color-text-muted)]">Total Ventas</p>
        <h2 class="text-xl font-bold">{{ number_format($games->sum('price'),2) }}€</h2>
    </div>

    <div class="bg-[var(--color-surface)] p-4 rounded-xl">
        <p class="text-[var(--color-text-muted)]">Anuncios Activos</p>
        <h2 class="text-xl font-bold">{{ $games->where('status','activo')->count() }}</h2>
    </div>

    <div class="bg-[var(--color-surface)] p-4 rounded-xl">
        <p class="text-[var(--color-text-muted)]">Vistas (30d)</p>
        <h2 class="text-xl font-bold">842</h2>
    </div>

</div>

<!-- TABLA -->
<div class="bg-[var(--color-surface)] p-6 rounded-xl">

<table class="w-full">

    <thead class="text-[var(--color-text-muted)] text-sm">
        <tr>
            <th class="text-left pb-3">TÍTULO</th>
            <th class="text-left pb-3">FORMATO</th>
            <th class="text-left pb-3">PRECIO</th>
            <th class="text-left pb-3">ESTADO</th>
            <th class="text-left pb-3">ACCIONES</th>
        </tr>
    </thead>

    <tbody>
    @foreach($games as $game)
        <tr class="border-t border-[var(--color-border)]">
            <td class="py-3">{{ $game->title }}</td>

            <td>
                <span class="px-2 py-1 rounded-lg text-sm
                    {{ $game->format == 'fisico' ? 'bg-blue-600' : 'bg-purple-600' }}">
                    {{ ucfirst($game->format) }}
                </span>
            </td>

            <td>{{ $game->price }}€</td>

            <td>
                <span class="text-green-400">
                    {{ ucfirst($game->status) }}
                </span>
            </td>

            <td>✏️ 🗑️</td>
        </tr>
    @endforeach
    </tbody>

</table>

<div class="mt-4">
    {{ $games->links('user.pagination') }}
</div>

</div>

@endsection