@extends('user.layout')

@section('title', isset($ad) ? 'Editar Anuncio' : 'Publicar Anuncio')

@section('content')

@php $isPro = auth()->user()->isProfessional(); @endphp

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

            <!-- ESTADO DEL ANUNCIO (activo/vendido) — solo en edición -->
            @if(isset($ad))
            <div>
                <label class="text-xs text-text-muted">Estado del anuncio</label>
                <div class="flex mt-1 bg-background border border-border rounded-lg overflow-hidden">
                    <input type="radio" name="status" value="ACTIVE" id="active" class="hidden peer/active"
                        {{ old('status', $ad->status ?? 'ACTIVE') == 'ACTIVE' ? 'checked' : '' }}>
                    <label for="active"
                        class="flex-1 text-center py-2 cursor-pointer peer-checked/active:bg-primary peer-checked/active:text-white">
                        Activo
                    </label>
                    <input type="radio" name="status" value="SOLD" id="sold" class="hidden peer/sold"
                        {{ old('status', $ad->status ?? '') == 'SOLD' ? 'checked' : '' }}>
                    <label for="sold"
                        class="flex-1 text-center py-2 cursor-pointer peer-checked/sold:bg-primary peer-checked/sold:text-white">
                        Vendido
                    </label>
                </div>
            </div>
            @else
            <input type="hidden" name="status" value="ACTIVE">
            @endif

        </div>

        <!-- FORMATO -->
        <div class="mb-6">
            <label class="text-xs text-text-muted">Formato</label>

            <div class="flex mt-1 bg-background border border-border rounded-lg overflow-hidden">
                <input type="radio" name="format" value="PHYSICAL" id="physical"
                    class="hidden peer/physical"
                    {{ old('format', $ad->format ?? 'PHYSICAL') == 'PHYSICAL' ? 'checked' : '' }}>
                <label for="physical"
                    class="flex-1 text-center py-2 cursor-pointer peer-checked/physical:bg-primary peer-checked/physical:text-white">
                    📦 Físico
                </label>

                @if($isPro)
                <input type="radio" name="format" value="DIGITAL_KEY" id="digital"
                    class="hidden peer/digital"
                    {{ old('format', $ad->format ?? '') == 'DIGITAL_KEY' ? 'checked' : '' }}>
                <label for="digital"
                    class="flex-1 text-center py-2 cursor-pointer peer-checked/digital:bg-primary peer-checked/digital:text-white">
                    🔑 Clave Digital
                </label>
                @else
                <div class="flex-1 text-center py-2 text-text-muted text-xs flex items-center justify-center gap-1 cursor-not-allowed opacity-50">
                    🔑 Clave Digital (solo vendedores Pro)
                </div>
                @endif
            </div>
        </div>

        <!-- CONDICIÓN (solo para anuncios físicos) -->
        <div id="condition-block" class="mb-6 {{ old('format', $ad->format ?? 'PHYSICAL') === 'DIGITAL_KEY' ? 'hidden' : '' }}">
            <label class="text-xs text-text-muted">Condición del producto</label>
            <div class="flex mt-1 bg-background border border-border rounded-lg overflow-hidden">
                <input type="radio" name="condition" value="USED" id="cond_used"
                    class="hidden peer/cond_used"
                    {{ old('condition', $ad->condition ?? 'USED') == 'USED' ? 'checked' : '' }}>
                <label for="cond_used"
                    class="flex-1 text-center py-2 cursor-pointer peer-checked/cond_used:bg-primary peer-checked/cond_used:text-white">
                    Segunda Mano
                </label>

                <input type="radio" name="condition" value="NEW" id="cond_new"
                    class="hidden peer/cond_new"
                    {{ old('condition', $ad->condition ?? '') == 'NEW' ? 'checked' : '' }}>
                <label for="cond_new"
                    class="flex-1 text-center py-2 cursor-pointer peer-checked/cond_new:bg-primary peer-checked/cond_new:text-white">
                    Como Nuevo
                </label>
            </div>
        </div>

        <!-- CLAVE DIGITAL (solo para profesionales con formato DIGITAL_KEY) -->
        @if($isPro)
        <div id="digital-block" class="mb-6 {{ old('format', $ad->format ?? 'PHYSICAL') !== 'DIGITAL_KEY' ? 'hidden' : '' }}">
            <label class="text-xs text-text-muted">Clave Digital</label>
            <input type="text" name="key"
                value="{{ old('key', $ad->digital_key ?? '') }}"
                placeholder="XXXX-XXXX-XXXX"
                class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg font-mono">

            <div class="mt-4">
                <label class="text-xs text-text-muted">Stock (cantidad de unidades)</label>
                <input type="number" name="quantity" min="1"
                    value="{{ old('quantity', $ad->quantity ?? 1) }}"
                    class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg">
            </div>
        </div>
        @endif

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

@if($isPro)
<script>
    const physicalRadio = document.getElementById('physical');
    const digitalRadio  = document.getElementById('digital');
    const condBlock     = document.getElementById('condition-block');
    const digitalBlock  = document.getElementById('digital-block');

    function toggleBlocks() {
        const isDigital = digitalRadio && digitalRadio.checked;
        condBlock.classList.toggle('hidden', isDigital);
        digitalBlock.classList.toggle('hidden', !isDigital);
    }

    physicalRadio?.addEventListener('change', toggleBlocks);
    digitalRadio?.addEventListener('change', toggleBlocks);
</script>
@endif

@endsection
