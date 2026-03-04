@extends('layouts.app')

@section('title', $game->title . ' - GameLink')

@section('content')

    <!-- 1. Cabecera del Juego -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Portada -->
        <div class="rounded-lg overflow-hidden shadow-2xl">
            <img src="{{ $game->cover_image }}" alt="{{ $game->title }}" class="w-full">
        </div>
        
        <!-- Info -->
        <div class="md:col-span-2">
            <h1 class="text-4xl font-bold mb-2">{{ $game->title }}</h1>
            <div class="flex gap-2 mb-4">
                <span class="bg-blue-900 text-blue-200 px-2 py-1 rounded text-xs">{{ $game->year }}</span>
                <span class="bg-purple-900 text-purple-200 px-2 py-1 rounded text-xs">⭐ {{ $game->rating }}</span>
            </div>
            <p class="text-gray-300 text-lg mb-6">{{ $game->description }}</p>
            
            <!-- Botón de vender este juego (Call to Action) -->
            <a href="#" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-500 transition">
                💰 Vender uno igual
            </a>
        </div>
    </div>

    <!-- 2. Ofertas de Tiendas (PRO) -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
            🏢 Tiendas Recomendadas 
            <span class="text-xs bg-blue-600 px-2 py-1 rounded-full">Digital Key</span>
        </h2>
        
        @if($proAds->count() > 0)
            @foreach($proAds as $ad)
                <x-ad-row :ad="$ad" />
            @endforeach
        @else
            <p class="text-gray-500">No hay ofertas de tiendas oficiales por ahora.</p>
        @endif
    </section>

    <!-- 3. Ofertas de Segunda Mano (USER) -->
    <section>
        <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
            📦 Segunda Mano 
            <span class="text-xs bg-orange-600 px-2 py-1 rounded-full">Físico</span>
        </h2>

        @if($userAds->count() > 0)
            @foreach($userAds as $ad)
                <x-ad-row :ad="$ad" />
            @endforeach
        @else
            <p class="text-gray-500">Nadie vende este juego de segunda mano. ¡Sé el primero!</p>
        @endif
    </section>

@endsection