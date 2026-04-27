@extends('user.layout')

@section('title', isset($ad) ? 'Editar Anuncio' : 'Publicar Anuncio')

@section('content')

<div class="max-w-2xl mx-auto bg-surface rounded-xl p-6 shadow-lg">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold flex items-center gap-2">
            📦 {{ isset($ad) ? 'Editar Anuncio' : 'Publicar Anuncio' }}
        </h2>
        <a href="{{ route('games.index') }}" class="text-text-muted hover:text-white">✖</a>
    </div>

    <!-- ERRORES -->
    @if ($errors->any())
        <div class="mb-4 bg-red-500/20 text-red-400 p-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ isset($ad) ? route('games.update', $ad->id) : route('games.store') }}">
        @csrf
        @if(isset($ad)) @method('PUT') @endif

        <!-- JUEGO -->
        <div class="mb-6">
            <label class="text-xs text-text-muted mb-2 block">JUEGO</label>

            <div class="flex gap-3 items-center">
                <select name="game_id"
                    class="w-full bg-background border border-border px-4 py-2 rounded-lg">
                    @foreach($games as $game)
                        <option value="{{ $game->id }}"
                            {{ old('game_id', $ad->game_id ?? '') == $game->id ? 'selected' : '' }}>
                            {{ $game->title }}
                        </option>
                    @endforeach
                </select>

                @if(isset($ad))
                    <img src="{{ $ad->game && $ad->game->cover_image
                        ? $ad->game->cover_image
                        : 'https://placehold.co/600x800?text=Sin+imagen' }}"
                         class="w-12 h-12 rounded object-cover">
                @endif
            </div>
        </div>

        <!-- DETALLES -->
        <p class="text-xs text-primary mb-3">DETALLES DEL ANUNCIO</p>

        <div class="grid grid-cols-2 gap-4 mb-6">

            <!-- PRECIO -->
            <div>
                <label class="text-xs text-text-muted">Precio</label>
                <input type="number" step="0.01" name="price"
                    value="{{ old('price', $ad->price ?? '') }}"
                    class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg">
            </div>

            <!-- ESTADO -->
            <div>
                <label class="text-xs text-text-muted">Estado</label>

                <div class="flex mt-1 bg-background border border-border rounded-lg overflow-hidden">

                    <input type="radio" name="status" value="ACTIVE" id="active"
                        class="hidden peer"
                        {{ old('status', $ad->status ?? 'ACTIVE') == 'ACTIVE' ? 'checked' : '' }}>

                    <label for="active"
                        class="flex-1 text-center py-2 cursor-pointer
                        peer-checked:bg-primary peer-checked:text-white">
                        Nuevo
                    </label>

                    <input type="radio" name="status" value="SOLD" id="sold"
                        class="hidden peer"
                        {{ old('status', $ad->status ?? '') == 'SOLD' ? 'checked' : '' }}>

                    <label for="sold"
                        class="flex-1 text-center py-2 cursor-pointer
                        peer-checked:bg-primary peer-checked:text-white">
                        Usado
                    </label>

                </div>
            </div>

        </div>

        <!-- FORMATO -->
        <div class="mb-6">
            <label class="text-xs text-text-muted">Formato</label>

            <div class="flex mt-1 bg-background border border-border rounded-lg overflow-hidden">

                <input type="radio" name="format" value="PHYSICAL" id="physical"
                    class="hidden peer"
                    {{ old('format', $ad->format ?? 'PHYSICAL') == 'PHYSICAL' ? 'checked' : '' }}>

                <label for="physical"
                    class="flex-1 text-center py-2 cursor-pointer
                    peer-checked:bg-primary peer-checked:text-white">
                    Físico
                </label>

                <input type="radio" name="format" value="DIGITAL_KEY" id="digital"
                    class="hidden peer"
                    {{ old('format', $ad->format ?? '') == 'DIGITAL_KEY' ? 'checked' : '' }}>

                <label for="digital"
                    class="flex-1 text-center py-2 cursor-pointer
                    peer-checked:bg-primary peer-checked:text-white">
                    Digital
                </label>

            </div>
        </div>

        <!-- CLAVE DIGITAL -->
        <div class="mb-6">
            <label class="text-xs text-text-muted">Clave Digital</label>
            <input type="text" name="key"
                value="{{ old('key', $ad->digital_key ?? '') }}"
                placeholder="XXXX-XXXX-XXXX"
                class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg">
        </div>

        <!-- DESCRIPCIÓN -->
        <div class="mb-6">
            <label class="text-xs text-text-muted">Descripción</label>
            <textarea name="description"
                class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg"
                rows="3"
                placeholder="Describe el estado, extras incluidos...">{{ old('description', $ad->description ?? '') }}</textarea>
        </div>

        <!-- BOTONES -->
        <div class="flex justify-between items-center">

            <a href="{{ route('games.index') }}"
               class="px-4 py-2 border border-border rounded-lg text-sm">
                Cancelar
            </a>

            <button class="bg-primary px-5 py-2 rounded-lg text-white font-medium">
                🚀 {{ isset($ad) ? 'Guardar cambios' : 'Publicar Anuncio' }}
            </button>

        </div>

    </form>

</div>

@endsection