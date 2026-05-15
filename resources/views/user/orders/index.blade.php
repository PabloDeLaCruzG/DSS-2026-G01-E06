@extends('user.layout')

@section('title', 'Mis Pedidos')

@section('content')

<h1 class="text-2xl font-bold mb-6">Mis Pedidos</h1>

@if(session('success'))
    <div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

@if($orders->isEmpty())
    <div class="bg-surface p-10 rounded-xl text-center text-text-muted">
        <p class="text-4xl mb-3">🛒</p>
        <p class="font-semibold">Aún no tienes pedidos realizados.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block text-primary hover:underline text-sm">Explorar juegos</a>
    </div>
@else
    <div class="space-y-6">
        @foreach($orders as $order)
            <div class="bg-surface rounded-xl border border-border overflow-hidden">

                {{-- Cabecera del pedido --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 px-5 py-4 border-b border-border bg-background/40">
                    <div>
                        <span class="text-xs text-text-muted font-mono">Pedido #GL-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                        <p class="text-xs text-text-muted mt-0.5">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="sm:text-right">
                        @php
                            $statusClass = match($order->status) {
                                \App\Enums\OrderStatus::PAID      => 'bg-green-500/20 text-green-400',
                                \App\Enums\OrderStatus::COMPLETED => 'bg-blue-500/20 text-blue-400',
                                default                           => 'bg-surface text-text-muted border border-border',
                            };
                        @endphp
                        <span class="text-xs font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                            {{ $order->status->value }}
                        </span>
                        <p class="text-sm font-bold text-text-main mt-1">€{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>

                {{-- Items del pedido --}}
                <div class="divide-y divide-border">
                    @foreach($order->orderItems as $item)
                        <div class="px-5 py-4">
                            <div class="flex items-start gap-4">

                                {{-- Imagen del juego --}}
                                <div class="w-14 h-14 rounded-lg overflow-hidden border border-border flex-shrink-0 bg-background">
                                    @if($item->gameAd && $item->gameAd->game && $item->gameAd->game->cover_image)
                                        <img src="{{ $item->gameAd->game->cover_image }}"
                                             alt="{{ $item->gameAd->game->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-2xl">🎮</div>
                                    @endif
                                </div>

                                {{-- Info del item --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-text-main text-sm truncate">
                                        {{ $item->gameAd->game->title ?? 'Juego eliminado' }}
                                    </p>

                                    @if($item->shipping_status === \App\Enums\ShippingStatus::INSTANT)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-400 mt-0.5">
                                            🔑 Clave Digital · Entrega Inmediata
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-text-muted mt-0.5">
                                            📦 Físico ·
                                            @if($item->shipping_status === \App\Enums\ShippingStatus::PENDING)
                                                Pendiente de envío
                                            @elseif($item->shipping_status === \App\Enums\ShippingStatus::SHIPPED)
                                                Enviado
                                            @elseif($item->shipping_status === \App\Enums\ShippingStatus::DELIVERED)
                                                Entregado
                                            @endif
                                        </span>
                                    @endif

                                    <p class="text-xs text-text-muted mt-0.5">€{{ number_format($item->unit_price, 2) }}</p>
                                </div>

                            </div>

                            {{-- Clave digital destacada --}}
                            @if($item->shipping_status === \App\Enums\ShippingStatus::INSTANT && $item->gameAd && $item->gameAd->digital_key)
                                <div class="mt-3 flex items-center gap-3 bg-blue-500/10 border border-blue-500/30 rounded-lg px-4 py-3">
                                    <span class="text-blue-400 text-lg">🔑</span>
                                    <div>
                                        <p class="text-xs text-blue-300 font-semibold mb-0.5">Tu clave de activación</p>
                                        <p class="font-mono font-bold text-blue-100 tracking-widest text-sm select-all">
                                            {{ $item->gameAd->digital_key }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

            </div>
        @endforeach
    </div>
@endif

@endsection
