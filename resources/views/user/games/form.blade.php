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
                        : asset('img/default-game.png') }}"
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

                    <label class="flex-1 text-center py-2 cursor-pointer
                        {{ old('status', $ad->status ?? 'ACTIVE') == 'ACTIVE' ? 'bg-primary text-white' : '' }}">
                        <input type="radio" name="status" value="ACTIVE" class="hidden"
                            {{ old('status', $ad->status ?? 'ACTIVE') == 'ACTIVE' ? 'checked' : '' }}>
                        Nuevo
                    </label>

                    <label class="flex-1 text-center py-2 cursor-pointer
                        {{ old('status', $ad->status ?? '') == 'SOLD' ? 'bg-primary text-white' : '' }}">
                        <input type="radio" name="status" value="SOLD" class="hidden"
                            {{ old('status', $ad->status ?? '') == 'SOLD' ? 'checked' : '' }}>
                        Usado
                    </label>

                </div>
            </div>

        </div>

        <!-- FORMATO -->
        <div class="mb-6">
            <label class="text-xs text-text-muted">Formato</label>

            <div class="flex mt-1 bg-background border border-border rounded-lg overflow-hidden">

                <label class="flex-1 text-center py-2 cursor-pointer
                    {{ old('format', $ad->format ?? 'PHYSICAL') == 'PHYSICAL' ? 'bg-primary text-white' : '' }}">
                    <input type="radio" name="format" value="PHYSICAL" class="hidden"
                        {{ old('format', $ad->format ?? 'PHYSICAL') == 'PHYSICAL' ? 'checked' : '' }}>
                    Físico
                </label>

                <label class="flex-1 text-center py-2 cursor-pointer
                    {{ old('format', $ad->format ?? '') == 'DIGITAL_KEY' ? 'bg-primary text-white' : '' }}">
                    <input type="radio" name="format" value="DIGITAL_KEY" class="hidden"
                        {{ old('format', $ad->format ?? '') == 'DIGITAL_KEY' ? 'checked' : '' }}>
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