@extends('user.layout')

@section('title', 'Perfil de ' . $user->name)

@section('content')

{{-- ===== HEADER DE PERFIL ===== --}}
<div class="bg-surface border border-border rounded-xl p-6 mb-6">
    <div class="flex items-start gap-6">

        {{-- Avatar --}}
        <div class="relative flex-shrink-0">
            <img src="{{ $user->avatar_url }}"
                 alt="Avatar"
                 class="w-24 h-24 rounded-xl object-cover border-2 border-primary">
            <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-surface"></span>
        </div>

        {{-- Info principal --}}
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-2xl font-bold text-text-main">{{ $user->name }}</h1>
                @if($user->isProfessional())
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#009194]/20 text-[#3bb1a5] border border-[#009194]/30">
                        ✔ Vendedor Verificado
                    </span>
                @endif
            </div>

            {{-- Stats --}}
            <div class="flex gap-3">
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide">Reputación</p>
                    <p class="font-bold text-sm mt-0.5 text-text-main flex items-center justify-center gap-1">
                        <svg viewBox="0 0 12 12" fill="currentColor" class="w-3 h-3 text-yellow-400">
                            <path d="M2.23125 11.0833L3.17917 6.98542L0 4.22917L4.2 3.86458L5.83333 0L7.46667 3.86458L11.6667 4.22917L8.4875 6.98542L9.43542 11.0833L5.83333 8.91042L2.23125 11.0833Z"/>
                        </svg>
                        {{ number_format($user->reputation ?? 0, 1) }} <span class="text-text-muted font-normal">/5.0</span>
                    </p>
                </div>
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide"> Ofertas Activas</p>
                    <p class="font-bold text-sm mt-0.5 text-text-main">{{ $ads->count() }}</p>
                </div>
                <div class="bg-background border border-border rounded-lg px-4 py-2 text-center">
                    <p class="text-xs text-text-muted uppercase tracking-wide">Miembro desde</p>
                    <p class="font-bold text-sm mt-0.5 text-text-main">{{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Boton denunciar --}}
        @if(auth()->id() !== $user->id)
            <div class="flex flex-col gap-2 flex-shrink-0 w-44">
                <a class="flex items-center gap-1 text-red-400 text-xs text-right hover:underline cursor-pointer mb-2">
                    <svg class="w-4 h-4 shrink-0" style="color:#facc15;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    Denunciar usuario
                </a>
            </div>
        @endif

    </div>
</div>

{{-- ===== ACTIVE LISTINGS ===== --}}
<section class="mb-10">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-text-main font-bold text-lg flex items-center gap-2">
            Ofertas Actuales
            <span class="text-xs text-[#3bb1a5] font-normal">{{ $ads->count() }} total</span>
        </h2>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($ads as $ad)
            <a href="{{ route('games.show', $ad->game->id) }}"
               class="bg-surface border border-border rounded-xl overflow-hidden hover:border-[#009194] transition-colors block group">

                {{-- Imagen --}}
                <div class="relative bg-gray-700 flex items-center justify-center" style="height: 160px;">
                    @if($ad->game->cover_image)
                        <img src="{{ $ad->game->cover_image }}"
                             alt="{{ $ad->game->title }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl">🎮</span>
                    @endif

                    {{-- Badge plataforma --}}
                    @php
                        $platform = is_array($ad->game->platforms) ? $ad->game->platforms[0] : $ad->game->platforms;
                        $platformColors = ['PS5' => '#1a4fc4', 'XBOX' => '#107c10', 'SWITCH' => '#e4000f', 'PC' => '#111111', 'RETRO' => '#b45309'];
                        $badgeColor = $platformColors[$platform] ?? '#009194';
                    @endphp
                    @if($platform)
                        <span class="absolute top-2 left-2 text-xs text-white px-2 py-0.5 rounded font-medium"
                              style="background-color: {{ $badgeColor }}">
                            {{ $platform }}
                        </span>
                    @endif

                    {{-- Badge instant --}}
                    @if($ad->format === 'DIGITAL_KEY')
                        <span class="absolute bottom-2 right-2 text-xs bg-[#009194] text-white px-2 py-0.5 rounded font-medium">
                            Digital
                        </span>
                    @else
                        <span class="absolute bottom-2 right-2 text-xs bg-[#009194] text-white px-2 py-0.5 rounded font-medium">
                            Físico
                        </span>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-3">
                    <p class="text-text-muted text-xs mb-0.5 uppercase tracking-wide">{{ $ad->game->genre }}</p>
                    <h3 class="text-text-main text-sm font-semibold mb-2 truncate" title="{{ $ad->game->title }}">
                        {{ $ad->game->title }}
                    </h3>
                    <div class="flex items-center justify-between">
                        <span class="text-text-main font-bold text-sm">{{ number_format($ad->price, 2) }}€</span>
                        <button class="text-text-muted hover:text-[#009194] transition-colors"
                                onclick="event.stopPropagation(); event.preventDefault();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        </button>
                    </div>
                </div>

            </a>
        @empty
            <div class="col-span-4 text-center text-text-muted py-16">
                <p class="text-5xl mb-4">🎮</p>
                <p class="text-sm">Este usuario no tiene anuncios activos.</p>
            </div>
        @endforelse
    </div>
    {{-- Paginación --}}
    @if($ads->hasPages())
        <div class="mt-8">
            {{ $ads->links() }}
        </div>
    @endif
</section>

{{-- ===== REVIEWS ===== --}}
<section>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-text-main font-bold text-lg flex items-center gap-2">
            Reviews y Reseñas
            <span class="flex items-center gap-1 text-xs bg-[#009194]/20 text-[#3bb1a5] px-2 py-0.5 rounded-full">
                <svg viewBox="0 0 12 12" fill="currentColor" class="w-3 h-3 text-yellow-400">
                    <path d="M2.23125 11.0833L3.17917 6.98542L0 4.22917L4.2 3.86458L5.83333 0L7.46667 3.86458L11.6667 4.22917L8.4875 6.98542L9.43542 11.0833L5.83333 8.91042L2.23125 11.0833Z"/>
                </svg>
                {{ number_format($reviews->avg('rating'), 1) }} · {{ $reviews->count() }} reseñas
            </span>
        </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Reviews --}}
        @forelse($reviews as $review)
            <div class="bg-surface border border-border rounded-xl p-4">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gray-700 flex-shrink-0 overflow-hidden">
                        <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('users.show', $review->user) }}" class="text-text-main text-sm font-semibold hover:text-[#3bb1a5] transition-colors">{{ $review->user->name }}</a>
                            <span class="text-xs bg-surface border border-border text-text-muted px-2 py-0.5 rounded-full">
                                {{ $review->gameAd->game->title ?? '' }}
                            </span>
                            <span class="text-xs text-text-muted ml-auto">{{ $review->created_at->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-0.5 mt-0.5">
                            @for($s = 1; $s <= 5; $s++)
                                <span class="{{ $s <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }} text-xs">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-text-muted text-sm">{{ $review->comment }}</p>
            </div>
        @empty
            <div class="col-span-2 text-center text-text-muted py-8">
                <p>Este usuario aún no tiene reseñas.</p>
            </div>
        @endforelse
    </div>
</section>

@endsection