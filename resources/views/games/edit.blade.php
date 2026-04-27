@extends('user.layout')

@section('title', 'Editar Anuncio')

@section('content')

<h1 class="text-xl font-bold mb-6">Editar Anuncio</h1>

@if ($errors->any())
    <div class="mb-4 text-red-400">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('games.update', $ad->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <!-- JUEGO (solo lectura) -->
    <div>
        <label class="block text-sm text-text-muted">Juego</label>
        <input type="text"
               value="{{ $ad->game->title }}"
               disabled
               class="w-full bg-surface p-2 rounded">
    </div>

    <!-- PRECIO -->
    <div>
        <label>Precio</label>
        <input type="number" name="price"
               value="{{ old('price', $ad->price) }}"
               class="w-full bg-surface p-2 rounded">
    </div>

    <!-- FORMATO -->
    <div>
        <label>Formato</label>
        <select name="format" class="w-full bg-surface p-2 rounded">
            <option value="PHYSICAL" {{ $ad->format == 'PHYSICAL' ? 'selected' : '' }}>Físico</option>
            <option value="DIGITAL_KEY" {{ $ad->format == 'DIGITAL_KEY' ? 'selected' : '' }}>Digital</option>
        </select>
    </div>

    <!-- ESTADO -->
    <div>
        <label>Estado</label>
        <select name="status" class="w-full bg-surface p-2 rounded">
            <option value="ACTIVE" {{ $ad->status == 'ACTIVE' ? 'selected' : '' }}>Activo</option>
            <option value="SOLD" {{ $ad->status == 'SOLD' ? 'selected' : '' }}>Vendido</option>
        </select>
    </div>

    <button class="bg-primary px-4 py-2 rounded">
        Guardar Cambios
    </button>

</form>

@endsection